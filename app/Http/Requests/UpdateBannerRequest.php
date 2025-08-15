<?php

namespace App\Http\Requests;

use App\Models\Banner;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateBannerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'nullable',
            ],
            'dis_index' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'link_open' => [
                'string',
                'nullable',
            ],
            'display_text' => [
                'nullable',
            ],
            'text_colour' => [
                'string',
                'nullable',
            ],
        ];
    }
}
