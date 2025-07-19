@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.productAttribute.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.product-attributes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttribute.fields.id') }}
                        </th>
                        <td>
                            {{ $productAttribute->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttribute.fields.product') }}
                        </th>
                        <td>
                            {{ $productAttribute->product->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttribute.fields.reference_code') }}
                        </th>
                        <td>
                            {{ $productAttribute->reference_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttribute.fields.price') }}
                        </th>
                        <td>
                            {{ $productAttribute->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttribute.fields.minimum_quantity') }}
                        </th>
                        <td>
                            {{ $productAttribute->minimum_quantity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttribute.fields.quantity') }}
                        </th>
                        <td>
                            {{ $productAttribute->quantity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttribute.fields.bulk_buy_discount') }}
                        </th>
                        <td>
                            {{ $productAttribute->bulk_buy_discount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttribute.fields.bulk_buy_minimum_quantity') }}
                        </th>
                        <td>
                            {{ $productAttribute->bulk_buy_minimum_quantity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttribute.fields.availability_date') }}
                        </th>
                        <td>
                            {{ $productAttribute->availability_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttribute.fields.images') }}
                        </th>
                        <td>
                            @if($productAttribute->images)
                                <a href="{{ $productAttribute->images->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $productAttribute->images->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttribute.fields.is_default') }}
                        </th>
                        <td>
                            {{ App\Models\ProductAttribute::IS_DEFAULT_RADIO[$productAttribute->is_default] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttribute.fields.out_of_stock') }}
                        </th>
                        <td>
                            {{ $productAttribute->out_of_stock }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttribute.fields.is_visible') }}
                        </th>
                        <td>
                            {{ App\Models\ProductAttribute::IS_VISIBLE_RADIO[$productAttribute->is_visible] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.product-attributes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection