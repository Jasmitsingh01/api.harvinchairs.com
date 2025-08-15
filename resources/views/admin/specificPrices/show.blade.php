@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.specificPrice.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.specific-prices.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.specificPrice.fields.id') }}
                        </th>
                        <td>
                            {{ $specificPrice->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.specificPrice.fields.product') }}
                        </th>
                        <td>
                            {{ $specificPrice->product->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.specificPrice.fields.customer') }}
                        </th>
                        <td>
                            {{ $specificPrice->customer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.specificPrice.fields.product_attribute') }}
                        </th>
                        <td>
                            {{ $specificPrice->product_attribute->reference_code ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.specificPrice.fields.from_quantity') }}
                        </th>
                        <td>
                            {{ $specificPrice->from_quantity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.specificPrice.fields.from') }}
                        </th>
                        <td>
                            {{ $specificPrice->from }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.specificPrice.fields.to') }}
                        </th>
                        <td>
                            {{ $specificPrice->to }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.specificPrice.fields.price') }}
                        </th>
                        <td>
                            {{ $specificPrice->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.specificPrice.fields.reduction') }}
                        </th>
                        <td>
                            {{ $specificPrice->reduction }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.specificPrice.fields.reduction_type') }}
                        </th>
                        <td>
                            {{ $specificPrice->reduction_type }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.specific-prices.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection