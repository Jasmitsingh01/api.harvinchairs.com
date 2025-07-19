<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoriesApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CategoryResource(Category::all());
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->all());

        if ($request->input('cover_image', false)) {
            $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('cover_image'))))->toMediaCollection('cover_image');
        }

        if ($request->input('thumbnail_image', false)) {
            $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('thumbnail_image'))))->toMediaCollection('thumbnail_image');
        }

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Category $category)
    {
        abort_if(Gate::denies('category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->all());

        if ($request->input('cover_image', false)) {
            if (! $category->cover_image || $request->input('cover_image') !== $category->cover_image->file_name) {
                if ($category->cover_image) {
                    $category->cover_image->delete();
                }
                $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('cover_image'))))->toMediaCollection('cover_image');
            }
        } elseif ($category->cover_image) {
            $category->cover_image->delete();
        }

        if ($request->input('thumbnail_image', false)) {
            if (! $category->thumbnail_image || $request->input('thumbnail_image') !== $category->thumbnail_image->file_name) {
                if ($category->thumbnail_image) {
                    $category->thumbnail_image->delete();
                }
                $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('thumbnail_image'))))->toMediaCollection('thumbnail_image');
            }
        } elseif ($category->thumbnail_image) {
            $category->thumbnail_image->delete();
        }

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Category $category)
    {
        abort_if(Gate::denies('category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
