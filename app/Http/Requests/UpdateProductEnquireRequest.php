<?php

namespace App\Http\Requests;

use App\Models\ProductEnquire;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProductEnquireRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer_name' => [
                'string',
                'nullable',
            ],
            'subject' => [
                'string',
                'nullable',
            ],
            'message' => [
                'string',
                'nullable',
            ],
            'product_title' => [
                'string',
                'nullable',
            ],
            'product_price' => [
                'numeric',
            ],
            'url' => [
                'string',
                'nullable',
            ],
        ];
    }
}
