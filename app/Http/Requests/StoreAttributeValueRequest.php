<?php

namespace App\Http\Requests;

use App\Models\AttributeValue;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAttributeValueRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'slug' => [
                'string',
                'nullable',
            ],
            'value' => [
                'string',
                'required',
            ],
            'meta_title' => [
                'string',
                'nullable',
            ],
            'language' => [
                'string',
                'nullable',
            ],
            // 'position' => [
            //     'nullable',
            //     'integer',
            //     'min:-2147483648',
            //     'max:2147483647',
            // ],
            'meta' => [
                'string',
                'nullable',
            ],

        ];
    }
}
