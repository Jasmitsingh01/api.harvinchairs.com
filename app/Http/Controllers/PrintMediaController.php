<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePrintMediumRequest;
use App\Http\Requests\UpdatePrintMediumRequest;
use App\Http\Resources\Admin\PrintMediumResource;
use App\Models\PrintMedium;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrintMediaController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        // abort_if(Gate::denies('print_medium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $mediumsByYear = PrintMedium::groupByRaw('YEAR(publish_date)')
        ->get();
        return response()->json(['status'=>true,'media'=>$mediumsByYear],200);
    }

    public function store(StorePrintMediumRequest $request)
    {
        $printMedium = PrintMedium::create($request->all());

        if ($request->input('image', false)) {
            $printMedium->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        }

        return (new PrintMediumResource($printMedium))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PrintMedium $printMedium)
    {
        // abort_if(Gate::denies('print_medium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PrintMediumResource($printMedium);
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

        return (new PrintMediumResource($printMedium))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PrintMedium $printMedium)
    {
        // abort_if(Gate::denies('print_medium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $printMedium->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function getPrintedMedia()
    {
        $printedMedia = PrintMedium::where('status','1')->orderByDesc('created_at')->orderBy('is_print_media')->get();
        return response()->json(['status'=>true,'printMedia'=>$printedMedia],200);
    }


    public function getMedia(Request $request)
    {
        $year = $request->input('year');

        $query = PrintMedium::where('status','1');

        if ($year) {
            // Use whereYear to filter based on the year part of publish_date
            $query->whereYear('publish_date', '=', $year);
        }
        $printedMedia = $query->orderBy('is_print_media')->orderByDesc('publish_date')->get();

        $years = PrintMedium::where('status', '1')
            ->select(\DB::raw('YEAR(publish_date) as year'))
            ->orderBy('is_print_media')
            ->orderByDesc('publish_date')
            ->distinct()
            ->pluck('year');
            //dd($years);

        return response()->json(['status' => true,'years'=>$years,'printMedia' => $printedMedia], 200);
    }


}
