<?php

namespace App\Http\Requests;

use App\Models\Order;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOrderRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('order_edit');
    }

    public function rules()
    {
        return [
            'tracking_number' => [
                'string',
                'nullable',
            ],
            'customer_contact' => [
                'string',
                'nullable',
            ],
            'customer_name' => [
                'string',
                'nullable',
            ],
            'amount' => [
                'numeric',
            ],
            'sales_tax' => [
                'numeric',
            ],
            'paid_total' => [
                'numeric',
            ],
            'total' => [
                'numeric',
            ],
            'cancelled_amount' => [
                'numeric',
            ],
            'language' => [
                'string',
                'nullable',
            ],
            'coupon' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'parent' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'discount' => [
                'numeric',
            ],
            'payment_gateway' => [
                'string',
                'nullable',
            ],
            'logistics_provider' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'delivery_fee' => [
                'numeric',
            ],
            'delivery_time' => [
                'string',
                'nullable',
            ],
        ];
    }
}
