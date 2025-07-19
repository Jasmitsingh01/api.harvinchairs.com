<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Banner;
use ImageKit\ImageKit;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroyBannerRequest;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BannerController extends Controller
{
    use MediaUploadingTrait;
    public function index()
    {
        abort_if(Gate::denies('banner_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $banners = Banner::with(['category'])->get();
        $categories = Category::get();
        return view('admin.banners.index', compact('banners', 'categories'));
    }

    public function create()
    {
        abort_if(Gate::denies('banner_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.banners.create', compact('categories'));
    }

    public function store(StoreBannerRequest $request)
    {
        abort_if(Gate::denies('banner_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $banner = Banner::create($request->all());
        if ($request->input('banner', false)) {

            $banner->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner'))))->preservingOriginal()->toMediaCollection('banner');
            $converted_url = [
                'thumbnail' => $banner->banners->getUrl('thumb'),
                'original' => $banner->banners->getUrl(),
                'filepath' =>$banner->banners->getPath(),
            ];
            $banner->update(['banner'=>json_encode($converted_url)]);
        }
        // if ($request->input('banner', false)) {
        //     if(config('app.env') != "local"){
        //         $imageKit = new ImageKit(
        //             config('shop.imagekit.public_key'),
        //             config('shop.imagekit.private_key'),
        //             config('shop.imagekit.endpoint')
        //         );
        //         $image_path = storage_path('tmp/uploads/' . basename($request->input('banner')));
        //         // dd($image_path);
        //         $cleanedImgCaption = str_replace(['(', ')', ' ', '/'], ['','','','-'], trim($request->title));

        //         $uploadFile = $imageKit->uploadFile([
        //             "file" => fopen($image_path,"r"),
        //             "fileName" => $cleanedImgCaption,
        //             "folder" => "banners"
        //         ]);
        //         $banner->banner  = ['url'=>$uploadFile->result->url,'thumbnail'=>$uploadFile->result->thumbnailUrl,'preview'=>$uploadFile->result->thumbnailUrl];

        //         $banner->save();
        //     }
        //     else{
        //         $banner->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner'))))->toMediaCollection('banner');

        //     }
        // }

        // if ($media = $request->input('ck-media', false)) {
        //     Media::whereIn('id', $media)->update(['model_id' => $banner->id]);
        // }

        return redirect()->route('admin.banners.index');
    }

    public function edit(Banner $banner)
    {
        abort_if(Gate::denies('banner_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $banner->load('category');
        // dd( $banner->banner);

        return view('admin.banners.edit', compact('banner', 'categories'));
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        abort_if(Gate::denies('banner_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $banner->update($request->all());
        // if ($request->input('banner', false)) {
        //     if(config('app.env') != "local"){
        //         $imageKit = new ImageKit(
        //             config('shop.imagekit.public_key'),
        //             config('shop.imagekit.private_key'),
        //             config('shop.imagekit.endpoint')
        //         );
        //         $image_path = storage_path('tmp/uploads/' . basename($request->input('banner')));
        //         // dd($image_path);
        //         $cleanedImgCaption = str_replace(['(', ')', ' ', '/'], ['','','','-'], trim($banner->title));
        //         $uploadFile = $imageKit->uploadFile([
        //             "file" => fopen($image_path,"r"),
        //             "fileName" => $cleanedImgCaption.'jpg',
        //             "folder" => "banners"
        //         ]);
        //         $banner->banner  = ['url'=>$uploadFile->result->url,'thumbnail'=>$uploadFile->result->thumbnailUrl,'preview'=>$uploadFile->result->thumbnailUrl];

        //         $banner->save();
        //     }
        //     else{
        //         if(file_exists(storage_path('tmp/uploads/' . basename($request->input('banner'))))){
        //             if ($banner->banner) {
        //                 $banner->banner->delete();
        //             }
        //             $banner->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner'))))->toMediaCollection('banner');
        //         }
        //     }
        // } elseif ($banner->banner) {

        //     $banner->banner->delete();
        // }
        //    dd($request);
        if ($request->input('banner', false)) {

            if (! $banner->banners || $request->input('banner') !== $banner->banners->file_name) {
                if ($banner->banners) {
                    $banner->banners->delete();
                }
                $newBanner  =  $banner->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner'))))->toMediaCollection('banner');


                $newBanner->url = $newBanner->getUrl();
                $newBanner->thumbnail = $newBanner->getUrl('thumb');
                $newBanner->filepath = $newBanner->getPath();
                //  dd($newBanner);
                $converted_url = [
                    'thumbnail' =>  $newBanner->thumbnail,
                    'original' =>    $newBanner->url,
                    'filepath' =>    $newBanner->filepath,
                ];

                $banner->update(['banner'=>json_encode($converted_url)]);
            }

        } elseif ($banner->banners) {
            // No new file uploaded, delete the current banner file
            $banner->banners->delete();
            $banner->update(['banner' => null]); // Update the banner field to null or any default value
        }


        return redirect()->route('admin.banners.index');
    }

    public function show(Banner $banner)
    {
        abort_if(Gate::denies('banner_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $banner->load('category');

        return view('admin.banners.show', compact('banner'));
    }

    public function destroy(Banner $banner)
    {
        abort_if(Gate::denies('banner_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $banner->delete();

        return back();
    }

    public function massDestroy(MassDestroyBannerRequest $request)
    {
        abort_if(Gate::denies('banner_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $banners = Banner::find(request('ids'));

        foreach ($banners as $banner) {
            $banner->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }


    public function updateStatus(Request $request)
    {
        abort_if(Gate::denies('banner_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'id' => 'required|exists:banners,id',
            'field' => 'required|in:active',
            'value' => 'required|boolean',
        ]);

        $banner = Banner::find($request->id);

        if (!$banner) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        $banner->{$request->field} = $request->value;
        $banner->save();

        return response()->json(['message' => 'Status updated successfully.']);
    }
}
