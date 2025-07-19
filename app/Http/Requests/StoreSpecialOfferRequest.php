<?php

namespace App\Http\Requests;

use App\Models\SpecialOffer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSpecialOfferRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('special_offer_create');
    }

    public function rules()
    {
        return [
            'offer_type' => [
                'string',
                'nullable',
            ],
            'discount' => [
                'numeric',
            ],
            'order_total_condition' => [
                'numeric',
            ],
        ];
    }
}
