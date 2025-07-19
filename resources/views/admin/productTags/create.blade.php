@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.productTag.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.product-tags.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="product_id">{{ trans('cruds.productTag.fields.product') }}</label>
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
                <span class="help-block">{{ trans('cruds.productTag.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tag_id">{{ trans('cruds.productTag.fields.tag') }}</label>
                <select class="form-control select2 {{ $errors->has('tag') ? 'is-invalid' : '' }}" name="tag_id" id="tag_id">
                    @foreach($tags as $id => $entry)
                        <option value="{{ $id }}" {{ old('tag_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('tag'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tag') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productTag.fields.tag_helper') }}</span>
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