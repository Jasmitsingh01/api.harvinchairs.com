<?php


namespace App\Database\Repositories;


use Exception;
use App\Models\Zone;
use App\Exceptions\MarvelException;
use App\Models\Zipcode;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Repository\Exceptions\RepositoryException;



class ZipcodeRepository extends BaseRepository
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
        'country_id',
        'zip_code',
        'amount'
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
        return Zipcode::class;
    }

    /**
     * store zone data
     **/
    public function storeZipcode($request){
        try {
            $zip = $this->create($request->only($this->dataArray));
            return $zip;
        } catch (\Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
      /**
     * update zone data
     **/
    public function updateZipcode($request,$id){
        try {
            $zip = $this->findOrFail($id);
            $zip->update($request->only($this->dataArray));
            return $zip;
        } catch (ValidatorException $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
}
