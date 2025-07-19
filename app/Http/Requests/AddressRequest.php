<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class AddressRequest extends FormRequest
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
            'first_name'       => ['required', 'string', 'max:255'],
            'address_name'       => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'max:255'],
            'mobile_number'     => ['required', 'string', 'max:255'],
            'alternate_mobile_number'     => ['nullable', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:255'],
            'society' => ['required', 'string', 'max:255'],
            'area' => ['required', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            // 'is_service_lift' => ['required'],
            'customer_id' => ['nullable', 'exists:App\Database\Models\User,id'],
        ];
    }

    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
