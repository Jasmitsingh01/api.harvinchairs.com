<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Http\Resources\Admin\CountryResource;
use App\Models\Country;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CountriesApiController extends Controller
{
    public function index()
    {
        return new CountryResource(Country::with(['zone'])->get());
    }

    public function store(StoreCountryRequest $request)
    {
        $country = Country::create($request->all());

        return (new CountryResource($country))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Country $country)
    {

        return new CountryResource($country->load(['zone']));
    }

    public function update(UpdateCountryRequest $request, Country $country)
    {
        $country->update($request->all());

        return (new CountryResource($country))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Country $country)
    {

        $country->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
