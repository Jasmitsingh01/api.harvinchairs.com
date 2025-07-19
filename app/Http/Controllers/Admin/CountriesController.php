<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCountryRequest;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Models\Country;
use App\Models\Zone;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CountriesController extends Controller
{
    public function index()
    {
        $countries = Country::with(['zone'])->get();

        $zones = Zone::get();

        return view('admin.countries.index', compact('countries', 'zones'));
    }

    public function create()
    {

        $zones = Zone::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.countries.create', compact('zones'));
    }

    public function store(StoreCountryRequest $request)
    {


        $country = Country::create($request->all());

        return redirect()->route('admin.countries.index');
    }

    public function edit(Country $country)
    {

        $zones = Zone::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $country->load('zone');

        return view('admin.countries.edit', compact('country', 'zones'));
    }

    public function update(UpdateCountryRequest $request, Country $country)
    {
        $country->update($request->all());
        return redirect()->route('admin.countries.index');
    }

    public function show(Country $country)
    {

        $country->load('zone');

        return view('admin.countries.show', compact('country'));
    }

    public function destroy(Country $country)
    {

        $country->delete();

        return back();
    }

    public function massDestroy(MassDestroyCountryRequest $request)
    {
        $countries = Country::find(request('ids'));

        foreach ($countries as $country) {
            $country->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
