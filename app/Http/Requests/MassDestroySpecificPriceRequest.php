<?php

namespace App\Http\Requests;

use App\Models\SpecificPrice;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySpecificPriceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('specific_price_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:specific_prices,id',
        ];
    }
}
