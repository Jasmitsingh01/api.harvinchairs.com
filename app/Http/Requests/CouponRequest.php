<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Enums\CouponType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class CouponRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $language = $this->language ?? DEFAULT_LANGUAGE;

        return  [
            'code'        => ['required', Rule::unique('coupons')->where('language', $language)],
            'customer_id' => ['nullable', 'string'],
            'type'        => ['required', Rule::in([CouponType::FIXED_COUPON, CouponType::PERCENTAGE_COUPON, CouponType::FREE_SHIPPING_COUPON])],
            'discount_type'    => ['required', Rule::in([CouponType::PERCENTAGE_COUPON,CouponType::AMOUNT])],
            'discount'      => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
            'image'       => ['array'],
            'language'     => ['nullable', 'string'],
            'active_from' => ['required', 'date'],
            'expire_at'   => ['required', 'date'],
            'max_redemption_per_user'     => ['nullable', 'integer'],
            'min_amount'     => ['nullable', 'integer'],
            'min_qty'     => ['nullable', 'integer'],
            'max_usage'     => ['nullable', 'integer'],
            'usage_count_per_user'     => ['nullable', 'integer'],
            'country'     => ['nullable', 'integer'],
            'is_used'     => ['nullable'],
            'category_id'   => ['nullable'],
            'product_id'     => ['nullable'],
            'free_shipping'    => ['nullable'],
            'attributes'     => ['nullable'],
            'coupon_title'  => ['required'],
            'country_id'  => ['nullable'],
            'discount_for'=>['nullable'],

        ];
    }

    /**
     * Get the error messages that apply to the request parameters.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'code.required'        => 'Code field is required and it should be unique',
            'amount.required'      => 'Amount field is required',
            'type.required'        => 'Coupon type is required and it can be only ' . CouponType::FIXED_COUPON . ' or ' . CouponType::PERCENTAGE_COUPON . ' or ' . CouponType::FREE_SHIPPING_COUPON . '',
            'type.in'              => 'Type only can be ' . CouponType::FIXED_COUPON . ' or ' . CouponType::PERCENTAGE_COUPON . ' or ' . CouponType::FREE_SHIPPING_COUPON . '',
            'active_from.required' => 'Active from field is required',
            'expire_at.required'   => 'Expire at field is required',
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
