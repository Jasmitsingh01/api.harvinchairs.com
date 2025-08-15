<?php

namespace App\Http\Requests;

use App\Models\ShoppingCart;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreShoppingCartRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'delivery_address' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'billing_address' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'total' => [
                'numeric',
            ],
            'language' => [
                'string',
                'nullable',
            ],
        ];
    }
}
