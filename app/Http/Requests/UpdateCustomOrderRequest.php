<?php

namespace App\Http\Requests;

use Gate;
use App\Models\CustomOrder;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
            ],
            'contact_no' => [
                'string',
                'nullable',
            ],
            'stone_name' => [
                'string',
                'nullable',
            ],
            'entry_type' => [
                'string',
                Rule::in(CustomOrder::ENTRY_TYPE_SELECT),
            ],
        ];
    }
}
