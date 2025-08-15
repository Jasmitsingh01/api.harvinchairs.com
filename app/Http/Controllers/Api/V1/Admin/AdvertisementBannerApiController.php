<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAdvertisementBannerRequest;
use App\Http\Requests\UpdateAdvertisementBannerRequest;
use App\Http\Resources\Admin\AdvertisementBannerResource;
use App\Models\AdvertisementBanner;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertisementBannerApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('advertisement_banner_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AdvertisementBannerResource(AdvertisementBanner::with(['category'])->get());
    }

    public function store(StoreAdvertisementBannerRequest $request)
    {
        $advertisementBanner = AdvertisementBanner::create($request->all());

        if ($request->input('banner', false)) {
            $advertisementBanner->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner'))))->toMediaCollection('banner');
        }

        return (new AdvertisementBannerResource($advertisementBanner))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AdvertisementBanner $advertisementBanner)
    {
        abort_if(Gate::denies('advertisement_banner_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AdvertisementBannerResource($advertisementBanner->load(['category']));
    }

    public function update(UpdateAdvertisementBannerRequest $request, AdvertisementBanner $advertisementBanner)
    {
        $advertisementBanner->update($request->all());

        if ($request->input('banner', false)) {
            if (! $advertisementBanner->banner || $request->input('banner') !== $advertisementBanner->banner->file_name) {
                if ($advertisementBanner->banner) {
                    $advertisementBanner->banner->delete();
                }
                $advertisementBanner->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner'))))->toMediaCollection('banner');
            }
        } elseif ($advertisementBanner->banner) {
            $advertisementBanner->banner->delete();
        }

        return (new AdvertisementBannerResource($advertisementBanner))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AdvertisementBanner $advertisementBanner)
    {
        abort_if(Gate::denies('advertisement_banner_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisementBanner->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
