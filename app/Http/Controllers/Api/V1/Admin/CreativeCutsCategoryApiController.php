<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreCreativeCutsCategoryRequest;
use App\Http\Requests\UpdateCreativeCutsCategoryRequest;
use App\Http\Resources\Admin\CreativeCutsCategoryResource;
use App\Models\CreativeCutsCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CreativeCutsCategoryApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        return new CreativeCutsCategoryResource(CreativeCutsCategory::all());
    }

    public function store(StoreCreativeCutsCategoryRequest $request)
    {
        $creativeCutsCategory = CreativeCutsCategory::create($request->all());

        if ($request->input('image', false)) {
            $creativeCutsCategory->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        }

        return (new CreativeCutsCategoryResource($creativeCutsCategory))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CreativeCutsCategory $creativeCutsCategory)
    {

        return new CreativeCutsCategoryResource($creativeCutsCategory);
    }

    public function update(UpdateCreativeCutsCategoryRequest $request, CreativeCutsCategory $creativeCutsCategory)
    {
        $creativeCutsCategory->update($request->all());

        if ($request->input('image', false)) {
            if (! $creativeCutsCategory->image || $request->input('image') !== $creativeCutsCategory->image->file_name) {
                if ($creativeCutsCategory->image) {
                    $creativeCutsCategory->image->delete();
                }
                $creativeCutsCategory->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
            }
        } elseif ($creativeCutsCategory->image) {
            $creativeCutsCategory->image->delete();
        }

        return (new CreativeCutsCategoryResource($creativeCutsCategory))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CreativeCutsCategory $creativeCutsCategory)
    {

        $creativeCutsCategory->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
