<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
// use App\Database\Models\Banner;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (Cache::has('bannerList')) {
        //     return Cache::get('bannerList');
        // }
        $language = config('shop.default_language');
        $limit = 4;
        $homebanner1 = Banner::where('active',true)->where('type','Home Banner 1')->orderBy('dis_index')->get();
        $homebanner2 = Banner::where('active',true)->where('type','Home Banner 2')->orderBy('dis_index')->get();
        $homebanner3 = Banner::where('active',true)->where('type','Home Banner 3')->orderBy('dis_index')->limit('2')->get();
        // $homebanner4 = Banner::where('active',true)->where('type','Home Banner 4')->orderBy('dis_index')->get();
        // $sidebanners = Banner::where('active',true)->where('type','Side Banner')->orderBy('dis_index')->get();
        $categorybanner = Category::where('is_home',true)->where('language', $language)->where('enabled',true)->orderBy('position','ASC')->paginate($limit)->makeHidden(['cover_image','thumbnail_image'])->each->append(['url']);

        $collection_focus = [];
        if(!empty($categorybanner)){
            foreach($categorybanner as $categorybann){
                $categorybannArray = $categorybann->toArray();
                $categorybannArray['image'] = $categorybannArray['collection_image'];
                unset($categorybannArray['collection_image']);

                // Convert the modified array back to a model
                $collection_focus[] = $categorybannArray;
            }
        }

        // $home_products = Product::where('is_home',true)->orderBy('is_home_date','ASC')->get()->append(['categoryList','product_combination_short'])->filter(function ($product) {
        //     return (count($product->product_combination_short) > 0);
        // })->values();
        // $testimonial = Testimonial::where('active',true)->first();
        // Cache::put('bannerList', ['homebanner1'=>$homebanner1, 'homebanner2'=>$homebanner2, 'homebanner3'=>$homebanner3, 'homebanner4'=>$homebanner4, 'sidebanners'=>$sidebanners, 'creativecuts_banners'=>$creativecuts_banners,'home_products'=>$home_products,'testimonial'=>$testimonial ], now()->addMinutes(10));
        // return response()->json(['homebanner1'=>$homebanner1, 'homebanner2'=>$homebanner2, 'homebanner3'=>$homebanner3, 'homebanner4'=>$homebanner4, 'sidebanners'=>$sidebanners, 'creativecuts_banners'=>$creativecuts_banners,'home_products'=>$home_products,'testimonial'=>$testimonial ]);
//    Cache::put('bannerList', ['homebanner1'=>$homebanner1, 'homebanner2'=>$homebanner2, 'homebanner3'=>$homebanner3, 'homebanner4'=>$homebanner4, 'sidebanners'=>$sidebanners,'collections_in_focus'=> $categorybanner], now()->addMinutes(10));
        return response()->json(['homebanner1'=>$homebanner1, 'homebanner2'=>$homebanner2, 'homebanner3'=>$homebanner3,'collections_in_focus'=> $collection_focus]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
