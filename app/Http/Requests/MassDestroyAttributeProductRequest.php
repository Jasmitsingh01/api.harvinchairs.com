<?php

namespace App\Http\Requests;

use App\Models\AttributeProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAttributeProductRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('attribute_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:attribute_products,id',
        ];
    }
}
