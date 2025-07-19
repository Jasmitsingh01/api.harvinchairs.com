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
        $categories = Category::with(['media','children'])->orderBy('position','asc')->where('parent', config('shop.parent_id'))
        ->where('language',$language)->get();
        $is_child = false;
        return view('admin.categories.index', compact('categories','is_child'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $language = $request->language ?? config('shop.default_language');
        $categories = Category::where('language',$language)->where('parent', config('shop.parent_id'))->get();
        return view('admin.categories.create',compact('categories'));
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

        $categories = Category::where('language',$language)->where('parent', config('shop.parent_id'))->get();

        return view('admin.categories.edit', compact('category','categories'));
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

    public function storeMedia(Request $request)
    {
        abort_if(Gate::denies('category_create') && Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->has('size')) {
            $this->validate($request, [
                'file' => 'max:' . $request->input('size') * 1024,
            ]);
        }
        if (request()->has('max_width') || request()->has('max_height')) {
            $this->validate(request(), [
                'file' => sprintf(
                    'image|dimensions:max_width=%s,max_height=%s',
                    request()->input('max_width', 200000),
                    request()->input('max_height', 200000)
                ),
            ]);
        }

        $model         = new Category();
        $model->id     = $request->input('model_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('file')->toMediaCollection($request->input('collection_name', 'default'));

        return response()->json($media, Response::HTTP_CREATED);
    }

    public function updatePositions(Request $request)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $positions = $request->input('positions');
        
        foreach ($positions as $position) {
            Category::where('id', $position['id'])->update(['position' => $position['position']]);
        }

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category = Category::findOrFail($request->input('category_id'));
        $field = $request->input('field');
        $value = $request->input('value');

        if (in_array($field, ['enabled', 'is_home', 'new_arrival', 'is_showcase'])) {
            $category->update([$field => $value]);
            return response()->json(['success' => true, 'message' => 'Category status updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid field'], 400);
    }

    // New enhanced methods for better category management

    public function duplicate(Category $category)
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $newCategory = $category->replicate();
        $newCategory->name = $category->name . ' (Copy)';
        $newCategory->slug = $category->slug . '-copy-' . time();
        $newCategory->position = Category::max('position') + 1;
        $newCategory->save();

        // Copy media files
        foreach ($category->getMedia() as $media) {
            $newCategory->addMedia($media->getPath())->toMediaCollection($media->collection_name);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category duplicated successfully');
    }

    public function bulkUpdate(Request $request)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'action' => 'required|in:enable,disable,delete,move',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $categoryIds = $request->input('category_ids');
        $action = $request->input('action');

        switch ($action) {
            case 'enable':
                Category::whereIn('id', $categoryIds)->update(['enabled' => true]);
                $message = 'Categories enabled successfully';
                break;
            case 'disable':
                Category::whereIn('id', $categoryIds)->update(['enabled' => false]);
                $message = 'Categories disabled successfully';
                break;
            case 'delete':
                Category::whereIn('id', $categoryIds)->delete();
                $message = 'Categories deleted successfully';
                break;
            case 'move':
                $parentId = $request->input('parent_id');
                Category::whereIn('id', $categoryIds)->update(['parent' => $parentId]);
                $message = 'Categories moved successfully';
                break;
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $language = $request->language ?? config('shop.default_language');
        
        $categories = Category::where('language', $language)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('slug', 'like', "%{$query}%")
                  ->orWhere('details', 'like', "%{$query}%");
            })
            ->with(['children', 'products'])
            ->orderBy('position')
            ->limit(20)
            ->get();

        return response()->json($categories);
    }

    public function getCategoryStats()
    {
        $stats = [
            'total_categories' => Category::count(),
            'enabled_categories' => Category::where('enabled', true)->count(),
            'categories_with_products' => Category::has('products')->count(),
            'categories_with_children' => Category::has('children')->count(),
        ];

        return response()->json($stats);
    }

    public function exportCategories(Request $request)
    {
        abort_if(Gate::denies('category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $language = $request->language ?? config('shop.default_language');
        $categories = Category::where('language', $language)
            ->with(['children', 'products'])
            ->orderBy('position')
            ->get();

        $filename = 'categories_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($categories) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['ID', 'Name', 'Slug', 'Parent', 'Position', 'Enabled', 'Products Count', 'Children Count']);
            
            foreach ($categories as $category) {
                fputcsv($file, [
                    $category->id,
                    $category->name,
                    $category->slug,
                    $category->parent,
                    $category->position,
                    $category->enabled ? 'Yes' : 'No',
                    $category->products->count(),
                    $category->children->count(),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importCategories(Request $request)
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $language = $request->language ?? config('shop.default_language');
        
        $imported = 0;
        $errors = [];

        if (($handle = fopen($file->getPathname(), "r")) !== FALSE) {
            // Skip header row
            fgetcsv($handle);
            
            while (($data = fgetcsv($handle)) !== FALSE) {
                try {
                    Category::create([
                        'name' => $data[0],
                        'slug' => $data[1],
                        'parent' => $data[2] ?: config('shop.parent_id'),
                        'position' => $data[3] ?: Category::max('position') + 1,
                        'enabled' => $data[4] === 'Yes',
                        'language' => $language,
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($imported + 2) . ": " . $e->getMessage();
                }
            }
            fclose($handle);
        }

        return response()->json([
            'success' => true,
            'imported' => $imported,
            'errors' => $errors
        ]);
    }
}
