<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Database\Models\Product;
use App\Database\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Exceptions\MarvelException;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\AttributeRequest;
use Illuminate\Database\Eloquent\Collection;
use App\Database\Repositories\ProductRepository;
use App\Database\Repositories\AttributeRepository;
use Illuminate\Support\Facades\Cache;
class AttributeController extends CoreController
{
    public $repository;
    public $productRepository;

    public function __construct(AttributeRepository $repository)
    {
        $this->repository = $repository;
        $this->productRepository = ProductRepository::class;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Type[]
     */
    public function index(Request $request)
    {
        $category = $request->category_id;
        $categories = explode(',', $request->category_id);
        $limit = $request->limit ?   $request->limit : 15;
        $language = $request->language ?? config('shop.default_language');
        // DB::connection()->enableQueryLog();
        // if (Cache::has('attributeList?category='.$category)) {
        //     return Cache::get('attributeList?category='.$category);
        // }
        if (isset($request->category_id)) {
            foreach($categories as $category_id){
                $category = Category::find($category_id);
                if(isset($category) && $category->parent == config('constants.parent_id')){
                    $categories = array_merge($categories, $category->children->pluck('id')->toArray());
                }
            }
            //get all products with selected categories
            $products = Product::whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('categories.id', $categories);
            })->where('language', $language)->where('is_active',1)->pluck('id');
            // dd($products);
            //get attributes and values for selected categories
            $data = $this->repository->whereHas('values', function ($query) use ($products) {
                $query->whereHas('combinations', function ($query) use ($products){
                    $query->whereHas('productAttribute', function ($query) use ($products){
                        $query->whereIn('product_attributes.product_id', $products);
                    });
                });
            })->with(['values' => function ($query) use ($products){
                $query->whereHas('combinations', function ($query) use ($products){
                    $query->whereHas('productAttribute', function ($query) use ($products){
                        $query->whereIn('product_attributes.product_id', $products);
                    });
                });
            }])->where('language', $language)->orderBy('position')->get();
            // $queries = DB::getQueryLog();
            // Cache::put('attributeList?category='.$category, $data, now()->addMinutes(10));
            return $data;
        }
        return $this->repository->where('language', $language)->with(['values', 'shop'])->paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AttributeRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store(AttributeRequest $request)
    {
        if ($this->repository->hasPermission($request->user(), $request->shop_id)) {
            return $this->repository->storeAttribute($request);
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show(Request $request, $params)
    {

        try {
            $language = $request->language ?? config('shop.default_language');
            if (is_numeric($params)) {
                $params = (int) $params;
                return $this->repository->with('values')->where('id', $params)->firstOrFail();
            }
            return $this->repository->with('values')->where('slug', $params)->where('language', $language)->firstOrFail();
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AttributeRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(AttributeRequest $request, $id)
    {
        $request->id = $id;
        return $this->updateAttribute($request);
    }

    public function updateAttribute(AttributeRequest $request)
    {

        if ($this->repository->hasPermission($request->user(), $request->shop_id)) {
            try {
                $attribute = $this->repository->with('values')->findOrFail($request->id);
            } catch (\Exception $e) {
                throw new MarvelException(NOT_FOUND);
            }
            return $this->repository->updateAttribute($request, $attribute);
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
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
        return $this->deleteAttribute($request);
    }

    public function deleteAttribute(Request $request)
    {
        try {

            $attribute = $this->repository->findOrFail($request->id);

        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
        if ($this->repository->hasPermission($request->user())) {
            $attribute->delete();
            return $attribute;
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }

    public function exportAttributes(Request $request, $shop_id)
    {
        $filename = 'attributes-for-shop-id-' . $shop_id . '.csv';
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $list = $this->repository->where('shop_id', $shop_id)->with(['values'])->get()->toArray();

        if (!count($list)) {
            return response()->stream(function () {
            }, 200, $headers);
        }
        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

        $callback = function () use ($list) {
            $FH = fopen('php://output', 'w');
            foreach ($list as $key => $row) {
                if ($key === 0) {
                    $exclude = ['id', 'created_at', 'updated_at', 'slug', 'translated_languages'];
                    $row = array_diff($row, $exclude);
                }
                unset($row['id']);
                unset($row['updated_at']);
                unset($row['slug']);
                unset($row['created_at']);
                unset($row['translated_languages']);
                if (isset($row['values'])) {
                    $row['values'] = implode(',', Arr::pluck($row['values'], 'value'));
                }

                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importAttributes(Request $request)
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
            $file = $uploadedCsv->storePubliclyAs('csv-files', 'attributes-' . $shop_id . '.' . $uploadedCsv->getClientOriginalExtension(), 'public');

            $attributes = $this->repository->csvToArray(storage_path() . '/app/public/' . $file);

            foreach ($attributes as $key => $attribute) {
                if (!isset($attribute['name'])) {
                    throw new MarvelException("MARVEL_ERROR.WRONG_CSV");
                }
                unset($attribute['id']);
                $attribute['shop_id'] = $shop_id;
                $values = [];
                if (isset($attribute['values'])) {
                    $values = explode(',', $attribute['values']);
                }
                unset($attribute['values']);
                $newAttribute = $this->repository->firstOrCreate($attribute);
                foreach ($values as $key => $value) {
                    $newAttribute->values()->create(['value' => $value]);
                }
            }
            return true;
        }
    }
}
