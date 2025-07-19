<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\MarvelException;
use App\Http\Requests\ProductAttributeCreateRequest;
use App\Http\Requests\ProductAttributeUpdateRequest;
use App\Database\Repositories\ProductAttributeRepository;

class ProductAttributeController extends Controller
{
    public $repository;

    public function __construct(ProductAttributeRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $product_id = $request->product_id;
        $limit = $request->limit ?   $request->limit : 15;
        return $this->fetchProductAttributes($request)->paginate($limit);
    }
    public function fetchProductAttributes(Request $request)
    {
        if (isset($request->product_id)) {
            return $this->repository->where('product_id',$request->product_id);
        }
        return $this->repository;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductAttributeCreateRequest $request)
    {
        if ($this->repository->hasPermission($request->user(), $request->shop_id)) {
            return $this->repository->storeProductAttribute($request);
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $params)
    {
        try {
            if (is_numeric($params)) {
                $params = (int) $params;
                return $this->repository->where('id', $params)->firstOrFail();
            }
            // return $this->repository->with(['type', 'parent', 'children'])->where('slug', $params)->where('language', $language)->firstOrFail();
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductAttributeUpdateRequest $request, $id)
    {
        $request->id = $id;
        if ($this->repository->hasPermission($request->user(), $request->shop_id)) {
            return $this->repository->updateProductAttribute($request);
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->repository->findOrFail($id)->attributeCombinations()->delete();
            return $this->repository->findOrFail($id)->delete();
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }
}
