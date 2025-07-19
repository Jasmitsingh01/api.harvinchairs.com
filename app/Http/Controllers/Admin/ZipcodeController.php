<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyZipcodeRequest;
use App\Http\Requests\StoreZipcodeRequest;
use App\Http\Requests\UpdateZipcodeRequest;
use App\Http\Requests\ZipcodeUpdateRequest;
use App\Models\Country;
use App\Models\Zipcode;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ZipcodeController extends Controller
{
    public function index()
    {

        $zipcodes = Zipcode::with(['country'])->get();

        $countries = Country::get();

        return view('admin.zipcodes.index', compact('countries', 'zipcodes'));
    }

    public function create()
    {

        $countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.zipcodes.create', compact('countries'));
    }

    public function store(StoreZipcodeRequest $request)
    {
        $zipcode = Zipcode::create($request->all());

        return redirect()->route('admin.zipcodes.index');
    }

    public function edit(Zipcode $zipcode)
    {

        $countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $zipcode->load('country');

        return view('admin.zipcodes.edit', compact('countries', 'zipcode'));
    }

    public function update(ZipcodeUpdateRequest $request, Zipcode $zipcode)
    {
        $zipcode->update($request->all());

        return redirect()->route('admin.zipcodes.index');
    }

    public function show(Zipcode $zipcode)
    {

        $zipcode->load('country');

        return view('admin.zipcodes.show', compact('zipcode'));
    }

    public function destroy(Zipcode $zipcode)
    {

        $zipcode->delete();

        return back();
    }

    public function massDestroy(MassDestroyZipcodeRequest $request)
    {
        $zipcodes = Zipcode::find(request('ids'));

        foreach ($zipcodes as $zipcode) {
            $zipcode->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
