<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShoppingCartRequest;
use App\Http\Requests\UpdateShoppingCartRequest;
use App\Http\Resources\Admin\ShoppingCartResource;
use App\Models\ShoppingCart;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShoppingCartApiController extends Controller
{
    public function index()
    {

        return new ShoppingCartResource(ShoppingCart::with(['user'])->get());
    }

    public function store(StoreShoppingCartRequest $request)
    {
        $shoppingCart = ShoppingCart::create($request->all());

        return (new ShoppingCartResource($shoppingCart))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ShoppingCart $shoppingCart)
    {

        return new ShoppingCartResource($shoppingCart->load(['user']));
    }

    public function update(UpdateShoppingCartRequest $request, ShoppingCart $shoppingCart)
    {
        $shoppingCart->update($request->all());

        return (new ShoppingCartResource($shoppingCart))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ShoppingCart $shoppingCart)
    {

        $shoppingCart->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
