<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Http\Resources\Admin\CouponResource;
use App\Models\Coupon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CouponApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('coupon_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CouponResource(Coupon::with(['customer', 'category', 'product'])->get());
    }

    public function store(StoreCouponRequest $request)
    {
        $coupon = Coupon::create($request->all());

        foreach ($request->input('image', []) as $file) {
            $coupon->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('image');
        }

        return (new CouponResource($coupon))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Coupon $coupon)
    {
        abort_if(Gate::denies('coupon_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CouponResource($coupon->load(['customer', 'category', 'product']));
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $coupon->update($request->all());

        if (count($coupon->image) > 0) {
            foreach ($coupon->image as $media) {
                if (! in_array($media->file_name, $request->input('image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $coupon->image->pluck('file_name')->toArray();
        foreach ($request->input('image', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $coupon->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('image');
            }
        }

        return (new CouponResource($coupon))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Coupon $coupon)
    {
        abort_if(Gate::denies('coupon_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $coupon->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
