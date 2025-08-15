@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.specificPrice.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.specific-prices.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="product_id">{{ trans('cruds.specificPrice.fields.product') }}</label>
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
                <span class="help-block">{{ trans('cruds.specificPrice.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="customer_id">{{ trans('cruds.specificPrice.fields.customer') }}</label>
                <select class="form-control select2 {{ $errors->has('customer') ? 'is-invalid' : '' }}" name="customer_id" id="customer_id">
                    @foreach($customers as $id => $entry)
                        <option value="{{ $id }}" {{ old('customer_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('customer'))
                    <div class="invalid-feedback">
                        {{ $errors->first('customer') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.specificPrice.fields.customer_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_attribute_id">{{ trans('cruds.specificPrice.fields.product_attribute') }}</label>
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
                <span class="help-block">{{ trans('cruds.specificPrice.fields.product_attribute_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="from_quantity">{{ trans('cruds.specificPrice.fields.from_quantity') }}</label>
                <input class="form-control {{ $errors->has('from_quantity') ? 'is-invalid' : '' }}" type="number" name="from_quantity" id="from_quantity" value="{{ old('from_quantity', '') }}" step="1">
                @if($errors->has('from_quantity'))
                    <div class="invalid-feedback">
                        {{ $errors->first('from_quantity') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.specificPrice.fields.from_quantity_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="from">{{ trans('cruds.specificPrice.fields.from') }}</label>
                <input class="form-control datetime {{ $errors->has('from') ? 'is-invalid' : '' }}" type="text" name="from" id="from" value="{{ old('from') }}">
                @if($errors->has('from'))
                    <div class="invalid-feedback">
                        {{ $errors->first('from') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.specificPrice.fields.from_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="to">{{ trans('cruds.specificPrice.fields.to') }}</label>
                <input class="form-control datetime {{ $errors->has('to') ? 'is-invalid' : '' }}" type="text" name="to" id="to" value="{{ old('to') }}">
                @if($errors->has('to'))
                    <div class="invalid-feedback">
                        {{ $errors->first('to') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.specificPrice.fields.to_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="price">{{ trans('cruds.specificPrice.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', '') }}" step="1">
                @if($errors->has('price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.specificPrice.fields.price_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="reduction">{{ trans('cruds.specificPrice.fields.reduction') }}</label>
                <input class="form-control {{ $errors->has('reduction') ? 'is-invalid' : '' }}" type="number" name="reduction" id="reduction" value="{{ old('reduction', '') }}" step="1">
                @if($errors->has('reduction'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reduction') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.specificPrice.fields.reduction_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="reduction_type">{{ trans('cruds.specificPrice.fields.reduction_type') }}</label>
                <input class="form-control {{ $errors->has('reduction_type') ? 'is-invalid' : '' }}" type="text" name="reduction_type" id="reduction_type" value="{{ old('reduction_type', '') }}">
                @if($errors->has('reduction_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reduction_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.specificPrice.fields.reduction_type_helper') }}</span>
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