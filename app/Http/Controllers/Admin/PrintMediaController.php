<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPrintMediumRequest;
use App\Http\Requests\StorePrintMediumRequest;
use App\Http\Requests\UpdatePrintMediumRequest;
use App\Models\PrintMedium;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PrintMediaController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        // abort_if(Gate::denies('print_medium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $printMedia = PrintMedium::with(['media'])->get();

        return view('admin.printMedia.index', compact('printMedia'));
    }

    public function create()
    {
        // abort_if(Gate::denies('print_medium_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.printMedia.create');
    }

    public function store(StorePrintMediumRequest $request)
    {
        $printMedium = PrintMedium::create($request->all());

        if ($request->input('image', false)) {
            $printMedium->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $printMedium->id]);
        }

        return redirect()->route('admin.print-media.index');
    }

    public function edit(PrintMedium $printMedium)
    {
        // abort_if(Gate::denies('print_medium_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.printMedia.edit', compact('printMedium'));
    }

    public function update(UpdatePrintMediumRequest $request, PrintMedium $printMedium)
    {
        $printMedium->update($request->all());

        if ($request->input('image', false)) {
            if (! $printMedium->image || $request->input('image') !== $printMedium->image->file_name) {
                if ($printMedium->image) {
                    $printMedium->image->delete();
                }
                $printMedium->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
            }
        } elseif ($printMedium->image) {
            $printMedium->image->delete();
        }

        return redirect()->route('admin.print-media.index');
    }

    public function show(PrintMedium $printMedium)
    {
        // abort_if(Gate::denies('print_medium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.printMedia.show', compact('printMedium'));
    }

    public function destroy(PrintMedium $printMedium)
    {
        // abort_if(Gate::denies('print_medium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $printMedium->delete();

        return back();
    }

    public function massDestroy(MassDestroyPrintMediumRequest $request)
    {
        $printMedia = PrintMedium::find(request('ids'));

        foreach ($printMedia as $printMedium) {
            $printMedium->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        // abort_if(Gate::denies('print_medium_create') && Gate::denies('print_medium_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new PrintMedium();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
