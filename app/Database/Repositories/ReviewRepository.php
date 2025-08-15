<?php


namespace App\Database\Repositories;

use App\Database\Models\Product;
use App\Traits\EmailTrait;
use App\Events\ReviewCreated;
use App\Database\Models\Review;
use App\Events\QuestionAnswered;
use App\Events\SendNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use App\Exceptions\MarvelException;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Repository\Exceptions\RepositoryException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class ReviewRepository extends BaseRepository
{
    use EmailTrait;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'rating',
        'shop_id',
        'product_id',
    ];

    /**
     * @var array[]
     */
    protected $dataArray = [
        'order_id',
        'product_id',
        'product_attributes_id',
        'user_id',
        'shop_id',
        'title',
        'comment',
        'rating',
        'customer_name',
        'photos'
    ];

    public function boot()
    {
        try {
            $this->pushCriteria(app(RequestCriteria::class));
        } catch (RepositoryException $e) {
        }
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return Review::class;
    }


    /**
     * @param $request
     * @return LengthAwarePaginator|JsonResponse|Collection|mixed
     */
    public function storeReview($request)
    {
        // add logic to verified purchase and only one rating on each product
        try {
            $reviewInput = $request->only($this->dataArray);
            if(!isset($request->is_active)){
                $reviewInput['is_active'] = false;
            }
            $review = $this->create($reviewInput);

            foreach($request['photos'] as $key=>$photo){
                $imgdata = $review->addMedia(storage_path('tmp/uploads/' . basename($photo)))->preservingOriginal()->toMediaCollection('photos');
                $converted_url[] = [
                    'thumbnail' => $imgdata->getUrl('thumb'),
                    'original' => $imgdata->getUrl(),
                    'id'=> $imgdata->id
                ];
            }
           // $data['photos'] =json_encode($converted_url);
            $review->update(['photos'=>$converted_url]);


            $product = Product::findOrFail($review->product_id);
            if( $product) {
                $url = config('shop.shop_url') . '/products/' . $product->slug;
            }

            $tags = [
                        'name' => $request->customer_name,
                        'app_url' => env("APP_URL"),
                        "app_name" => env("APP_NAME"),
                        "review" => $review,
                        'url' => $url,
                        'product' => $product
                    ];
            //$toids=array("jigar@indapoint.com");
            $toIds=array(config('constants.admin_email'));
            $this->sendEmailNotification('REVIEW_RECIEVED', $toIds,$tags);
            //event(new ReviewCreated($review));
            return $review;
        } catch (\Exception $e) {
            // dd($e);
            dd($e->getMessage());
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }

    public function updateReview($request, $id)
    {
        try {
            $review = $this->findOrFail($id);
            $review->update($request->only($this->dataArray));
            return $review;
        } catch (ValidatorException $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
}
