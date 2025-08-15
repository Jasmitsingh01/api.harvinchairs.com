<?php


namespace App\Database\Repositories;

use Exception;
use Carbon\Carbon;
use App\Database\Models\Order;
use App\Models\Coupon;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class CouponRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'code'        => 'like',
        'language',

    ];

    public function boot()
    {
        try {
            $this->pushCriteria(app(RequestCriteria::class));
        } catch (RepositoryException $e) {
            //
        }
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Coupon::class;
    }

    public function verifyCoupon($code)
    {
        try {
            $coupon = $this->findOneByFieldOrFail('code', $code);
            if(!isset($coupon)){
                return ["is_valid" => false];
            }
            if ($coupon->is_valid) {
                return  ["is_valid" => true, "coupon" => $coupon];
            }
            return  ["is_valid" => false];
        } catch (\Exception $th) {
            return  ["is_valid" => false];
        }
    }

    public function validateCoupon($code)
    {
        $coupon = $this->where('code', $code)->first();
        if(!isset($coupon)){
            return ["is_valid" => false];
        }
        $now = Carbon::now()->toDateTimeString();
        // check start date
        if(isset($coupon->active_from)) {
            if(!($now >= $coupon->active_from)) {
                return ["is_valid" => false];
            }
        }

        // check end date
        if(isset($coupon->expire_at)) {
            if(!($now <= $coupon->expire_at)) {
                return ["is_valid" => false];
            }
        }

        //check customer id assgin or not
        // if(!empty($coupon->customer_id) && $coupon->customer_id != auth()->user()->id){
        //     return ["is_valid" => false];
        // }

        // check max_usage
        if(isset($coupon->max_usage) && null !== $coupon->max_usage) {
            $max_redemption_count = Order::where(['coupon_id'=>$coupon->id])
            ->where('payment_status','!=','payment-failed')
            ->where('order_status','!=','order-cancelled')
            ->where('order_status','!=','order-failed')
            //->where('order_status','!=','Payment Pending')
            ->get();
            if((count($max_redemption_count) >= $coupon->max_usage)) {
                return ["is_valid" => false];
            }
        }

        // check max_redemptions for user against existing in DB
        $user_redemption_count = Order::where(['coupon_id'=>$coupon->id ,'customer_id' =>auth()->user()->id])
        ->where('payment_status','!=','payment-failed')
        ->where('order_status','!=','order-cancelled')
        ->where('order_status','!=','order-failed')
        //->where('order_status','!=','order-processing')
        //->where('order_status','!=','Payment Pending')
        ->get();
        if(count($user_redemption_count) > $coupon->max_redemption_per_user ) {
            //throw new Exception("coupon already applied");
            return ["is_valid" => false];
        }

        return ["is_valid" => true, "coupon" => $coupon];
    }
    public function applyCoupon($coupondata, $request){
        $coupon = $coupondata['coupon'];
        // $shipping_amount = $request->shipping_charge;
        // if(isset($coupon->free_shipping) && 1 == $coupon->free_shipping) {
        //     $shipping_amount = 0;
        // }
        // $total_amount = $shipping_amount + $request->amount;
        $total_amount = $request->amount;

        // if(isset($coupon->free_shipping) && 1 == $coupon->free_shipping && isset($coupon->free_shipping_min_amount) && null !== $coupon->free_shipping_min_amount && $coupon->free_shipping_min_amount > $total_amount) {
        //     return ["is_valid" => false, "mesasge"=>"minimum amount for free shipping of this coupon is: $coupon->free_shipping_min_amount"];
        // }

        if(isset($coupon->min_amount) && null !== $coupon->min_amount && $coupon->min_amount > $total_amount) {
            return ["is_valid" => false, "mesasge"=>"minimum amount for this coupon is: $coupon->min_amount"];
        }
        // if(isset($coupon->min_qty) && null !== $coupon->min_qty && $coupon->min_qty > $request->quantity) {
        //     return ["is_valid" => false, "mesasge"=>"minimum Quantity for this coupon is: $coupon->min_qty"];
        // }
        if(isset($coupon->product_id) && null !== $coupon->product_id) {
            $products = explode(",",$request->product_id);
            foreach($products as $product){
                if($product != $coupon->product_id){
                    return ["is_valid" => false, "mesasge"=>"Coupon is Not valid for this product."];
                }
            }
        }
        if(isset($coupon->category_id) && null !== $coupon->category_id ) {
            $categories = explode(",",$request->category_id);
            foreach($categories as $category){
                if($category != $coupon->category_id){
                    return ["is_valid" => false, "mesasge"=>"Coupon is Not valid for this category."];
                }
            }
        }
        $discounted_price = 0;
        $amount_to_pay = $total_amount;
        $discount = '';
        if ($coupon->discount_type == 'percentage') {
            $coupon_discounted_price = ($total_amount * $coupon->discount)/100;
            $amount_to_pay = ($total_amount - $coupon_discounted_price);
            $discounted_price = $coupon_discounted_price;
        }
        else if($coupon->discount_type == "amount"){
            $coupon_discounted_price = ($total_amount) - ($coupon->discount);
            $amount_to_pay = $coupon_discounted_price;
            $discounted_price = $coupon->discount;
        }
        $coupon_data['coupon_id'] =  $coupon->id;
        $coupon_data['discount_amount'] = $discounted_price;
        $coupon_data['amount_to_pay'] = $amount_to_pay;
        $coupon_data['amount'] = $amount_to_pay;
        //$coupon_data['shipping_amount'] = $shipping_amount;

        return ["is_valid" => true, "coupon" => $coupon_data];
    }
}
