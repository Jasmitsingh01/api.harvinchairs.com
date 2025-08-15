<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyShopRequest;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\Shop;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ShopController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        $shops = Shop::with(['owner', 'media'])->get();

        return view('admin.shops.index', compact('shops'));
    }

    public function create()
    {

        $owners = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.shops.create', compact('owners'));
    }

    public function store(StoreShopRequest $request)
    {
        $shop = Shop::create($request->all());

        foreach ($request->input('cover_image', []) as $file) {
            $shop->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('cover_image');
        }

        if ($request->input('logo', false)) {
            $shop->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo'))))->toMediaCollection('logo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $shop->id]);
        }

        return redirect()->route('admin.shops.index');
    }

    public function edit(Shop $shop)
    {

        $owners = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shop->load('owner');

        return view('admin.shops.edit', compact('owners', 'shop'));
    }

    public function update(UpdateShopRequest $request, Shop $shop)
    {
        $shop->update($request->all());

        if (count($shop->cover_image) > 0) {
            foreach ($shop->cover_image as $media) {
                if (! in_array($media->file_name, $request->input('cover_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $shop->cover_image->pluck('file_name')->toArray();
        foreach ($request->input('cover_image', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $shop->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('cover_image');
            }
        }

        if ($request->input('logo', false)) {
            if (! $shop->logo || $request->input('logo') !== $shop->logo->file_name) {
                if ($shop->logo) {
                    $shop->logo->delete();
                }
                $shop->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo'))))->toMediaCollection('logo');
            }
        } elseif ($shop->logo) {
            $shop->logo->delete();
        }

        return redirect()->route('admin.shops.index');
    }

    public function show(Shop $shop)
    {

        $shop->load('owner');

        return view('admin.shops.show', compact('shop'));
    }

    public function destroy(Shop $shop)
    {

        $shop->delete();

        return back();
    }

    public function massDestroy(MassDestroyShopRequest $request)
    {
        $shops = Shop::find(request('ids'));

        foreach ($shops as $shop) {
            $shop->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {

        $model         = new Shop();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
