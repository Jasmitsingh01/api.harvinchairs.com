<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProductFeatureRequest;
use App\Http\Requests\StoreProductFeatureRequest;
use App\Http\Requests\UpdateProductFeatureRequest;
use App\Models\Feature;
use App\Models\Product;
use App\Models\ProductFeature;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductFeatureController extends Controller
{
    public function index(Request $request)
    {
        $language = $request->language ?? config('shop.default_language');
        $productFeatures = ProductFeature::with(['product:id,name', 'featureValue'])->get();

        return view('admin.productFeatures.index', compact('productFeatures'));
    }

    public function create()
    {
        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $feature_values = Feature::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.productFeatures.create', compact('feature_values', 'products'));
    }

    public function store(StoreProductFeatureRequest $request)
    {
        $productFeature = ProductFeature::create($request->all());

        return redirect()->route('admin.product-features.index');
    }

    public function edit(ProductFeature $productFeature)
    {
        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $feature_values = Feature::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $productFeature->load('product', 'featureValue');

        return view('admin.productFeatures.edit', compact('feature_values', 'productFeature', 'products'));
    }

    public function update(UpdateProductFeatureRequest $request, ProductFeature $productFeature)
    {
        $productFeature->update($request->all());

        return redirect()->route('admin.product-features.index');
    }

    public function show(ProductFeature $productFeature)
    {
        $productFeature->load('product', 'feature_value');

        return view('admin.productFeatures.show', compact('productFeature'));
    }

    public function destroy(ProductFeature $productFeature)
    {
        $productFeature->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductFeatureRequest $request)
    {
        $productFeatures = ProductFeature::find(request('ids'));

        foreach ($productFeatures as $productFeature) {
            $productFeature->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
