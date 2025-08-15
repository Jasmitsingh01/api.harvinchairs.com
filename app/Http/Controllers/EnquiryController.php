<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\MarvelException;
use App\Http\Requests\EnquiryCreateRequest;
use App\Database\Repositories\EnquiryRepository;
use App\Http\Requests\CreativecutsEnquiryStoreRequest;

class EnquiryController extends Controller
{
    public $repository;

    public function __construct(EnquiryRepository $repository)
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
        $user = $request->user();
        $limit = $request->limit ?   $request->limit : 15;
        return $this->repository->where('customer_email',$user->email)->paginate($limit);
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
    public function store(EnquiryCreateRequest $request)
    {
        return $this->repository->storeEnquiry($request);
    }
    public function storeCretivecutsEnquiry(CreativecutsEnquiryStoreRequest $request)
    {
        return $this->repository->storeCretivecutsEnquiry($request);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
