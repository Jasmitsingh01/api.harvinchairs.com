@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.order.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.orders.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="tracking_number">{{ trans('cruds.order.fields.tracking_number') }}</label>
                <input class="form-control {{ $errors->has('tracking_number') ? 'is-invalid' : '' }}" type="text" name="tracking_number" id="tracking_number" value="{{ old('tracking_number', '') }}">
                @if($errors->has('tracking_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tracking_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.tracking_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="customer_id">{{ trans('cruds.order.fields.customer') }}</label>
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
                <span class="help-block">{{ trans('cruds.order.fields.customer_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="customer_contact">{{ trans('cruds.order.fields.customer_contact') }}</label>
                <input class="form-control {{ $errors->has('customer_contact') ? 'is-invalid' : '' }}" type="text" name="customer_contact" id="customer_contact" value="{{ old('customer_contact', '') }}">
                @if($errors->has('customer_contact'))
                    <div class="invalid-feedback">
                        {{ $errors->first('customer_contact') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.customer_contact_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="customer_name">{{ trans('cruds.order.fields.customer_name') }}</label>
                <input class="form-control {{ $errors->has('customer_name') ? 'is-invalid' : '' }}" type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', '') }}">
                @if($errors->has('customer_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('customer_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.customer_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="amount">{{ trans('cruds.order.fields.amount') }}</label>
                <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('amount', '') }}" step="0.01">
                @if($errors->has('amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('amount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.amount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sales_tax">{{ trans('cruds.order.fields.sales_tax') }}</label>
                <input class="form-control {{ $errors->has('sales_tax') ? 'is-invalid' : '' }}" type="number" name="sales_tax" id="sales_tax" value="{{ old('sales_tax', '') }}" step="0.01">
                @if($errors->has('sales_tax'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sales_tax') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.sales_tax_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="paid_total">{{ trans('cruds.order.fields.paid_total') }}</label>
                <input class="form-control {{ $errors->has('paid_total') ? 'is-invalid' : '' }}" type="number" name="paid_total" id="paid_total" value="{{ old('paid_total', '') }}" step="0.01">
                @if($errors->has('paid_total'))
                    <div class="invalid-feedback">
                        {{ $errors->first('paid_total') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.paid_total_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="total">{{ trans('cruds.order.fields.total') }}</label>
                <input class="form-control {{ $errors->has('total') ? 'is-invalid' : '' }}" type="number" name="total" id="total" value="{{ old('total', '') }}" step="0.01">
                @if($errors->has('total'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.total_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cancelled_amount">{{ trans('cruds.order.fields.cancelled_amount') }}</label>
                <input class="form-control {{ $errors->has('cancelled_amount') ? 'is-invalid' : '' }}" type="number" name="cancelled_amount" id="cancelled_amount" value="{{ old('cancelled_amount', '') }}" step="0.01">
                @if($errors->has('cancelled_amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cancelled_amount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.cancelled_amount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="language">{{ trans('cruds.order.fields.language') }}</label>
                <input class="form-control {{ $errors->has('language') ? 'is-invalid' : '' }}" type="text" name="language" id="language" value="{{ old('language', '') }}">
                @if($errors->has('language'))
                    <div class="invalid-feedback">
                        {{ $errors->first('language') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.language_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="coupon">{{ trans('cruds.order.fields.coupon') }}</label>
                <input class="form-control {{ $errors->has('coupon') ? 'is-invalid' : '' }}" type="number" name="coupon_id" id="coupon" value="{{ old('coupon', '') }}" step="1">
                @if($errors->has('coupon'))
                    <div class="invalid-feedback">
                        {{ $errors->first('coupon') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.coupon_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="parent">{{ trans('cruds.order.fields.parent') }}</label>
                <input class="form-control {{ $errors->has('parent') ? 'is-invalid' : '' }}" type="number" name="parent_id" id="parent" value="{{ old('parent', '') }}" step="1">
                @if($errors->has('parent'))
                    <div class="invalid-feedback">
                        {{ $errors->first('parent') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.parent_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="shop_id">{{ trans('cruds.order.fields.shop') }}</label>
                <select class="form-control select2 {{ $errors->has('shop') ? 'is-invalid' : '' }}" name="shop_id" id="shop_id">
                    @foreach($shops as $id => $entry)
                        <option value="{{ $id }}" {{ old('shop_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('shop'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shop') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.shop_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="discount">{{ trans('cruds.order.fields.discount') }}</label>
                <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number" name="discount" id="discount" value="{{ old('discount', '') }}" step="0.01">
                @if($errors->has('discount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.discount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="payment_gateway">{{ trans('cruds.order.fields.payment_gateway') }}</label>
                <input class="form-control {{ $errors->has('payment_gateway') ? 'is-invalid' : '' }}" type="text" name="payment_gateway" id="payment_gateway" value="{{ old('payment_gateway', '') }}">
                @if($errors->has('payment_gateway'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payment_gateway') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.payment_gateway_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="shipping_address">{{ trans('cruds.order.fields.shipping_address') }}</label>
                <textarea class="form-control {{ $errors->has('shipping_address') ? 'is-invalid' : '' }}" name="shipping_address" id="shipping_address">{{ old('shipping_address') }}</textarea>
                @if($errors->has('shipping_address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipping_address') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.shipping_address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="billing_address">{{ trans('cruds.order.fields.billing_address') }}</label>
                <textarea class="form-control {{ $errors->has('billing_address') ? 'is-invalid' : '' }}" name="billing_address" id="billing_address">{{ old('billing_address') }}</textarea>
                @if($errors->has('billing_address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('billing_address') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.billing_address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="logistics_provider">{{ trans('cruds.order.fields.logistics_provider') }}</label>
                <input class="form-control {{ $errors->has('logistics_provider') ? 'is-invalid' : '' }}" type="number" name="logistics_provider" id="logistics_provider" value="{{ old('logistics_provider', '') }}" step="1">
                @if($errors->has('logistics_provider'))
                    <div class="invalid-feedback">
                        {{ $errors->first('logistics_provider') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.logistics_provider_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="delivery_fee">{{ trans('cruds.order.fields.delivery_fee') }}</label>
                <input class="form-control {{ $errors->has('delivery_fee') ? 'is-invalid' : '' }}" type="number" name="delivery_fee" id="delivery_fee" value="{{ old('delivery_fee', '') }}" step="0.01">
                @if($errors->has('delivery_fee'))
                    <div class="invalid-feedback">
                        {{ $errors->first('delivery_fee') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.delivery_fee_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="delivery_time">{{ trans('cruds.order.fields.delivery_time') }}</label>
                <input class="form-control {{ $errors->has('delivery_time') ? 'is-invalid' : '' }}" type="text" name="delivery_time" id="delivery_time" value="{{ old('delivery_time', '') }}">
                @if($errors->has('delivery_time'))
                    <div class="invalid-feedback">
                        {{ $errors->first('delivery_time') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.delivery_time_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.order.fields.order_status') }}</label>
                @foreach(App\Models\Order::ORDER_STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('order_status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="order_status_{{ $key }}" name="order_status" value="{{ $key }}" {{ old('order_status', 'order-pending') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="order_status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('order_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('order_status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.order_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.order.fields.payment_status') }}</label>
                @foreach(App\Models\Order::PAYMENT_STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('payment_status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="payment_status_{{ $key }}" name="payment_status" value="{{ $key }}" {{ old('payment_status', 'payment-pending') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="payment_status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('payment_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payment_status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.payment_status_helper') }}</span>
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

