<?php

namespace App\Http\Requests;

use App\Models\CreativeCutsCategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCreativeCutsCategoryRequest extends FormRequest
{
    public function authorize()
    {

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:creative_cuts_categories,id',
        ];
    }
}
