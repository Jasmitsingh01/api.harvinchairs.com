@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.productFeature.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.product-features.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="product_id">{{ trans('cruds.productFeature.fields.product') }}</label>
                <select class="form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="product_id" id="product_id" required>
                    @foreach($products as $id => $entry)
                        <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productFeature.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="feature_value_id">{{ trans('cruds.productFeature.fields.feature_value') }}</label>
                <select class="form-control select2 {{ $errors->has('feature_value') ? 'is-invalid' : '' }}" name="feature_value_id" id="feature_value_id" required>
                    @foreach($feature_values as $id => $entry)
                        <option value="{{ $id }}" {{ old('feature_value_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('feature_value'))
                    <div class="invalid-feedback">
                        {{ $errors->first('feature_value') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productFeature.fields.feature_value_helper') }}</span>
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