<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryUpdateRequest extends FormRequest
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
            'zone_id'      => 'nullable|exists:App\Models\Zone,id',
            'name'         => 'required',
            'shortname'    => 'required',
            'phonecode'    => 'required',
        ];
    }
}
