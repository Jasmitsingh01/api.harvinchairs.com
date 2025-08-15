<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySpecificPriceRequest;
use App\Http\Requests\StoreSpecificPriceRequest;
use App\Http\Requests\UpdateSpecificPriceRequest;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\SpecificPrice;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SpecificPriceController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('specific_price_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $specificPrices = SpecificPrice::with(['product', 'customer', 'product_attribute'])->get();

        return view('admin.specificPrices.index', compact('specificPrices'));
    }

    public function create()
    {
        // abort_if(Gate::denies('specific_price_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $customers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $product_attributes = ProductAttribute::pluck('reference_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.specificPrices.create', compact('customers', 'product_attributes', 'products'));
    }

    public function store(Request $request)
    {
        if ($request->has('tabname')) {
            $tabname = $request->input('tabname');
        }
        $disc_array = array(
            'product_id' => $request->input('product_id'),
            'customer_id' => $request->input('customer'),
            'product_attribute_id' => $request->input('combination'),
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'from_quantity' => $request->input('starting_unit'),
            'reduction' => $request->input('discount_amount'),
            'reduction_type' => $request->input('discount_type'),
        );
        // dd($disc_array);

        $specificPrice = SpecificPrice::create($disc_array);
        return redirect()->route('admin.products.edit', ['product' => $request->input('product_id'), 'activeTab' => $tabname]);
    }

    public function edit(SpecificPrice $specificPrice)
    {
        $customers = User::where('is_admin', false)->where('is_active', true)->get();
        $specificPrice->load('product', 'product_attribute');
        return view('admin.specificPrices.edit', compact('specificPrice', 'customers'));
    }

    public function update(UpdateSpecificPriceRequest $request, SpecificPrice $specificPrice)
    {
        $data= $request->all();
        if ($request->has('tabname')) {
            $tabname = $request->input('tabname');
            unset($data['tabname']);
        }
        $specificPrice->update($data);
        return redirect()->route('admin.products.edit', ['product' => $request->input('product_id'), 'activeTab' => $tabname]);
    }

    public function show(SpecificPrice $specificPrice)
    {
        // abort_if(Gate::denies('specific_price_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $specificPrice->load('product', 'customer', 'product_attribute');

        return view('admin.specificPrices.show', compact('specificPrice'));
    }

    public function destroy(Request $request, SpecificPrice $specificPrice)
    {
        // abort_if(Gate::denies('specific_price_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->has('tabname')) {
            $tabname = $request->input('tabname');
        }
        $specificPrice->delete();

        return redirect()->route('admin.products.edit', ['product' => $request->input('product_id'), 'activeTab' => $tabname]);
    }

    public function massDestroy(MassDestroySpecificPriceRequest $request)
    {
        $specificPrices = SpecificPrice::find(request('ids'));

        foreach ($specificPrices as $specificPrice) {
            $specificPrice->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
