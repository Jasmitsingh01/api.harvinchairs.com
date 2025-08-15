<?php

namespace App\Http\Requests;

use App\Models\PrintMedium;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePrintMediumRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
                'unique:print_media,title,' . request()->route('print_medium')->id,
            ],
            'publish_date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
        ];
    }
}
