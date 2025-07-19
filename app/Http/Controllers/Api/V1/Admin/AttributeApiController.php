<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Http\Resources\Admin\AttributeResource;
use App\Models\Attribute;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttributeApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('attribute_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AttributeResource(Attribute::with(['shop'])->get());
    }

    public function store(StoreAttributeRequest $request)
    {
        $attribute = Attribute::create($request->all());

        return (new AttributeResource($attribute))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Attribute $attribute)
    {
        abort_if(Gate::denies('attribute_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AttributeResource($attribute->load(['shop']));
    }

    public function update(UpdateAttributeRequest $request, Attribute $attribute)
    {
        $attribute->update($request->all());

        return (new AttributeResource($attribute))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Attribute $attribute)
    {
        abort_if(Gate::denies('attribute_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attribute->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
