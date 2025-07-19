<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProductEnquireRequest;
use App\Http\Requests\StoreProductEnquireRequest;
use App\Http\Requests\UpdateProductEnquireRequest;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductEnquire;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProductEnquireController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {


        if ($request->ajax()) {
            $query = ProductEnquire::with(['product'])->select(sprintf('%s.*', (new ProductEnquire)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'product_enquire_show';
                $editGate      = 'product_enquire_edit';
                $deleteGate    = 'product_enquire_delete';
                $crudRoutePart = 'product-enquires';

                return view('partials.ViewDeleteActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('customer_name', function ($row) {
                return $row->customer_name ? $row->customer_name : '';
            });
            $table->editColumn('customer_email', function ($row) {
                return $row->customer_email ? $row->customer_email : '';
            });
            // $table->editColumn('subject', function ($row) {
            //     return $row->subject ? $row->subject : '';
            // });
            // $table->editColumn('message', function ($row) {
            //     return $row->message ? $row->message : '';
            // });
            // $table->editColumn('product_title', function ($row) {
            //     return $row->product_title ? $row->product_title : '';
            // });
            // $table->editColumn('product_price', function ($row) {
            //     return $row->product_price ? $row->product_price : '';
            // });
            // $table->editColumn('product_img', function ($row) {
            //     return $row->product_img ? '<a href="' . $row->product_img->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            // });
            $table->addColumn('product_name', function ($row) {
                return $row->product ? $row->product->name : '';
            });
            $table->addColumn('created_at', function ($row) {
                return date('d/m/Y', strtotime($row->created_at)) ? date('d/m/Y ', strtotime($row->created_at)) : '';
            });


            // $table->addColumn('product_attributes_reference_code', function ($row) {
            //     return $row->product_attributes ? $row->product_attributes->reference_code : '';
            // });

            // $table->editColumn('url', function ($row) {
            //     return $row->url ? $row->url : '';
            // });

            $table->rawColumns(['actions', 'placeholder','created_at']);

            return $table->make(true);
        }

        if ($request->has('product_enquire_id')) {

            $id = $request->input('product_enquire_id');
                $productEnquire = ProductEnquire::findOrFail($id);

               $result= $productEnquire->update(['notification' => 0]);
            //    dd(  $result);
               return redirect()->route('admin.product-enquires.index');
        }

        // $products           = Product::get();
        // $product_attributes = ProductAttribute::get();

        return view('admin.productEnquires.index');
    }

    public function create()
    {
        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $product_attributes = ProductAttribute::pluck('reference_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.productEnquires.create', compact('product_attributes', 'products'));
    }

    public function store(StoreProductEnquireRequest $request)
    {
        $productEnquire = ProductEnquire::create($request->all());

        if ($request->input('product_img', false)) {
            $productEnquire->addMedia(storage_path('tmp/uploads/' . basename($request->input('product_img'))))->toMediaCollection('product_img');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $productEnquire->id]);
        }

        return redirect()->route('admin.product-enquires.index');
    }

    public function edit(ProductEnquire $productEnquire)
    {

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $product_attributes = ProductAttribute::pluck('reference_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $productEnquire->load('product', 'product_attributes');

        return view('admin.productEnquires.edit', compact('productEnquire', 'product_attributes', 'products'));
    }

    public function update(UpdateProductEnquireRequest $request, ProductEnquire $productEnquire)
    {
        $productEnquire->update($request->all());

        if ($request->input('product_img', false)) {
            if (! $productEnquire->product_img || $request->input('product_img') !== $productEnquire->product_img->file_name) {
                if ($productEnquire->product_img) {
                    $productEnquire->product_img->delete();
                }
                $productEnquire->addMedia(storage_path('tmp/uploads/' . basename($request->input('product_img'))))->toMediaCollection('product_img');
            }
        } elseif ($productEnquire->product_img) {
            $productEnquire->product_img->delete();
        }

        return redirect()->route('admin.product-enquires.index');
    }

    public function show(ProductEnquire $productEnquire)
    {

        $productEnquire->load('product', 'product_attributes');

        return view('admin.productEnquires.show', compact('productEnquire'));
    }

    public function destroy(ProductEnquire $productEnquire)
    {

        $productEnquire->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductEnquireRequest $request)
    {
        $productEnquires = ProductEnquire::find(request('ids'));

        foreach ($productEnquires as $productEnquire) {
            $productEnquire->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {

        $model         = new ProductEnquire();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
