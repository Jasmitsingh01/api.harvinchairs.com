<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreOurStoreRequest;
use App\Http\Requests\UpdateOurStoreRequest;
use App\Http\Resources\Admin\OurStoreResource;
use App\Models\OurStore;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OurStoreController extends Controller
{
    use MediaUploadingTrait;



    public function index(Request $request)
    {
        // Get the search parameter from the query string
        $search = $request->input('search');

        // Query to filter stores based on search parameter
        $query = OurStore::query();

        if ($search) {
            // If search parameter is provided, filter by city or pincode
            $query->where('city', 'like', '%' . $search . '%')
                  ->orWhere('pincode', 'like', '%' . $search . '%');
        } else {
            // If no search parameter, group by city with store details and count
            // $query->select('*', \DB::raw('COUNT(*) as store_count'))
            //       ->groupBy('city');
        }

        // Get the final result
        $stores = $query->get();

        return response()->json(['status'=>true,'stores'=>$stores],200);
    }


    public function store(StoreOurStoreRequest $request)
    {
        $ourStore = OurStore::create($request->all());

        foreach ($request->input('gallery', []) as $file) {
            $ourStore->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('gallery');
        }

        return (new OurStoreResource($ourStore))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show($id)
    {
        // abort_if(Gate::denies('our_store_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ourstore = OurStore::findOrFail($id);
        if($ourstore){
            $otherStores = OurStore::where('city', $ourstore->city)
                           ->where('id', '!=', $ourstore->id)
                           ->get();
            $ourstore->otherStores = $otherStores;
        }
        return $ourstore;
        //return new OurStoreResource($ourStore);
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

        return (new OurStoreResource($ourStore))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(OurStore $ourStore)
    {
        // abort_if(Gate::denies('our_store_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ourStore->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
