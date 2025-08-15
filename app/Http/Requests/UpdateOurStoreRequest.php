<?php

namespace App\Http\Requests;

use App\Models\OurStore;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOurStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:our_stores,name,' . request()->route('our_store')->id,
            ],
            'short_line' => [
                'string',
                'nullable',
            ],
            'address' => [
                'string',
                'nullable',
            ],
            'opening_hours' => [
                'string',
                'nullable',
            ],
            'contact_number' => [
                'string',
                'nullable',
            ],
            'gallery' => [
                'array',
            ],
            'city' => [
                'string',
                'required',
            ],
        ];
    }
}
