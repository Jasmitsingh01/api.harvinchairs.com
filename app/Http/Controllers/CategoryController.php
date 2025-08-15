<?php


namespace App\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Database\Repositories\CategoryRepository;
use App\Exceptions\MarvelException;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Prettus\Validator\Exceptions\ValidatorException;


class CategoryController extends CoreController
{
    public $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @param Request $request
    //  * @return Collection|Category[]
    //  */
    // public function fetchOnlyParent(Request $request)
    // {
    //     $limit = $request->limit ?   $request->limit : 15;
    //     return $this->repository->withCount(['products'])->with(['type', 'parent', 'children'])->where('parent', null)->paginate($limit);
    //     // $limit = $request->limit ?   $request->limit : 15;
    //     // return $this->repository->withCount(['children', 'products'])->with(['type', 'parent', 'children.type', 'children.children.type', 'children.children' => function ($query) {
    //     //     $query->withCount('products');
    //     // },  'children' => function ($query) {
    //     //     $query->withCount('products');
    //     // }])->where('parent', null)->paginate($limit);
    // }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @param Request $request
    //  * @return Collection|Category[]
    //  */
    // public function fetchCategoryRecursively(Request $request)
    // {
    //     $limit = $request->limit ?   $request->limit : 15;
    //     return $this->repository->withCount(['products'])->with(['parent', 'subCategories'])->where('parent', null)->paginate($limit);
    // }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Category[]
     */
    public function index(Request $request)
    {
        $language = $request->language ?? config('shop.default_language');
        $parent = $request->parent;
        if($request->language == 'en'){
            $language = config('shop.default_language');
        }
        // $categories = $this->repository->get();
        // foreach($categories as $category){

        //     $old_parent = $category->parent;
        //     if($old_parent != null && $old_parent != 0){
        //         $old_id = $category->old_id;
        //         $old_category = Category::where('old_id',$old_parent)->first();
        //         $new_id = $old_category->id;
        //         $category->parent = $new_id;
        //         $category->save();
        //     }
        // }
        // return( $categories);
        $limit = $request->limit ?   $request->limit : 15;
        if ($parent === null) {
            return $this->repository->with(['type', 'parent', 'children'])->whereNull('parent')->where('language', $language)->where('enabled',true)->orderBy('position','ASC')->paginate($limit)->makeHidden(['cover_image','thumbnail_image']);
        }
        elseif(is_numeric($parent)){
            return $this->repository->with(['type', 'parent', 'children'])->where('parent', $parent)->where('language', $language)->where('enabled',true)->orderBy('position','ASC')->paginate($limit)->makeHidden(['cover_image','thumbnail_image']);
        } else {
            return $this->repository->with(['type', 'parent', 'children'])->where('language', $language)->where('enabled',true)->orderBy('position','ASC')->paginate($limit)->makeHidden(['cover_image','thumbnail_image']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryCreateRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store(CategoryCreateRequest $request)
    {
        $validatedData = $request->validated();
        if(!isset($request->parent)){
            $validatedData['parent'] = config('constants.parent_id');
        }
        return $this->repository->create($validatedData);
        // $language = $request->language ?? DEFAULT_LANGUAGE;
        // $translation_item_id = $request->translation_item_id ?? null;
        // $category->storeTranslation($translation_item_id, $language);
        // return $category;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, $params)
    {

        try {
            $language = $request->language ?? config('shop.default_language');
            if (is_numeric($params)) {
                $params = (int) $params;
                return $this->repository->with(['type', 'parent', 'children'])->where('id', $params)->firstOrFail();
            }
            return $this->repository->with(['type', 'parent', 'children'])->where('slug', $params)->where('language', $language)->firstOrFail();
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            return $this->repository->findOrFail($id)->update($validatedData);
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
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
    public function topCategories(Request $request)
    {
        $language = $request->language ?? config('shop.default_language');
        $parent = $request->parent;
        if($request->language == 'en'){
            $language = config('shop.default_language');
        }

        $limit = $request->limit ?   $request->limit : 12;
        if ($parent === null) {
            return $this->repository->with(['type', 'parent', 'children'])->whereNull('parent')->where('enabled',true)->where('language', $language)->orderBy('position','ASC')->paginate($limit)->makeHidden(['cover_image','thumbnail_image']);
        }
        elseif(is_numeric($parent)){
            return $this->repository->with(['type', 'parent', 'children'])->where('parent', $parent)->where('language', $language)->where('enabled',true)->orderBy('position','ASC')->paginate($limit)->makeHidden(['cover_image','thumbnail_image']);
        } else {
            return $this->repository->with(['type', 'parent', 'children'])->where('language', $language)->where('enabled',true)->orderBy('position','ASC')->paginate($limit)->makeHidden(['cover_image','thumbnail_image']);
        }
    }

    public function shopbymaterial(Request $request){
        $language = $request->language ?? config('shop.default_language');
        $parent = $request->parent;
        if($request->language == 'en'){
            $language = config('shop.default_language');
        }

        $attribute_id = Attribute::where('name','Frame Material')->pluck('id')->first();
        //$attribute_id = 6;

        $category = $this->repository->where('parent', $parent)
        ->whereHas('products',function($q) use($attribute_id){
            $q->whereHas('attributeCombinations',function($q1) use($attribute_id){
                $q1->whereHas('attributeCombinations',function($q2) use($attribute_id){
                    $q2->whereHas('attribute',function($q3) use($attribute_id){
                        $q3->where('id',$attribute_id);
                    });
                });
            });
        })->get();

        return $category;
    }

    public function styleShowCase($parent_id){
        $language = config('shop.default_language');
        $limit = 3;
        $category = $this->repository->where('parent', $parent_id)->where('is_showcase',true)->where('enabled',true)->where('language', $language)->orderBy('position','ASC')->paginate($limit)->makeHidden(['cover_image','thumbnail_image','children','collection_image'])->each->append(['url']);
        return $category;
    }

    public function getParentCateogryWithChild(){
        $language = config('shop.default_language');
        $categories = $this->repository->select('id','name','slug','parent')
        ->with(['children' => function($query){
            $query->select('id','slug','name','parent');
        }])
        ->whereNull('parent')
        ->where('language', $language)
        ->where('enabled',true)
        ->orderBy('position','ASC')
        ->get()
        ->makeHidden(['cover_image','thumbnail_image','collection_image'])
        ->each->append('url');

        foreach ($categories as $category) {
            if ($category->children) {
                foreach ($category->children as $child) {
                    $child->append('url')->makeHidden(['cover_image','thumbnail_image','collection_image','children']);
                }
            }
        }

        return $categories;

    }
}
