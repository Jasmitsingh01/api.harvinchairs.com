<?php


namespace App\Database\Repositories;

use Exception;
use App\Database\Models\Feature;
use App\Exceptions\MarvelException;

class FeatureRepository extends BaseRepository
{
    protected $dataArray = [
        'title',
        'language',
        'position'
    ];
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Feature::class;
    }
     /**
     * @param $request
     * @return LengthAwarePaginator|JsonResponse|Collection|mixed
     */
    public function storeFeatures($request)
    {
        try {
            $featureInput = $request->only($this->dataArray);
            return $this->create($featureInput);
        } catch (Exception $e) {
            echo $e;
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
    public function updateFeature($request, $feature)
    {
        $feature->update($request->only($this->dataArray));
        return $this->findOrFail($feature->id);
    }
}
