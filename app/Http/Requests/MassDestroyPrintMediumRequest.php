<?php

namespace App\Http\Requests;

use App\Models\PrintMedium;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPrintMediumRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('print_medium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:print_media,id',
        ];
    }
}
