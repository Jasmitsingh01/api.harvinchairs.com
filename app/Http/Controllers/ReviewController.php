<?php


namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Database\Models\Order;
use App\Database\Models\Review;
use Illuminate\Http\JsonResponse;
use App\Exceptions\MarvelException;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ReviewCreateRequest;
use App\Http\Requests\ReviewUpdateRequest;
use App\Http\Requests\FeedbackCreateRequest;
use Illuminate\Database\Eloquent\Collection;
use App\Database\Repositories\ReviewRepository;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Controllers\Traits\MediaUploadingTrait;


class ReviewController extends CoreController
{
    use MediaUploadingTrait;
    public $repository;

    public function __construct(ReviewRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Review[]
     */
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        if (isset($request['product_id']) && !empty($request['product_id'])) {
            if (null !== $request->user()) {
                $request->user()->id; // need another way to force login
            }
            return $this->repository->where('product_id', $request['product_id'])->where('is_active',true)->paginate($limit);
        }
        if ($this->repository->hasPermission($request->user(), $request->shop_id)) {
            return $this->repository->paginate($limit);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ReviewCreateRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store(ReviewCreateRequest $request)
    {
        $product_id = $request['product_id'];
        $order_id = $request['order_id'];
        $hasProductInOrder = Order::where('id', $order_id)->whereHas('products', function ($q) use ($product_id) {
            $q->where('product_id', $product_id);
        })->exists();

        if( false === $hasProductInOrder) {
            // throw new MarvelException(NOT_FOUND);
        }

        try {
            if(isset($request->user()->id)){
                $user_id = $request->user()->id;
                $request['user_id'] = $user_id;
                $request['customer_name'] = $request->user()->first_name;
                if(isset($request['variation_option_id']) && !empty($request['variation_option_id'])) {
                    $review = $this->repository->where('user_id', $user_id)->where('order_id', $order_id)->where('product_id', $product_id)->where('shop_id', $request['shop_id'])->where('variation_option_id', $request['variation_option_id'])->get();
                } else {
                    $review = $this->repository->where('user_id', $user_id)->where('order_id', $order_id)->where('product_id', $product_id)->where('shop_id', $request['shop_id'])->get();
                }
                if (count($review)) {
                    throw new MarvelException(ALREADY_GIVEN_REVIEW_FOR_THIS_PRODUCT);
                }
            }
            return $this->repository->storeReview($request);
        } catch (\Exception $e) {
            // dd($e);
            throw new MarvelException(ALREADY_GIVEN_REVIEW_FOR_THIS_PRODUCT);
        }
    }

    public function show($id)
    {
        try {
            return $this->repository->findOrFail($id);
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }

    public function update(ReviewUpdateRequest $request, $id)
    {
        $request->id = $id;
        return $this->updateReview($request);
    }

    public function updateReview(ReviewUpdateRequest $request)
    {
        $id =  $request->id;
        return $this->repository->updateReview($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            return $this->repository->findOrFail($id)->delete();
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }
    public function reviewStatusUpdate(Request $request,$id)
    {
        $user = $request->user();
        if ($this->repository->hasPermission($user)) {
            $review_id = $request->review_id ? $request->review_id : $id;
            try {
                $review = $this->repository->findOrFail($review_id);
                if ($review->is_active == true) {
                    $review->update(['is_active'=>false]);
                    return $review;
                }
            } catch (Exception $e) {
                throw new Exception("Review not found");
            }
            $review->update(['is_active'=> true]);
            return $review;
        }

        throw new MarvelException(NOT_AUTHORIZED);
    }
}
