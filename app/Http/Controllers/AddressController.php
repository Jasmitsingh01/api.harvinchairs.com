<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Database\Models\Address;
use App\Database\Repositories\AddressRepository;
use App\Enums\Permission;
use App\Exceptions\MarvelException;
use App\Http\Requests\AddressRequest;
use Prettus\Validator\Exceptions\ValidatorException;

class AddressController extends CoreController
{
    public $repository;

    public function __construct(AddressRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Address[]
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if ($this->repository->hasPermission($request->user())) {
            return $this->repository->with('customer')->all();
        }
        return $this->repository->where('customer_id',$user->id)->with('customer')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddressRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store(AddressRequest $request)
    {
        if ($this->repository->hasUserPermission($request->user())) {
            return $this->repository->storeAddress($request);
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            return $this->repository->with('customer')->findOrFail($id);
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AddressRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(AddressRequest $request, $id)
    {
        $request->id = $id;
        return $this->updateAddress($request);
    }
    public function updateAddress(AddressRequest $request)
    {
        if ($this->repository->hasUserPermission($request->user())) {
            try {
                $address = $this->repository->findOrFail($request->id);
            } catch (\Exception $e) {
                throw new MarvelException(NOT_FOUND);
            }
            return $this->repository->updateAddress($request, $address);
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id, Request $request)
    {
        try {
            $user = $request->user();
            if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN)) {
                return $this->repository->findOrFail($id)->delete();
            } else {
                $address = $this->repository->findOrFail($id);
                if ($address->customer_id == $user->id) {
                    return $address->delete();
                }
            }
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }
}
