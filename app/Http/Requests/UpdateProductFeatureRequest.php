<?php

namespace App\Http\Requests;

use App\Models\ProductFeature;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProductFeatureRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_feature_edit');
    }

    public function rules()
    {
        return [
            'product_id' => [
                'required',
                'integer',
            ],
            'feature_value_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
