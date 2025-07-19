<?php


namespace App\Database\Repositories;


use App\Models\Country;
use App\Database\Models\Tag;
use App\Exceptions\MarvelException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Repository\Exceptions\RepositoryException;



class CountryRepository extends BaseRepository
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
        'shortname',
        'name',
        'phonecode',
        'zone_id',
        'active',
        'contains_states',
        'need_zip_code',
        'need_identification_number'
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
        return Country::class;
    }
    /**
     * store country data
     **/
    public function storeCountry($request){
        try {
            $country = $this->create($request->only($this->dataArray));
            return $country;
        } catch (\Exception $e) {
            dd($e);
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
    /**
     * update country data
     **/
    public function updateCountry($request,$id){
        try {
            $country = $this->findOrFail($id);
            $country->update($request->only($this->dataArray));
            return $country;
        } catch (ValidatorException $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
}
