<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $language = $request->language ?? config('shop.default_language');
        $categories = Category::with(['media','children'])->orderBy('position','asc')->whereNull('parent')
        ->where('language',$language)->get();
        $is_child = false;
        return view('admin.categories.index', compact('categories','is_child'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $language = $request->language ?? config('shop.default_language');
        $categories = Category::where('language', $language)->whereNull('parent')->get();
        $types = \App\Database\Models\Type::all();
        return view('admin.categories.create',compact('categories', 'types'));
    }

    public function store(StoreCategoryRequest $request)
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $language = $request->language ?? config('shop.default_language');
        // $position = Category::where('language',$language)->orderby('id','desc')->pluck('position')->first();
        $category_data = $request->all();
        $category_data['language'] = $language;
        // $category_data['position'] = $position + 1;
        $category = Category::create($category_data);
        if ($request->input('thumbnail_image', false)) {
            $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('thumbnail_image'))))->preservingOriginal()->toMediaCollection('thumbnail_image');
        }
        if ($request->input('collection_image', false)) {
            $data = $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('collection_image'))))->preservingOriginal()->toMediaCollection('collection_image');
        }
        if ($request->input('cover_image', false)) {
            $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('cover_image'))))->preservingOriginal()->toMediaCollection('cover_image');
            $converted_url = [
                'thumbnail' => $category->cover_image->getUrl('thumb'),
                'original' => $category->cover_image->getUrl(),
                'filepath' =>$category->cover_image->getPath(),
            ];
            $category->update(['image'=>json_encode($converted_url)]);
        }



        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $category->id]);
        }
        if ($request->input('thumbnail_image', false)) {
             $converted_url = [
                'thumbnail' => $category->thumbnail_image->getUrl('thumb'),
                'original' => $category->thumbnail_image->getUrl(),
                'filepath' =>$category->thumbnail_image->getPath(),
            ];
            $category->update(['icon'=>json_encode($converted_url)]);
        }

    //    return response()->json(["data"=>$category]);
        return redirect()->route('admin.categories.index');
    }

    public function edit(Category $category,Request $request)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $language = $request->language ?? config('shop.default_language');

        $categories = Category::where('language', $language)->whereNull('parent')->get();
        $types = \App\Database\Models\Type::all();

        return view('admin.categories.edit', compact('category','categories', 'types'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category->update($request->all());

        if ($request->input('cover_image', false)) {
            if (! $category->cover_image || $request->input('cover_image') !== $category->cover_image->file_name) {
                if ($category->cover_image) {
                    $category->cover_image->delete();
                }
                $newCover = $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('cover_image'))))->toMediaCollection('cover_image');
                $newCover->url = $newCover->getUrl();
                $newCover->thumbnail = $newCover->getUrl('thumb');
                $newCover->filepath = $newCover->getPath();
                //  dd($newBanner);
                $converted_url = [
                    'thumbnail' =>  $newCover->thumbnail,
                    'original' =>    $newCover->url,
                    'filepath' =>    $newCover->filepath,
                ];
                $category->update(['image'=>json_encode($converted_url)]);

            }
        }
        // elseif ($category->cover_image) {
        //     $category->cover_image->delete();
        //     $category->update(['image'=>null]);
        // }

        if ($request->input('thumbnail_image', false)) {
            if (! $category->thumbnail_image || $request->input('thumbnail_image') !== $category->thumbnail_image->file_name) {
                if ($category->thumbnail_image) {
                    $category->thumbnail_image->delete();
                }
                $newThumb=   $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('thumbnail_image'))))->toMediaCollection('thumbnail_image');
                $newThumb->url = $newThumb->getUrl();
                $newThumb->thumbnail = $newThumb->getUrl('thumb');
                $newThumb->filepath = $newThumb->getPath();
                //  dd($newBanner);
                $converted_url = [
                    'thumbnail' =>  $newThumb->thumbnail,
                    'original' =>    $newThumb->url,
                    'filepath' =>    $newThumb->filepath,

                ];
                $category->update(['icon'=>json_encode($converted_url)]);
            }
        }
        if ($request->input('collection_image', false)) {
            if (! $category->collection_image_url || $request->input('collection_image') !== $category->collection_image_url->file_name) {
                if ($category->collection_image_url) {
                    $category->collection_image_url->delete();
                }
                $newCover = $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('collection_image'))))->toMediaCollection('collection_image');

            }
        }
        // elseif ($category->thumbnail_image) {
        //     $category->thumbnail_image->delete();
        //     $category->update(['icon'=>null]);
        // }

        return redirect()->route('admin.categories.index');
    }

    public function show(Request $request,$category)
    {
        abort_if(Gate::denies('category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $language = $request->language ?? config('shop.default_language');

        $categories = Category::where('parent',$category)->where('language',$language)->get();
        $is_child = true;
        return view('admin.categories.index', compact('categories','is_child'));
    }

    public function destroy(Category $category)
    {
        abort_if(Gate::denies('category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category->delete();

        return back();
    }

    public function massDestroy(MassDestroyCategoryRequest $request)
    {
        abort_if(Gate::denies('category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::find(request('ids'));

        foreach ($categories as $category) {
            $category->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        $model         = new Category();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
    public function updatePositions(Request $request)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sortedIds = $request->input('product');
        $position = 1;
        $positions = [];
        foreach ($sortedIds as $productId) {

            $product = Category::find($productId);
            $product->position = $position;
            $product->save();

            // Store the updated position for each product ID
            $positions[$productId] = $position;

            $position++;
        }

        return response()->json(['positions' => $positions]);

    }
    public function updateStatus(Request $request)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'id' => 'required|exists:categories,id',
            'field' => 'required|in:enabled,new_arrival,is_home',
            'value' => 'required|boolean',
        ]);

        $category = Category::find($request->id);

        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        $category->{$request->field} = $request->value;
        $category->save();

        return response()->json(['message' => 'Status updated successfully.']);
    }
}
