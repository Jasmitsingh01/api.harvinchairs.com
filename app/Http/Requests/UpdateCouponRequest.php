<?php

namespace App\Http\Requests;

use App\Models\Coupon;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCouponRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => [
                'string',
                'required',
            ],
            'coupon_title' => [
                'string',
                'nullable',
            ],
            'language' => [
                'string',
                'nullable',
            ],
            'image' => [
               // 'array',
            ],
            'max_redemption_per_user' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'discount' => [
                'numeric',
            ],
            'active_from' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'expire_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'min_amount' => [
                'numeric',
            ],
            'min_qty' => [
                'numeric',
            ],
            'max_usage' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'usage_count_per_user' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'country' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
