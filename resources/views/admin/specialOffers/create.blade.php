@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.specialOffer.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.special-offers.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="product_id">{{ trans('cruds.specialOffer.fields.product') }}</label>
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
                <span class="help-block">{{ trans('cruds.specialOffer.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="offer_type">{{ trans('cruds.specialOffer.fields.offer_type') }}</label>
                <input class="form-control {{ $errors->has('offer_type') ? 'is-invalid' : '' }}" type="text" name="offer_type" id="offer_type" value="{{ old('offer_type', '') }}">
                @if($errors->has('offer_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('offer_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.specialOffer.fields.offer_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.specialOffer.fields.discount_type') }}</label>
                @foreach(App\Models\SpecialOffer::DISCOUNT_TYPE_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('discount_type') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="discount_type_{{ $key }}" name="discount_type" value="{{ $key }}" {{ old('discount_type', '') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="discount_type_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('discount_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discount_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.specialOffer.fields.discount_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="discount">{{ trans('cruds.specialOffer.fields.discount') }}</label>
                <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number" name="discount" id="discount" value="{{ old('discount', '') }}" step="0.01">
                @if($errors->has('discount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.specialOffer.fields.discount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="order_total_condition">{{ trans('cruds.specialOffer.fields.order_total_condition') }}</label>
                <input class="form-control {{ $errors->has('order_total_condition') ? 'is-invalid' : '' }}" type="number" name="order_total_condition" id="order_total_condition" value="{{ old('order_total_condition', '') }}" step="0.01">
                @if($errors->has('order_total_condition'))
                    <div class="invalid-feedback">
                        {{ $errors->first('order_total_condition') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.specialOffer.fields.order_total_condition_helper') }}</span>
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