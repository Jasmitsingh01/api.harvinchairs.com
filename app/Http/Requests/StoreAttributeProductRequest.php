<?php

namespace App\Http\Requests;

use App\Models\AttributeProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAttributeProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('attribute_product_create');
    }

    public function rules()
    {
        return [];
    }
}
