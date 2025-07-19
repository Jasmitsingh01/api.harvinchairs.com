<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProductAttributeCombinationRequest;
use App\Http\Requests\StoreProductAttributeCombinationRequest;
use App\Http\Requests\UpdateProductAttributeCombinationRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeCombination;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductAttributeCombinationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('product_attribute_combination_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productAttributeCombinations = ProductAttributeCombination::with(['product_attribute', 'attribute', 'attribute_value'])->get();

        return view('admin.productAttributeCombinations.index', compact('productAttributeCombinations'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_attribute_combination_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product_attributes = ProductAttribute::pluck('reference_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $attributes = Attribute::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $attribute_values = AttributeValue::pluck('slug', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.productAttributeCombinations.create', compact('attribute_values', 'attributes', 'product_attributes'));
    }

    public function store(StoreProductAttributeCombinationRequest $request)
    {
        $productAttributeCombination = ProductAttributeCombination::create($request->all());

        return redirect()->route('admin.product-attribute-combinations.index');
    }

    public function edit(ProductAttributeCombination $productAttributeCombination)
    {
        abort_if(Gate::denies('product_attribute_combination_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product_attributes = ProductAttribute::pluck('reference_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $attributes = Attribute::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $attribute_values = AttributeValue::pluck('slug', 'id')->prepend(trans('global.pleaseSelect'), '');

        $productAttributeCombination->load('product_attribute', 'attribute', 'attribute_value');

        return view('admin.productAttributeCombinations.edit', compact('attribute_values', 'attributes', 'productAttributeCombination', 'product_attributes'));
    }

    public function update(UpdateProductAttributeCombinationRequest $request, ProductAttributeCombination $productAttributeCombination)
    {
        $productAttributeCombination->update($request->all());

        return redirect()->route('admin.product-attribute-combinations.index');
    }

    public function show(ProductAttributeCombination $productAttributeCombination)
    {
        abort_if(Gate::denies('product_attribute_combination_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productAttributeCombination->load('product_attribute', 'attribute', 'attribute_value');

        return view('admin.productAttributeCombinations.show', compact('productAttributeCombination'));
    }

    public function destroy(ProductAttributeCombination $productAttributeCombination)
    {
        abort_if(Gate::denies('product_attribute_combination_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productAttributeCombination->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductAttributeCombinationRequest $request)
    {
        $productAttributeCombinations = ProductAttributeCombination::find(request('ids'));

        foreach ($productAttributeCombinations as $productAttributeCombination) {
            $productAttributeCombination->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
