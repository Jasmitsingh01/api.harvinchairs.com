<?php

namespace App\Http\Requests;

use App\Models\ProductAttributeCombination;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProductAttributeCombinationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_attribute_combination_create');
    }

    public function rules()
    {
        return [];
    }
}
