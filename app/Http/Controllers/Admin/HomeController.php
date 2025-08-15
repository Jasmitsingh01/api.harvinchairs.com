<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactUs;
use App\Models\LastViewedProduct;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\OrderProduct;
use App\Models\User;
use App\Models\ProductEnquire;
use Carbon\Carbon;
use DB;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Coupon;

class HomeController
{
    public function index(Request $request)
    {
        // abort_if(Gate::denies('user_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        //$startDate = \Carbon\Carbon::today()->subDays(7);
        $startDate = now()->startOfYear();
        $endDate = now()->endOfYear();
        //$searchType = 'current_day';
        $searchType = 'current_year';

        // Filter According to Requested Date
        if ($request->has('search_type')) {
            $searchType = $request->input('search_type');

            if ($searchType === 'current_month') {
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
            } elseif ($searchType === 'current_year') {
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
            } elseif ($searchType === 'previous_day') {
                $startDate = now()->subDay()->startOfDay();
                $endDate = now()->subDay()->endOfDay();
            } elseif ($searchType === 'previous_month') {
                $startDate = now()->subMonth()->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();
            } elseif ($searchType === 'previous_year') {
                $startDate = now()->subYear()->startOfYear();
                $endDate = now()->subYear()->endOfYear();
            }elseif ($searchType === 'current_day') {
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
            }

        } elseif ($request->has('search_range')) {
            $dateRange=$request->get('search_range');
            list($startDateString, $endDateString) = explode(" - ", $dateRange);
        $startDate = Carbon::createFromFormat('m/d/Y', $startDateString)->startOfDay();
        $endDate = Carbon::createFromFormat('m/d/Y', $endDateString)->endOfDay();
        }
        //End

        // Orders List
        $dashData =  Order::select(['id', 'order_status', 'total'])->where('parent_id',null)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();

        // Total Sale
        $totalSale = $dashData->whereIn('order_status', ['delivered','order-completed', 'payment-accepted'])->sum('total');

        //Total Orders
        $totalOrders = $dashData->count();

        //Pending Orders
        $pendingOrders = $dashData->where('order_status', 'pending')->count();
        $deliverdOrders = $dashData->where('order_status', 'delivered')->count();
        $canceledOrders = $dashData->where('order_status', 'order-cancelled')->count();
        $processingOrders = $dashData->where('order_status', 'order-processing')->count();
        $shippedOrders = $dashData->where('order_status', 'shipped')->count();
        $failedOrders = $dashData->where('order_status', 'order-failed')->count();
        $refundedOrders = $dashData->where('order_status', 'order-refunded')->count();


        //Recent Orders
            $recentOrders = Order::select('amount', 'customer_name', 'created_at', 'order_status')
            ->with('products')
            ->where('parent_id', null)
            ->withCount('products')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Best Selling Products
            $bestSellingProduct = DB::table('products')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('categories', 'products.default_category', '=', 'categories.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                'categories.name as category_name',
                DB::raw('SUM(order_product.subtotal) as total_sale'),
                DB::raw('SUM(order_product.order_quantity) as product_quantity')
            )
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.parent_id', null)
            ->whereIn('orders.order_status', ['delivered','order-completed', 'payment-accepted'])
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sale', 'desc')
            ->take(5)
            ->get();
        // $bestSellingProduct = Product::whereHas('orders', function ($query) use ($startDate, $endDate) {
        //         $query->whereIn('orders.order_status',['order-completed', 'payment-accepted'])
        //         ->whereBetween('orders.created_at', [$startDate, $endDate]);
        //     })
        //     ->withCount('orders')
        //     ->with(['orders' => function ($query) use ($startDate, $endDate) {
        //         $query->select('orders.id', 'product_id', 'order_quantity', 'subtotal')
        //             ->whereBetween('orders.created_at', [$startDate, $endDate]);
        //     }])
        //     ->orderBy('orders_count', 'desc');
        //    $bestSellingProduct->each(function ($product) {
        //         $totalOrderQuantity = 0;
        //         $totalSubtotal = 0;

        //         foreach ($product->orders as $order) {
        //             $totalOrderQuantity += $order->pivot->order_quantity;
        //             $totalSubtotal += $order->pivot->subtotal;
        //         }
        //         $product->totalOrderQuantity = $totalOrderQuantity;
        //         $product->totalSubtotal = $totalSubtotal;
        //     });
        // end best Selling Products

          // Sales By Category
          $salesbyCategory = DB::table('categories')
          ->join('category_product', 'categories.id', '=', 'category_product.category_id')
          ->join('order_product', 'category_product.id', '=', 'order_product.product_id')
          ->join('orders', 'order_product.order_id', '=', 'orders.id')
          ->select(
              'categories.id as category_id',
              'categories.name as category_name',
              DB::raw('SUM(order_product.subtotal) as total_sale'),
              DB::raw('COUNT(DISTINCT order_product.product_id) as product_count')
          )
          ->whereBetween('orders.created_at', [$startDate, $endDate])
          ->where('orders.parent_id', null)
          ->whereIn('orders.order_status', ['delivered','order-completed', 'payment-accepted'])
          ->groupBy('categories.id', 'categories.name')
          ->orderBy('total_sale', 'desc')
          ->take(5)
          ->get();

         // end bestSales By Category




        //Most Viewed Products counts , purchased count and added to cart
        // $mostViewedProducts = DB::table('last_viewed_products')
        // ->join('products', 'last_viewed_products.product_id', '=', 'products.id')
        // ->rightJoin('cart_products', 'last_viewed_products.product_id', '=', 'cart_products.product_id')
        // ->rightJoin('order_product', 'last_viewed_products.product_id', '=', 'order_product.product_id')
        // ->rightJoin('orders', 'order_product.order_id', '=', 'orders.id')
        // ->select(
        //     'products.id as product_id',
        //     'products.name as product_name',
        //     DB::raw('COUNT(DISTINCT last_viewed_products.id) as view_count'),
        //     DB::raw('COUNT(DISTINCT order_product.product_id, order_product.order_id) as purchased_count'),
        //     DB::raw('COUNT(DISTINCT cart_products.product_id, cart_products.cart_id) as added_to_cart_count')
        // )
        // //->whereBetween('orders.created_at', [$startDate, $endDate])
        // ->whereBetween('last_viewed_products.created_at', [$startDate, $endDate])
        // //->whereBetween('cart_products.created_at', [$startDate, $endDate])
        // //->where('orders.parent_id', null)
        // //->where('orders.order_status',['order-completed', 'payment-accepted'] )
        // ->groupBy('products.id', 'products.name')
        // ->orderBy('view_count', 'desc')
        // ->take(5)
        // ->get();
        $mostViewedProducts = LastViewedProduct::with('product')
        ->whereHas('product',function($q){
            $q->where('deleted_at',null);
        })
        ->select('*', DB::raw('count(product_id) as view_count'))->whereBetween('created_at', [$startDate, $endDate])->groupBy('product_id')->orderBy('view_count', 'desc')->take(5)->get();

        foreach ($mostViewedProducts as $product) {
            $productId = $product->product_id;

            // Get count of Cart Products for this product
            $cartProductsCount = CartProduct::where('product_id', $productId)->count();

            // Get count of Order Products for this product
            $orderProductsCount = OrderProduct::where('product_id', $productId)->whereHas('order',function($q){
                $q->where('order_status',['order-completed', 'payment-accepted']);
            })->count();

            // Assign the counts to the existing object
            $product->cart_count = $cartProductsCount;
            $product->order_count = $orderProductsCount;
        }
        //dd($mostViewedProducts);
        //end Most viewed Counts


        //Recent Reviews
        $recentReviews = DB::table('reviews')
        ->leftJoin('products', 'reviews.product_id', '=', 'products.id')
        ->select(
            'products.name as product_name',
            'products.id as product_id',
            'reviews.customer_name as customer_name',
            'reviews.comment as comment',
            'reviews.rating as rating',
            'reviews.is_active as is_active',
            'reviews.id as review_id',
        )
        ->whereBetween('reviews.created_at', [$startDate, $endDate])
        ->where('reviews.deleted_at',null)
        ->take(5)
        ->get();
        //end Recent Reviews
        // contact Us Counts
       $contactUs = DB::table('contact_us')
       ->select('id')
       ->whereBetween('created_at', [$startDate, $endDate])
       ->where('deleted_at',null)
       ->count();

       //Products Enquire Count
       $enquire = DB::table('enquiries')
       ->select('id')
       ->whereBetween('created_at', [$startDate, $endDate])
       ->where('deleted_at',null)
       ->count();

       //Abandoned Carts
    //    $abandonedCarts =Cart::select('id')
    //    ->whereBetween('created_at', [$startDate, $endDate])
    //    ->count();

       //New Customers
       $customers = User::where(function ($query) {
        $query->whereHas('roles', function ($subQuery) {
            $subQuery->where('title', 'Customer');
        })->orWhereDoesntHave('roles');
    })
    ->whereBetween('created_at', [$startDate, $endDate])
    ->count();


        //Products
        $products =  DB::table('products')->select('id')->where('deleted_at',null)->whereBetween('created_at', [$startDate, $endDate]);


        $customersTotal = User::where(function ($query) {
            $query->whereHas('roles', function ($subQuery) {
                $subQuery->where('title', 'Customer');
            })->orWhereDoesntHave('roles');
        })->where('deleted_at',null);

       //New Subscriptions
       $subscriptions = User::where('newsletter',1)->whereBetween('created_at', [$startDate, $endDate])->count();


       // coupon stats
        $totalCouponsIssued = Coupon::count();
        $couponsRedeemed = Order::whereNotNull('coupon_id')
            ->whereIn('orders.order_status', ['delivered','order-completed', 'payment-accepted'])
            ->distinct('coupon_id')
            ->count('coupon_id');

        $redemptionRate = Order::leftJoin('coupons', 'orders.coupon_id', '=', 'coupons.id')
        ->whereNotNull('orders.coupon_id')
        ->selectRaw('(COUNT(DISTINCT orders.coupon_id) / (SELECT COUNT(*) FROM coupons)) * 100 AS redemption_rate')
        ->whereIn('orders.order_status', ['delivered','order-completed', 'payment-accepted'])
        ->first()
        ->redemption_rate;

        $averageSavingsPerCoupon = Order::whereNotNull('coupon_id')
        ->whereIn('orders.order_status', ['delivered','order-completed', 'payment-accepted'])
        ->avg('discount');

        $totalSavingsToCustomers = Order::whereNotNull('coupon_id')
        ->selectRaw('COUNT(DISTINCT orders.coupon_id) * AVG(orders.discount) AS total_savings')
        ->whereIn('orders.order_status', ['delivered','order-completed', 'payment-accepted'])
        ->first()
        ->total_savings;

        $salesGenerated = Order::whereNotNull('coupon_id')
            ->selectRaw('COUNT(DISTINCT orders.coupon_id) * AVG(orders.total) AS sales_generated')
            ->whereIn('orders.order_status', ['delivered','order-completed', 'payment-accepted'])
            ->first()
            ->sales_generated;

        $topPerformingCoupons = Order::select('orders.coupon_id', DB::raw('COUNT(*) as coupon_count'))
        ->join('coupons', 'orders.coupon_id', '=', 'coupons.id')
        ->whereNotNull('orders.coupon_id')
        ->whereNull('coupons.deleted_at') // Ensures the coupon is not deleted
        ->whereIn('orders.order_status', ['delivered', 'order-completed', 'payment-accepted'])
        ->groupBy('orders.coupon_id')
        ->orderByDesc('coupon_count')
        ->take(5)
        ->get();

        return view('home',compact('dashData','totalSale','totalOrders','deliverdOrders','pendingOrders','canceledOrders','recentOrders','bestSellingProduct','mostViewedProducts','contactUs','enquire','subscriptions','customers','customersTotal','products','salesbyCategory','recentReviews','startDate','endDate','processingOrders','shippedOrders','failedOrders','refundedOrders','totalCouponsIssued','couponsRedeemed','redemptionRate','averageSavingsPerCoupon','totalSavingsToCustomers','salesGenerated','topPerformingCoupons'))->with('i', (request()->input('page', 1) - 1) * 6);
    }
}
