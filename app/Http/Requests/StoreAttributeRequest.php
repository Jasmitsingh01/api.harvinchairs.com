<?php

namespace App\Http\Requests;

use App\Models\Attribute;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAttributeRequest extends FormRequest
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
            'language' => [
                'string',
                'nullable',
            ],
            'position' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'name' => [
                'string',
                'required',
            ],
            'public_name' => [
                'string',
                'nullable',
            ],
            'group_type' => [
                'string',
                'nullable',
            ],
        ];
    }
}
