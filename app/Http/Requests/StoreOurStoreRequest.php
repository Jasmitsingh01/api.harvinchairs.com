<?php

namespace App\Http\Requests;

use App\Models\OurStore;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOurStoreRequest extends FormRequest
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
                'unique:our_stores',
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
