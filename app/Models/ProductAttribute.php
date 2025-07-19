<?php

namespace App\Models;

use App\Models\PriceUnit;
use App\Models\SpecificPrice;
use App\Database\Models\Wishlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductAttributeCombination;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\CalculatePaymentTrait;

class ProductAttribute extends Model
{
    use HasFactory,SoftDeletes,CalculatePaymentTrait;
    protected $guarded = [];
    protected $table = 'product_attributes';
    protected $casts = [
        'images' => 'json',
        "combinations"=>'json'

    ];
    protected $appends = [
        'product_total_tax',
        'gst_percentage',
        'combinations',
        'in_wishlist',
        'discounted_price',
        'bulk_buy_discounted_price',
        'gst_detail',
        'all_combination',
    ];

    protected $hidden = [
        "impact_on_price",
        "impact_on_price_of",
        "impact_on_weight",
        "impact_on_weight_of"
    ];
     /**
     * @return BelongsToMany
     */
    public function combinations(): BelongsToMany
    {
        return $this->belongsToMany(ProductAttributeCombination::class, 'product_attribute_combinations','product_attribute_id','attribute_value_id');
    }
    public function getPriceAttribute($value)
    {
        return $value;
        //return (float)number_format($value, 2); // Format to 2 decimal places
    }
    public function attributeCombinations(): hasMany
    {
        return $this->hasMany(ProductAttributeCombination::class,'product_attribute_id');
    }

    public function getGstDetailAttribute(){
        $price = $this->price;
        if($this->discounted_price != null){
            $price = $this->discounted_price->discounted_price;
        }

        return $this->calculateProductDetailGSTCalculation($this->product_id,$price);
    }
    public function getCombinationsAttribute()
    {
        return $this->attributeCombinations()->with(['attribute:id,name,group_type','attributeValue:id,value,cover_image,fabric_image'])->whereHas('attribute', function ($query) {
            $query->whereNotNull('id');
        })
        ->whereHas('attributeValue', function ($query) {
            $query->whereNotNull('id');
        })->get();
    }
    public function getAllCombinationAttribute(){
        $combinations =  $this->attributeCombinations()->get();
        $full_title = "";
        foreach($combinations as $key=>$combination){
            if ($combination->attribute_value_title && $combination->attribute_title) {
                // Generate the string by concatenating the attribute names and values
                $generatedString = $combination->attribute_title . '- ' . $combination->attribute_value_title;
                // Output the generated string
                if($key == 0){
                    $full_title =$generatedString;
                }else{
                    $full_title = $full_title. ' , '.$generatedString;
                }

            }
        }
        return $full_title;

    }
    public function units(): BelongsTo
    {
        return $this->BelongsTo(PriceUnit::class,'unit_id');
    }
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'product_attribute_id');
    }
    public function getInWishlistAttribute()
    {
        if (auth()->guard('api')->user() && !empty($this->wishlists()->where('user_id', auth()->guard('api')->user()->id)->first())) {
            return true;
        }
        return false;
    }
    // public function specificprices(): BelongsTo
    // {
    //     return $this->belongsTo(SpecificPrice::class, 'product_attribute_id');
    // }
    public function getDiscountedPriceAttribute(){
        // return $this->specificPrices()->first() ? $this->specificPrices()->first()->discounted_amount : 0;
        // $specific_prices =DB::table('specific_prices')->select(['id','product_attribute_id','from','to','reduction_type','reduction','customer_id'])->where('product_attribute_id',$this->id)->orWhere('product_attribute_id',0)->orWhere('product_id',$this->product_id)->first();
        $customer_specific_prices ='';
        if(isset(auth()->guard('api')->user()->id)){
            $customer_specific_prices =$specific_prices = DB::table('specific_prices')->select('id','product_attribute_id','customer_id')->where('customer_id',auth()->guard('api')->user()->id)->where('product_attribute_id',$this->id)->exists() ? DB::table('specific_prices')->where('customer_id',auth()->guard('api')->user()->id)->where('product_attribute_id',$this->id)->select('id','product_attribute_id','from','to','reduction_type','reduction','customer_id','from_quantity')->first() : DB::table('specific_prices')->where('customer_id',auth()->guard('api')->user()->id)->where('product_id',$this->product_id)->where('product_attribute_id','0')->select('id','product_attribute_id','from','to','reduction_type','reduction','customer_id','from_quantity')->first();
        }
        if($customer_specific_prices){
           $specific_prices =  $customer_specific_prices;
        }
        else{
            $specific_prices = DB::table('specific_prices')->select('id','product_attribute_id')->where('product_attribute_id',$this->id)->exists() ? DB::table('specific_prices')->where('product_attribute_id',$this->id)->select('id','product_attribute_id','from','to','reduction_type','reduction','customer_id','from_quantity')->first() : DB::table('specific_prices')->where('product_attribute_id','0')->where('product_id',$this->product_id)->select('id','product_attribute_id','from','to','reduction_type','reduction','customer_id','from_quantity')->first();
        }
        if($specific_prices){
            if((!isset($specific_prices->from) && !isset($specific_prices->to)) || ($specific_prices->from <= date('Y-m-d H:i:s') && $specific_prices->to >= date('Y-m-d H:i:s'))){
                if ($specific_prices->reduction_type == 'percentage') {
                    $price = ($this->price * $specific_prices->reduction)/100;
                }
                else if($specific_prices->reduction_type == "dollar"){
                    $price =  $specific_prices->reduction;
                }
                // return $specific_prices;
                if( $specific_prices->customer_id == null){
                    $specific_prices->discounted_price = $this->price - $price;
                    return $specific_prices;
                }

                 //check specific customer price
                elseif (isset(auth()->guard('api')->user()->id) && $specific_prices->customer_id == auth()->guard('api')->user()->id){
                    $specific_prices->discounted_price = $this->price - $price;
                    return $specific_prices;
                }
                else{
                  return null;
                }
            }
        }
        return null;
    }
    public function getBulkBuyDiscountedPriceAttribute(){
        if($this->bulk_buy_minimum_quantity && $this->bulk_buy_discount && $this->discount_type){
            if ($this->discount_type == 'percentage') {
                $price = ($this->price * $this->bulk_buy_discount)/100;
            }
            else if($this->discount_type == "dollar"){
                $price =  $this->bulk_buy_discount;
            }

            return $price;
        }
        return null;
    }

    public function product(): BelongsTo
    {
        return $this->BelongsTo(Product::class, 'product_id');
    }

    public function getGstPercentageAttribute(){
        $gstPercentage = SiteConfiguration::where('varname', 'GST_PERCENTAGE')->value('value') ?? 0;
        return $gstPercentage;
    }

    public function getProductTotalTaxAttribute(){
        $tax_amount = 0;
        if($this->price_without_gst && $this->price){
            $price_without_gst = $this->price_without_gst;
            $price = $this->price;
            $tax_amount = $price - $price_without_gst;
            $tax_amount = number_format($tax_amount, 2);
        }
        return  $tax_amount;
    }
}
