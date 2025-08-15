<?php

namespace App\Http\Requests;

use App\Models\ProductFeature;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyProductFeatureRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('product_feature_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:product_features,id',
        ];
    }
}
