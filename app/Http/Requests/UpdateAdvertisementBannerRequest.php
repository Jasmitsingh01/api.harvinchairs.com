<?php

namespace App\Http\Requests;

use App\Models\AdvertisementBanner;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAdvertisementBannerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('advertisement_banner_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'banner' => [
                'required',
            ],
            'link_open' => [
                'string',
                'nullable',
            ],
        ];
    }
}
