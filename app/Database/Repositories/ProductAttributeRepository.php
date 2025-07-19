<?php


namespace App\Database\Repositories;

use Exception;
use App\Models\ProductAttribute;
use App\Exceptions\MarvelException;
use App\Models\ProductAttributeCombination;


class ProductAttributeRepository extends BaseRepository
{
    protected $dataArray = [
        'product_id',
        'reference_code',
        'wholesale_price',
        'impact_on_price',
        'impact_on_price_of',
        'impact_on_weight',
        'impact_on_weight_of',
        'minimum_quantity',
        'availability_date',
        'images',
        'is_default'
        // 'combinations'
    ];
    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductAttribute::class;
    }
     /**
     * @param $request
     * @return LengthAwarePaginator|JsonResponse|Collection|mixed
     */
    public function storeProductAttribute($request)
    {
        try {
            $data = $request->only($this->dataArray);

            $productAttributeInput= $this->create($data);

            if (isset($request['combinations'])) {
                $combination=[];
                foreach($request['combinations'] as $combinations){
                    $combination= $combinations;
                    $combination['product_attribute_id'] = $productAttributeInput['id'];
                    $productAttributeInput->combinations()->attach([$combination]);
                }

            }
            return $productAttributeInput;
        } catch (Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
    public function updateProductAttribute($request)
    {
        try {
            $data = $request->only($this->dataArray);
            $product_attribute = $this->findOrFail($request->id);
            $productAttributeInput=  $product_attribute->update($data);
            $productAttributeInput=  $this->findOrFail($request->id);
            if (isset($request['combinations'])) {
                $combination=[];
                foreach($request['combinations'] as $key=>$combinations){
                    $combination[$key] = $combinations;
                    $combination[$key]['product_attribute_id'] = $productAttributeInput['id'];

                }
                $existing_combinations = ProductAttributeCombination::where('product_attribute_id',$productAttributeInput['id'])->delete();
                foreach($combination as $comb){
                    $productAttributeInput->combinations()->attach([$comb]);
                }
            }
            return $productAttributeInput;
        } catch (Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
}
