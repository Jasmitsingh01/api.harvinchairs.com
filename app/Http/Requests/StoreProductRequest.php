<?php

namespace App\Http\Requests;

use App\Models\Product;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProductRequest extends FormRequest
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
                'unique:products',
            ],
            'slug' => [
                'string',
                'required',
                'unique:products',
            ],
            'type' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'price' => [
                'numeric',
            ],
            'sale_price' => [
                'numeric',
            ],
            'language' => [
                'string',
                'nullable',
            ],
            'min_price' => [
                'numeric',
            ],
            'max_price' => [
                'numeric',
            ],
            'sku' => [
                'string',
                'nullable',
            ],
            'quantity' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'minimum_quantity' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'in_stock' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'is_taxable' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'additional_shipping_fees' => [
                'string',
                'nullable',
            ],
            'unit' => [
                'string',
                'required',
            ],
            'height' => [
                'string',
                'nullable',
            ],
            'width' => [
                'string',
                'nullable',
            ],
            'length' => [
                'string',
                'nullable',
            ],
            'depth' => [
                'string',
                'nullable',
            ],
            'weight' => [
                'string',
                'nullable',
            ],
            'gallery' => [
                'array',
            ],
            'is_digital' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'is_external' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'external_product_url' => [
                'string',
                'nullable',
            ],
            'external_product_button_text' => [
                'string',
                'nullable',
            ],
            'redirect_when_disabled' => [
                'string',
                'nullable',
            ],
            'options' => [
                'string',
                'nullable',
            ],
            'available_for_order' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'show_price' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'conditions' => [
                'string',
                'nullable',
            ],
            'retail_price' => [
                'numeric',
            ],
            'unit_price' => [
                'numeric',
            ],
            'unity' => [
                'string',
                'nullable',
            ],
            'meta_title' => [
                'string',
                'nullable',
            ],
            'from_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'to_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'from_time' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
            'to_time' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
            'video_link' => [
                'string',
                'nullable',
            ],
            'cover_image' => [
                'string',
                'nullable',
            ],
            'video_heading' => [
                'string',
                'nullable',
            ],
            'out_of_stock' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'advanced_stock_management' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'available_now' => [
                'string',
                'nullable',
            ],
            'available_later' => [
                'string',
                'nullable',
            ],
            'is_new' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'is_featured' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'is_active' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
