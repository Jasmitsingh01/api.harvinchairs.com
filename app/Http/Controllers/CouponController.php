<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Database\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Exceptions\MarvelException;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CouponRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateCouponRequest;
use App\Database\Repositories\CouponRepository;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CouponController extends CoreController
{
    public $repository;

    public function __construct(CouponRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?   $request->limit : 15;
        $language = $request->language ??  config('shop.default_language');
        $coupons = $this->repository;
        // if(auth()->guard('api')->user()){
        //     $coupons =  $coupons->where('customer_id',auth()->guard('api')->user()->id)->orWhere('customer_id',null)->where('language', $language)->where('expire_at','>=',date('Y-m-d'));
        // }
        if(isset($request->code)){
            $coupons =  $coupons->where('code',$request->code);
        }
        return $coupons->paginate($limit);
        // return $this->repository->where('language', $language)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CouponRequest $request
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function store(CouponRequest $request)
    {
        $validateData = $request->validated();
        $data= array();
        if(isset($validateData['customer_id'])){
            foreach(explode(",",$validateData['customer_id']) as $customer_id){
                $validateData['customer_id'] = $customer_id;
                $data[]=   $this->repository->create($validateData);
            }
        }else{
            $data[] = $this->repository->create($validateData);
        }

        return $data;
    }
    public function create(Request $request, $param)
    {
        try {
            $language = $request->language ??  config('shop.default_language');
            $category = Category::all();
            $countries = Country::all();
            return $this->repository->where('code', $param)->where('language', $language)->firstOrFail();
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }
    public function createForm(Request $request){
        try {
            $language = $request->language ??  config('shop.default_language');
            $category = Category::all();
            $countries = Country::all();
            return response()->json(['message'=>'Coupon form data fetch succesfully.','countries'=>$countries, 'category'=>$category]);
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, $params)
    {
        try {
            $language = $request->language ??  config('shop.default_language');
            if (is_numeric($params)) {
                $params = (int) $params;
                return $this->repository->where('id', $params)->firstOrFail();
            }
            return $this->repository->where('code', $params)->select('*',DB::raw('group_concat(customer_id) as customer_id'))->where('language', $language)->firstOrFail();
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }
    /**
     * Verify Coupon by code.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'code'     => 'required|string',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = $errors->first();
            return response()->json(['status' => 'fail','message' => $error_message], 422);
        }
        $code = $request->code;
        try {
            return $this->repository->verifyCoupon($code);
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CouponRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateCouponRequest $request, $id)
    {
        $request->id = $id;
        return $this->updateCoupon($request);
    }

    /**
     * Undocumented function
     *
     * @param  $request
     * @return void
     */
    public function updateCoupon(Request $request)
    {
        $id = $request->id;
        $dataArray = ['id', 'code', 'language', 'description', 'image', 'customer_id', 'type', 'discount_type', 'discount', 'max_redemption_per_user', 'product_id', 'category_id', 'active_from', 'expire_at'];
        try {
            $code = $this->repository->findOrFail($id);
            if ($request->has('language') && $request->language ===  config('shop.default_language')) {
                $updatedCoupon = $request->only($dataArray);
                $nonTranslatableKeys = ['language', 'image', 'description', 'id'];
                foreach ($nonTranslatableKeys as $key) {
                    if (isset($updatedCoupon[$key])) {
                        unset($updatedCoupon[$key]);
                    }
                }

                $this->repository->where('code', $code->code)->update($updatedCoupon);
            }

            return $this->repository->update($request->only($dataArray), $id);
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            return $this->repository->findOrFail($id)->delete();
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }
      /**
     * validate Coupon by code.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function validateCoupon(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'code'     => 'required|string',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = $errors->first();
            return response()->json(['status' => 'fail','message' => $error_message], 422);
        }
        $code = $request->code;
        try {
            return $this->repository->validateCoupon($code);
        } catch (\Exception $e) {
            throw new Exception(NOT_FOUND);
        }
    }
    public function applyCoupon(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'code'              => 'required|string',
            'amount'            =>'required',
          //  'shipping_charge'   =>'required',
          //  'quantity'          =>'required',
           // 'product_id'        =>'required',
           // 'category_id'       =>'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = $errors->first();
            return response()->json(['status' => 'fail','message' => $error_message], 422);
        }
        $code = $request->code;
        try {
            $coupon = $this->repository->validateCoupon($code);
            if($coupon['is_valid'] == true){
                return $this->repository->applyCoupon($coupon, $request);
            }
            return response()->json(["is_valid" => false, "coupon" => null],400);

        } catch (\Exception $e) {
            throw new Exception(NOT_FOUND);
        }
    }
}
