<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\MarvelException;
use App\Http\Requests\FeatureCreateRequest;
use App\Database\Repositories\FeatureRepository;

class FeatureController extends Controller
{
    public $repository;

    public function __construct(FeatureRepository $repository)
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
        $language = $request->language ?? config('shop.default_language');

        $limit = $request->limit ?   $request->limit : 15;
        return $this->repository->where('language', $language)->paginate($limit);
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
    public function store(FeatureCreateRequest $request)
    {
        if ($this->repository->hasPermission($request->user())) {
            return $this->repository->storeFeatures($request);
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
    public function show($id)
    {
        //
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
    public function update(FeatureCreateRequest $request, $id)
    {
        $request->id = $id;
        return $this->updateFeature($request);
    }

    public function updateFeature(FeatureCreateRequest $request)
    {
        if ($this->repository->hasPermission($request->user(), $request->shop_id)) {
            try {
                $feature = $this->repository->findOrFail($request->id);
            } catch (\Exception $e) {
                throw new MarvelException(NOT_FOUND);
            }
            return $this->repository->updateFeature($request, $feature);
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
    public function destroy(Request $request, $id)
    {
        $request->id = $id;
        return $this->deleteFeature($request);
    }

    public function deleteFeature(Request $request)
    {
        try {
            $feature = $this->repository->findOrFail($request->id);
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
        if ($this->repository->hasPermission($request->user())) {
            $feature->delete();
            return $feature;
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }
}
