<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyZoneRequest;
use App\Http\Requests\StoreZoneRequest;
use App\Http\Requests\UpdateZoneRequest;
use App\Models\Zone;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ZoneController extends Controller
{
    public function index()
    {
        $zones = Zone::all();

        return view('admin.zones.index', compact('zones'));
    }

    public function create()
    {
        return view('admin.zones.create');
    }

    public function store(StoreZoneRequest $request)
    {
        $zone = Zone::create($request->all());

        return redirect()->route('admin.zones.index');
    }

    public function edit(Zone $zone)
    {

        return view('admin.zones.edit', compact('zone'));
    }

    public function update(UpdateZoneRequest $request, Zone $zone)
    {
        $zone->update($request->all());

        return redirect()->route('admin.zones.index');
    }

    public function show(Zone $zone)
    {
        return view('admin.zones.show', compact('zone'));
    }

    public function destroy(Zone $zone)
    {

        $zone->delete();

        return back();
    }

    public function massDestroy(MassDestroyZoneRequest $request)
    {
        $zones = Zone::find(request('ids'));

        foreach ($zones as $zone) {
            $zone->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
