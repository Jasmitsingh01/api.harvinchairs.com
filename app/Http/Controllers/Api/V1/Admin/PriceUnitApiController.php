<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriceUnitRequest;
use App\Http\Requests\UpdatePriceUnitRequest;
use App\Http\Resources\Admin\PriceUnitResource;
use App\Models\PriceUnit;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PriceUnitApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('price_unit_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PriceUnitResource(PriceUnit::all());
    }

    public function store(StorePriceUnitRequest $request)
    {
        $priceUnit = PriceUnit::create($request->all());

        return (new PriceUnitResource($priceUnit))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PriceUnit $priceUnit)
    {
        abort_if(Gate::denies('price_unit_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PriceUnitResource($priceUnit);
    }

    public function update(UpdatePriceUnitRequest $request, PriceUnit $priceUnit)
    {
        $priceUnit->update($request->all());

        return (new PriceUnitResource($priceUnit))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PriceUnit $priceUnit)
    {
        abort_if(Gate::denies('price_unit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $priceUnit->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
