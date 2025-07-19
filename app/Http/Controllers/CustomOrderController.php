<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomOrderRequest;
use App\Http\Requests\UpdateCustomOrderRequest;
use App\Http\Resources\Admin\CustomOrderResource;
use App\Models\CustomOrder;
use Symfony\Component\HttpFoundation\Response;

class CustomOrderController extends Controller
{

    public function index()
    {

        return CustomOrder::all();
    }

    public function store(StoreCustomOrderRequest $request)
    {

        $customOrder = CustomOrder::create($request->all());
        if ($request->input('attach_file', false)) {
            $customOrder->addMedia(storage_path('tmp/uploads/' . basename($request->input('attach_file'))))->toMediaCollection('attach_file');
        }
        return response()->json($customOrder);
        // return (new CustomOrderResource($customOrder))
        //     ->response()
        //     ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CustomOrder $customOrder)
    {
        // return new CustomOrderResource($customOrder);
    }

    public function update(UpdateCustomOrderRequest $request, CustomOrder $customOrder)
    {
        $customOrder->update($request->all());

        if ($request->input('attach_file', false)) {
            if (! $customOrder->attach_file || $request->input('attach_file') !== $customOrder->attach_file->file_name) {
                if ($customOrder->attach_file) {
                    $customOrder->attach_file->delete();
                }
                $customOrder->addMedia(storage_path('tmp/uploads/' . basename($request->input('attach_file'))))->toMediaCollection('attach_file');
            }
        } elseif ($customOrder->attach_file) {
            $customOrder->attach_file->delete();
        }

        // return (new CustomOrderResource($customOrder))
        //     ->response()
        //     ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CustomOrder $customOrder)
    {
        $customOrder->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
