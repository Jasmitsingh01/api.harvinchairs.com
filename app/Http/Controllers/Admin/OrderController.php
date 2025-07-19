<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\EmailLog;
use App\Models\Carrier;
use App\Models\OrderCarrier;
use App\Models\OrderMessage;
use App\Models\OrderStatusHistory;
use App\Models\OrderStatus;
use App\Models\Shop;
use App\Models\User;
use App\Traits\EmailTrait;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\TranslationTrait;
use niklasravnsborg\LaravelPdf\Facades\Pdf as PDF;
use App\Database\Models\Settings;

class OrderController extends Controller
{
    use EmailTrait,TranslationTrait;
    public function index(Request $request)
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = Order::with(['customer', 'shop','orderShippingAddress'])->where('parent_id', null)->select(sprintf('%s.*', (new Order())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                $viewGate      = 'order_show';
                $editGate      = 'order_edit';
                $deleteGate    = 'order_delete';
                $crudRoutePart = 'orders';

                return view('partials.ViewDeleteActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });


            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('tracking_number', function ($row) {
                return $row->tracking_number ? $row->tracking_number : '';
            });
            $table->addColumn('customer_name', function ($row) {
                return $row->customer ? $row->customer->first_name : '';
            });

            $table->editColumn('customer_contact', function ($row) {
                return $row->customer_contact ? $row->customer_contact : '';
            });
            $table->editColumn('customer_name', function ($row) {
                return $row->customer_name ? $row->customer_name : '';
            });
            // $table->editColumn('amount', function ($row) {
            //     return $row->amount ? $row->amount : '';
            // });
            // $table->editColumn('sales_tax', function ($row) {
            //     return $row->sales_tax ? $row->sales_tax : '';
            // });
            // $table->editColumn('paid_total', function ($row) {
            //     return $row->paid_total ? $row->paid_total : '';
            // });
            $table->editColumn('total', function ($row) {
                return $row->total ? $row->total : '';
            });
            // $table->editColumn('cancelled_amount', function ($row) {
            //     return $row->cancelled_amount ? $row->cancelled_amount : '';
            // });
            // $table->editColumn('language', function ($row) {
            //     return $row->language ? $row->language : '';
            // });
            // $table->editColumn('coupon', function ($row) {
            //     return $row->coupon ? $row->coupon : '';
            // });
            // $table->editColumn('parent', function ($row) {
            //     return $row->parent ? $row->parent : '';
            // });
            // $table->addColumn('shop_name', function ($row) {
            //     return $row->shop ? $row->shop->name : '';
            // });

            // $table->editColumn('discount', function ($row) {
            //     return $row->discount ? $row->discount : '';
            // });
            $table->editColumn('payment_gateway', function ($row) {
                return $row->payment_gateway ? $row->payment_gateway : '';
            });
            $table->editColumn('shipping_address', function ($row) {
                return $row->orderShippingAddress ? $row->orderShippingAddress['country'] : '';
            });
            // $table->editColumn('billing_address', function ($row) {
            //     return $row->billing_address ? $row->billing_address : '';
            // });
            // $table->editColumn('logistics_provider', function ($row) {
            //     return $row->logistics_provider ? $row->logistics_provider : '';
            // });
            // $table->editColumn('delivery_fee', function ($row) {
            //     return $row->delivery_fee ? $row->delivery_fee : '';
            // });
            $table->editColumn('delivery_time', function ($row) {
                return $row->delivery_time ? $row->delivery_time : '';
            });
            $table->editColumn('order_status', function ($row) {
                return $row->order_status ? Order::ORDER_STATUS_RADIO[$row->order_status] : '';
            });
            // $table->editColumn('payment_status', function ($row) {
            //     return $row->payment_status ? Order::PAYMENT_STATUS_RADIO[$row->payment_status] : '';
            // });

            $table->rawColumns(['actions', 'placeholder', 'customer', 'shop']);

            return $table->make(true);
        }

        if ($request->has('order_id')) {
            $id = $request->input('order_id');
                $order = Order::findOrFail($id);
               $result= $order->update(['notification' => 0]);
               return redirect()->route('admin.orders.index');
        }

        $orders = Order::with(['customer', 'shop','orderShippingAddress','orderBillingAddress'])->where('parent_id', null)->get();
        // $users = User::get();
        // $shops = Shop::get();

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        abort_if(Gate::denies('order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shops = Shop::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.orders.create', compact('customers', 'shops'));
    }

    public function store(StoreOrderRequest $request)
    {
        abort_if(Gate::denies('order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $order = Order::create($request->all());

        return redirect()->route('admin.orders.index');
    }

    public function edit(Order $order)
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shops = Shop::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $order->load('customer', 'shop');

        return view('admin.orders.edit', compact('customers', 'order', 'shops'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->update($request->all());

        return redirect()->route('admin.orders.index');
    }

    public function show(Order $order)
    {
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order = Order::with(['customer','products','orderShippingAddress','orderBillingAddress','orderStatusHistory','orderPaymentHistory','orderCarrier','messages','orderMessage'])->withCount('products')->where('id', $order->id)->first();
        // $validOrderPlaced= Order::where('customer_id',$order->customer->id)->sum('total')->count('id as validorder');
        if($order->payment_gateway == 'PAYPAL'){
            $orderStatuses = OrderStatus::where('online_payment', '1')->get();
        }elseif($order->payment_gateway == 'BANKWIRE_TRANSFER'){
            $orderStatuses = OrderStatus::whereNotNull('online_payment')->get();
        }else{
            $orderStatuses = OrderStatus::all();
        }
         $shippingCarriers =Carrier::all();

        $validOrderPlaced = Order::where('customer_id', $order->customer->id)
                            ->select('id')
                            ->selectRaw('SUM(paid_total) as totalSum, COUNT(id) as validOrder')
                            ->whereIn('order_status',['delivered','shipped'])
                            ->first();
        // $order->load('customer', 'shop');
        return view('admin.orders.show', compact('order', 'validOrderPlaced', 'orderStatuses','shippingCarriers'));
    }

    public function destroy(Order $order)
    {
        abort_if(Gate::denies('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->delete();

        return back();
    }

    public function massDestroy(MassDestroyOrderRequest $request)
    {
        abort_if(Gate::denies('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::find(request('ids'));

        foreach ($orders as $order) {
            $order->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function updateOrderStatus(Request $request)
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $request->validate([
            'order_id' => 'required',
            'status' => 'required',
        ]);
        $orderStatus = OrderStatusHistory::where('order_id', $request->input('order_id'))->latest()->first();

        $paymentStatus = $orderStatus ? $orderStatus->payment_status : '';
        $status = OrderStatus::where('name',$request->status)->first();

        if(isset($status) && $status->paid == true){
            $paymentStatus = PaymentStatus::SUCCESS;
        }
        $data = [
            'order_id' => $request->input('order_id'),
            'status' => $request->input('status'),
            'payment_status' => $paymentStatus,
        ];

        OrderStatusHistory::create($data);

        $order = Order::with('orderCarrier')->findOrFail($request->input('order_id'));
        // dd($request->input('status'));
        $updateArray = ['order_status'=>$request->input('status')];
        if(!empty($paymentStatus)){
            $updateArray['payment_status'] =  $paymentStatus;
        }

        // check status and update shipping tracking number
        if($request->status == 'shipped' && isset($request->tracking_number) && isset($request->tracking_url)){
            $updateArray['shipping_tracking_number'] = $request->tracking_number;
            $updateArray['shipping_tracking_url'] = $request->tracking_url;
            // change forat to yyyy-mm-dd
            $updateArray['expected_date'] = date('Y-m-d', strtotime($request->expected_date));
        }
        // check status and update shipping tracking number end
        $order->update($updateArray);
        $tags = [
            'app_url' => env("APP_URL"),
            "app_name" => env("APP_NAME"),
            'name' => $order->customer->name,
            "order" => $order,
            'url' => config('shop.shop_url') . '/orderDetails/' . $order->id
        ];
        $toIds=array($order->customer->email);
        // dd($tags);
        switch ($request->input('status')) {
            case 'Payment accepted':
                $this->sendEmailNotification('OrderPaymentReceived', $toIds,$tags);
                break;

            case 'shipped':
                $this->sendEmailNotification('OrderShipped', $toIds,$tags);

                // Code to be executed if $variable is 'value2'
                break;
            case 'order-processing':
                $this->sendEmailNotification('OrderProcessing', $toIds,$tags);

                // Code to be executed if $variable is 'value2'
                break;

            case 'delivered':
                $this->sendEmailNotification('OrderDelivered', $toIds,$tags);

                // Code to be executed if $variable is 'value3'
                break;

            default:
                // Code to be executed if $variable doesn't match any case
        }

        if($request->status == 'shipped'){
            return redirect()->route('admin.orders.show', ['order' => $request->input('order_id')]);
        }
        return response()->json(['message' => 'Status updated successfully.']);
    }

    public function updateShippingCarriers(Request $request)
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->input('id');
        $orderId = $request->input('order_id');

        $request->validate([
            'id'=>'required',
            'order_id' => 'required',
            'carrier_id' => 'required',
            'shipping_cost' => 'nullable',
            'shipping_date' => 'nullable',
            'tracking_number' => 'nullable',
            'url' => 'nullable',
        ]);

        $data =[
            'carrier_id' => $request->input('carrier_id'),
            'shipping_cost' => $request->input('shipping_cost'),
            'shipping_date' => $request->input('shipping_date'),
            'tracking_number' => $request->input('tracking_number'),
            'url' => $request->input('url'),
        ];


        // dd($data);
        OrderCarrier::where('id',$id)->where('order_id', $orderId)->update($data);

        return response()->json(['message' => 'Shipping Details Updated successfully.']);


    }

    public function downloadInvoice($order_id){
        // first check if the order pdf is exist
        if(!file_exists(public_path('storage/pdfs/'.$order_id.'.pdf'))){
            //return response()->download(public_path('invoices/'.$order_id.'.pdf'));

            //find the order using order id
            $order = Order::find($order_id);
            $payloads = [
                'order_id'        => $order->id,
                'language'        => config('shop.default_language'),
                'translated_text' => $this->formatInvoiceTranslateText([]),
                'is_rtl'          => false,
            ];
            $settings = Settings::getData($payloads['language']);
            $invoiceData = [
                'order'           => $order,
                'settings'        => $settings,
                'translated_text' => $payloads['translated_text'],
                'is_rtl'          => $payloads['is_rtl'],
                'language'        => $payloads['language'],
            ];
            // return view('pdf.order-invoice', $invoiceData);
            $pdf = PDF::loadView('pdf.order-invoice', $invoiceData);
            $filename = 'invoice-order-' . $payloads['order_id'] . '.pdf';
            return $pdf->stream('invoice.pdf');
            $pdf->save(public_path('storage/pdfs/' . $filename));
        }

        return response()->download(public_path('storage/pdfs/'.$filename));
    }
}
