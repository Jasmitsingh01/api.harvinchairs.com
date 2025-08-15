<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use App\Enums\ProductType;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                         => ['string', 'max:255'],
            'price'                        => ['nullable', 'numeric'],
            'sale_price'                   => ['nullable', 'lte:price'],
            'type_id'                      => ['exists:App\Database\Models\Type,id'],
            'shop_id'                      => ['exists:App\Database\Models\Shop,id'],
            'manufacturer_id'              => ['nullable', 'exists:App\Database\Models\Manufacturer,id'],
            'author_id'                    => ['nullable', 'exists:App\Database\Models\Author,id'],
            'categories'                   => ['exists:App\Database\Models\Category,id'],
            'tags'                         => ['exists:App\Database\Models\Tag,id'],
            'dropoff_locations'            => ['array'],
            'pickup_locations'             => ['array'],
            'language'                     => ['nullable', 'string'],
            'digital_file'                 => ['array'],
            'product_type'                 => [Rule::in([ProductType::SIMPLE, ProductType::VARIABLE])],
            'unit'                         => ['string'],
            'description'                  => ['nullable', 'string'],
            'quantity'                     => ['nullable', 'integer'],
            'sku'                          => ['string'],
            'image'                        => ['array'],
            'gallery'                      => ['array'],
            'video'                        => ['array'],
            'status'                       => ['string', Rule::in(['publish', 'draft'])],
            'height'                       => ['nullable', 'numeric'],
            'length'                       => ['nullable', 'numeric'],
            'width'                        => ['nullable', 'numeric'],
            'external_product_url'         => ['nullable', 'string'],
            'external_product_button_text' => ['nullable', 'string'],
            'in_stock'                     => ['boolean'],
            'is_taxable'                   => ['boolean'],
            'is_digital'                   => ['boolean'],
            'is_external'                  => ['boolean'],
            'is_rental'                    => ['boolean'],
            'redirect_when_disabled'       => ['nullable','string'],
            'options'                      => ['nullable'],
            'conditions'                   => ['nullable','string'],
            'retail_price'                 => ['nullable','numeric'],
            'unit_price'                   => ['nullable','numeric'],
            'unity'                        => ['nullable','string'],
            'meta_title'                   => ['nullable', 'string'],
            'meta_description'             => ['nullable', 'string'],
            'slug'                         => ['nullable', 'string'],
            'from_date'                    => ['date','nullable'],
            'to_date'                      => ['date','nullable'],
            'from_time'                    => ['date_format:H:i','nullable'],
            'to_time'                      => ['date_format:H:i','nullable'],
            'video_link'                   => ['string','nullable'],
            'cover_image'                  => ['array','nullable'],
            'video_heading'                => ['string','nullable'],
            'video_description'            => ['string','nullable'],
            'additional_shipping_fees'     => ['nullable'],
            'weight'                       => ['nullable', 'numeric'],
            'depth'                        => ['nullable', 'numeric'],
            'default_category'             => ['nullable', 'integer' ,'exists:App\Database\Models\Category,id'],
            'advanced_stock_management'    => ['nullable', Rule::in([0, 1])],
            'text_when_in_stock'           => ['nullable','string'],
            'backordering_text'            => ['nullable','string'],
            'isNew'                       => ['nullable', 'boolean'],
            'isFeatured'                  => ['nullable', 'boolean'],
            'show_price'                   => ['nullable', 'boolean'],
            'available_for_order'          => ['nullable', 'boolean'],
            'is_active'                    => ['nullable', 'boolean'],
            'online_only'                  => ['nullable', 'boolean'],
            'minimum_quantity'             => ['nullable', 'numeric'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
