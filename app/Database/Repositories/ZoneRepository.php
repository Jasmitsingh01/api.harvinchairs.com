<?php


namespace App\Database\Repositories;


use Exception;
use App\Models\Zone;
use App\Exceptions\MarvelException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Repository\Exceptions\RepositoryException;



class ZoneRepository extends BaseRepository
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
        'Ship_Amt',
        'MinShip_Amt',
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
        return Zone::class;
    }

    /**
     * store zone data
     **/
    public function storeZone($request){
        try {
            $zone = $this->create($request->only($this->dataArray));
            return $zone;
        } catch (\Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
      /**
     * update zone data
     **/
    public function updateZone($request,$id){
        try {
            $zone = $this->findOrFail($id);
            $zone->update($request->only($this->dataArray));
            return $zone;
        } catch (ValidatorException $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
}
