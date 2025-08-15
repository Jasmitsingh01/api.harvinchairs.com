@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.attributeProduct.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.attribute-products.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="attribute_value_id">{{ trans('cruds.attributeProduct.fields.attribute_value') }}</label>
                <select class="form-control select2 {{ $errors->has('attribute_value') ? 'is-invalid' : '' }}" name="attribute_value_id" id="attribute_value_id">
                    @foreach($attribute_values as $id => $entry)
                        <option value="{{ $id }}" {{ old('attribute_value_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('attribute_value'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attribute_value') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attributeProduct.fields.attribute_value_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_id">{{ trans('cruds.attributeProduct.fields.product') }}</label>
                <select class="form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="product_id" id="product_id">
                    @foreach($products as $id => $entry)
                        <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attributeProduct.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-lg" type="submit">
                    {{ trans('global.save') }}
                </button>
                <button class="btn btn-primary btn-lg" type="button" onclick="window.history.back()">
                    {{ trans('global.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
