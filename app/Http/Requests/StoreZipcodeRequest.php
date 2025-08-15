<?php

namespace App\Http\Requests;

use App\Models\Zipcode;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreZipcodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'zip_code' => [
                'string',
                'nullable',
            ],
            'amount' => [
                'numeric',
            ],
        ];
    }
}
