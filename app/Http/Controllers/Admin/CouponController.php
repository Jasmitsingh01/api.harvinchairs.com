<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCouponRequest;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {

    // $subquery = Coupon::select('code', \DB::raw('MAX(id) as id'))
    //               ->groupBy('code');

    // $coupons = \DB::table(\DB::raw("({$subquery->toSql()}) as sub"))
    //          ->mergeBindings($subquery->getQuery())
    //          ->join('coupons', function($join) {
    //              $join->on('sub.code', '=', 'coupons.code')
    //                   ->on('sub.id', '=', 'coupons.id');
    //          })
    //          ->get();


        $coupons = Coupon::groupBy('code')->with(['category', 'product', 'media'])->get();
        // $users = User::get();

        $categories = Category::get();

       $products = Product::get();

        return view('admin.coupons.index', compact( 'coupons','categories','products'));
    }

    public function create(Request $request)
    {
        $customers = User::pluck('first_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $language = $request->language ?? config('shop.default_language');
        $categories = Category::where('language',$language)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::where('language',$language)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.coupons.create', compact('categories', 'customers', 'products'));
    }

    public function store(StoreCouponRequest $request ,Coupon $coupon)
    {
        //dd($request->all());
        $data= array();
        // if(isset($request['customer_id'])) {
        //     foreach($request['customer_id'] as $customer_id) {
        //         $request['customer_id'] = $customer_id;

        //         Coupon::create($request->all());
        //     }

        // }else{
        //      Coupon::create($request->all());

        // }

        $coupon = Coupon::create($request->all());
        // if ($request->input('image', false)) {

        //     $coupon->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->preservingOriginal()->toMediaCollection('image');
        //     $converted_url = [
        //         'thumbnail' => $coupon->image->getUrl('thumb'),
        //         'original' => $coupon->image->getUrl(),
        //         'filepath' =>$coupon->image->getPath(),
        //     ];
        //     $coupon->update(['image'=>json_encode($converted_url)]);
        // }
        if ($request->input('image', false)) {
            $coupon->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $coupon->id]);
        }
        // foreach ($request->input('image', []) as $file) {
        //     $coupon->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('image');
        // }



        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $coupon->id]);
        }

        return redirect()->route('admin.coupons.index');
    }

    public function edit(Coupon $coupon,Request $request)
    {
       // $customers = User::pluck('first_name', 'id')->toArray();
        //$customer = coupon::where('code', $coupon->code)->pluck('customer_id');
        //$selected_customer = user::whereIn('id',$customer)->pluck('id')->toArray();
        $language = $request->language ?? config('shop.default_language');
        $categories = Category::where('language',$language)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $products = Product::where('language',$language)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $coupon->load(['category:id,name', 'product:id,name']);
        return view('admin.coupons.edit', compact('coupon','categories','products'));
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $coupon->update($request->all());
        // $code = $request['code'];
        // $selectedCustomerIds = $request['customer_id'];
        // list($customersToCreate, $customersToDelete, $customersToUpdate) = [ [], [], [] ];
        // $existingEntries = Coupon::where('code', $code)->get();
        // $existingCustomerIds = $existingEntries->pluck('customer_id')->toArray();

        //         $customersToCreate = array_diff($selectedCustomerIds, $existingCustomerIds);
        //         $customersToUpdate = array_intersect($selectedCustomerIds, $existingCustomerIds);
        //         $customersToDelete = array_diff($existingCustomerIds, $selectedCustomerIds);


        // foreach ($customersToCreate as $customer_id) {
        //       $data = $request->except('customer_id');
        //         $data['customer_id']= $customer_id;
        //        Coupon::create($data);
        // }

        // foreach ($customersToUpdate as $customer_id) {
        //     $existingEntry = Coupon::where('code', $request['code'])
        //         ->where('customer_id', $customer_id)
        //         ->first();

        //         $data = $request->except('customer_id');
        //        $existingEntry->update( $data);
        // }

        // foreach ($customersToDelete as $customer_id) {
        //     $entryToDelete = Coupon::where('code', $request['code'])
        //         ->where('customer_id', $customer_id)
        //         ->first();

        //     if ($entryToDelete) {
        //         $entryToDelete->delete();
        //     }
        // }



        // if($coupon->image == !null){
        //     if (count($coupon->image) > 0) {
        //         foreach ($coupon->image as $media) {
        //             if (! in_array($media->file_name, $request->input('image', []))) {
        //                 $media->delete();
        //             }
        //         }
        //     }
        //     $media = $coupon->image->pluck('file_name')->toArray();
        //     foreach ($request->input('image', []) as $file) {
        //         if (count($media) === 0 || ! in_array($file, $media)) {
        //             $coupon->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('image');
        //         }
        //     }
        // }

        if ($request->input('image', false)) {
            if (! $coupon->image || $request->input('image') !== $coupon->image->file_name) {
                if ($coupon->image) {
                    $coupon->image->delete();
                }
                $coupon->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
            }
        } elseif ($coupon->image) {
            $coupon->image->delete();
        }
        return redirect()->route('admin.coupons.index');
    }

    public function show(Coupon $coupon)
    {
        // $customerIds = Coupon::where('code',$coupon->code)->pluck('customer_id');
        // $customer = User::whereIn('id',$customerIds)->pluck('first_name')->toArray();
        // $coupon->load( 'category', 'product');

        return view('admin.coupons.show', compact('coupon'));
    }

    public function destroy(Coupon $coupon)
    {
        // dd($coupon);
        $coupons = Coupon::where('code',$coupon->code)->get();
        foreach($coupons as $data){
           $couponDelete= Coupon::find($data->id);
           $couponDelete->delete();
        }
        // $coupon->delete();

        return back();
    }

    public function massDestroy(MassDestroyCouponRequest $request)
    {
        $coupons = Coupon::find(request('ids'));

        foreach ($coupons as $coupon) {
            $data = Coupon::where('code',$coupon->code)->get();
                foreach($data as $value){
                    Coupon::where('id',$value->id)->delete();
                }
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        $model         = new Coupon();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
