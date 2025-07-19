<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreProductEnquireRequest;
use App\Http\Requests\UpdateProductEnquireRequest;
use App\Http\Resources\Admin\ProductEnquireResource;
use App\Models\ProductEnquire;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductEnquireApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {

        return new ProductEnquireResource(ProductEnquire::with(['product', 'product_attributes'])->get());
    }

    public function store(StoreProductEnquireRequest $request)
    {
        $productEnquire = ProductEnquire::create($request->all());

        if ($request->input('product_img', false)) {
            $productEnquire->addMedia(storage_path('tmp/uploads/' . basename($request->input('product_img'))))->toMediaCollection('product_img');
        }

        return (new ProductEnquireResource($productEnquire))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ProductEnquire $productEnquire)
    {

        return new ProductEnquireResource($productEnquire->load(['product', 'product_attributes']));
    }

    public function update(UpdateProductEnquireRequest $request, ProductEnquire $productEnquire)
    {
        $productEnquire->update($request->all());

        if ($request->input('product_img', false)) {
            if (! $productEnquire->product_img || $request->input('product_img') !== $productEnquire->product_img->file_name) {
                if ($productEnquire->product_img) {
                    $productEnquire->product_img->delete();
                }
                $productEnquire->addMedia(storage_path('tmp/uploads/' . basename($request->input('product_img'))))->toMediaCollection('product_img');
            }
        } elseif ($productEnquire->product_img) {
            $productEnquire->product_img->delete();
        }

        return (new ProductEnquireResource($productEnquire))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ProductEnquire $productEnquire)
    {

        $productEnquire->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
