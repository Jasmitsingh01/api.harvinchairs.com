@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.productAttributeCombination.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.product-attribute-combinations.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="product_attribute_id">{{ trans('cruds.productAttributeCombination.fields.product_attribute') }}</label>
                <select class="form-control select2 {{ $errors->has('product_attribute') ? 'is-invalid' : '' }}" name="product_attribute_id" id="product_attribute_id">
                    @foreach($product_attributes as $id => $entry)
                        <option value="{{ $id }}" {{ old('product_attribute_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product_attribute'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product_attribute') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productAttributeCombination.fields.product_attribute_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="attribute_id">{{ trans('cruds.productAttributeCombination.fields.attribute') }}</label>
                <select class="form-control select2 {{ $errors->has('attribute') ? 'is-invalid' : '' }}" name="attribute_id" id="attribute_id">
                    @foreach($attributes as $id => $entry)
                        <option value="{{ $id }}" {{ old('attribute_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('attribute'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attribute') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productAttributeCombination.fields.attribute_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="attribute_value_id">{{ trans('cruds.productAttributeCombination.fields.attribute_value') }}</label>
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
                <span class="help-block">{{ trans('cruds.productAttributeCombination.fields.attribute_value_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-lg" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection