@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.productAttribute.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.product-attributes.update", [$productAttribute->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="product_id">{{ trans('cruds.productAttribute.fields.product') }}</label>
                <select class="form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="product_id" id="product_id">
                    @foreach($products as $id => $entry)
                        <option value="{{ $id }}" {{ (old('product_id') ? old('product_id') : $productAttribute->product->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productAttribute.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="reference_code">{{ trans('cruds.productAttribute.fields.reference_code') }}</label>
                <input class="form-control {{ $errors->has('reference_code') ? 'is-invalid' : '' }}" type="text" name="reference_code" id="reference_code" value="{{ old('reference_code', $productAttribute->reference_code) }}">
                @if($errors->has('reference_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reference_code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productAttribute.fields.reference_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="price">{{ trans('cruds.productAttribute.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', $productAttribute->price) }}" step="0.01">
                @if($errors->has('price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productAttribute.fields.price_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="minimum_quantity">{{ trans('cruds.productAttribute.fields.minimum_quantity') }}</label>
                <input class="form-control {{ $errors->has('minimum_quantity') ? 'is-invalid' : '' }}" type="number" name="minimum_quantity" id="minimum_quantity" value="{{ old('minimum_quantity', $productAttribute->minimum_quantity) }}" step="1">
                @if($errors->has('minimum_quantity'))
                    <div class="invalid-feedback">
                        {{ $errors->first('minimum_quantity') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productAttribute.fields.minimum_quantity_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="quantity">{{ trans('cruds.productAttribute.fields.quantity') }}</label>
                <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="number" name="quantity" id="quantity" value="{{ old('quantity', $productAttribute->quantity) }}" step="1">
                @if($errors->has('quantity'))
                    <div class="invalid-feedback">
                        {{ $errors->first('quantity') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productAttribute.fields.quantity_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="bulk_buy_discount">{{ trans('cruds.productAttribute.fields.bulk_buy_discount') }}</label>
                <input class="form-control {{ $errors->has('bulk_buy_discount') ? 'is-invalid' : '' }}" type="number" name="bulk_buy_discount" id="bulk_buy_discount" value="{{ old('bulk_buy_discount', $productAttribute->bulk_buy_discount) }}" step="0.01">
                @if($errors->has('bulk_buy_discount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('bulk_buy_discount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productAttribute.fields.bulk_buy_discount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="bulk_buy_minimum_quantity">{{ trans('cruds.productAttribute.fields.bulk_buy_minimum_quantity') }}</label>
                <input class="form-control {{ $errors->has('bulk_buy_minimum_quantity') ? 'is-invalid' : '' }}" type="number" name="bulk_buy_minimum_quantity" id="bulk_buy_minimum_quantity" value="{{ old('bulk_buy_minimum_quantity', $productAttribute->bulk_buy_minimum_quantity) }}" step="0.01">
                @if($errors->has('bulk_buy_minimum_quantity'))
                    <div class="invalid-feedback">
                        {{ $errors->first('bulk_buy_minimum_quantity') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productAttribute.fields.bulk_buy_minimum_quantity_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="availability_date">{{ trans('cruds.productAttribute.fields.availability_date') }}</label>
                <input class="form-control date {{ $errors->has('availability_date') ? 'is-invalid' : '' }}" type="text" name="availability_date" id="availability_date" value="{{ old('availability_date', $productAttribute->availability_date) }}">
                @if($errors->has('availability_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('availability_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productAttribute.fields.availability_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="images">{{ trans('cruds.productAttribute.fields.images') }}</label>
                <div class="needsclick dropzone {{ $errors->has('images') ? 'is-invalid' : '' }}" id="images-dropzone">
                </div>
                @if($errors->has('images'))
                    <div class="invalid-feedback">
                        {{ $errors->first('images') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productAttribute.fields.images_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.productAttribute.fields.is_default') }}</label>
                @foreach(App\Models\ProductAttribute::IS_DEFAULT_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('is_default') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="is_default_{{ $key }}" name="is_default" value="{{ $key }}" {{ old('is_default', $productAttribute->is_default) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_default_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('is_default'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_default') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productAttribute.fields.is_default_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="out_of_stock">{{ trans('cruds.productAttribute.fields.out_of_stock') }}</label>
                <input class="form-control {{ $errors->has('out_of_stock') ? 'is-invalid' : '' }}" type="number" name="out_of_stock" id="out_of_stock" value="{{ old('out_of_stock', $productAttribute->out_of_stock) }}" step="1">
                @if($errors->has('out_of_stock'))
                    <div class="invalid-feedback">
                        {{ $errors->first('out_of_stock') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productAttribute.fields.out_of_stock_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.productAttribute.fields.is_visible') }}</label>
                @foreach(App\Models\ProductAttribute::IS_VISIBLE_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('is_visible') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="is_visible_{{ $key }}" name="is_visible" value="{{ $key }}" {{ old('is_visible', $productAttribute->is_visible) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_visible_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('is_visible'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_visible') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productAttribute.fields.is_visible_helper') }}</span>
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

@section('scripts')
<script>
    Dropzone.options.imagesDropzone = {
    url: '{{ route('admin.product-attributes.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="images"]').remove()
      $('form').append('<input type="hidden" name="images" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="images"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($productAttribute) && $productAttribute->images)
      var file = {!! json_encode($productAttribute->images) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="images" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}

</script>
@endsection