<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreativeCutsEnquireRequest;
use App\Http\Requests\UpdateCreativeCutsEnquireRequest;
use App\Http\Resources\Admin\CreativeCutsEnquireResource;
use App\Models\CreativeCutsEnquire;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CreativeCutsEnquireApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('creative_cuts_enquire_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CreativeCutsEnquireResource(CreativeCutsEnquire::all());
    }

    public function store(StoreCreativeCutsEnquireRequest $request)
    {
        $creativeCutsEnquire = CreativeCutsEnquire::create($request->all());

        return (new CreativeCutsEnquireResource($creativeCutsEnquire))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CreativeCutsEnquire $creativeCutsEnquire)
    {
        abort_if(Gate::denies('creative_cuts_enquire_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CreativeCutsEnquireResource($creativeCutsEnquire);
    }

    public function update(UpdateCreativeCutsEnquireRequest $request, CreativeCutsEnquire $creativeCutsEnquire)
    {
        $creativeCutsEnquire->update($request->all());

        return (new CreativeCutsEnquireResource($creativeCutsEnquire))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CreativeCutsEnquire $creativeCutsEnquire)
    {
        abort_if(Gate::denies('creative_cuts_enquire_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $creativeCutsEnquire->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
