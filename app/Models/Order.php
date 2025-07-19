<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'orders';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const ORDER_STATUS_RADIO = [
        'order-pending'           => 'Order Pending',
        // 'order-processing'        => 'Order Processing',
        'order-completed'         => 'Order Completed',
        // 'order-refunded'          => 'Order Refunded',
        // 'order-failed'            => 'Order Failed',
        // 'order-cancelled'         => 'Order Cancelled',
        // 'order-at-local-facility' => 'Order At Local Facility',
        // 'order-out-for-delivery'  => 'Order Out For Delivery',
        // 'awaiting-check-payment'  => 'Awaiting check payment',
        // 'payment-accepted'  => 'Payment Accepted',
        // 'processing-in-progress' => 'Processing in progress',
        'shipped' => 'Order Shipped',
        'delivered' => 'Order Delivered',
        'pending' => 'Order Pending',
        'order-processing'        => 'Order Processing',
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

    protected $fillable = [
        'tracking_number',
        'customer_id',
        'customer_contact',
        'customer_name',
        'amount',
        'sales_tax',
        'paid_total',
        'total',
        'cancelled_amount',
        'language',
        'coupon',
        'parent',
        'notification',
        'shop_id',
        'discount',
        'payment_gateway',
        'shipping_address',
        'billing_address',
        'logistics_provider',
        'delivery_fee',
        'delivery_time',
        'order_status',
        'payment_status',
        'created_at',
        'updated_at',
        'deleted_at',
        'assembly_charges',
        'product_discount',
        'shipping_tracking_number',
        'shipping_tracking_url',
        'expected_date',
    ];

    protected $hidden = [
        'amount',
        'sales_tax'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
    public function orderShippingAddress()
    {
        return $this->belongsTo(Address::class,'shipping_address');
    }
    public function orderBillingAddress()
    {
        return $this->belongsTo(Address::class,'billing_address');
    }
    public function products(): belongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('order_quantity', 'unit_price','discounted_price', 'gst_percentage','price_without_gst','subtotal', 'product_attribute_id')
            ->with('attributeCombinations')
            ->withTimestamps()
            ->withTrashed();
    }
    public function orderStatusHistory(): hasMany
    {
        return $this->hasMany(OrderStatusHistory::class,'order_id');
    }
    public function orderPaymentHistory(): hasMany
    {
        return $this->hasMany(OrderPayment::class,'order_id');
    }
    public function orderCarrier()
    {
        return $this->hasOne(OrderCarrier::class,'order_id')->with('carrierName');
    }
    public function messages():hasMany
    {
        return $this->hasMany(EmailLog::class,'orderid','id');
    }
    public function orderMessage():hasMany
    {
        return $this->hasMany(OrderMessage::class,'order_id','id');
    }
    // public function attribute_product(): belongsToMany
    // {
    //     return $this->belongsToMany(OrderProduct::class ,'id')->with('product_attribute')
    //         ->withTimestamps();
    // }

    // public function attribute_product()
    // {
    //     return $this->belongsToMany(OrderProduct::class,'order_product')->with('product_attribute')
    //     ->withTimestamps();
    // }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

}
