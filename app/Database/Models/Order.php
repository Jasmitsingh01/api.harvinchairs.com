<?php

namespace App\Database\Models;

use App\Enums\OrderStatus;
use App\Models\OrderCarrier;
use App\Models\OrderMessage;
use App\Models\OrderProduct;
use App\Models\ProductAttribute;
use App\Models\SiteConfiguration;
use App\Traits\TranslationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use SoftDeletes;
    use TranslationTrait;
    protected $table = 'orders';

    public $guarded = [];

    protected $casts = [
        'shipping_address'    => 'json',
        'billing_address'     => 'json',
        'payment_intent_info' => 'json',
    ];

    protected $hidden = [
//        'created_at',
        'updated_at',
        'deleted_at',
        'amount',
        'sales_tax'
    ];
    protected $appends = [
    'order_billing_address',
    'order_shipping_address',
    'can_cancel_order'
    ];

    public const ORDER_STATUS_RADIO = [
        'order-pending'           => 'Order Pending',
        // 'order-processing'        => 'Order Processing',
        // 'order-refunded'          => 'Order Refunded',
        // 'order-failed'            => 'Order Failed',
        // 'order-cancelled'         => 'Order Cancelled',
        // 'order-at-local-facility' => 'Order At Local Facility',
        // 'order-out-for-delivery'  => 'Order Out For Delivery',
        // 'awaiting-check-payment'  => 'Awaiting check payment',
        // 'payment-accepted'  => 'Payment Accepted',
        // 'processing-in-progress' => 'Processing in progress',
        'pending' => 'Order Pending',
        'order-processing'        => 'Order Processing',
        'shipped' => 'Order Shipped',
        'delivered' => 'Order Delivered',
        'order-completed'         => 'Order Completed',

        'order-cancelled'         => 'Order Cancelled',
        'order-failed'            => 'Order Failed',
        'order-refunded'          => 'Order Refunded',
        // 'payment-error' => 'Payment error',
        // 'on-background-paid' => 'On backorder (paid)',
        // 'awaiting-bank-wire-payment' => 'Awaiting bank wire payment',
        // 'awiting-paypal-payment' => 'Awaiting PayPal payment',
        // 'remote-payment-accepted' => 'Remote payment accepted',
        // 'on-backorder-not-paid' => 'On backorder (not paid)',
        // 'awaiting-cash-on-delivery-validation' => 'Awaiting Cash On Delivery validation',
        // 'authorization-accepted-from-paypal' => 'Authorization accepted from PayPal',
        // 'authorization-accepted-from-braintree' => 'Authorization accepted from Braintree',
        // 'awaiting-for-braintree-payment' => 'Awaiting for Braintree payment',
        // 'waiting-for-validation-by-paypal' => 'Waiting for validation by PayPal'
    ];

    public const PAYMENT_STATUS_RADIO = [
        'payment-pending'               => 'Payment Pending',
        'payment-processing'            => 'Payment Processing',
        'payment-success'               => 'Payment Success',
        'payment-failed'                => 'Payment Failed',
        'payment-reversal'              => 'Payment Reversal',
        'payment-cash-on-delivery'      => 'Payment Cash On Delivery',
        'payment-cash'                  => 'Payment Cash',
        'payment-wallet'                => 'Payment Wallet',
        'payment-awaiting-for-approval' => 'Payment Awaiting For Approval',
    ];

    protected static function boot()
    {
        parent::boot();
        // Order by created_at desc
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
        Order::creating(function ($model) {
            $model->notification = true;
        });
        // Delete related child records when the order is deleted
        Order::deleting(function ($order) {
            $order->children()->delete();
        });
    }

    protected $with = ['customer'];
    // protected $with = ['customer', 'products.variation_options'];


    /**
     * @return belongsToMany
     */
    public function products(): belongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('order_quantity', 'unit_price', 'subtotal', 'product_attribute_id','discounted_price')
            ->withTimestamps()
            ->withTrashed();
    }
    /**
     * @return belongsTo
     */
    public function coupon(): belongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    /**
     * @return belongsTo
     */
    public function customer(): belongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    /**
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany('App\Database\Models\Order', 'parent_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function parent_order()
    {
        return $this->hasOne('App\Database\Models\Order', 'id', 'parent_id');
    }

    /**
     * @return HasOne
     */
    public function refund()
    {
        return $this->hasOne(Refund::class, 'order_id');
    }
    /**
     * @return HasOne
     */
    public function wallet_point()
    {
        return $this->hasOne(OrderWalletPoint::class, 'order_id');
    }

    /**
     * @return HasMany
     */
    public function orderBillingAddress(){
        return $this->HasMany(Address::class,'id', 'billing_address');
    }
    public function orderShippingAddress(){
        return $this->HasMany(Address::class,'id', 'shipping_address');
    }
    public function payment_intent()
    {
        return $this->hasMany(PaymentIntent::class);
    }
    public function orderMessage()
    {
        return $this->hasMany(OrderMessage::class);
    }
    public function getOrderBillingAddressAttribute(){
        return $this->orderBillingAddress()->first();
    }
    public function getOrdershippingAddressAttribute(){
        return $this->orderShippingAddress()->first();
    }
    public function orderCarrier()
    {
        return $this->hasOne(OrderCarrier::class,'order_id')->with('carrierName');
    }

    public function getOrderStatusAttribute($value){
        return self::ORDER_STATUS_RADIO[$value];
    }

    public function getPaymentStatusAttribute($value){
        if(!empty($value)){
            return self::PAYMENT_STATUS_RADIO[$value];
        }
        return $value;
    }

    public function getCanCancelOrderAttribute($value){
        $order_cancel_timeframe = SiteConfiguration::where('varname', 'ORDER_CANCEL_TIMEFRAME')->value('value');
        $order_eligible_for_cancellation = SiteConfiguration::where('varname', 'ELIGIBAL_ORDER_STATUS_FOR_CANCELLATION')->value('value');
        $orderPlacedDate = $this->created_at;
        $days = $orderPlacedDate->diffInDays(now());
        if ($days > $order_cancel_timeframe) {
            return false;
        }

        $order_eligible_array = explode(',', $order_eligible_for_cancellation);
        if (in_array('order-pending', explode(',', $order_eligible_for_cancellation))) {
            // append pending in order_eligible_for_cancellation
            $order_eligible_array[] = 'pending';
            //$order_eligible_for_cancellation = 'pending';
        }
        // $order_eligible_for_cancellation getting shipped value from site configuration so get other status above shipped from order status enum
        //$orderStatusKeys = array_keys(Order::ORDER_STATUS_RADIO);
        //$order_eligible_for_cancellation = array_keys(Self::ORDER_STATUS_RADIO, $order_eligible_for_cancellation);
        //$order_eligible_for_cancellation = reset($order_eligible_for_cancellation);

        //$key  =  array_search($order_eligible_for_cancellation, $orderStatusKeys);

        //$alloworderStatus = array_slice($orderStatusKeys, 0, $key);
        //dd(explode(',', $order_eligible_for_cancellation));
        if (!in_array($this->getRawOriginal('order_status'), $order_eligible_array)) {
            return false;
        }

        // if (!in_array($this->getRawOriginal('order_status'), [OrderStatus::PROCESSING, OrderStatus::PENDING])) {
        //     return false;
        // }
        return true;
    }
}
