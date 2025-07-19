<?php


namespace App\Database\Repositories;


use Exception;
use App\Models\Testimonial;
use App\Exceptions\MarvelException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Repository\Exceptions\RepositoryException;



class TestimonialRepository extends BaseRepository
{
    protected $dataArray = [
        'author_name',
        'author_info',
        'author_email',
        'author_url',
        'content'
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
        return Testimonial::class;
    }

    /**
     * store Testimonial data
     **/
    public function storeTestimonial($request){
        try {
            $testimonial = $this->create($request->only($this->dataArray));
            return $testimonial;
        } catch (\Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }

}
