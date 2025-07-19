<?php

namespace App\Http\Controllers;

use App\Database\Models\Product;
use App\Models\ProductAttributeCombination;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Database\Models\Attribute;
use App\Exceptions\MarvelException;
use App\Http\Requests\AttributeValueRequest;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Database\Repositories\AttributeValueRepository;

class AttributeValueController extends CoreController
{
    public $repository;

    public function __construct(AttributeValueRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $attribute_id = $request->attribute_id;
        $language = $request->language ?? config('shop.default_language');

        // $attribute_values = $this->repository->where('updated_at',null)->orderBy('id','DESC')->get();
        // foreach($attribute_values as $value){

        //     $old_attribute_id = $value->attribute_id;
        //     if($old_attribute_id != null && $old_attribute_id != 0){
        //         $old_attribute = Attribute::where('old_id',$old_attribute_id)->first();
        //         // dd($old_attribute);
        //         $new_id = $old_attribute->id;
        //         $value->attribute_id = $new_id;
        //         $value->save();
        //     }
        // }
        // return( $attribute_values);
        $limit = $request->limit ?   $request->limit : 15;
        if(isset($attribute_id)){
            return $this->repository->where('attribute_id',$attribute_id)->where('language',$language)->with('attribute')->paginate($limit);
        }
        return $this->repository->with('attribute')->where('language',$language)->paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AttributeValueRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store(AttributeValueRequest $request)
    {
        if ($this->repository->hasPermission($request->user(), $request->shop_id)) {
            return $this->repository->storeAttributeValue($request);
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
            return $this->repository->with('attribute')->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Attribute Value not found!'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AttributeValueRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(AttributeValueRequest $request, $id)
    {
        try {
            $request->id = $id;
            return $this->updateAttributeValue($request);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Attribute Value not found!'], 404);
        }
    }

    public function updateAttributeValue(AttributeValueRequest $request)
    {

        if ($this->repository->hasPermission($request->user(), $request->shop_id)) {
            try {
                $attribute = $this->repository->with('attribute')->findOrFail($request->id);
            } catch (\Exception $e) {
                throw new MarvelException(NOT_FOUND);
            }
            return $this->repository->updateAttributeValue($request, $attribute);
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
    public function destroy($id)
    {
        try {
            return $this->repository->findOrFail($id)->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Attribute Value not found!'], 404);
        }
    }
    public function getAttributeValues(Request $request,$id)
    {
        $language = $request->language ?? config('shop.default_language');
        return $this->repository->where('attribute_id',$id)->where('language',$language)->get();
    }

    public function getAttributeValueByAttributeCategory(Request $request){
        $category = $request->category_id;
        $attribute = Attribute::where('name','LIKE','%Fabric Type%')->first();
        $attribute_id = 6;

        $productValue = ProductAttributeCombination::with('attributeValue')
        ->where('attribute_id',$attribute_id)
        ->whereHas('productAttribute',function($q) use ($category) {
            $q->whereHas('product',function($q1) use ($category) {
                $q1->whereHas('categories', function ($query) use ($category) {
                    $query->where('categories.id', $category);
                });
            });
        })->groupBy('attribute_value_id')->get();

        $att_value = [];
        foreach($productValue as $pr_value){
            $att_value[] = $pr_value->attributeValue;
        }
        return $att_value;
    }
}
