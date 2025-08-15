<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Database\Models\Feature;
use App\Exceptions\MarvelException;
use App\Http\Requests\FeatureValueCreateRequest;
use App\Database\Repositories\FeatureValueRepository;

class FeatureValueController extends Controller
{
    public $repository;

    public function __construct(FeatureValueRepository $repository)
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
        $feature_id = $request->feature_id;

        if(isset($feature_id)){
            return $this->repository->where('feature_id',$feature_id)->where(['is_custom'=>0,'language'=>$language])->with('feature')->paginate($limit);
        }
        return $this->repository->where(['is_custom'=>0,'language'=>$language])->paginate($limit);
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
    public function store(FeatureValueCreateRequest $request)
    {
        if ($this->repository->hasPermission($request->user())) {
            return $this->repository->storeFeatureValue($request);
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
    public function update(FeatureValueCreateRequest $request, $id)
    {
        $request->id = $id;
        return $this->updateFeature($request);
    }

    public function updateFeature(FeatureValueCreateRequest $request)
    {
        if ($this->repository->hasPermission($request->user(), $request->shop_id)) {
            try {
                $featureValue = $this->repository->findOrFail($request->id);
            } catch (\Exception $e) {
                throw new MarvelException(NOT_FOUND);
            }
            return $this->repository->updateFeatureValue($request, $featureValue);
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
        return $this->deleteFeatureValue($request);
    }

    public function deleteFeatureValue(Request $request)
    {
        try {
            $featureValue = $this->repository->findOrFail($request->id);
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
        if ($this->repository->hasPermission($request->user())) {
            $featureValue->delete();
            return $featureValue;
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }
}
