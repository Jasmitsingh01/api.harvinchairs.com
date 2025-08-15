<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Database\Models\Product;
use Illuminate\Http\JsonResponse;
use App\Exceptions\MarvelException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Database\Models\AbusiveReport;
use App\Enums\Permission;
use App\Http\Requests\WishlistCreateRequest;
use Illuminate\Database\Eloquent\Collection;
use App\Database\Repositories\WishlistRepository;
use App\Http\Requests\AbusiveReportCreateRequest;
use Prettus\Validator\Exceptions\ValidatorException;


class WishlistController extends CoreController
{
    public $repository;

    public function __construct(WishlistRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|AbusiveReport[]
     */
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        $user = $request->user();
        if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN) && (!isset($request->shop_id) || $request->shop_id === 'undefined')) {
            $wishlist = $this->repository->pluck('product_id');
            return Product::whereIn('id', $wishlist)->paginate($limit);

        }elseif($this->repository->hasUserPermission($request->user())){
            $wishlists = $this->repository->where('user_id', auth()->guard('api')->user()->id)
            ->with(['product','combination'])
            ->whereHas('product', function ($query) {
                $query->where('is_active', 1)
                ->whereNull('deleted_at');
            })
           ->paginate($limit);
            foreach($wishlists as $wishlist){
               $wishlist['default_category'] =  $wishlist->product ? $wishlist->product->default_category_detail : '';
               $wishlist['all_combinations']  = $wishlist->combination ? $wishlist->combination->all_combination : '';
               $wishlist['combination_details']  = $wishlist->combination ? $wishlist->combination->makeHidden(['combinations']) : '';
               $wishlist['product_image'] = (isset($wishlist->combination)) ? (($wishlist->combination->images == null) ? $wishlist->product->gallery : $wishlist->combination->images) : $wishlist->product->gallery;
            }
            // dd($wishlist);
            return $wishlists;

        }else{
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AbusiveReportCreateRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store(WishlistCreateRequest $request)
    {
        return $this->repository->storeWishlist($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AbusiveReportCreateRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function toggle(WishlistCreateRequest $request)
    {
        return $this->repository->toggleWishlist($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $request->id = $id;
        return $this->repository->findOrFail($id)->delete();
    }

    public function delete(Request $request)
    {
        if (!$request->user()) {
            throw new MarvelException(NOT_AUTHORIZED);
        }
        $product = Product::where('id', $request->id)->first();
        $wishlist = $this->repository->where('product_id', $product->id)->where('user_id', auth()->user()->id)->first();
        if (!empty($wishlist)) {
            return $wishlist->delete();
        }
        throw new MarvelException(NOT_FOUND);
    }

    /**
     * Check in wishlist product for authenticated user
     *
     * @param int $product_id
     * @return JsonResponse
     */
    public function in_wishlist(Request $request, $product_id)
    {
        $request->product_id = $product_id;
        return $this->inWishlist($request);
    }

    public function inWishlist(Request $request)
    {
        if (auth()->guard('api')->user() && !empty($this->repository->where('product_id', $request->product_id)->where('user_id', auth()->guard('api')->user()->id)->first())) {
            return true;
        }
        return false;
    }
}
