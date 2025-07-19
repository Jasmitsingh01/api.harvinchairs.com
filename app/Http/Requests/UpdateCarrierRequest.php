<?php

namespace App\Http\Requests;

use App\Models\Carrier;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCarrierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'reference_id' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'name' => [
                'string',
                'nullable',
            ],
            'url' => [
                'string',
                'nullable',
            ],
            'external_module_name' => [
                'string',
                'nullable',
            ],
            'shipping_method' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'position' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'max_width' => [
                'numeric',
            ],
            'max_height' => [
                'numeric',
            ],
            'max_depth' => [
                'numeric',
            ],
            'max_weight' => [
                'numeric',
            ],
            'grade' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
