<?php


namespace App\Database\Repositories;


use Exception;
use App\Models\Zone;
use App\Exceptions\MarvelException;
use App\Models\OrderStatus;
use App\Models\Zipcode;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Repository\Exceptions\RepositoryException;



class OrderStatusRepository extends BaseRepository
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
        'template',
        'module_name',
        'invoice',
        'send_email',
        'unremovable',
        'hidden',
        'logable',
        'delivery',
        'shipped',
        'paid',
        'pdf_invoice',
        'pdf_delivery'
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
        return OrderStatus::class;
    }
      /**
     * update zone data
     **/
    public function updateOrderStatus($request,$id){
        try {
            $status = $this->findOrFail($id);
            $status->update($request->only($this->dataArray));
            return $status;
        } catch (ValidatorException $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
}
