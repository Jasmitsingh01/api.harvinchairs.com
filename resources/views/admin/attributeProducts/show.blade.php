@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.attributeProduct.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.attribute-products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.attributeProduct.fields.id') }}
                        </th>
                        <td>
                            {{ $attributeProduct->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attributeProduct.fields.attribute_value') }}
                        </th>
                        <td>
                            {{ $attributeProduct->attribute_value->slug ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attributeProduct.fields.product') }}
                        </th>
                        <td>
                            {{ $attributeProduct->product->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.attribute-products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection