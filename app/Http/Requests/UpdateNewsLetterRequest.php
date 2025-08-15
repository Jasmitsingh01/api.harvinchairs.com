<?php

namespace App\Http\Requests;

use App\Models\NewsLetter;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateNewsLetterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ip_registration_newsletter' => [
                'string',
                'nullable',
            ],
            'http_referer' => [
                'string',
                'nullable',
            ],
        ];
    }
}
