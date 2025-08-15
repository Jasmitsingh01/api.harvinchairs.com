<?php


namespace App\Database\Repositories;

use App\Database\Models\Address;
use Exception;
use Carbon\Carbon;
use App\Models\Cart;
use App\Enums\CouponType;
use App\Enums\Permission;
use App\Enums\OrderStatus;
use App\Enums\ProductType;
use App\Traits\EmailTrait;
use Illuminate\Support\Str;
use App\Enums\PaymentStatus;
use App\Events\OrderCreated;
use App\Models\OrderCarrier;
use App\Traits\PaymentTrait;
use App\Traits\WalletsTrait;
use App\Database\Models\User;
use App\Events\OrderReceived;
use App\Database\Models\Order;
use App\Events\OrderProcessed;
use App\Database\Models\Coupon;
use App\Database\Models\Wallet;
use App\Database\Models\Balance;
use App\Database\Models\Product;
use App\Models\ProductAttribute;
use App\Enums\PaymentGatewayType;
use Illuminate\Http\JsonResponse;
use App\Database\Models\Variation;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Collection;
use App\Exceptions\MarvelException;
use App\Database\Models\OrderedFile;
use App\Traits\OrderManagementTrait;
use App\Traits\CalculatePaymentTrait;
use App\Events\BankWireOrderProcessed;
use App\Database\Models\OrderWalletPoint;
use App\Jobs\GenerateOrderInvoice;
use App\Models\OrderMessage;
use App\Models\SiteConfiguration;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Traits\OrderStatusManagerWithPaymentTrait;
use Prettus\Repository\Exceptions\RepositoryException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Traits\GenerateInvoiceTrait;

class OrderRepository extends BaseRepository
{
    use WalletsTrait,
        CalculatePaymentTrait,
        OrderManagementTrait,
        OrderStatusManagerWithPaymentTrait,
        PaymentTrait,
        EmailTrait,
        GenerateInvoiceTrait;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tracking_number' => 'like',
        'shop_id',
        'language',

    ];
    /**
     * @var string[]
     */
    protected array $dataArray = [
        'tracking_number',
        'customer_id',
        'shop_id',
        'cart_id',
        'language',
        'order_status',
        'payment_status',
        'amount',
        'sales_tax',
        'paid_total',
        'total',
        'delivery_time',
        'payment_gateway',
        'discount',
        'coupon_id',
        'logistics_provider',
        'billing_address',
        'shipping_address',
        'delivery_fee',
        'customer_contact',
        'customer_name',
        'assembly_charges',
        'product_discount',
        'billing_address_detail',
        'shipping_address_detail',
        'address_firstname',
        'address_mobile_no',
        'address_alternate_mobile_no',
        'average_cgst_rate',
        'average_sgst_rate',
        'total_cgst',
        'total_sgst'
    ];

    public function boot()
    {
        try {
            $this->pushCriteria(app(RequestCriteria::class));
        } catch (RepositoryException $e) {
            //
        }
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Order::class;
    }

    /**
     * Store order
     *
     * @param $request
     * @param $settings
     * @return LengthAwarePaginator|JsonResponse|Collection|mixed
     * @throws Exception
     */
    public function storeOrder($request, $settings): mixed
    {
        $out_of_stock = [];
        foreach($request['products'] as $product){
            $product_attribute = ProductAttribute::where('id',$product['product_attribute_id'])->first();
            if(!isset($product_attribute) || $product_attribute->out_of_stock == 0 && $product_attribute->quantity < $product['order_quantity']){
                $out_of_stock['status'] = 1;
                $out_of_stock['product'] = $product;
            }
        }
        if($out_of_stock){
            return response()->json(['status'=>false,'message'=>'Selected Product is Out Of Stock.','data'=>$out_of_stock['product']]);
        }
        $request['tracking_number'] = $this->generateTrackingNumber();
        $payment_gateway_type = isset($request->payment_gateway) ? $request->payment_gateway : PaymentGatewayType::CASH_ON_DELIVERY;

        switch ($payment_gateway_type) {
            case PaymentGatewayType::CASH_ON_DELIVERY:
                $request['order_status'] = OrderStatus::PROCESSING;
                $request['payment_status'] = PaymentStatus::CASH_ON_DELIVERY;
                break;

            case PaymentGatewayType::BANKWIRE_TRANSFER:
                $request['order_status'] = OrderStatus::PROCESSING;
                $request['payment_status'] = PaymentStatus::PENDING;
                break;

            case PaymentGatewayType::CASH:
                $request['order_status'] = OrderStatus::PROCESSING;
                $request['payment_status'] = PaymentStatus::CASH;
                break;

            case PaymentGatewayType::FULL_WALLET_PAYMENT:
                $request['order_status'] = OrderStatus::PROCESSING;
                $request['payment_status'] = PaymentStatus::WALLET;
                break;

            default:
                $request['order_status'] = OrderStatus::PENDING;
                $request['payment_status'] = PaymentStatus::PENDING;
                break;
        }

        $request['order_status'] = OrderStatus::PENDING;

        $useWalletPoints = isset($request->use_wallet_points) ? $request->use_wallet_points : false;
        if ($request->user() && $request->user()->hasPermissionTo(Permission::SUPER_ADMIN) && isset($request['customer_id'])) {
            $request['customer_id'] =  $request['customer_id'];
        } else {
            $request['customer_id'] = $request->user()->id ?? null;
        }
        try {
            $user = User::findOrFail($request['customer_id']);
            if($user){
                $request['customer_name'] = $user->first_name . ' ' . $user->last_name;
            }
        } catch (Exception $e) {
            $user = null;
        }
        //$request['amount'] = $this->calculateSubtotal($request['products']);
        // validate the total items in the cart
        if (count($request['products']) === 0) {
            return response()->json(['status'=>false,'message'=>'Selected Product is Out Of Stock.']);
        }
        $max_cart_item_limit = SiteConfiguration::where('varname', 'MAX_CART_ITEM_LIMIT')->value('value');
        $max_amount_per_order = SiteConfiguration::where('varname', 'MAX_AMOUNT_PER_ORDER')->value('value');
        if ($max_cart_item_limit && (count($request['products']) > $max_cart_item_limit)) {
            return response()->json(['status'=>false,'message'=>'Maximum cart item limit of '.$max_cart_item_limit.' exceeded.']);
        }
        $request['products'] = $this->processRequestProducts($request['products']);
        $request['total'] = $this->calculateSubtotal($request['products']);
        $request['product_discount'] = $this->calculateProductDiscount($request['products']);
        $request['assembly_charges'] = $this->calculateAssemblyCharges($request['products']);
        $request['billing_address_detail'] = $this->findAddressFromId($request['billing_address'],'full_address');
        $request['shipping_address_detail'] = $this->findAddressFromId($request['shipping_address'],'full_address');
        $request['address_firstname'] = $this->findAddressFromId($request['billing_address'],'first_name');
        $request['address_mobile_no'] = $this->findAddressFromId($request['billing_address'],'mobile_number');
        $request['address_alternate_mobile_no'] = $this->findAddressFromId($request['billing_address'],'alternate_mobile_number');
        $GSTCalculation = $this->calculateWithGST($request['products']);
        $request['average_cgst_rate'] = (isset($GSTCalculation) && isset($GSTCalculation['Average_CGST'])) ? $GSTCalculation['Average_CGST']:0;
        $request['average_sgst_rate'] = (isset($GSTCalculation) && isset($GSTCalculation['Average_SGST'])) ? $GSTCalculation['Average_SGST']:0;
        $request['total_cgst'] = (isset($GSTCalculation) && isset($GSTCalculation['Total_CGST'])) ? $GSTCalculation['Total_CGST']:0;
        $request['total_sgst'] = (isset($GSTCalculation) && isset($GSTCalculation['Total_SGST'])) ? $GSTCalculation['Total_SGST']:0;

        if (isset($request->coupon_id)) {
            try {
                $coupon = Coupon::findOrFail($request['coupon_id']);
                $discount = $this->calculateDiscount($coupon,  $request['total'],$request['delivery_fee']);
                if($discount['status'] == false){
                    return response()->json($discount,409);
                }
                $request['discount'] =  $discount['discount'];
            } catch (\Throwable $th) {
                // return response()->json(['message'=>$th->getMessage()]);
                throw new MarvelException($th);
            }
        }

        if (!isset($request['delivery_fee'])) {
            $request['delivery_fee'] = 0;
        }

        $request['paid_total'] = $this->calculatePaidTotal($request);
        if($max_amount_per_order && ($max_amount_per_order < $request['paid_total'])){
            return response()->json(['status'=>false,'message'=>'Maximum order amount limit of '.$max_amount_per_order.' exceeded.']);
        }
        //$request['total'] = $request['paid_total'];
        if ($useWalletPoints && $user) {
            $wallet = $user->wallet;
            $amount = null;
            if (isset($wallet->available_points)) {
                $amount = round($request['paid_total'], 2) - $this->walletPointsToCurrency($wallet->available_points);
            }

            if ($amount !== null && $amount <= 0) {
                $request['payment_gateway'] = 'FULL_WALLET_PAYMENT';
                $order = $this->createOrder($request);
                $this->storeOrderWalletPoint($request['paid_total'], $order->id);
                $this->manageWalletAmount($request['paid_total'], $user->id);
                return $order;
            }
        } else {
            $amount = round($request['paid_total'], 2);
        }
        if($amount == 0){
            $request['order_status'] = OrderStatus::PROCESSING;
        }
        $order = $this->createOrder($request);

        // generate invoice
        $invoiceFile = $this->generateInvoice($order->id);
        // Create Intent
        if ($order && !in_array($order->payment_gateway, [
            PaymentGatewayType::CASH, PaymentGatewayType::BANKWIRE_TRANSFER,PaymentGatewayType::CASH_ON_DELIVERY, PaymentGatewayType::FULL_WALLET_PAYMENT
        ], true)) {
            if($order->total > 0){
                $order['payment_intent'] = $this->processPaymentIntent($request, $settings);
            }
            else{
                $tags = [
                    'app_url' => env("APP_URL"),
                    "app_name" => env("APP_NAME"),
                    'name' => $order->customer->name,
                    "order" => $order,
                    'url' => config('shop.shop_url') . '/orders/' . $order->tracking_number,
                    'attachment' => $invoiceFile['filelink']
                ];
                $toIds=array($order->customer->email);
                $this->sendEmailNotification('OrderCreated', $toIds,$tags);
            }
        }


        if ($useWalletPoints === true && $user) {
            $this->storeOrderWalletPoint(round($request['paid_total'], 2) - $amount, $order->id);
            $this->manageWalletAmount(round($request['paid_total'], 2), $user->id);
        }

        if ($payment_gateway_type === PaymentGatewayType::CASH_ON_DELIVERY || $payment_gateway_type === PaymentGatewayType::CASH) {
            $this->orderStatusManagementOnCOD($order, OrderStatus::PENDING, OrderStatus::PROCESSING);
        } else {
            $this->orderStatusManagementOnPayment($order, OrderStatus::PENDING, PaymentStatus::PENDING);
        }
        if(isset($request->cart_id)){
            $cart = Cart::find($request->cart_id);
            if(isset($cart)){
                if(isset($request->message)){
                    $cart->update(['custom_message'=>$request->message]);
                    $order_message['custom_message']= $request->message;
                    $order_message['cart_id']= $cart->id;
                    $order_message['order_id']= $order->id;
                    $order_message['customer_id']=  $request->user()->id;
                    $order_message['tracking_number']=  $order->tracking_number;
                    OrderMessage::create($order_message);
                }
                $cart->update(['is_confirm'=>true]);
            }

        }
        if($order->payment_gateway == PaymentGatewayType::BANKWIRE_TRANSFER){
            //event(new BankWireOrderProcessed($order));
            $this->storeOrderStatusHistory($order);
            $tags = [
                'app_url' => env("APP_URL"),
                "app_name" => env("APP_NAME"),
                'name' => $order->customer->name,
                "order" => $order,
                'url' => config('shop.shop_url') . '/orders/' . $order->tracking_number
            ];
            $toIds=array($order->customer->email);
            // $this->sendEmailNotification('OrderCreated', $toIds,$tags);


            $tags = [
                'app_url' => env("APP_URL"),
                "app_name" => env("APP_NAME"),
                'name' => $order->customer->name,
                "order" => $order,
            ];

            $toIds=array($order->customer->email);
            //$this->sendEmailNotification('BankWireOrderProcessed', $toIds,$tags);

        }
        //GenerateOrderInvoice::dispatch($order);
        event(new OrderProcessed($order));
        // $orderCarrier['order_id'] = $order->id;
        // $orderCarrier['shipping_cost'] = $order->delivery_fee;
        // OrderCarrier::create($orderCarrier);
        return $order;
    }


    /**
     * updateOrder
     *
     * @param  mixed $request
     * @return void
     */
    public function updateOrder($request)
    {
        try {
            $order = Order::findOrFail($request->id);
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }

        $user = $request->user();
        if (isset($order->shop_id)) {
            if ($this->hasPermission($user, $order->shop_id)) {
                return $this->changeOrderStatus($order, $request->order_status);
            }
        } else if ($user->hasPermissionTo(Permission::SUPER_ADMIN)) {
            return $this->changeOrderStatus($order, $request->order_status);
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }

    /**
     * storeOrderWalletPoint
     *
     * @param  mixed $amount
     * @param  mixed $order_id
     * @return void
     */
    public function storeOrderWalletPoint($amount, $order_id)
    {
        if ($amount > 0) {
            OrderWalletPoint::create(['amount' =>  $amount, 'order_id' =>  $order_id]);
        }
    }


    /**
     * manageWalletAmount
     *
     * @param  mixed $total
     * @param  mixed $customer_id
     * @return void
     */
    public function manageWalletAmount($total, $customer_id)
    {
        try {
            $total = $this->currencyToWalletPoints($total);
            $wallet = Wallet::where('customer_id', $customer_id)->first();
            $available_points = $wallet->available_points - $total >= 0 ? $wallet->available_points - $total : 0;
            if ($available_points === 0) {
                $spend = $wallet->points_used + $wallet->available_points;
            } else {
                $spend = $wallet->points_used + $total;
            }
            $wallet->available_points = $available_points;
            $wallet->points_used = $spend;
            $wallet->save();
        } catch (Exception $e) {

            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }

    /**
     * @param $request
     * @return array|LengthAwarePaginator|Collection|mixed
     */
    protected function createOrder($request)
    {
        try {
            $orderInput = $request->only($this->dataArray);
            $order = $this->create($orderInput);
            $products = $this->processProducts($request['products'], $request['customer_id'], $order);
            //dd($products);
            $order->products()->attach($products);
            //$this->createChildOrder($order->id, $request);
            //  $this->calculateShopIncome($order);
            //event(new OrderCreated($order));

            return $order;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * calculateShopIncome
     *
     * @param  mixed $parent_order
     * @return void
     */
    protected function calculateShopIncome($parent_order)
    {
        foreach ($parent_order->children as  $order) {
            $balance = Balance::where('shop_id', '=', $order->shop_id)->first();
            $adminCommissionRate = $balance->admin_commission_rate;
            $shop_earnings = ($order->total * (100 - $adminCommissionRate)) / 100;
            $balance->total_earnings = $balance->total_earnings + $shop_earnings;
            $balance->current_balance = $balance->current_balance + $shop_earnings;
            $balance->save();
        }
    }

    /**
     * processProducts
     *
     * @param  mixed $products
     * @param  mixed $customer_id
     * @param  mixed $order
     * @return void
     */
    protected function processProducts($products, $customer_id, $order)
    {
        foreach ($products as $key => $product) {
            if (!isset($product['product_attribute_id'])) {
                $product['product_attribute_id'] = null;
                $products[$key] = $product;
            }
            try {
                if ($order->parent_id === null) {
                    if (isset($product['product_attribute_id'])) {
                        //$order_price = number_format($product['subtotal'], 2, '.', '');

                        $prodAttribute = ProductAttribute::find($product['product_attribute_id']);
                        $product['unit_price'] = $prodAttribute->price ? $prodAttribute->price : 0;
                        $product_amount =number_format(($product['unit_price'] * $product['order_quantity']),2, '.', '');
                        $discounted_amount = 0;
                        //$discounted_amount = $prodAttribute->discounted_price ? ($product_amount - $order_price) : 0;
                        $discounted_amount = $prodAttribute->discounted_price ? $prodAttribute->discounted_price->discounted_price : 0;
                        $price_without_gst = $prodAttribute->price_without_gst ? $prodAttribute->price_without_gst : 0;
                        $gstPercentage =  SiteConfiguration::where('varname', 'GST_PERCENTAGE')->value('value') ?? 0;
                        $product['discounted_price'] = ($discounted_amount > 0) ? ($product['unit_price'] - $discounted_amount):0;
                        $product['subtotal'] = $product['unit_price'] - $product['discounted_price'];
                        $product['price_without_gst'] = $price_without_gst;
                        $product['gst_percentage'] = $gstPercentage;
                        $products[$key] = $product;
                    }
                    $productData = Product::with('digital_file')->findOrFail($product['product_id']);
                    $product['product_name'] = $productData->name;
                    $product['combination_name'] = (isset($prodAttribute)) ? $prodAttribute->all_combination : '';
                    $product['product_image'] = (isset($prodAttribute)) ? json_encode($prodAttribute->images) : '';

                    $products[$key] = $product;
                    $isRentalProduct = $productData->is_rental;
                    if ($isRentalProduct) {
                        $this->processRentalProduct($product, $order->id);
                    }
                    if ($productData->product_type === ProductType::SIMPLE) {

                        $this->storeOrderedFile($productData, $product['order_quantity'], $customer_id, $order->tracking_number);
                    } else if ($productData->product_type === ProductType::VARIABLE) {
                        $variation_option = ProductAttribute::with('digital_file')->findOrFail($product['product_attribute_id']);
                        $this->storeOrderedFile($variation_option, $product['order_quantity'], $customer_id, $order->tracking_number);
                    }
                }
            } catch (Exception $e) {
                throw new MarvelException(NOT_FOUND);
            }
        }
        // dd($products);
        return $products;
    }


    /**
     * storeOrderedFile
     *
     * @param  mixed $item
     * @param  mixed $order_quantity
     * @param  mixed $customer_id
     * @return void
     */
    public function storeOrderedFile($item, $order_quantity, $customer_id, $order_tracking_number)
    {
        if ($item->is_digital) {
            $digital_file = $item->digital_file;
            for ($i = 0; $i < $order_quantity; $i++) {
                OrderedFile::create([
                    'purchase_key'    => Str::random(16),
                    'digital_file_id' => $digital_file->id,
                    'customer_id'     => $customer_id,
                    'tracking_number'  => $order_tracking_number
                ]);
            }
        }
    }

    /**
     * processRentalProduct
     *
     * @param  mixed $product
     * @param  mixed $orderId
     * @return void
     */
    protected function processRentalProduct($product, $orderId)
    {
        $product['from'] = Carbon::parse($product['from']);
        $product['to'] = Carbon::parse($product['to']);
        $product['booking_duration'] = $product['from']->diffAsCarbonInterval($product['to']);
        $product['order_id'] = $orderId;
        $product['language'] = $orderId;
        unset($product['unit_price']);
        unset($product['subtotal']);
        try {
            if ($product['variation_option_id'] === null) {
                $productData = Product::findOrFail($product['product_id']);
                unset($product['variation_option_id']);
                $product['language'] = $productData->language;
                if (TRANSLATION_ENABLED) {
                    $this->processAllTranslatedProducts($productData, $product);
                } else {
                    $productData->availabilities()->create($product);
                }
            } else {
                $variation_option = Variation::findOrFail($product['variation_option_id']);
                unset($product['variation_option_id']);
                if (TRANSLATION_ENABLED) {
                    $this->processAllTranslatedVariations($variation_option, $product);
                } else {
                    $variation_option->availabilities()->create($product);
                }
            }
        } catch (\Throwable $th) {
            throw new MarvelException(NOT_FOUND);
        }
    }

    /**
     * processAllTranslatedProducts
     *
     * @param  mixed $product
     * @param  mixed $orderedItem
     * @return void
     */
    public function processAllTranslatedProducts($product, $orderedItem)
    {
        $translatedProducts = Product::where('sku', $product->sku)->get();
        foreach ($translatedProducts as $translatedProduct) {
            $orderedItem['language'] = $translatedProduct->language;
            $orderedItem['product_id'] = $translatedProduct->id;
            $translatedProduct->availabilities()->create($orderedItem);
        }
    }

    /**
     * processAllTranslatedVariations
     *
     * @param  mixed $variation
     * @param  mixed $orderedItem
     * @return void
     */
    public function processAllTranslatedVariations($variation, $orderedItem)
    {
        $translatedVariations = Variation::where('sku', $variation->sku)->get();
        foreach ($translatedVariations as $translatedVariation) {
            $orderedItem['language'] = $translatedVariation->language;
            $translatedVariation->availabilities()->create($orderedItem);
        }
    }


    /**
     * createChildOrder
     *
     * @param  mixed $id
     * @param  mixed $request
     * @return void
     * @throws Exception
     */
    public function createChildOrder($id, $request): void
    {
        $products = $request->products;
        $productsByShop = [];
        $language = $request->language ?? config('shop.default_language');

        foreach ($products as $key => $cartProduct) {
            $product = Product::findOrFail($cartProduct['product_id']);
            $productsByShop[$product->shop_id][] = $cartProduct;
        }

        foreach ($productsByShop as $shop_id => $cartProduct) {
            $amount = array_sum(array_column($cartProduct, 'subtotal'));
            $orderInput = [
                'tracking_number'  => $this->generateTrackingNumber(),
                'shop_id'          => $shop_id,
                'order_status'     => $request->order_status,
                'payment_status'   => $request->payment_status,
                'customer_id'      => $request->customer_id,
                'shipping_address' => $request->shipping_address,
                'billing_address'  => $request->billing_address,
                'customer_contact' => $request->customer_contact,
                'customer_name'    => $request->customer_name,
                'delivery_time'    => $request->delivery_time,
                'delivery_fee'     => 0,
                'sales_tax'        => 0,
                'discount'         => 0,
                'parent_id'        => $id,
                'amount'           => $amount,
                'total'            => $amount,
                'paid_total'       => $amount,
                'language'         => $language,
                "payment_gateway"  => $request->payment_gateway,
            ];

            $order = $this->create($orderInput);
            $order->products()->attach($this->processProducts($cartProduct,  $request['customer_id'],  $order));
            event(new OrderReceived($order));
        }
    }

    /**
     * Helper method to generate unique tracking number
     *
     * @return string
     * @throws Exception
     */
    public function generateTrackingNumber(): string
    {
        $today = date('Ymd');
        $trackingNumbers = Order::where('tracking_number', 'like', $today . '%')->pluck('tracking_number');

        do {
            $trackingNumber = $today . random_int(100000, 999999);
        } while ($trackingNumbers->contains($trackingNumber));

        return $trackingNumber;
    }

    public function findAddressFromId($addressId,$key){
        $address = Address::find($addressId);
        if($address){
            return $address->$key;
        }
        return '';
    }

    protected function processRequestProducts($products)
    {
        $mergedProducts = [];

        foreach ($products as $product) {
            $key = "{$product['product_id']}_{$product['product_attribute_id']}";

            if (isset($mergedProducts[$key])) {
                // If the product already exists, update the quantity
                $mergedProducts[$key]['quantity'] += $product['quantity'];
            } else {
                // If the product does not exist, add it to the merged array
                $mergedProducts[$key] = $product;
            }
        }

        // Convert the merged array back to indexed array
        $mergedProducts = array_values($mergedProducts);

        // Perform additional logic (e.g., fetch product attributes) if needed
        foreach ($mergedProducts as $key => $product) {
            try {
                if (isset($product['product_attribute_id'])) {
                    $prodAttribute = ProductAttribute::find($product['product_attribute_id']);
                    $mergedProducts[$key]['unit_price'] = $prodAttribute->price ? $prodAttribute->price : 0;
                    $mergedProducts[$key]['discounted_price'] = $prodAttribute->discounted_price ? ($prodAttribute->discounted_price->discounted_price) : $prodAttribute->price;
                }
            } catch (Exception $e) {
                throw new MarvelException(NOT_FOUND);
            }
        }
        return $mergedProducts;
    }
}
