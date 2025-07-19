<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderStatusRequest;
use App\Http\Requests\StoreOrderStatusRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\OrderStatus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class OrderStatusController extends Controller
{
    public function index(Request $request)
    {
        // abort_if(Gate::denies('order_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = OrderStatus::query()->select(sprintf('%s.*', (new OrderStatus)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'order_status_show';
                $editGate      = 'order_status_edit';
                $deleteGate    = 'order_status_delete';
                $crudRoutePart = 'order-statuses';

                return view('partials.HideActions', compact(
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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('template', function ($row) {
                return $row->template ? $row->template : '';
            });
            // $table->editColumn('module_name', function ($row) {
            //     return $row->module_name ? $row->module_name : '';
            // });
            // $table->editColumn('online_payment', function ($row) {
            //     return '<input type="checkbox" disabled ' . ($row->online_payment ? 'checked' : null) . '>';
            // });
            $table->editColumn('invoice', function ($row) {
                // return '<input type="checkbox" disabled ' . ($row->invoice ? 'checked' : null) . '>';
                return $row->invoice ?
                '<button class="border-0 text-success bg-transparent btn-invoice" data-id="' .$row->id.
                '"><i class="fa-solid fa-circle-check"></i></button>' :
                '<button class="border-0 text-danger bg-transparent btn-invoice-false" data-id="'  .$row->id.
                '"><i class="fa-solid fa-circle-xmark"></i></button>';
            });
            $table->editColumn('send_email', function ($row) {
                // return '<input type="checkbox" disabled ' . ($row->send_email ? 'checked' : null) . '>';
                return $row->send_email ?
                '<button class="border-0 text-success bg-transparent btn-send-email" data-id="' .$row->id.
                '"><i class="fa-solid fa-circle-check"></i></button>' :
                '<button class="border-0 text-danger bg-transparent btn-send-email-false" data-id="'  .$row->id.
                '"><i class="fa-solid fa-circle-xmark"></i></button>';
            });
            // $table->editColumn('unremovable', function ($row) {
            //     return '<input type="checkbox" disabled ' . ($row->unremovable ? 'checked' : null) . '>';
            // });
            // $table->editColumn('hidden', function ($row) {
            //     return '<input type="checkbox" disabled ' . ($row->hidden ? 'checked' : null) . '>';
            // });
            // $table->editColumn('logable', function ($row) {
            //     return '<input type="checkbox" disabled ' . ($row->logable ? 'checked' : null) . '>';
            // });
            $table->editColumn('delivery', function ($row) {
                // return '<input type="checkbox" disabled ' . ($row->delivery ? 'checked' : null) . '>';
                return $row->delivery ?
                '<button class="border-0 text-success bg-transparent btn-delivery" data-id="' .$row->id.
                '"><i class="fa-solid fa-circle-check"></i></button>' :
                '<button class="border-0 text-danger bg-transparent btn-delivery-false" data-id="'  .$row->id.
                '"><i class="fa-solid fa-circle-xmark"></i></button>';
            });
            // $table->editColumn('shipped', function ($row) {
            //     return '<input type="checkbox" disabled ' . ($row->shipped ? 'checked' : null) . '>';
            // });
            // $table->editColumn('paid', function ($row) {
            //     return '<input type="checkbox" disabled ' . ($row->paid ? 'checked' : null) . '>';
            // });
            // $table->editColumn('pdf_invoice', function ($row) {
            //     return '<input type="checkbox" disabled ' . ($row->pdf_invoice ? 'checked' : null) . '>';
            // });
            // $table->editColumn('pdf_delivery', function ($row) {
            //     return '<input type="checkbox" disabled ' . ($row->pdf_delivery ? 'checked' : null) . '>';
            // });

            $table->rawColumns(['actions', 'placeholder',  'invoice', 'send_email', 'delivery']);

            return $table->make(true);
        }

        return view('admin.orderStatuses.index');
    }

    public function create()
    {
        // abort_if(Gate::denies('order_status_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.orderStatuses.create');
    }

    public function store(StoreOrderStatusRequest $request)
    {
        $orderStatus = OrderStatus::create($request->all());

        return redirect()->route('admin.order-statuses.index');
    }

    public function edit(OrderStatus $orderStatus)
    {
        // abort_if(Gate::denies('order_status_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.orderStatuses.edit', compact('orderStatus'));
    }

    public function update(UpdateOrderStatusRequest $request, OrderStatus $orderStatus)
    {
        $orderStatus->update($request->all());

        return redirect()->route('admin.order-statuses.index');
    }

    public function show(OrderStatus $orderStatus)
    {
        // abort_if(Gate::denies('order_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.orderStatuses.show', compact('orderStatus'));
    }

    public function destroy(OrderStatus $orderStatus)
    {
        // abort_if(Gate::denies('order_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orderStatus->delete();

        return back();
    }

    public function massDestroy(MassDestroyOrderStatusRequest $request)
    {
        $orderStatuses = OrderStatus::find(request('ids'));

        foreach ($orderStatuses as $orderStatus) {
            $orderStatus->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:order_statuses,id',
            'field' => 'required|in:send_email,invoice,delivery',
            'value' => 'required|boolean',
        ]);

        $status = OrderStatus::find($request->id);

        if (!$status) {
            return response()->json(['error' => 'Product not found.'], 404);
        }

        $status->{$request->field} = $request->value;
        $status->save();

        return response()->json(['message' => 'Status updated successfully.']);
    }
}
