<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAdvertisementBannerRequest;
use App\Http\Requests\StoreAdvertisementBannerRequest;
use App\Http\Requests\UpdateAdvertisementBannerRequest;
use App\Models\AdvertisementBanner;
use App\Models\Category;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class AdvertisementBannerController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('advertisement_banner_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisementBanners = AdvertisementBanner::with(['category', 'media'])->get();

        $categories = Category::get();

        return view('admin.advertisementBanners.index', compact('advertisementBanners', 'categories'));
    }

    public function create()
    {
        abort_if(Gate::denies('advertisement_banner_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.advertisementBanners.create', compact('categories'));
    }

    public function store(StoreAdvertisementBannerRequest $request)
    {
        $advertisementBanner = AdvertisementBanner::create($request->all());

        if ($request->input('banner', false)) {
            $advertisementBanner->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner'))))->toMediaCollection('banner');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $advertisementBanner->id]);
        }

        return redirect()->route('admin.advertisement-banners.index');
    }

    public function edit(AdvertisementBanner $advertisementBanner)
    {
        abort_if(Gate::denies('advertisement_banner_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $advertisementBanner->load('category');

        return view('admin.advertisementBanners.edit', compact('advertisementBanner', 'categories'));
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

        return redirect()->route('admin.advertisement-banners.index');
    }

    public function show(AdvertisementBanner $advertisementBanner)
    {
        abort_if(Gate::denies('advertisement_banner_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisementBanner->load('category');

        return view('admin.advertisementBanners.show', compact('advertisementBanner'));
    }

    public function destroy(AdvertisementBanner $advertisementBanner)
    {
        abort_if(Gate::denies('advertisement_banner_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisementBanner->delete();

        return back();
    }

    public function massDestroy(MassDestroyAdvertisementBannerRequest $request)
    {
        $advertisementBanners = AdvertisementBanner::find(request('ids'));

        foreach ($advertisementBanners as $advertisementBanner) {
            $advertisementBanner->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('advertisement_banner_create') && Gate::denies('advertisement_banner_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new AdvertisementBanner();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
