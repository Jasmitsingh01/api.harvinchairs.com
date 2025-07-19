@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.productAttributeCombination.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.product-attribute-combinations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttributeCombination.fields.id') }}
                        </th>
                        <td>
                            {{ $productAttributeCombination->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttributeCombination.fields.product_attribute') }}
                        </th>
                        <td>
                            {{ $productAttributeCombination->product_attribute->reference_code ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttributeCombination.fields.attribute') }}
                        </th>
                        <td>
                            {{ $productAttributeCombination->attribute->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productAttributeCombination.fields.attribute_value') }}
                        </th>
                        <td>
                            {{ $productAttributeCombination->attribute_value->slug ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.product-attribute-combinations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection