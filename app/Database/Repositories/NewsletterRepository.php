<?php


namespace App\Database\Repositories;

use App\Exceptions\MarvelException;
use App\Models\Newsletter;
use App\Models\Zipcode;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Repository\Exceptions\RepositoryException;

class NewsletterRepository extends BaseRepository
{
    protected $dataArray = [
        'email',
        'ip_registration_newsletter',
        'http_referer',
        'is_active'
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
        return Newsletter::class;
    }

    /**
     * store newsletter data
     **/
    public function storeNewsletter($request){
        try {
            $newsletter = $this->create($request->only($this->dataArray));
            return $newsletter;
        } catch (\Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }

}
