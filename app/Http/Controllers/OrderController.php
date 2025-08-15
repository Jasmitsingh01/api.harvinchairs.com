<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cart;
use App\Enums\Permission;
use App\Enums\OrderStatus;
use Illuminate\Support\Str;
use App\Enums\PaymentStatus;
use App\Exports\OrderExport;
use App\Traits\PaymentTrait;
use App\Traits\WalletsTrait;
use Illuminate\Http\Request;
use App\Database\Models\Order;
use App\Events\OrderCancelled;
use App\Traits\TranslationTrait;
use App\Database\Models\Settings;
use App\Enums\PaymentGatewayType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Exceptions\MarvelException;
use App\Traits\OrderManagementTrait;
use Maatwebsite\Excel\Facades\Excel;
use App\Database\Models\DownloadToken;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderUpdateRequest;
use Illuminate\Database\Eloquent\Collection;
use App\Database\Repositories\OrderRepository;
use App\Traits\PaymentStatusManagerWithOrderTrait;
use niklasravnsborg\LaravelPdf\Facades\Pdf as PDF;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class OrderController extends CoreController
{
    use WalletsTrait,
        OrderManagementTrait,
        TranslationTrait,
        PaymentStatusManagerWithOrderTrait,
        PaymentTrait;

    public OrderRepository $repository;
    public Settings $settings;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
        $this->settings = Settings::first();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Order[]
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?   $request->limit : 10;
        $orders = $this->fetchOrders($request)->paginate($limit);

        foreach($orders as $order) {
            $products = $order->products;

            foreach ($products as $product) {
                $pivotData = $product->pivot;
                foreach($product->attributeCombinations as $combination){
                    if($combination->id == $product->pivot->product_attribute_id){
                        $pivotData->all_combination =  $combination->all_combination;
                        $pivotData->product_attribute_combination = $combination;
                        $pivotData->order_status = $order->order_status;
                    }
                }
                unset($product->attributeCombinations);
            }
        }
        return $orders;
    }

    /**
     * fetchOrders
     *
     * @param  mixed $request
     * @return object
     */
    public function fetchOrders(Request $request)
    {
        $user = $request->user();
        $order = $this->repository;
        if(isset($request->order_reference)){
            $order = $order->where('tracking_number','like',"%".$request->order_reference."%");
        }
        if(isset($request->order_date)){
            $order = $order->whereDate('created_at',$request->order_date);
        }
        if(isset($request->filter_start_date)){
            $order = $order->whereDate('created_at','>=',$request->filter_start_date)->whereDate('created_at','<=',$request->filter_end_date);
        }
        $order = $order->where(function ($query) {
            $query->where('payment_gateway', '!=', 'PAYPAL')
                  ->orWhere('payment_status', '!=', 'payment-pending');
        });
        if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN) && (!isset($request->shop_id) || $request->shop_id === 'undefined')) {
            return $order->with(['orderCarrier'])->where('id', '!=', null)->where('parent_id', '=', null); //->paginate($limit);
        } else if ($this->repository->hasPermission($user, $request->shop_id)) {
            // if ($user && $user->hasPermissionTo(Permission::STORE_OWNER)) {
            return $order->where('shop_id', '=', $request->shop_id)->where('parent_id', '!=', null); //->paginate($limit);
            // } elseif ($user && $user->hasPermissionTo(Permission::STAFF)) {
            //     return $this->repository->with('children')->where('shop_id', '=', $request->shop_id)->where('parent_id', '!=', null); //->paginate($limit);
            // }
        } else {
            return $order->with(['products','orderCarrier'])->where('customer_id', '=', $user->id)->where('parent_id', '=', null); //->paginate($limit);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  OrderCreateRequest  $request
     * @return LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws MarvelException
     */
    public function store(OrderCreateRequest $request)
    {
        return $this->repository->storeOrder($request, $this->settings);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param $params
     * @return JsonResponse
     * @throws MarvelException
     */
    public function show(Request $request, $params)
    {
        $request["tracking_number"] = $params;
        $order =  $this->fetchSingleOrder($request);
        $products = $order->products;

        foreach ($products as $product) {
            $pivotData = $product->pivot;
            foreach($product->attributeCombinations as $combination){
                if($combination->id == $product->pivot->product_attribute_id){
                    $pivotData->all_combination =  $combination->all_combination;
                    $pivotData->product_attribute_combination = $combination;
                }
            }
            unset($product->attributeCombinations);
        }
        return $order;
    }

    /**
     * fetchSingleOrder
     *
     * @param mixed $request
     * @return void
     * @throws MarvelException
     */
    public function fetchSingleOrder($request)
    {
        $user = $request->user() ?? null;
        //$language = $request->language ?? DEFAULT_LANGUAGE;
        $language = $request->language ?? 'en'; // default define in constant but it's not getting proper so taken static
        $orderParam = $request->tracking_number ?? $request->id;
        try {
            $order = $this->repository->where('language', $language)->with([
                'products',
                'children.shop',
                'wallet_point',
                'orderCarrier',
                'coupon' => function ($query) {
                    $query->select('id', 'code', 'discount','discount_type');
                }
            ])->where('id', $orderParam)->orWhere('tracking_number', $orderParam)->firstOrFail();
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }

        // Create Intent
        if (!in_array($order->payment_gateway, [
            PaymentGatewayType::CASH, PaymentGatewayType::CASH_ON_DELIVERY, PaymentGatewayType::FULL_WALLET_PAYMENT,PaymentGatewayType::BANKWIRE_TRANSFER
        ])) {
            // $order['payment_intent'] = $this->processPaymentIntent($request, $this->settings);
        }

        if (!$order->customer_id) {
            return $order;
        }
        if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN)) {
            return $order;
        } elseif (isset($order->shop_id)) {
            if ($user && ($this->repository->hasPermission($user, $order->shop_id) || $user->id == $order->customer_id)) {
                return $order;
            }
        } elseif ($user && $user->id == $order->customer_id) {
            return $order;
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }

    /**
     * findByTrackingNumber
     *
     * @param  mixed $request
     * @param  mixed $tracking_number
     * @return void
     */
    public function findByTrackingNumber(Request $request, $tracking_number)
    {
        $user = $request->user() ?? null;
        try {
            $order = $this->repository->with(['products', 'children.shop', 'wallet_point', 'payment_intent'])
                ->findOneByFieldOrFail('tracking_number', $tracking_number);

            if ($order->customer_id === null) {
                return $order;
            }
            if ($user && ($user->id === $order->customer_id || $user->can('super_admin'))) {
                return $order;
            } else {
                throw new MarvelException(NOT_AUTHORIZED);
            }
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OrderUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(OrderUpdateRequest $request, $id)
    {
        $request->id = $id;
        return $this->repository->updateOrder($request);
    }

    // TODO: Remove this duplicate
    public function updateOrderGql(OrderUpdateRequest $request)
    {
        return $this->repository->updateOrder($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            return $this->repository->findOrFail($id)->delete();
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }

    /**
     * Export order dynamic url
     *
     * @param Request $request
     * @param int $shop_id
     * @return string
     */
    public function exportOrderUrl(Request $request, $shop_id = null)
    {
        $user = $request->user();

        if ($user && !$this->repository->hasPermission($user, $request->shop_id)) {
            throw new MarvelException(NOT_AUTHORIZED);
        }

        $dataArray = [
            'user_id' => $user->id,
            'token'   => Str::random(16),
            'payload' => $request->shop_id
        ];
        $newToken = DownloadToken::create($dataArray);

        return route('export_order.token', ['token' => $newToken->token]);
    }

    /**
     * Export order to excel sheet
     *
     * @param string $token
     * @return void
     */
    public function exportOrder($token)
    {
        $shop_id = 0;
        try {
            $downloadToken = DownloadToken::where('token', $token)->first();

            $shop_id = $downloadToken->payload;
            if ($downloadToken) {
                $downloadToken->delete();
            } else {
                return ['message' => TOKEN_NOT_FOUND];
            }
        } catch (Exception $e) {
            throw new MarvelException(TOKEN_NOT_FOUND);
        }

        try {
            return Excel::download(new OrderExport($this->repository, $shop_id), 'orders.xlsx');
        } catch (Exception $e) {
            return ['message' => NOT_FOUND];
        }
    }

    /**
     * Export order dynamic url
     *
     * @param Request $request
     * @param int $shop_id
     * @return string
     */
    public function downloadInvoiceUrl(Request $request)
    {
        $user = $request->user();

        if (empty($request->order_id)) {
            throw new NotFoundException(NOT_FOUND);
        }

        $language = $request->language ?? config('shop.default_language');
        $isRTL = $request->is_rtl ?? false;
        $translatedText = $this->formatInvoiceTranslateText($request->translated_text);

        $payload = [
            'user_id'           => $user->id,
            'order_id'          => intval($request->order_id),
            'language'          => $language,
            'translated_text'   => $translatedText,
            'is_rtl'            => $isRTL
        ];
        $data = [
            'user_id' => $user->id,
            'token'   => Str::random(16),
            'payload' => serialize($payload)
        ];

        $newToken = DownloadToken::create($data);
        return route('download_invoice.token', ['token' => $newToken->token]);
    }

    /**
     * Export order to excel sheet
     *
     * @param string $token
     * @return void
     */
    public function downloadInvoice($token)
    {
        $payloads = [];
        try {
            $downloadToken = DownloadToken::where('token', $token)->first();
            $payloads      = unserialize($downloadToken->payload);

            if ($downloadToken) {
                $downloadToken->delete();
            } else {
                return ['message' => TOKEN_NOT_FOUND];
            }
        } catch (Exception $e) {
            throw new MarvelException(TOKEN_NOT_FOUND);
        }
        try {
            $settings = Settings::getData($payloads['language']);
            $order = $this->repository->with(['products', 'children.shop', 'wallet_point'])->where('id', $payloads['order_id'])->firstOrFail();
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }

        $invoiceData = [
            'order'           => $order,
            'settings'        => $settings,
            'translated_text' => $payloads['translated_text'],
            'is_rtl'          => $payloads['is_rtl'],
            'language'        => $payloads['language'],
        ];

        $pdf = PDF::loadView('pdf.order-invoice', $invoiceData);
        $filename = 'invoice-order-' . $payloads['order_id'] . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * submitPayment
     *
     * @param  mixed  $request
     * @return void
     * @throws Exception
     */
    public function submitPayment(Request $request)
    {
        $tracking_number = $request->tracking_number ?? null;
        try {
            $order = $this->repository->with(['products', 'children.shop', 'wallet_point', 'payment_intent'])
                ->findOneByFieldOrFail('tracking_number', $tracking_number);

            switch ($order->payment_gateway) {
                case PaymentGatewayType::STRIPE:
                    $this->stripe($order, $request, $this->settings);
                    break;
                case PaymentGatewayType::PAYPAL:
                    $this->paypal($order, $request, $this->settings);
                    break;
                case PaymentGatewayType::RAZORPAY:
                    $this->razorpay($order, $request, $this->settings);
            }

            return response()->json(['message' => 'submit payment successfully...'], 200);
        } catch (\Exception $e) {
            throw new Exception($e);
        }
    }
    public function cancelPayment(Request $request)
    {
        $tracking_number = $request->tracking_number ?? null;
        try {
            $order = $this->repository->findOneByFieldOrFail('tracking_number', $tracking_number);
            if(isset($order)){

                $cart = Cart::find($order->cart_id);
                $cart->update(['is_confirm'=>false]);
                $this->updatePaymentOrderStatus($request, OrderStatus::PENDING, PaymentStatus::FAILED);
            }
            return response()->json(['message' => 'order deleted.'], 200);

        } catch (\Exception $e) {
            throw new Exception($e);
        }
    }
    public function updatePaymentOrderStatus($request, $orderStatus, $paymentStatus): void
    {
        $order = Order::where('tracking_number', '=', $request->tracking_number)->first();
        event(new OrderCancelled($order));
        $this->webhookSuccessResponse($order, $orderStatus, $paymentStatus);
    }

    public function cancelOrder($order_id,Request $request){
        // update cancelled-order status when order status is not shipped
        try{

            $order = Order::findOrFail($order_id);
            if($order->getRawOriginal('order_status') == OrderStatus::CANCELLED){
                return response()->json(['status' =>true,'message' => 'order already cancelled.'], 200);
            }
            if ($order->can_cancel_order == true) {
                $order_update = [
                    'order_status' => OrderStatus::CANCELLED,
                    'cancelled_amount' => $order->paid_total,
                    'cancellation_date' => now(),
                    'shipping_tracking_number' => null,
                    'shipping_tracking_url' => null
                ];
                if(isset($request->cancel_reason)){
                    $order_update['cancel_reason'] = $request->cancel_reason;
                }
                $order->update($order_update);
                // cancel order notification
                event(new OrderCancelled($order));
                return response()->json(['status' =>true,'message' => 'order cancelled.'], 200);
            } else {
                return response()->json(['status' =>false,'message' => 'order can not be cancelled.'], 400);
            }
        } catch(\Exception $e){
            dd($e->getMessage());
            return response()->json(['status' =>false,'message' => 'order not found.'], 400);
        }
    }
}
