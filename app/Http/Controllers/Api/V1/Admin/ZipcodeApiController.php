<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreZipcodeRequest;
use App\Http\Requests\UpdateZipcodeRequest;
use App\Http\Resources\Admin\ZipcodeResource;
use App\Models\Zipcode;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ZipcodeApiController extends Controller
{
    public function index()
    {

        return new ZipcodeResource(Zipcode::with(['country'])->get());
    }

    public function store(StoreZipcodeRequest $request)
    {
        $zipcode = Zipcode::create($request->all());

        return (new ZipcodeResource($zipcode))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Zipcode $zipcode)
    {

        return new ZipcodeResource($zipcode->load(['country']));
    }

    public function update(UpdateZipcodeRequest $request, Zipcode $zipcode)
    {
        $zipcode->update($request->all());

        return (new ZipcodeResource($zipcode))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Zipcode $zipcode)
    {

        $zipcode->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
