<?php

namespace App\Http\Requests;

use App\Models\SpecificPrice;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSpecificPriceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('specific_price_edit');
    }

    public function rules()
    {
        return [
            'from_quantity' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'from' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'to' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'price' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'reduction' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'reduction_type' => [
                'string',
                'nullable',
            ],
        ];
    }
}
