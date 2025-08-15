@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.orderProduct.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.order-products.update", [$orderProduct->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="order_id">{{ trans('cruds.orderProduct.fields.order') }}</label>
                <select class="form-control select2 {{ $errors->has('order') ? 'is-invalid' : '' }}" name="order_id" id="order_id">
                    @foreach($orders as $id => $entry)
                        <option value="{{ $id }}" {{ (old('order_id') ? old('order_id') : $orderProduct->order->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('order'))
                    <div class="invalid-feedback">
                        {{ $errors->first('order') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.order_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_id">{{ trans('cruds.orderProduct.fields.product') }}</label>
                <select class="form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="product_id" id="product_id">
                    @foreach($products as $id => $entry)
                        <option value="{{ $id }}" {{ (old('product_id') ? old('product_id') : $orderProduct->product->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="order_quantity">{{ trans('cruds.orderProduct.fields.order_quantity') }}</label>
                <input class="form-control {{ $errors->has('order_quantity') ? 'is-invalid' : '' }}" type="text" name="order_quantity" id="order_quantity" value="{{ old('order_quantity', $orderProduct->order_quantity) }}">
                @if($errors->has('order_quantity'))
                    <div class="invalid-feedback">
                        {{ $errors->first('order_quantity') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.order_quantity_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="unit_price">{{ trans('cruds.orderProduct.fields.unit_price') }}</label>
                <input class="form-control {{ $errors->has('unit_price') ? 'is-invalid' : '' }}" type="number" name="unit_price" id="unit_price" value="{{ old('unit_price', $orderProduct->unit_price) }}" step="0.01">
                @if($errors->has('unit_price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('unit_price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.unit_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="subtotal">{{ trans('cruds.orderProduct.fields.subtotal') }}</label>
                <input class="form-control {{ $errors->has('subtotal') ? 'is-invalid' : '' }}" type="number" name="subtotal" id="subtotal" value="{{ old('subtotal', $orderProduct->subtotal) }}" step="0.01">
                @if($errors->has('subtotal'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subtotal') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.subtotal_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_attribute_id">{{ trans('cruds.orderProduct.fields.product_attribute') }}</label>
                <select class="form-control select2 {{ $errors->has('product_attribute') ? 'is-invalid' : '' }}" name="product_attribute_id" id="product_attribute_id">
                    @foreach($product_attributes as $id => $entry)
                        <option value="{{ $id }}" {{ (old('product_attribute_id') ? old('product_attribute_id') : $orderProduct->product_attribute->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product_attribute'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product_attribute') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.product_attribute_helper') }}</span>
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
