<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAttributeProductRequest;
use App\Http\Requests\StoreAttributeProductRequest;
use App\Http\Requests\UpdateAttributeProductRequest;
use App\Models\AttributeProduct;
use App\Models\AttributeValue;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttributeProductController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('attribute_product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attributeProducts = AttributeProduct::with(['attribute_value', 'product'])->get();

        return view('admin.attributeProducts.index', compact('attributeProducts'));
    }

    public function create()
    {
        abort_if(Gate::denies('attribute_product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attribute_values = AttributeValue::pluck('slug', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.attributeProducts.create', compact('attribute_values', 'products'));
    }

    public function store(StoreAttributeProductRequest $request)
    {
        $attributeProduct = AttributeProduct::create($request->all());

        return redirect()->route('admin.attribute-products.index');
    }

    public function edit(AttributeProduct $attributeProduct)
    {
        abort_if(Gate::denies('attribute_product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attribute_values = AttributeValue::pluck('slug', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $attributeProduct->load('attribute_value', 'product');

        return view('admin.attributeProducts.edit', compact('attributeProduct', 'attribute_values', 'products'));
    }

    public function update(UpdateAttributeProductRequest $request, AttributeProduct $attributeProduct)
    {
        $attributeProduct->update($request->all());

        return redirect()->route('admin.attribute-products.index');
    }

    public function show(AttributeProduct $attributeProduct)
    {
        abort_if(Gate::denies('attribute_product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attributeProduct->load('attribute_value', 'product');

        return view('admin.attributeProducts.show', compact('attributeProduct'));
    }

    public function destroy(AttributeProduct $attributeProduct)
    {
        abort_if(Gate::denies('attribute_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attributeProduct->delete();

        return back();
    }

    public function massDestroy(MassDestroyAttributeProductRequest $request)
    {
        $attributeProducts = AttributeProduct::find(request('ids'));

        foreach ($attributeProducts as $attributeProduct) {
            $attributeProduct->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
