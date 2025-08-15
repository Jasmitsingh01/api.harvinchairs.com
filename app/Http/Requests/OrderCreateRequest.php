<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Enums\PaymentGatewayType;

class OrderCreateRequest extends FormRequest
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
        return [
            'coupon_id'         => 'nullable|exists:App\Database\Models\Coupon,id',
            'shop_id'           => 'nullable|exists:App\Database\Models\Shop,id',
            // 'cart_id'           => 'required|exists:App\Models\Cart,id',
            'customer_id'       => 'nullable|exists:App\Database\Models\User,id',
            'language'          => ['nullable', 'string'],
            'amount'            => 'required|numeric',
            'paid_total'        => 'required|numeric',
            'total'             => 'required|numeric',
            'delivery_time'     => 'nullable|string',
            'customer_contact'  => 'string|required',
            'customer_name'     => 'nullable|string',
            'payment_gateway'   => ['required', Rule::in(PaymentGatewayType::getValues())],
            'products'          => 'required|array',
            'card'              => 'array',
            'token'             => 'nullable|string',
            'use_wallet_points' => 'nullable|boolean',
            'shipping_address'  => 'exists:App\Database\Models\Address,id',
            'billing_address'   =>'exists:App\Database\Models\Address,id',
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
