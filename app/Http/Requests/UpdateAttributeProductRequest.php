<?php

namespace App\Http\Requests;

use App\Models\AttributeProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAttributeProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('attribute_product_edit');
    }

    public function rules()
    {
        return [];
    }
}
