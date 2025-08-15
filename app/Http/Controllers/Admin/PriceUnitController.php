<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPriceUnitRequest;
use App\Http\Requests\StorePriceUnitRequest;
use App\Http\Requests\UpdatePriceUnitRequest;
use App\Models\PriceUnit;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PriceUnitController extends Controller
{
    public function index()
    {
        $priceUnits = PriceUnit::all();

        return view('admin.priceUnits.index', compact('priceUnits'));
    }

    public function create()
    {
        return view('admin.priceUnits.create');
    }

    public function store(StorePriceUnitRequest $request)
    {
        $priceUnit = PriceUnit::create($request->all());

        return redirect()->route('admin.price-units.index');
    }

    public function edit(PriceUnit $priceUnit)
    {
        return view('admin.priceUnits.edit', compact('priceUnit'));
    }

    public function update(UpdatePriceUnitRequest $request, PriceUnit $priceUnit)
    {
        $priceUnit->update($request->all());

        return redirect()->route('admin.price-units.index');
    }

    public function show(PriceUnit $priceUnit)
    {
        return view('admin.priceUnits.show', compact('priceUnit'));
    }

    public function destroy(PriceUnit $priceUnit)
    {
        $priceUnit->delete();

        return back();
    }

    public function massDestroy(MassDestroyPriceUnitRequest $request)
    {
        $priceUnits = PriceUnit::find(request('ids'));

        foreach ($priceUnits as $priceUnit) {
            $priceUnit->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
