<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartCreateRequest extends FormRequest
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
            'delivery_address_id'  => ['nullable','exists:App\Database\Models\Address,id'],
            'billing_address_id'   => ['nullable', 'exists:App\Database\Models\Address,id'],
            'total'                => ['required'],
            'products'             => ['array'],
            'totalUniqueItems'     => ['required'],
            'language'             => ['sometimes'],
        ];
    }
}
