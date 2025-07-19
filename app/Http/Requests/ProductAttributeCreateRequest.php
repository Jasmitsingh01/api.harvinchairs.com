<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class ProductAttributeCreateRequest extends FormRequest
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
            // 'product_id'                   => ['required', 'exists:App\Database\Models\Product,id'],
            'reference_code'               => ['required', 'string'],
            'wholesale_price'              => ['nullable', 'numeric'],
            'impact_on_price'              => ['required', 'string'],
            'impact_on_price_of'           => ['nullable', 'string'],
            'impact_on_weight'             => ['nullable', 'string'],
            'impact_on_weight_of'          => ['nullable', 'string'],
            'minimum_quantity'             => ['required', 'numeric'],
            'availability_date'            => ['required', 'date'],
            'images'                       => ['array', 'nullable'],
            'combinations'                 => ['array', 'nullable'],
            'is_default'                   => [Rule::in([1, 0]), 'nullable'],
        ];
    }
     /**
     * failedValidation
     *
     * @param  mixed $validator
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
