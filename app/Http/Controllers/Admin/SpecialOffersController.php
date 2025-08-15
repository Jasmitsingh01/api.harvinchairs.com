<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySpecialOfferRequest;
use App\Http\Requests\StoreSpecialOfferRequest;
use App\Http\Requests\UpdateSpecialOfferRequest;
use App\Models\Product;
use App\Models\SpecialOffer;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SpecialOffersController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('special_offer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SpecialOffer::with(['product'])->select(sprintf('%s.*', (new SpecialOffer)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'special_offer_show';
                $editGate      = 'special_offer_edit';
                $deleteGate    = 'special_offer_delete';
                $crudRoutePart = 'special-offers';

                return view('partials.Actions', compact(
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
            $table->addColumn('product_name', function ($row) {
                return $row->product ? $row->product->name : '';
            });

            $table->editColumn('offer_type', function ($row) {
                return $row->offer_type ? $row->offer_type : '';
            });
            $table->editColumn('discount_type', function ($row) {
                return $row->discount_type ? SpecialOffer::DISCOUNT_TYPE_RADIO[$row->discount_type] : '';
            });
            $table->editColumn('discount', function ($row) {
                return $row->discount ? $row->discount : '';
            });
            $table->editColumn('order_total_condition', function ($row) {
                return $row->order_total_condition ? $row->order_total_condition : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'product']);

            return $table->make(true);
        }

        $products = Product::get();

        return view('admin.specialOffers.index', compact('products'));
    }

    public function create()
    {
        abort_if(Gate::denies('special_offer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.specialOffers.create', compact('products'));
    }

    public function store(StoreSpecialOfferRequest $request)
    {
        $specialOffer = SpecialOffer::create($request->all());

        return redirect()->route('admin.special-offers.index');
    }

    public function edit(SpecialOffer $specialOffer)
    {
        abort_if(Gate::denies('special_offer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $specialOffer->load('product');

        return view('admin.specialOffers.edit', compact('products', 'specialOffer'));
    }

    public function update(UpdateSpecialOfferRequest $request, SpecialOffer $specialOffer)
    {
        $specialOffer->update($request->all());

        return redirect()->route('admin.special-offers.index');
    }

    public function show(SpecialOffer $specialOffer)
    {
        abort_if(Gate::denies('special_offer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $specialOffer->load('product');

        return view('admin.specialOffers.show', compact('specialOffer'));
    }

    public function destroy(SpecialOffer $specialOffer)
    {
        abort_if(Gate::denies('special_offer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $specialOffer->delete();

        return back();
    }

    public function massDestroy(MassDestroySpecialOfferRequest $request)
    {
        $specialOffers = SpecialOffer::find(request('ids'));

        foreach ($specialOffers as $specialOffer) {
            $specialOffer->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
