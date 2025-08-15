<?php

namespace App\Http\Controllers;

use App\Models\PostcodeRegion;
use Exception;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Str;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Database\Models\Type;
use App\Models\SpecificPrice;
use App\Database\Models\Product;
use App\Models\ProductAttribute;
use App\Database\Models\Category;
use App\Database\Models\Wishlist;
use App\Models\LastViewedProduct;
use Illuminate\Http\JsonResponse;
use App\Database\Models\Variation;
use Illuminate\Support\Facades\DB;
use App\Exceptions\MarvelException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Algolia\AlgoliaSearch\SearchClient;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\ProductAttributeCombination;
use Illuminate\Database\Eloquent\Collection;
use Algolia\AlgoliaSearch\Config\SearchConfig;
use App\Database\Repositories\OrderRepository;
use App\Database\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Zipcode;
class ProductController extends CoreController
{
    public $repository;
    public $orderRepository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
        $this->orderRepository = resolve(OrderRepository::class);

    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Product[]
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?   $request->limit : 15;
        return $this->repository->fetchProducts($request)->paginate($limit);
    }
    public function productList(Request $request)
    {
        if (Cache::has('productList?category='.$request->input('category').'&product_search='.$request->input('product_search'))) {
            return Cache::get('productList?category='.$request->input('category').'&product_search='.$request->input('product_search'));
        }

        // category detail
        $categoryDetail = Category::select('name','description','details')->where('id',$request->input('category'))->first()->makeHidden('parent_id','details');

        $products = $this->repository->fetchProductList($request)->get()->filter(function ($product) {
            return (count($product->product_combination_short) > 0);
        })->append(['categoryList','product_combination_short','default_category_detail','combinations','product_color_combination'])->values();

        // Cache::put('productList?category='.$request->input('category').'&product_search='.$request->input('product_search'), $products->makeHidden(['translated_languages','product_features','product_combinations','categories','reviews']), now()->addMinutes(10));
        $products = $products->makeHidden(['translated_languages','product_features','product_combinations','categories','reviews']);

        return ['category_detail' => $categoryDetail,'productList' => $products];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param ProductCreateRequest $request
     * @return mixed
     */
    public function store(ProductCreateRequest $request)
    {
        if ($this->repository->hasPermission($request->user(), $request->shop_id)) {
            return $this->repository->storeProduct($request);
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return JsonResponse
     */
    public function show(Request $request, $slug)
    {
        $request->slug = $slug;
        return $this->fetchSingleProduct($request);
    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return JsonResponse
     */
    public function fetchSingleProduct(Request $request)
    {
        $slug = $request->slug;

        $language = $request->language ?? config('shop.default_language');
        $user = $request->user();
        $limit = isset($request->limit) ? $request->limit : 10;

        try {
            $field = 'slug';
            if (is_numeric($slug)) {
                    $slug = (int) $slug;
                    $field = 'id';
            }
            $product = $this->repository->where($field, $slug)->where('is_active',true)->firstOrFail()->append('product_specific_prices','product_combinations','default_category_detail','product_faqs','coupon');
            // if (is_numeric($slug)) {
            //     $slug = (int) $slug;
            //     $product = $this->repository->where('id', $slug)->where('is_active',true)
            //         // ->with(['type', 'shop', 'categories', 'tags', 'variations.attribute.values', 'variation_options', 'author', 'manufacturer',''])
            //         ->firstOrFail()->append('product_specific_prices','product_combinations','default_category_detail','product_faqs');
            // }
            // else{
            //     $parts = explode("-", $slug);
            //     if (count($parts) >= 2) {
            //         $id = $parts[0];  // "id"
            //         $product_slug = implode("-", array_slice($parts, 1)); // "slug"
            //     }
            //     $product = $this->repository->where('language', $language)->where('slug', $product_slug)->where(function ($query) use ($id) {
            //         $query->where('old_id', $id)
            //               ->orWhere('id', $id);
            //     })->where('is_active',true)
            //     // ->with(['type', 'shop', 'categories', 'tags', 'variations.attribute.values', 'variation_options', 'author', 'manufacturer'])
            //     ->firstOrFail()->append('product_specific_prices','product_combinations','default_category_detail');
            // }


        //    $product_comb = [];
            // $i=0;
            // $groupedAttributes = [];
            // foreach ($product->product_combinations as $attr) {
            //     $j=0;

            //     foreach ($attr->combinations as $combo) {
            //         $name = $combo->attribute->name;
            //         $id = $combo->attribute->id;
            //         $group_type = $combo->attribute->group_type;
            //         $value = $combo->attributeValue->value;
            //         $cover_image = $combo->attributeValue->cover_image;

            //         if (!isset($groupedAttributes[$name])) {
            //             $groupedAttributes[$name] = ['id' => $id, 'group_type' => $group_type, 'values' => []];
            //         }else{
            //             $valueExists = false;
            //             foreach ($groupedAttributes[$name]['values'] as $val) {
            //                 if ($val['value'] === $value) {
            //                     $valueExists = true;
            //                     break;
            //                 }
            //             }
            //             if (!$valueExists) {
            //                 $groupedAttributes[$name]['values'][] = [
            //                     'id' => $combo['attribute_value_id'],
            //                     'value' => $value,
            //                     'cover_image' => $cover_image
            //                 ];
            //             }
            //         }
            //         $j++;
            //     }
            //     $i++;
            // }

            $featuredAttribute = [];
            foreach($product->product_features as $product_feature){
                $featuredAttribute[] = [
                    "product_feature_id" => $product_feature->id,
                    "feature_value_id" => $product_feature->feature_value_id,
                    "feature_id" => $product_feature->featureValue->feature_id,
                    "feature_title" => $product_feature->featureValue->feature_title,
                    "feature_value" => $product_feature->featureValue->value,
                ];
            }

            $newProduct = new \stdClass();
            //$newProduct->combination_detail = $groupedAttributes;
            $newProduct->product_featured_detail = $featuredAttribute;

            $product = $product->makeHidden('product_features');

            $product1 = json_decode(json_encode($product), true);
            $product2 = json_decode(json_encode($newProduct), true);
            // Merge arrays
            $mergedArray = array_merge($product1, $product2);

            $productObject = (object)$mergedArray;


        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['status'=>false,'message'=>"Product Not Found"],404);
        }
        $data['product_details'] =  $product;
        $data['similar_products'] =  $this->repository->fetchRelated($product->id, $limit, $language);
        //store last viewed product
        $this->repository->addLastViewedProduct($request,$product);
        if (
            in_array('variation_options.digital_file', explode(';', $request->with)) || in_array('digital_file', explode(';', $request->with))
        ) {
            if (!$this->repository->hasPermission($user, $product->shop_id)) {
                throw new MarvelException(config('shop.app_notice_domain') . 'ERROR.NOT_AUTHORIZED');
            }
        }
        return $productObject;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param ProductUpdateRequest $request
     * @param int $id
     * @return array
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        $request->id = $id;
        return $this->updateProduct($request);
    }

    public function updateProduct(Request $request)
    {
        if ($this->repository->hasPermission($request->user(), $request->shop_id)) {
            $id = $request->id;
            return $this->repository->updateProduct($request, $id);
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
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

    public function relatedProducts(Request $request)
    {
        $limit = isset($request->limit) ? $request->limit : 10;
        $slug =  $request->slug;
        $language = $request->language ?? config('shop.default_language');
        return $this->repository->fetchRelated($slug, $limit, $language);
    }


    public function exportProducts(Request $request, $shop_id)
    {

        $filename = 'products-for-shop-id-' . $shop_id . '.csv';
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $list = $this->repository->where('shop_id', $shop_id)->get()->toArray();

        if (!count($list)) {
            return response()->stream(function () {
                //
            }, 200, $headers);
        }
        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

        $callback = function () use ($list) {
            $FH = fopen('php://output', 'w');
            foreach ($list as $key => $row) {
                if ($key === 0) {
                    $exclude = ['id', 'slug', 'deleted_at', 'created_at', 'updated_at', 'shipping_class_id', 'author_id', 'manufacturer_id', 'ratings', 'total_reviews', 'my_review', 'in_wishlist', 'rating_count', 'translated_languages'];
                    $row = array_diff($row, $exclude);
                }
                unset($row['id']);
                unset($row['deleted_at']);
                unset($row['shipping_class_id']);
                unset($row['updated_at']);
                unset($row['created_at']);
                unset($row['slug']);
                unset($row['author_id']);
                unset($row['manufacturer_id']);
                unset($row['ratings']);
                unset($row['total_reviews']);
                unset($row['my_review']);
                unset($row['in_wishlist']);
                unset($row['rating_count']);
                unset($row['translated_languages']);
                if (isset($row['image'])) {
                    $row['image'] = json_encode($row['image']);
                }
                if (isset($row['gallery'])) {
                    $row['gallery'] = json_encode($row['gallery']);
                }
                if (isset($row['blocked_dates'])) {
                    $row['blocked_dates'] = json_encode($row['blocked_dates']);
                }
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportVariableOptions(Request $request, $shop_id)
    {
        $filename = 'variable-options-' . Str::random(5) . '.csv';
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $products = $this->repository->where('shop_id', $shop_id)->get();

        $list = Variation::WhereIn('product_id', $products->pluck('id'))->get()->toArray();

        if (!count($list)) {
            return response()->stream(function () {
                //
            }, 200, $headers);
        }
        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

        $callback = function () use ($list) {
            $FH = fopen('php://output', 'w');
            foreach ($list as $key => $row) {
                if ($key === 0) {
                    $exclude = ['id', 'created_at', 'updated_at', 'translated_languages'];
                    $row = array_diff($row, $exclude);
                }
                unset($row['id']);
                unset($row['updated_at']);
                unset($row['created_at']);
                unset($row['translated_languages']);
                if (isset($row['options'])) {
                    $row['options'] = json_encode($row['options']);
                }
                if (isset($row['blocked_dates'])) {
                    $row['blocked_dates'] = json_encode($row['blocked_dates']);
                }
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importProducts(Request $request)
    {
        $requestFile = $request->file();
        $user = $request->user();
        $shop_id = $request->shop_id;

        if (count($requestFile)) {
            if (isset($requestFile['csv'])) {
                $uploadedCsv = $requestFile['csv'];
            } else {
                $uploadedCsv = current($requestFile);
            }
        }

        if (!$this->repository->hasPermission($user, $shop_id)) {
            throw new MarvelException(NOT_AUTHORIZED);
        }
        if (isset($shop_id)) {
            $file = $uploadedCsv->storePubliclyAs('csv-files', 'products-' . $shop_id . '.' . $uploadedCsv->getClientOriginalExtension(), 'public');

            $products = $this->repository->csvToArray(storage_path() . '/app/public/' . $file);

            foreach ($products as $key => $product) {
                if (!isset($product['type_id'])) {
                    throw new MarvelException("MARVEL_ERROR.WRONG_CSV");
                }
                unset($product['id']);
                $product['shop_id'] = $shop_id;
                $product['image'] = json_decode($product['image'], true);
                $product['gallery'] = json_decode($product['gallery'], true);
                try {
                    $type = Type::findOrFail($product['type_id']);
                    if (isset($type->id)) {
                        Product::firstOrCreate($product);
                    }
                } catch (Exception $e) {
                    //
                }
            }
            return true;
        }
    }

    public function importVariationOptions(Request $request)
    {
        $requestFile = $request->file();
        $user = $request->user();
        $shop_id = $request->shop_id;

        if (count($requestFile)) {
            if (isset($requestFile['csv'])) {
                $uploadedCsv = $requestFile['csv'];
            } else {
                $uploadedCsv = current($requestFile);
            }
        } else {
            throw new MarvelException(CSV_NOT_FOUND);
        }

        if (!$this->repository->hasPermission($user, $shop_id)) {
            throw new MarvelException(NOT_AUTHORIZED);
        }
        if (isset($user->id)) {
            $file = $uploadedCsv->storePubliclyAs('csv-files', 'variation-options-' . Str::random(5) . '.' . $uploadedCsv->getClientOriginalExtension(), 'public');

            $attributes = $this->repository->csvToArray(storage_path() . '/app/public/' . $file);

            foreach ($attributes as $key => $attribute) {
                if (!isset($attribute['title']) || !isset($attribute['price'])) {
                    throw new MarvelException("MARVEL_ERROR.WRONG_CSV");
                }
                unset($attribute['id']);
                $attribute['options'] = json_decode($attribute['options'], true);
                try {
                    $product = Type::findOrFail($attribute['product_id']);
                    if (isset($product->id)) {
                        Variation::firstOrCreate($attribute);
                    }
                } catch (Exception $e) {
                    //
                }
            }
            return true;
        }
    }

    public function fetchDigitalFilesForProduct(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $product = $this->repository->with(['digital_file'])->findOrFail($request->parent_id);
            if ($this->repository->hasPermission($user, $product->shop_id)) {
                return $product->digital_file;
            }
        }
    }

    public function fetchDigitalFilesForVariation(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $variation_option = Variation::with(['digital_file', 'product'])->findOrFail($request->parent_id);
            if ($this->repository->hasPermission($user, $variation_option->product->shop_id)) {
                return $variation_option->digital_file;
            }
        }
    }

    public function bestSellerProducts(Request $request)
    {
        // if (Cache::has('bestSellerProductList')) {
        //     return Cache::get('bestSellerProductList');
        // }
        $limit = $request->limit ? $request->limit : 10;
        $language = $request->language ?? config('shop.default_language');
        $range = !empty($request->range) && $request->range !== 'undefined'  ? $request->range : '';
        $type_id = $request->type_id ? $request->type_id : '';
        $category_id = $request->category_id ? $request->category_id : '0';
        if (!ctype_digit($category_id)) {
            return response()->json(['error' => 'Invalid Product id.'], 400);
        }
        if (isset($request->type_slug) && empty($type_id)) {
            try {
                $type = Type::where('slug', $request->type_slug)->where('language', $language)->firstOrFail();
                $type_id = $type->id;
            } catch (ModelNotFoundException $e) {
                throw new MarvelException(NOT_FOUND);
            }
        }
        $products_query = $this->repository->withCount('orders')->orderBy('orders_count', 'desc')->where(['language'=> $language,'available_for_order'=>true]);
        if ($range) {
            $products_query = $products_query->whereDate('created_at', '>', Carbon::now()->subDays($range));
        }

        if(isset($request->category_id) && !empty($request->category_id)){
            $products_query = $products_query->whereHas('categories',
             function ($query) use ($category_id) {
                $query->where('categories.id', $category_id)
                    ->orWhere('parent', $category_id);
            });
        }

        $best_seller_product = $products_query->take($limit)->get()->filter(function ($product) {
            return (count($product->product_combination_short) > 0);
        })->append(['default_category_detail','product_combination_short'])->values();
        // Cache::put('bestSellerProductList', $best_seller_product, now()->addMinutes(10));
        return $best_seller_product;
    }
    public function popularProducts(Request $request)
    {
        $limit = $request->limit ? $request->limit : 10;
        $language = $request->language ?? config('shop.default_language');
        $products_query = $this->repository
        ->select('*')
        ->selectSub(function ($query) {
            $query->from('last_viewed_products')
                ->whereColumn('products.id', 'last_viewed_products.product_id')
                ->selectRaw('count(*)');
        }, 'last_viewed_products_count')
        ->orderBy('last_viewed_products_count', 'desc')
        ->where('is_active',true)
        ->where(['language'=> $language,'available_for_order'=>true])
        ->take($limit)

        ->get()->filter(function ($product) {
            return (count($product->product_combination_short) > 0);
        })->append(['default_category_detail','product_combination_short'])->makeHidden(['product_combinations','reviews'])->values();
        return $products_query;
    }

    public function calculateRentalPrice(Request $request)
    {
        $isAvailable = true;
        $product_id = $request->product_id;
        try {
            $product = Product::findOrFail($product_id);
        } catch (\Throwable $th) {
            throw new MarvelException(NOT_FOUND);
        }
        if (!$product->is_rental) {
            throw new MarvelException(NOT_A_RENTAL_PRODUCT);
        }
        $variation_id = $request->variation_id;
        $quantity = $request->quantity;
        $persons = $request->persons;
        $dropoff_location_id = $request->dropoff_location_id;
        $pickup_location_id = $request->pickup_location_id;
        $deposits = $request->deposits;
        $features = $request->features;
        $from = $request->from;
        $to = $request->to;
        if ($variation_id) {
            $blockedDates = $this->repository->fetchBlockedDatesForAVariationInRange($from, $to, $variation_id);
            $isAvailable = $this->repository->isVariationAvailableAt($from, $to, $variation_id, $blockedDates, $quantity);
            if (!$isAvailable) {
                throw new marvelException(NOT_AVAILABLE_FOR_BOOKING);
            }
        } else {
            $blockedDates = $this->repository->fetchBlockedDatesForAProductInRange($from, $to, $product_id);
            $isAvailable = $this->repository->isProductAvailableAt($from, $to, $product_id, $blockedDates, $quantity);
            if (!$isAvailable) {
                throw new marvelException(NOT_AVAILABLE_FOR_BOOKING);
            }
        }

        $from = Carbon::parse($from);
        $to = Carbon::parse($to);

        $bookedDay = $from->diffInDays($to);

        return $this->repository->calculatePrice($bookedDay, $product_id, $variation_id, $quantity, $persons, $dropoff_location_id, $pickup_location_id, $deposits, $features);
    }

    public function myWishlists(Request $request)
    {
        $limit = $request->limit ? $request->limit : 10;
        return $this->fetchWishlists($request)->paginate($limit);
    }

    public function fetchWishlists(Request $request)
    {
        $user = $request->user();
        $wishlist = Wishlist::where('user_id', $user->id)->pluck('product_id');
        return $this->repository->whereIn('id', $wishlist);
    }
    public function searchProduct(Request $request){
        //$category = $request->category_id;
        $language = $request->language ?? config('shop.default_language');

        $category = Category::where('name','LIKE','%' . $request->product_search . '%')
        ->whereHas('parent',function($query){
            $query->where('enabled',true);
        })
        ->where('language', $language)->orderBy('position','ASC')
        ->limit(config('constants.SEARCH_RESULT_LIMIT'))
        ->get()
        ->makeHidden('description','parent_id')
        ->each->append(['url']);
        //return $category;
        //dd($category);

        $product_limit = config('constants.SEARCH_RESULT_LIMIT') - count($category);

        $productList = [];
        $ourArray =  array_merge($category->toArray(),$productList);
        if($product_limit > 0){
            $productList = $this->repository
            ->where('language', $language)
            ->where('is_active', true)
            ->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->product_search . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->product_search . '%');
            })
            ->orWhereHas('tags', function ($query) use ($request) {
                $query->where('tags.name', 'like', '%' . $request->product_search . '%');
            })->limit($product_limit)->get()->makeHidden('ratings','total_reviews','rating_count','my_review','in_wishlist','product_features','product_combinations','reviews')->each->append(['url']);
            //return $productList;
            $ourArray =  array_merge($category->toArray(),$productList->toArray());
        }


        $filteredArray = array_map(function ($item) {
            return [
                'name' => $item['name'], // Replace 'name' with the actual key of the 'name' field
                'url' => $item['url'],   // Replace 'url' with the actual key of the 'url' field
            ];
        }, $ourArray);
        return $filteredArray;
        // $productList = $this->repository
        // ->where('language', $language)
        // ->where('is_active', true)
        // ->where(function ($query) use ($request) {
        //     $query->where('name', 'LIKE', '%' . $request->product_search . '%')
        //         ->orWhere('description', 'LIKE', '%' . $request->product_search . '%');
        // })
        // ->orWhereHas('tags', function ($query) use ($request) {
        //     $query->where('tags.name', 'like', '%' . $request->product_search . '%');
        // })
        // ->orWhereHas('categories', function ($query) use ($request) {
        //     $query->where('categories.name','LIKE', '%' . $request->product_search . '%');
        // });

        // if (isset($category)) {
        //     $productList->whereHas('categories', function ($query) use ($category) {
        //         $query->where('categories.id', $category);
        //     });
        // }
        // $productList = $productList->get(['id', 'name', 'is_active', 'slug', 'description', 'default_category', 'language'])
        //     ->each(function ($prod) {
        //         $prod->setAppends(['default_category_detail']);
        //     });
        //return  $productList;
    }
    public function getCategoryProduct(Request $request ,$productId){
        $product = Product::findorfail($productId);
        $categoryId = $product->default_category;
        $category = Category::find($categoryId);
        $language = $request->language ?? config('shop.default_language');
        $categories = array($categoryId);

        $productList =  $this->repository->whereHas('categories', function ($query) use ($categories) {
            $query->whereIn('categories.id', $categories);
        })->where('language',$language)->where('is_active',true)->where('id','!=',$productId)->get()->take(20)->append(['default_category_detail','product_combination_short'])->makeHidden(['product_features','product_combinations','product_specific_prices']);

        return  $productList;
    }
    public function otherOrderItems(Request $request, $product_id){
        $orderProduct = OrderProduct::where('product_id',$product_id)->pluck('order_id');
        $productIds = OrderProduct::whereIn('order_id',$orderProduct)->pluck('product_id');
        $limit = $request->limit ? $request->limit : 10;
        $language = $request->language ?? config('shop.default_language');
        return  $this->repository->where('language', $language)->where('id','!=',$product_id)->whereIn('id',$productIds)->paginate($limit)->append('product_combination_short')->makeHidden('product_combinations');
    }
    public function getNewArrivals(Request $request){
        // if (Cache::has('newArrivalProductList')) {
        //     return Cache::get('newArrivalProductList');
        // }

        $limit = isset($request->limit) ? $request->limit : 10;
        $language = $request->language ?? config('shop.default_language');
        // $duration = config('shop.new_product_duration');
         // Parse the dynamic period using Carbon (make sure you have Carbon installed)
        // $startDate = now()->sub(CarbonInterval::fromString($duration));
        // Fetch the products created within the dynamic period
        $categories = []; // An array to store products category-wise
        // Query the products
        $new_arrival_categories = Category::where(['language'=>$language,'new_arrival'=>true,'enabled'=>true])->take(3)->pluck('id','name')->toArray();
        foreach($new_arrival_categories as $key=>$category){
            $products = $this->repository->where(['language'=> $language,'available_for_order'=>true])->where('is_active',true)->whereHas('categories', function ($query) use ($category) {
                $query->where('category_product.category_id', $category);
            })->orderBy('products.id','desc')->get()->filter(function ($product) {
                return (count($product->product_combination_short) > 0);
            })->take(8)->append(['product_combination_short'])->makeHidden(['product_features','product_combinations','product_specific_prices']);
            foreach ($products as $product) {
                $new_product = $product->toArray();
                $product_category = Category::select('id','name','slug')->find($category);
                $new_product['default_category_detail'] = $product_category;
                $categoryname = $key;
                $categories[$categoryname][] = $new_product;
            }
        }
    //    Cache::put('newArrivalProductList', $categories, now()->addMinutes(10));
        return $categories;
    }
    public function getNewArrivalProducts(Request $request){
        $limit = isset($request->limit) ? $request->limit : 10;
        $language = $request->language ?? config('shop.default_language');
        $duration = config('shop.new_product_duration');
         // Parse the dynamic period using Carbon (make sure you have Carbon installed)
        // $startDate = now()->sub(CarbonInterval::fromString($duration));
        // Fetch the products created within the dynamic period
        $categories = []; // An array to store products category-wise
        // Query the products
        $products = $this->repository->select(['products.id','old_id','name','slug','meta_description','description','is_active','language','in_stock','gallery','unity','default_category','advanced_stock_management','available_now','available_later','isFeatured','available_for_order'])
        ->where(['language'=> $language])->where('is_active',true)->orderBy('created_at','desc')->orderBy('id', 'desc')->get();
        $products = $products->filter(function ($product) {
            return (count($product->product_combination_short) > 0);
        })->take(10)->append(['default_category_detail','product_combination_short'])->makeHidden(['product_features','product_combinations','product_specific_prices'])->values();
        return $products;
    }
    public function validateSlug(Request $request){
        $slug = $request->slug;
        $data = $this->repository->where('slug',$slug)->exists();
        return ["is_taken"=>$data];
    }
    public function hotdealsProducts(Request $request){
        // Get the current date and time
        $currentDateTime = Carbon::now();
        // if (Cache::has('hotdealsProductList')) {
        //     return Cache::get('hotdealsProductList');
        // }
        // Fetch active hot deals from the database
        $activeHotDeals = $this->repository->where(function ($query) use ($currentDateTime) {
            $query->where(function ($q) use ($currentDateTime) {
                $q->where('from_date', '<', $currentDateTime->toDateString())
                  ->where('to_date', '>=', $currentDateTime->toDateString());
            })->orWhere(function ($q) use ($currentDateTime) {
                $q->where('from_date', '=', $currentDateTime->toDateString())
                  ->where('from_time', '<=', $currentDateTime->toTimeString());
            })->orWhere(function ($q) use ($currentDateTime) {
                $q->where('to_date', '=', $currentDateTime->toDateString())
                  ->where('to_time', '>=', $currentDateTime->toTimeString());
            });
        })->orderBy('to_date')->where(['is_active'=>true,'available_for_order'=>true])->get()->filter(function ($product) {
            return (count($product->product_combination_short) > 0);
        })->append(['default_category_detail','product_combination_short'])->makeHidden(['product_combinations'])->values();

        // Cache::put('hotdealsProductList', $activeHotDeals, now()->addMinutes(10));
        return $activeHotDeals;
    }
    public function lastViewedProducts(Request $request){
        return $this->repository->lastViewedProducts($request);
    }
    public function featuredProducts(Request $request){
        // if (Cache::has('featuredProductList')) {
        //     return Cache::get('featuredProductList');
        // }
        $limit = $request->limit ? $request->limit : 10;
        $language = $request->language ?? config('shop.default_language');

        $feature_products = $this->repository->where('isFeatured',1)->where(['language'=> $language,'available_for_order'=>true])->paginate($limit)->filter(function ($product) {
            return (count($product->product_combination_short) > 0);
        })->append(['default_category_detail','product_combination_short'])->values();

        // Cache::put('featuredProductList', $feature_products, now()->addMinutes(10));
        return $feature_products;
    }
    public function catalogueProducts(Request $request){
        $limit = $request->limit ? $request->limit : 10;
        $language = $request->language ?? config('shop.default_language');
        return $this->repository->where('creative_cuts',1)->where('language',$language)->get()->take(6)->append(['default_category_detail','product_combination_short'])->makeHidden(['product_specific_prices','reviews','product_features','product_combinations']);
    }
    public function importSheet(Request $request){
        Log::debug($request);
        return Response($request);
    }
    public function checkPostcode(Request $request) {
        $productIds = explode(',',$request->product_id);
        foreach($productIds as $productId){
            $product = $this->repository->find($productId);
            if (!$product || !$this->isProductDeliverable($product, $request->postcode)) {
                return response()->json(['status'=>false,'message' => 'Product cannot be delivered to the provided postal code.'], 200);
            }else{
                return response()->json(['status'=>true,'message' => 'Product can be delivered to the specified postal code'], 200);
            }
        }
    }

    private function isProductDeliverable($product, $postcode) {
        if($postcode){
            $postcodeRegions = PostcodeRegion::where('postcode', 'like', '%'.$postcode.'%')->exists();
            //check here if its exists in product postcodes
            if(isset($product->postcodes) && in_array($postcode, $product->postcodes)){
                return true;
            }
            if($postcodeRegions){
                return true;
            }
            return false;
        }
        return false;
        //return is_array($product->postcodes) && in_array($postcode, $product->postcodes);
    }

    public function addLastViewedProducts(Request $request){
        //dd($request->all());
        try{
            $product_ids = $request->all();
            if(!empty($product_ids)){
                foreach($product_ids as $product_id){
                    $product = Product::find($product_id);
                    if($product){
                        $this->repository->addLastViewedProduct($request,$product);
                    }
                }
            }
            return response()->json(['status'=>true,'message' => 'Product added successfully...'], 200);
        } catch(\Exception $e){
            return response()->json(['status'=>false,'message' =>'Internal Server Error.'], 500);
        }
    }

    public function getManualSearchList(Request $request){
        //$request->product_search
        $language = $request->language ?? config('shop.default_language');

        $category = Category::where('name','LIKE','%' . $request->product_search . '%')
        ->where('language', $language)->orderBy('position','ASC')
        ->limit(config('constants.SEARCH_RESULT_LIMIT'))
        ->get()
        ->each->append(['url']);

        $products = $this->repository->fetchProductList($request)->get()->append(['categoryList','product_combination_short','default_category_detail','combinations','product_color_combination']);

        $products = $products->makeHidden(['translated_languages','product_features','product_combinations','categories','reviews']);

        return ['category_list' => $category,'productList' => $products];
            //return $productList;

    }

}
