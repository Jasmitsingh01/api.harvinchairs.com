@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.orderStatus.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.order-statuses.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">{{ trans('cruds.orderStatus.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}">
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderStatus.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="template">{{ trans('cruds.orderStatus.fields.template') }}</label>
                <input class="form-control {{ $errors->has('template') ? 'is-invalid' : '' }}" type="text" name="template" id="template" value="{{ old('template', '') }}">
                @if($errors->has('template'))
                    <div class="invalid-feedback">
                        {{ $errors->first('template') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderStatus.fields.template_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="module_name">{{ trans('cruds.orderStatus.fields.module_name') }}</label>
                <input class="form-control {{ $errors->has('module_name') ? 'is-invalid' : '' }}" type="text" name="module_name" id="module_name" value="{{ old('module_name', '') }}">
                @if($errors->has('module_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('module_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderStatus.fields.module_name_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('online_payment') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="online_payment" value="0">
                    <input class="form-check-input" type="checkbox" name="online_payment" id="online_payment" value="1" {{ old('online_payment', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="online_payment">{{ trans('cruds.orderStatus.fields.online_payment') }}</label>
                </div>
                @if($errors->has('online_payment'))
                    <div class="invalid-feedback">
                        {{ $errors->first('online_payment') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderStatus.fields.online_payment_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('invoice') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="invoice" value="0">
                    <input class="form-check-input" type="checkbox" name="invoice" id="invoice" value="1" {{ old('invoice', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="invoice">{{ trans('cruds.orderStatus.fields.invoice') }}</label>
                </div>
                @if($errors->has('invoice'))
                    <div class="invalid-feedback">
                        {{ $errors->first('invoice') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderStatus.fields.invoice_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('send_email') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="send_email" value="0">
                    <input class="form-check-input" type="checkbox" name="send_email" id="send_email" value="1" {{ old('send_email', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="send_email">{{ trans('cruds.orderStatus.fields.send_email') }}</label>
                </div>
                @if($errors->has('send_email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('send_email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderStatus.fields.send_email_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('unremovable') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="unremovable" value="0">
                    <input class="form-check-input" type="checkbox" name="unremovable" id="unremovable" value="1" {{ old('unremovable', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="unremovable">{{ trans('cruds.orderStatus.fields.unremovable') }}</label>
                </div>
                @if($errors->has('unremovable'))
                    <div class="invalid-feedback">
                        {{ $errors->first('unremovable') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderStatus.fields.unremovable_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('hidden') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="hidden" value="0">
                    <input class="form-check-input" type="checkbox" name="hidden" id="hidden" value="1" {{ old('hidden', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="hidden">{{ trans('cruds.orderStatus.fields.hidden') }}</label>
                </div>
                @if($errors->has('hidden'))
                    <div class="invalid-feedback">
                        {{ $errors->first('hidden') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderStatus.fields.hidden_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('logable') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="logable" value="0">
                    <input class="form-check-input" type="checkbox" name="logable" id="logable" value="1" {{ old('logable', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="logable">{{ trans('cruds.orderStatus.fields.logable') }}</label>
                </div>
                @if($errors->has('logable'))
                    <div class="invalid-feedback">
                        {{ $errors->first('logable') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderStatus.fields.logable_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('delivery') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="delivery" value="0">
                    <input class="form-check-input" type="checkbox" name="delivery" id="delivery" value="1" {{ old('delivery', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="delivery">{{ trans('cruds.orderStatus.fields.delivery') }}</label>
                </div>
                @if($errors->has('delivery'))
                    <div class="invalid-feedback">
                        {{ $errors->first('delivery') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderStatus.fields.delivery_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('shipped') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="shipped" value="0">
                    <input class="form-check-input" type="checkbox" name="shipped" id="shipped" value="1" {{ old('shipped', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="shipped">{{ trans('cruds.orderStatus.fields.shipped') }}</label>
                </div>
                @if($errors->has('shipped'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipped') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderStatus.fields.shipped_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('paid') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="paid" value="0">
                    <input class="form-check-input" type="checkbox" name="paid" id="paid" value="1" {{ old('paid', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="paid">{{ trans('cruds.orderStatus.fields.paid') }}</label>
                </div>
                @if($errors->has('paid'))
                    <div class="invalid-feedback">
                        {{ $errors->first('paid') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderStatus.fields.paid_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('pdf_invoice') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="pdf_invoice" value="0">
                    <input class="form-check-input" type="checkbox" name="pdf_invoice" id="pdf_invoice" value="1" {{ old('pdf_invoice', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="pdf_invoice">{{ trans('cruds.orderStatus.fields.pdf_invoice') }}</label>
                </div>
                @if($errors->has('pdf_invoice'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pdf_invoice') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderStatus.fields.pdf_invoice_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('pdf_delivery') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="pdf_delivery" value="0">
                    <input class="form-check-input" type="checkbox" name="pdf_delivery" id="pdf_delivery" value="1" {{ old('pdf_delivery', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="pdf_delivery">{{ trans('cruds.orderStatus.fields.pdf_delivery') }}</label>
                </div>
                @if($errors->has('pdf_delivery'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pdf_delivery') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.orderStatus.fields.pdf_delivery_helper') }}</span>
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