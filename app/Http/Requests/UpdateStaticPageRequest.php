<?php

namespace App\Http\Requests;

use App\Models\StaticPage;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateStaticPageRequest extends FormRequest
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
                'unique:static_pages,name,' . request()->route('static_page')->id,
            ],
            'slug' => [
                'string',
                'required',
            ],
            'meta_title' => [
                'string',
                'nullable',
            ],
            'content' => [
                'required',
            ],
        ];
    }
}
