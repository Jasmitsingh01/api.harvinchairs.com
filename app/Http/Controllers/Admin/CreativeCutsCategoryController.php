<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCreativeCutsCategoryRequest;
use App\Http\Requests\StoreCreativeCutsCategoryRequest;
use App\Http\Requests\UpdateCreativeCutsCategoryRequest;
use App\Models\CreativeCutsCategory;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CreativeCutsCategoryController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {

        $creativeCutsCategories = CreativeCutsCategory::with(['media'])->get();
        foreach($creativeCutsCategories as $key => $creativeCutsCategory){
            // dd( $creativeCutsCategory->image->getUrl('thumb') );
            // dd($creativeCutsCategory->image->geturl());
        }
        return view('admin.creativeCutsCategories.index', compact('creativeCutsCategories'));
    }

    public function create()
    {

        return view('admin.creativeCutsCategories.create');
    }

    public function store(StoreCreativeCutsCategoryRequest $request)
    {
        $creativeCutsCategory = CreativeCutsCategory::create($request->all());


        if ($request->input('image', false)) {
            $creativeCutsCategory->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $creativeCutsCategory->id]);
        }

        return redirect()->route('admin.creative-cuts-categories.index');
    }

    public function edit(CreativeCutsCategory $creativeCutsCategory)
    {

        return view('admin.creativeCutsCategories.edit', compact('creativeCutsCategory'));
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

        return redirect()->route('admin.creative-cuts-categories.index');
    }

    public function show(CreativeCutsCategory $creativeCutsCategory)
    {

        return view('admin.creativeCutsCategories.show', compact('creativeCutsCategory'));
    }

    public function destroy(CreativeCutsCategory $creativeCutsCategory)
    {

        $creativeCutsCategory->delete();

        return back();
    }

    public function massDestroy(MassDestroyCreativeCutsCategoryRequest $request)
    {
        $creativeCutsCategories = CreativeCutsCategory::find(request('ids'));

        foreach ($creativeCutsCategories as $creativeCutsCategory) {
            $creativeCutsCategory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {

        $model         = new CreativeCutsCategory();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
    public function updateStatus(Request $request)
    {

        $request->validate([
            'id' => 'required|exists:creative_cuts_categories,id',
            'field' => 'required|in:active',
            'value' => 'required|boolean',
        ]);

        $CreativeCuts = CreativeCutsCategory::find($request->id);

        if (!$CreativeCuts) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        $CreativeCuts->{$request->field} = $request->value;
        $CreativeCuts->save();

        return response()->json(['message' => 'Status updated successfully.']);
    }
}
