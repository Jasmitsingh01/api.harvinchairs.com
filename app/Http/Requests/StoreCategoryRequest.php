<?php

namespace App\Http\Requests;

use App\Models\Category;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCategoryRequest extends FormRequest
{
    public function authorize()
    {
        // return Gate::allows('category_create');
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'slug' => [
                'string',
                'required',
            ],
            'type_id' => [
                'required',
                'integer',
                'exists:types,id',
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
            'parent' => [
                'nullable',
                'integer',
                'min:1',
                'max:2147483647',
            ],
            'meta_title' => [
                'string',
                'nullable',
            ],
            'sgst_rate' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100'
            ],
            'cgst_rate' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100'
            ]
        ];
    }
}
