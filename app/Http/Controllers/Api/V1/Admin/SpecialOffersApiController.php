<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSpecialOfferRequest;
use App\Http\Requests\UpdateSpecialOfferRequest;
use App\Http\Resources\Admin\SpecialOfferResource;
use App\Models\SpecialOffer;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SpecialOffersApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('special_offer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SpecialOfferResource(SpecialOffer::with(['product'])->get());
    }

    public function store(StoreSpecialOfferRequest $request)
    {
        $specialOffer = SpecialOffer::create($request->all());

        return (new SpecialOfferResource($specialOffer))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SpecialOffer $specialOffer)
    {
        abort_if(Gate::denies('special_offer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SpecialOfferResource($specialOffer->load(['product']));
    }

    public function update(UpdateSpecialOfferRequest $request, SpecialOffer $specialOffer)
    {
        $specialOffer->update($request->all());

        return (new SpecialOfferResource($specialOffer))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SpecialOffer $specialOffer)
    {
        abort_if(Gate::denies('special_offer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $specialOffer->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
