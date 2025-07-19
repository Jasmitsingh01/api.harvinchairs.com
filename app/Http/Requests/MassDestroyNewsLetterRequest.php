<?php

namespace App\Http\Requests;

use App\Models\NewsLetter;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyNewsLetterRequest extends FormRequest
{
    public function authorize()
    {


        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:news_letters,id',
        ];
    }
}
