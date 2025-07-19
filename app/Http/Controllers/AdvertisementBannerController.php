<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAdvertisementBannerRequest;
use App\Http\Requests\UpdateAdvertisementBannerRequest;
use App\Http\Resources\Admin\AdvertisementBannerResource;
use App\Models\AdvertisementBanner;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertisementBannerController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        //abort_if(Gate::denies('advertisement_banner_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $limit = (isset($request->limit)) ? $request->limit : 1;
        $advertisementBanner = AdvertisementBanner::where('active',true);
        if(isset($request->category_id) && !empty($request->category_id)){
            $advertisementBanner = $advertisementBanner->where('category_id',$request->category_id);
        }else{
            $advertisementBanner = $advertisementBanner->whereNull('category_id');
        }
        if($limit > 0){
            $advertisementBanner = $advertisementBanner->limit($limit);
        }
        $advertisementBanner = $advertisementBanner->orderBy('id','desc')->get();
        return $advertisementBanner;
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
