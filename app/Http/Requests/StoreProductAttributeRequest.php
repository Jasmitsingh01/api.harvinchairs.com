<?php

namespace App\Http\Requests;

use App\Models\ProductAttribute;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProductAttributeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_attribute_create');
    }

    public function rules()
    {
        return [
            'reference_code' => [
                'string',
                'nullable',
            ],
            'price' => [
                'numeric',
            ],
            'minimum_quantity' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'quantity' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'bulk_buy_discount' => [
                'numeric',
            ],
            'bulk_buy_minimum_quantity' => [
                'numeric',
            ],
            'availability_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'out_of_stock' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
