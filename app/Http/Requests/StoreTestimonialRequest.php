<?php

namespace App\Http\Requests;

use App\Models\Testimonial;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTestimonialRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'author_name' => [
                'string',
                'nullable',
            ],
            'author_info' => [
                'string',
                'nullable',
            ],
            'author_url' => [
                'string',
                'nullable',
            ],
            'author_email' => [
                'string',
                'nullable',
            ],
            'rating' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'content' => [
                'string',
                'nullable',
            ],
        ];
    }
}
