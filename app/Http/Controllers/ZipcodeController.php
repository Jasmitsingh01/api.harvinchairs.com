<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use Illuminate\Http\Request;
use App\Exceptions\MarvelException;
use App\Http\Requests\ZipcodeCreateRequest;
use App\Http\Requests\ZipcodeUpdateRequest;
use App\Database\Repositories\ZipcodeRepository;

class ZipcodeController extends Controller
{
    public $repository;

    public function __construct(ZipcodeRepository $repository)
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
        $limit = $request->limit ? $request->limit : 15;
        $user = $request->user();
        if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN) && (!isset($request->shop_id) || $request->shop_id === 'undefined')) {
            return $this->repository->paginate($limit);
        }
        return $this->repository->get();
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
    public function store(ZipcodeCreateRequest $request)
    {
        return $this->repository->storeZipcode($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return $this->repository->findOrFail($id);
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
    public function update(ZipcodeUpdateRequest $request, $id)
    {
        $request->id = $id;
        return $this->updateZipcode($request);
    }
    public function updateZipcode(ZipcodeUpdateRequest $request)
    {
        $id =  $request->id;
        return $this->repository->updateZipcode($request, $id);
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
            return $this->repository->findOrFail($id)->delete();
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }
}
