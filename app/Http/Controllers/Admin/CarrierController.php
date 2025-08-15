<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCarrierRequest;
use App\Http\Requests\StoreCarrierRequest;
use App\Http\Requests\UpdateCarrierRequest;
use App\Models\Carrier;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CarrierController extends Controller
{
    public function index()
    {

        $carriers = Carrier::all();

        return view('admin.carriers.index', compact('carriers'));
    }

    public function create()
    {

        return view('admin.carriers.create');
    }

    public function store(StoreCarrierRequest $request)
    {
        $carrier = Carrier::create($request->all());

        return redirect()->route('admin.carriers.index');
    }

    public function edit(Carrier $carrier)
    {

        return view('admin.carriers.edit', compact('carrier'));
    }

    public function update(UpdateCarrierRequest $request, Carrier $carrier)
    {
        $carrier->update($request->all());

        return redirect()->route('admin.carriers.index');
    }

    public function show(Carrier $carrier)
    {

        return view('admin.carriers.show', compact('carrier'));
    }

    public function destroy(Carrier $carrier)
    {

        $carrier->delete();

        return back();
    }

    public function massDestroy(MassDestroyCarrierRequest $request)
    {
        $carriers = Carrier::find(request('ids'));

        foreach ($carriers as $carrier) {
            $carrier->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
