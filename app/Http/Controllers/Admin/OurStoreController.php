<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyOurStoreRequest;
use App\Http\Requests\StoreOurStoreRequest;
use App\Http\Requests\UpdateOurStoreRequest;
use App\Models\OurStore;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class OurStoreController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        // abort_if(Gate::denies('our_store_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ourStores = OurStore::with(['media'])->get();

        return view('admin.ourStores.index', compact('ourStores'));
    }

    public function create()
    {
        // abort_if(Gate::denies('our_store_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ourStores.create');
    }

    public function store(StoreOurStoreRequest $request)
    {
        $ourStore = OurStore::create($request->all());

        foreach ($request->input('gallery', []) as $file) {
            $ourStore->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('gallery');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ourStore->id]);
        }

        return redirect()->route('admin.our-stores.index');
    }

    public function edit(OurStore $ourStore)
    {
        // abort_if(Gate::denies('our_store_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ourStores.edit', compact('ourStore'));
    }

    public function update(UpdateOurStoreRequest $request, OurStore $ourStore)
    {
        $ourStore->update($request->all());

        if (count($ourStore->gallery) > 0) {
            foreach ($ourStore->gallery as $media) {
                if (! in_array($media->file_name, $request->input('gallery', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ourStore->gallery->pluck('file_name')->toArray();
        foreach ($request->input('gallery', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $ourStore->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('gallery');
            }
        }

        return redirect()->route('admin.our-stores.index');
    }

    public function show(OurStore $ourStore)
    {
        // abort_if(Gate::denies('our_store_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ourStores.show', compact('ourStore'));
    }

    public function destroy(OurStore $ourStore)
    {
        // abort_if(Gate::denies('our_store_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ourStore->delete();

        return back();
    }

    public function massDestroy(MassDestroyOurStoreRequest $request)
    {
        $ourStores = OurStore::find(request('ids'));

        foreach ($ourStores as $ourStore) {
            $ourStore->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        // abort_if(Gate::denies('our_store_create') && Gate::denies('our_store_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new OurStore();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
