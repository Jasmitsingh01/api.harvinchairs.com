<?php

namespace App\Database\Repositories;

use Exception;
use App\Models\Enquiry;
use App\Exceptions\MarvelException;
use App\Events\EnquirySubmittedEvent;
use App\Models\CreativeCutsEnquire;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use App\Traits\EmailTrait;

/**
 * Interface EnquiryRepositoryRepository.
 *
 * @package namespace App\Repositories;
 */
class EnquiryRepository extends BaseRepository
{
    use EmailTrait;
    protected $dataArray = [
        'customer_name',
        'customer_email',
        'subject',
        'message',
        'product_id',
        'product_attributes_id',
        'product_title',
        'product_img',
        'url',
        'product_price'
    ];
    protected $creativecutsDataArray = [
        'name',
        'email',
        'description',
        'upload_file',
        'product_name'
    ];
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Enquiry::class;
    }

    public function boot()
    {
        try {
            $this->pushCriteria(app(RequestCriteria::class));
        } catch (RepositoryException $e) {
            //
        }
    }
    public function storeEnquiry($request)
    {
        try {
            $enquiryInput = $request->only($this->dataArray);
            $inquiry=  $this->create($enquiryInput);
           // event(new EnquirySubmittedEvent($data));

           $tags = [
            'name' => $inquiry->customer_name,
            'app_url' => env("APP_URL"),
            "app_name" => env("APP_NAME"),
            "inquiry" => $inquiry
        ];
        $toIds=array(config('constants.admin_email'));
        $this->sendEmailNotification('EnquirySubmitted', $toIds,$tags);

            return $inquiry;
        } catch (Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
    public function storeCretivecutsEnquiry($request)
    {
        try {
            $enquiryInput = $request->only($this->creativecutsDataArray);
            $inquiry=  CreativeCutsEnquire::create($enquiryInput);
            return $inquiry;
        } catch (Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
}
