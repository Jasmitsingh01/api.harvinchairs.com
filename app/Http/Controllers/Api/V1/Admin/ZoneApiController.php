<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreZoneRequest;
use App\Http\Requests\UpdateZoneRequest;
use App\Http\Resources\Admin\ZoneResource;
use App\Models\Zone;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ZoneApiController extends Controller
{
    public function index()
    {

        return new ZoneResource(Zone::all());
    }

    public function store(StoreZoneRequest $request)
    {
        $zone = Zone::create($request->all());

        return (new ZoneResource($zone))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Zone $zone)
    {

        return new ZoneResource($zone);
    }

    public function update(UpdateZoneRequest $request, Zone $zone)
    {
        $zone->update($request->all());

        return (new ZoneResource($zone))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Zone $zone)
    {

        $zone->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
