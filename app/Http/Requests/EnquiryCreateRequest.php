<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EnquiryCreateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'customer_name'         => ['required', 'string'],
            'customer_email'        => ['required', 'email'],
            'subject'               => ['nullable', 'string'],
            'message'               => ['required', 'string'],
            'product_id'            => ['required' ,'integer','exists:App\Database\Models\Product,id'],
            'product_attributes_id' => ['required' ,'integer','exists:App\Models\ProductAttribute,id'],
            'product_title'         => ['nullable', 'string'],
            'product_img'           => ['nullable', 'string'],
            'url'                   => ['nullable', 'string'],
            'product_price'         => ['nullable', 'string'],
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
