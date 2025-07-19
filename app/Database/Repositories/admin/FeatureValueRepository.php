<?php


namespace App\Database\Repositories\admin;

use Exception;
use App\Models\FeatureValue;
use App\Exceptions\MarvelException;

class FeatureValueRepository extends BaseRepository
{
    protected $dataArray = [
        'value',
        'feature_id',
        'language',
        'is_custom'
    ];
    /**
     * Configure the Model
     **/
    public function model()
    {
        return FeatureValue::class;
    }
     /**
     * @param $request
     * @return LengthAwarePaginator|JsonResponse|Collection|mixed
     */
    public function storeFeatureValue($request)
    {
        try {
            if(!is_array($request))
            {
                $featureInput = $request->only($this->dataArray);
            }
            else{
                $featureInput= $request;
            }
            return $this->create($featureInput);
        } catch (Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
    public function updateFeatureValue($request, $featureValue)
    {
        $featureValue->update($request->only($this->dataArray));
        return $this->findOrFail($featureValue->id);
    }
}
