@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.shoppingCart.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.shopping-carts.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="user_id">{{ trans('cruds.shoppingCart.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id">
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.shoppingCart.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="delivery_address">{{ trans('cruds.shoppingCart.fields.delivery_address') }}</label>
                <input class="form-control {{ $errors->has('delivery_address') ? 'is-invalid' : '' }}" type="number" name="delivery_address" id="delivery_address" value="{{ old('delivery_address', '') }}" step="1">
                @if($errors->has('delivery_address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('delivery_address') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.shoppingCart.fields.delivery_address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="billing_address">{{ trans('cruds.shoppingCart.fields.billing_address') }}</label>
                <input class="form-control {{ $errors->has('billing_address') ? 'is-invalid' : '' }}" type="number" name="billing_address" id="billing_address" value="{{ old('billing_address', '') }}" step="1">
                @if($errors->has('billing_address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('billing_address') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.shoppingCart.fields.billing_address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="total">{{ trans('cruds.shoppingCart.fields.total') }}</label>
                <input class="form-control {{ $errors->has('total') ? 'is-invalid' : '' }}" type="number" name="total" id="total" value="{{ old('total', '') }}" step="0.01">
                @if($errors->has('total'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.shoppingCart.fields.total_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="language">{{ trans('cruds.shoppingCart.fields.language') }}</label>
                <input class="form-control {{ $errors->has('language') ? 'is-invalid' : '' }}" type="text" name="language" id="language" value="{{ old('language', '') }}">
                @if($errors->has('language'))
                    <div class="invalid-feedback">
                        {{ $errors->first('language') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.shoppingCart.fields.language_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_empty') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_empty" value="0">
                    <input class="form-check-input" type="checkbox" name="is_empty" id="is_empty" value="1" {{ old('is_empty', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_empty">{{ trans('cruds.shoppingCart.fields.is_empty') }}</label>
                </div>
                @if($errors->has('is_empty'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_empty') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.shoppingCart.fields.is_empty_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_confirm') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_confirm" value="0">
                    <input class="form-check-input" type="checkbox" name="is_confirm" id="is_confirm" value="1" {{ old('is_confirm', 0) == 1 || old('is_confirm') === null ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_confirm">{{ trans('cruds.shoppingCart.fields.is_confirm') }}</label>
                </div>
                @if($errors->has('is_confirm'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_confirm') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.shoppingCart.fields.is_confirm_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection