<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Tag;
use App\Models\Shop;
use App\Models\User;
use App\Models\Feature;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\PriceUnit;
use Illuminate\Http\Request;
use App\Models\SpecificPrice;
use App\Jobs\ImportProductJob;
use App\Models\ImportFileDetail;
use Yajra\DataTables\DataTables;
use App\Imports\BulkProductImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroyProductRequest;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Database\Repositories\admin\ProductRepository;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductController extends Controller
{
    use MediaUploadingTrait;
    public $repository;
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $language = $request->language ?? config('shop.default_language');
        $products = Product::where('id', 2)->get();
        if ($request->ajax()) {
            $quantitySearchValue = $request->input('columns')[6]['search']['value'];
            $products_array = Product::leftjoin('categories', 'products.default_category', '=', 'categories.id')
                ->select([
                    'products.*',
                    'categories.id as category_id',
                    'categories.name as category_name',
                    'categories.slug as category_slug',
                    DB::raw('COALESCE(SUM(product_attributes.quantity), 0) as product_quantity')
                ])
                ->leftJoin('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                ->groupBy('products.id')
                ->where('products.language', 'en');
                if ($quantitySearchValue) {
                    $products_array->havingRaw('COALESCE(SUM(product_attributes.quantity), 0) LIKE ?', ["%{$quantitySearchValue}%"]);
                }
            return DataTables::of($products_array)->addIndexColumn()
                ->addColumn('action', function ($product) {
                    //dd($product);
                    $editButton = '';
                    $deleteButton = '';
                    $viewButton = '';
                    // Check if the user has the 'product_edit' permission
                    if (Gate::allows('product_edit')) {
                        $editButton = '<a class="text-theme-color" href="' . route('admin.products.edit', $product->id) . '"><i class="fa-solid fa-pen-to-square"></i></a>';
                    }
                    if (Gate::allows('product_show')) {
                        // $viewButton = '<a class="mx-1 text-theme-color" href="' . config('shop.dashboard_url') . '/' . $product->category_slug . '/' . $product->id . '-' . $product->slug . '" target="_blank"><i class="fa-solid fa-eye"></i></a>';
                        $viewButton = '<a class="mx-1 text-theme-color" href="' . config('shop.dashboard_url') . '/product/' . $product->id . '-' . $product->slug . '" target="_blank"><i class="fa-solid fa-eye"></i></a>';
                    }
                    if (Gate::allows('product_delete')) {
                        $deleteButton = ' <form action="' . route('admin.products.destroy', $product->id) . '" method="POST" onsubmit="return confirm(\'' . trans('global.areYouSure') . '\');" style="display: inline-block;">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <button type="submit" class="text-theme-color border-0 bg-transparent px-0" value=""><i class="fa-solid fa-trash-can"></i></button>
                            </form>';
                    }
                    return '
                    <div class="text-nowrap text-theme-color">
                        ' . $editButton . '
                        ' . $viewButton . '
                        ' . $deleteButton . '
                        </div>
                        ';
                })
                ->addColumn('image', function ($product) {
                    $imageUrl = asset($product->image);
                    // dd(  $imageUrl );
                    $altSrc = asset('images/placeholder.png');
                    return '<img src="' . $imageUrl . '"height="50px" width="50px" alt="Product Image" onerror="this.src=\'' . $altSrc . '\';" class="product-image">';
                })
                ->filterColumn('product_quantity', function ($query, $keyword) {
                    $query->havingRaw('COALESCE(SUM(product_attributes.quantity), 0) LIKE ?', ["%{$keyword}%"]);
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        // $products = Product::where('language', $language)->get();

        return view('admin.products.index', compact('products'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shops = Shop::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $language = $request->language ?? config('shop.default_language');
        $categories = Category::select('name', 'id', 'parent', 'enabled')->where('language', $language)->where('enabled', true)->get();
        $tags = Tag::select('name', 'id', 'slug', 'language')->where('language', $language)->get();
        $features = Feature::select('title', 'id', 'position', 'language')->where('language', $language)->OrderBy('position', 'asc')->with('featureValues', function ($query) use ($language) {
            $query->where('is_custom', false)->where('language', $language);
        })->get();
        $default_categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $attributes =  Attribute::where('language', $language)->with('values', function ($query) use ($language) {
            $query->select('id', 'value', 'language', 'attribute_id')->where('language', $language);
        })->select('id', 'language', 'name')->get();
        $customers = User::where('is_admin', false)->where('is_active', true)->get();
        // dd($customers);
        return view('admin.products.create', compact('default_categories', 'categories', 'shops', 'tags', 'features', 'attributes', 'customers'));
    }

    public function store(request $request)
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product =  $this->repository->storeProduct($request);
        $tabname = "product-information";
        if ($request->has('product_submit')) {
            $action = $request->input('product_submit');
            if ($action === 'save') {
                // Handle 'Save' button action
                // Redirect or do any other necessary actions after saving
                return redirect()->route('admin.products.index');
            } elseif ($action === 'save_and_stay') {
                // Handle 'Save & Stay' button action
                // Redirect or stay on the same page after saving
                return redirect()->route('admin.products.edit', ['product' => $product->id, 'activeTab' => $tabname]);
            }
        }
        return redirect()->back();
    }

    public function edit(Product $product,Request $request)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $shops = Shop::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $language = $request->language ?? config('shop.default_language');
        $categories = Category::select('name', 'id', 'parent', 'enabled')->where('language', $language)->where('enabled', true)->get();
        $tags = Tag::select('name', 'id', 'slug', 'language')->where('language', $language)->get();
        $features = Feature::select('title', 'id', 'position', 'language')->where('language', $language)->OrderBy('position', 'asc')->with('featureValues', function ($query) use ($language) {
            $query->where('is_custom', false)->where('language', $language);
        })->get();
        $default_categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $attributes =  Attribute::where('language', $language)->with('values', function ($query) use ($language) {
            $query->select('id', 'value', 'language', 'attribute_id')->where('language', $language)->orderBy('position');
        })->select('id', 'language', 'name')->orderBy('name', 'asc')->get();
        $customers = User::where('is_admin', false)->where('is_active', true)->get();
        // dd($customers);
        $specific_prices = SpecificPrice::with(['customer', 'product_attribute'])->where('product_id', $product->id)->OrderBy('id', 'desc')->get();

        $product->load('shop', 'default_category', 'default_product_category', 'specificPrices', 'productFeatures', 'categories');
        $selected_categories = array();
        foreach ($product->categories as $c => $sCategory) {
            $selected_categories[$c]['id'] = $sCategory->id;
            $selected_categories[$c]['name'] = $sCategory->name;
        }

        $product->append(['product_combinations']);

        $all_attr_value_ids = [];
        $all_attr_ids = [];
        foreach ($product->product_combinations as $attribute) {
            foreach ($attribute->attributeCombinations as $key => $attributeCombinations) {
                $all_attr_value_ids[] = $attributeCombinations->attribute_value_id;

                if (!in_array($attributeCombinations->attribute_id, $all_attr_ids)) {
                    $all_attr_ids[] = $attributeCombinations->attribute_id;
                }
            }
        }
        // dd($all_attr_ids);
        $product_tags = $product->tags->pluck('id')->toArray();
        $price_units = PriceUnit::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'default_categories', 'categories', 'shops', 'tags', 'features', 'attributes', 'customers', 'product_tags', 'specific_prices', 'selected_categories', 'price_units', 'all_attr_value_ids', 'all_attr_ids'));
    }

    public function update(request $request, Product $product)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data =  $this->repository->updateProduct($request, $product);
        $tabname = "product-information";
        if ($request->has('tabname')) {
            $tabname = $request->input('tabname');
        }
        if ($request->has('product_submit')) {
            $action = $request->input('product_submit');
            if ($action === 'save') {
                // Handle 'Save' button action
                // Redirect or do any other necessary actions after saving
                return redirect()->route('admin.products.index');
            } elseif ($action === 'save_and_stay') {
                // Handle 'Save & Stay' button action
                // Redirect or stay on the same page after saving

                return redirect()->route('admin.products.edit', ['product' => $product->id, 'activeTab' => $tabname]);
            }
        }
        return redirect()->route('admin.products.index');
    }

    public function show(Product $product)
    {
        $product->load('shop', 'default_category');

        return view('admin.products.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductRequest $request)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::find(request('ids'));

        foreach ($products as $product) {
            $product->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        $model         = new Product();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
    public function updateStatus(Request $request)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'id' => 'required|exists:products,id',
            'field' => 'required|in:is_active,is_home,isFeatured',
            'value' => 'required|boolean',
        ]);

        $product = Product::find($request->id);

        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }

        $product->{$request->field} = $request->value;
        $product->save();

        return response()->json(['message' => 'Status updated successfully.']);
    }
    public function massUpdate(Request $request)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|in:is_active,is_home,delete,isFeatured',
            'value' => 'required|boolean',
        ]);
        $products = Product::find(request('ids'));

        foreach ($products as $product) {
            if ($request->action == "delete") {
                $product->delete();
            } else {
                $product->{$request->action} = $request->value;
                $product->save();
            }
        }

        return response()->json(['message' => 'Status updated successfully.']);
    }
    public function validateSlug(Request $request, $slug)
    {
        // Check if the slug exists in the database
        $product = Product::where('slug', $slug)->first();

        if ($product) {
            // If the slug is taken, return a JSON response with an error message
            return response()->json(['message' => 'URL is taken'], 400);
        } else {
            // If the slug is valid, return a JSON response with a success message
            return response()->json(['message' => 'URL is available']);
        }
    }

    public function importProductfile(Request $request){
        $validator = Validator::make($request->all(),
            [
                'importfile'          => 'required|mimes:csv,xlsx,xls'
            ]
        );
        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = $errors->first();
            return redirect()->route('admin.products.index',["message" => $error_message]);
            //return redirect()->route('create.bulkdata',["message" => $error_message]);
        }

        try {
            if ($request->hasFile('importfile')) {
                $importfile = $request->file('importfile');
                $importfilename = 'importfile-' . rand() . '-' . time() . '.' . $importfile->extension();

                // Original image upload
                Storage::disk('public')->putFileAs('importfile', $importfile, $importfilename);

                ImportFileDetail::create([
                    'original_file_name' => $importfile->getClientOriginalName(),
                    'import_filename' => $importfilename,
                    'status' => 'pending'
                ]);

                // Original image upload
                ImportProductJob::dispatch($importfilename);
                $message = "The import data process has been initiated successfully. You will receive updates soon.";
            }
        } catch (\Exception $e) {
            $message = 'Something Went Wrong...!';
            // Log the exception details for debugging
            \Log::error('Import Product File upload error: ' . $e->getMessage());
        }

       return redirect()->route('admin.products.index')->with('message',$message);
    }

    public function exportProductfile(){
        $file= public_path(). "/sample_file/harvin_products_import.xlsx";

        $headers = array(
            'Content-Type: application/xlsx',
        );

        return \Illuminate\Support\Facades\Response::download($file, 'products-sample-file.xlsx', $headers);
    }

    public function importProductfileStatus(){
        $importfiledetails = ImportFileDetail::orderBy('id', 'desc')->get();
        return view('admin.products.importfilestatus', compact('importfiledetails'));
    }

}
