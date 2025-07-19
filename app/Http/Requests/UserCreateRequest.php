<?php


namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserCreateRequest extends FormRequest
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
            'first_name'          => ['required', 'string', 'max:255'],
            'last_name'          => ['nullable', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users'],
            'password'      => ['required', 'string',Password::defaults()],
            'newsletter'    => ['nullable'],
            'gender'        => ['nullable',Rule::in(['Mr','Mrs','Other'])],
            'birthdate'     => ['nullable','date'],
            'shop_id'       => ['nullable', 'exists:App\Database\Models\Shop,id'],
            'profile'       => ['array'],
            'address'       => ['array'],
            // 'shop'       => ['array'],
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
            'name.required'      => 'Name is required',
            'name.string'        => 'Name is not a valid string',
            'name.max:255'       => 'Name can not be more than 255 character',
            'email.required'     => 'email is required',
            'email.email'        => 'email is not a valid email address',
            'email.unique:users' => 'email must be unique',
            'password.required'  => 'password is required',
            'password.string'    => 'password is not a valid string',
            'address.array'      => 'address is not a valid json',
            'profile.array'      => 'profile is not a valid json',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
