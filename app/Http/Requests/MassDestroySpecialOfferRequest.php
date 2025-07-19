<?php

namespace App\Http\Requests;

use App\Models\SpecialOffer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySpecialOfferRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('special_offer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:special_offers,id',
        ];
    }
}
