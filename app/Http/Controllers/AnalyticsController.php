<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Database\Models\Shop;
use App\Database\Repositories\AddressRepository;
use App\Enums\OrderStatus;
use App\Enums\Permission;
use App\Exceptions\MarvelException;
use Spatie\Permission\Models\Permission as ModelsPermission;

class AnalyticsController extends CoreController
{
    public $repository;

    public function __construct(AddressRepository $repository)
    {
        $this->repository = $repository;
    }


    public function analytics(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $day = $request->day;
        $month = $request->month;
        $year = $request->year;
        $past_day = $request->past_day;
        $past_month = $request->past_month;
        $past_year = $request->past_year;

        switch($request->filter)
        {
            case 'day' :
                if (! $this->auth->check())
                    return redirect('quiz');
                $tests = $this->test->doneTest($this->auth->user());
                $name = 'Các đề bạn đã làm';
                break;

            case 'month' :
                $tests = $this->test->orderBy('tests.created_at','DESC');
                $name = 'Quiz';
                break;

            case 'year' :
                $tests = $this->test->orderBy('tests.created_at','DESC');
                $name = 'Quiz';
                break;
            case 'past_day' :
                $tests = $this->test->orderBy('tests.created_at','DESC');
                $name = 'Quiz';
                break;
            default :
                return redirect('quiz');
        }
        $user = $request->user();
        if ($user && ($user->hasPermissionTo(Permission::SUPER_ADMIN) || $user->hasPermissionTo(Permission::STORE_OWNER))) {
            $dbRevenueQuery = DB::table('orders as A')
                ->whereDate('A.created_at', '>', Carbon::now()->subDays(30))
                ->where('A.order_status', OrderStatus::COMPLETED)
                ->where('A.parent_id', '!=', null)
                ->join('orders as B', 'A.parent_id', '=', 'B.id')
                ->where('B.order_status', OrderStatus::COMPLETED)
                ->select(
                    'A.id',
                    'A.parent_id',
                    'A.paid_total',
                    'B.delivery_fee',
                    'B.sales_tax',
                    'A.created_at')->get();

            if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN)) {
                $totalRevenue = $dbRevenueQuery->sum('paid_total') +
                    $dbRevenueQuery->unique('parent_id')->sum('delivery_fee') + $dbRevenueQuery->unique('parent_id')->sum('sales_tax');
            }

            $totalRefundQuery = DB::table('refunds')->whereDate('created_at', '>', Carbon::now()->subDays(30));
            if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN)) {
                $totalRefunds = $totalRefundQuery->where('shop_id', null)->sum('amount');
            }
            // else {
            //     $totalRevenue = $totalRevenueQuery->where('shop_id', '=', $user->id)->sum('paid_total');
            // }

           $todaysRevenueQuery =  DB::table('orders as A')
                    ->whereDate('A.created_at', '>', Carbon::now()->subDays(1))
                    ->where('A.order_status', OrderStatus::COMPLETED)
                    ->where('A.parent_id', '!=', null)
                    ->join('orders as B', 'A.parent_id', '=', 'B.id')
                    ->where('B.order_status', OrderStatus::COMPLETED)
                    ->select(
                        'A.id',
                        'A.parent_id',
                        'A.paid_total',
                        'B.delivery_fee',
                        'B.sales_tax',
                        'A.created_at')->get();

            if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN)) {
                $todaysRevenue =  $todaysRevenueQuery->sum('paid_total') +
                    $todaysRevenueQuery->unique('parent_id')->sum('delivery_fee') + $todaysRevenueQuery->unique('parent_id')->sum('sales_tax');
            }
            // else {
            //     $todaysRevenue = $todaysRevenueQuery->where('shop_id', '=', $user->id)->sum('paid_total');
            // }
            $totalOrdersQuery = DB::table('orders')->whereDate('created_at', '>', Carbon::now()->subDays(30));
            if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN)) {
                $totalOrders = $totalOrdersQuery->where('parent_id', null)->count();
            }
            //  else {
            //     $totalOrders = $totalOrdersQuery->where('shop_id', '=', $user->id)->count();
            // }
            if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN)) {
                $totalShops = Shop::count();
            } else {
                $totalShops = Shop::where('owner_id', '=', $user->id)->count();
            }
            $customerPermission = ModelsPermission::where('name', Permission::CUSTOMER)->first();
            $newCustomers = $customerPermission->users()->whereDate('created_at', '>', Carbon::now()->subDays(30))->count();
            $newYearSaleByMonthQuery =
                DB::table('orders as A')
                    ->where('A.order_status', OrderStatus::COMPLETED)
                    ->where('A.parent_id', '!=', null)
                    ->join('orders as B', 'A.parent_id', '=', 'B.id')
                    ->where('B.order_status', OrderStatus::COMPLETED)
                    ->select(
                        'A.id',
                        'A.parent_id',
                        'A.payment_status',
                        'A.payment_gateway',
                        'A.payment_status',
                        'A.paid_total',
                        'A.created_at',
                        'A.shop_id',
                    )
                    ->selectRaw(
                        "
                         DATE_FORMAT(A.created_at,'%M') as month",
                    )->whereYear('A.created_at', date('Y'))->get();


            $calculateDeliveryFee = DB::table('orders as A')
                ->where('A.order_status', OrderStatus::COMPLETED)
                ->where('A.parent_id', null)
                ->join('orders as B', 'A.id', '=', 'B.parent_id')
                ->where('B.order_status', OrderStatus::COMPLETED)
                ->whereYear('A.created_at', date('Y'))
                ->select(
                    'A.id',
                    'A.parent_id',
                    'A.payment_status',
                    'A.payment_gateway',
                    'A.paid_total',
                    'A.delivery_fee',
                    'A.sales_tax',
                    'A.created_at',
                    'A.shop_id',

                )
                ->groupBy('A.id')
                ->selectRaw(
                    "DATE_FORMAT(A.created_at,'%M') as month",
                )
                ->get()->groupBy('month');
            $monthlyDeliveryFee = [];
            $monthlySalesTax = [];
            foreach ($calculateDeliveryFee as $key => $value) {
                $monthlyDeliveryFee[$key] = $value->sum('delivery_fee');
                $monthlySalesTax[$key] = $value->sum('sales_tax');
            }
            if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN)) {
                $totalYearSaleByMonth = $newYearSaleByMonthQuery->groupBy('month');
            } else {
                $totalYearSaleByMonth = $newYearSaleByMonthQuery->where('shop_id', '=', $user->id)->groupBy('month')->get();

                $monthlySalesTax = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);
                $monthlyDeliveryFee = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);
            }

            $months = [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
            ];

            $processedData = [];
            $monthlySaleOnly = [];
            foreach ($totalYearSaleByMonth as $key => $value) {
                $tax = 0;
                $deliveryFee = 0;
                if (isset($monthlySalesTax[$key])) {
                    $tax = $monthlySalesTax[$key];
                }
                if (isset($monthlyDeliveryFee[$key])) {
                    $deliveryFee = $monthlyDeliveryFee[$key];
                }
                $monthlySaleOnly[$key] = $value->sum('paid_total')+ $tax + $deliveryFee;
            }


            foreach ($months as $key => $month) {
                foreach ($monthlySaleOnly as $keyMonth => $value) {
                    if ($keyMonth === $month) {
                        $processedData[$key] = ['total' => $value, 'month' => $month];
                    }
                }
            }
            foreach ($months as $key => $month) {
                if (!isset($processedData[$key])) {
                    $processedData[$key] = ['total' => 0, 'month' => $month];
                }
            }
            ksort($processedData);
            return [
                'totalRevenue' => $totalRevenue,
                'totalRefunds' => $totalRefunds,
                'totalShops' => $totalShops,
                'todaysRevenue' => $todaysRevenue,
                'totalOrders' => $totalOrders,
                'newCustomers' =>  $newCustomers,
                'totalYearSaleByMonth' => $processedData
            ];
        }
        throw new MarvelException(NOT_AUTHORIZED);
    }
}
