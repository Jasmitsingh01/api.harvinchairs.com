<?php


namespace App\Database\Repositories;


use Exception;
use App\Exceptions\MarvelException;
use App\Models\Carrier;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Repository\Exceptions\RepositoryException;



class CarrierRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'        => 'like',
        'type.slug',
        'language'
    ];

    protected $dataArray = [
        'name',
        'position',
        'active'
    ];
    public function boot()
    {
        try {
            $this->pushCriteria(app(RequestCriteria::class));
        } catch (RepositoryException $e) {
            //
        }
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return Carrier::class;
    }

    /**
     * store carrier data
     **/
    public function storeCarrier($request){
        try {
            $carrier = $this->create($request->only($this->dataArray));
            return $carrier;
        } catch (\Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
      /**
     * update carrier data
     **/
    public function updateCarrier($request,$id){
        try {
            $carrier = $this->findOrFail($id);
            $carrier->update($request->only($this->dataArray));
            return $carrier;
        } catch (ValidatorException $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
}
