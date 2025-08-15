@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.coupon.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.coupons.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="code">{{ trans('cruds.coupon.fields.code') }}</label>
                <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code" id="code" value="{{ old('code', '') }}" required>
                @if($errors->has('code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.coupon.fields.title') }}</label>
                <input class="form-control {{ $errors->has('coupon_title') ? 'is-invalid' : '' }}" type="text" name="coupon_title" id="coupon_title" value="{{ old('coupon_title', '') }}" required>
                @if($errors->has('coupon_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('coupon_title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.title_helper') }}</span>
            </div>
            {{-- <div class="form-group">
                <label for="customer_id">{{ trans('cruds.coupon.fields.customer') }}</label>
                <select multiple class="form-control select2 {{ $errors->has('customer') ? 'is-invalid' : '' }}" name="customer_id[]" id="customer_id" >
                    <option value="0">All Customers</option>
                    @foreach($customers as $id => $entry)
                        <option value="{{ $id }}" {{ old('customer_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('customer'))
                    <div class="invalid-feedback">
                        {{ $errors->first('customer') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.customer_helper') }}</span>
            </div> --}}
            {{-- <div class="form-group">
                <label for="language">{{ trans('cruds.coupon.fields.language') }}</label>
                <input class="form-control {{ $errors->has('language') ? 'is-invalid' : '' }}" type="text" name="language" id="language" value="{{ old('language', '') }}" >
                @if($errors->has('language'))
                    <div class="invalid-feedback">
                        {{ $errors->first('language') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.language_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <label for="description">{{ trans('cruds.coupon.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="image">{{ trans('cruds.coupon.fields.image') }}</label>
                <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}" id="image-dropzone">
                </div>
                @if($errors->has('image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.image_helper') }}</span>
                <div class="recommended-settings">
                    <b>Recommended:</b> 582 x 68 pixels [jpeg, jpg, png, gif], {{ config('constants.FILEMAXSIZE') }} MB
                </div>
            </div>
            <div class="form-group">
                <label class="required" for="discount">{{ trans('cruds.coupon.fields.discount') }}</label>
                <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number" name="discount" id="discount" value="{{ old('discount', '') }}" step="0.01" min="0" required>
                @if($errors->has('discount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.discount_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="discount_type">{{ trans('cruds.coupon.fields.discount_type') }}</label>
                <select class="form-control {{ $errors->has('discount_type') ? 'is-invalid' : '' }}" name="discount_type" id="discount_type" required>
                    <option value disabled {{ old('discount_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Coupon::DISCOUNT_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('discount_type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('discount_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discount_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.discount_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="active_from">{{ trans('cruds.coupon.fields.active_from') }}</label>
                <input class="form-control datetime {{ $errors->has('active_from') ? 'is-invalid' : '' }}" type="text" name="active_from" id="active_from" value="{{ old('active_from') }}" required>
                @if($errors->has('active_from'))
                    <div class="invalid-feedback">
                        {{ $errors->first('active_from') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.active_from_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="expire_at">{{ trans('cruds.coupon.fields.expire_at') }}</label>
                <input class="form-control datetime {{ $errors->has('expire_at') ? 'is-invalid' : '' }}" type="text" name="expire_at" id="expire_at" value="{{ old('expire_at') }}" required>
                @if($errors->has('expire_at'))
                    <div class="invalid-feedback">
                        {{ $errors->first('expire_at') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.expire_at_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="min_amount">{{ trans('cruds.coupon.fields.min_amount') }}</label>
                <input class="form-control {{ $errors->has('min_amount') ? 'is-invalid' : '' }}" type="number" name="min_amount" id="min_amount" value="{{ old('min_amount', '') }}" step="0.01" min="0" required>
                @if($errors->has('min_amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('min_amount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.min_amount_helper') }}</span>
            </div>
            {{-- <div class="form-group">
                <label for="min_qty">{{ trans('cruds.coupon.fields.min_qty') }}</label>
                <input class="form-control {{ $errors->has('min_qty') ? 'is-invalid' : '' }}" type="number" name="min_qty" id="min_qty" value="{{ old('min_qty', '') }}" step="0.01" required>
                @if($errors->has('min_qty'))
                    <div class="invalid-feedback">
                        {{ $errors->first('min_qty') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.min_qty_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <label class="required" for="max_usage">{{ trans('cruds.coupon.fields.max_usage') }}</label>
                <input class="form-control {{ $errors->has('max_usage') ? 'is-invalid' : '' }}" type="number" name="max_usage" id="max_usage" value="{{ old('max_usage', '') }}" step="1" min="1" required>
                @if($errors->has('max_usage'))
                    <div class="invalid-feedback">
                        {{ $errors->first('max_usage') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.max_usage_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="max_redemption_per_user">{{ trans('cruds.coupon.fields.max_redemption_per_user') }}</label>
                <input class="form-control {{ $errors->has('max_redemption_per_user') ? 'is-invalid' : '' }}" type="number" name="max_redemption_per_user" id="max_redemption_per_user" value="{{ old('max_redemption_per_user', '') }}" step="1" min="0" required>
                @if($errors->has('max_redemption_per_user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('max_redemption_per_user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.max_redemption_per_user_helper') }}</span>
            </div>

            {{-- <div class="form-group">
                <label for="usage_count_per_user">{{ trans('cruds.coupon.fields.usage_count_per_user') }}</label>
                <input class="form-control {{ $errors->has('usage_count_per_user') ? 'is-invalid' : '' }}" type="number" name="usage_count_per_user" id="usage_count_per_user" value="{{ old('usage_count_per_user', '') }}" step="1" required>
                @if($errors->has('usage_count_per_user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('usage_count_per_user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.usage_count_per_user_helper') }}</span>
            </div> --}}
            {{-- <div class="form-group">
                <label for="country">{{ trans('cruds.coupon.fields.country') }}</label>
                <input class="form-control {{ $errors->has('country') ? 'is-invalid' : '' }}" type="number" name="country" id="country" value="{{ old('country', '') }}" step="1">
                @if($errors->has('country'))
                    <div class="invalid-feedback">
                        {{ $errors->first('country') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.country_helper') }}</span>
            </div> --}}
            {{-- <div class="form-group">
                <div class="form-check {{ $errors->has('free_shipping') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="free_shipping" value="0">
                    <input class="form-check-input" type="checkbox" name="free_shipping" id="free_shipping" value="1" {{ old('free_shipping', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="free_shipping">{{ trans('cruds.coupon.fields.free_shipping') }}</label>
                </div>
                @if($errors->has('free_shipping'))
                    <div class="invalid-feedback">
                        {{ $errors->first('free_shipping') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.free_shipping_helper') }}</span>
            </div>
            <div class="form-group" id="free_shipping_input" style="display:none;">
                <label for="free_shipping_min_amount">{{ trans('cruds.coupon.fields.free_shipping_min_amount') }}</label>
                <input class="form-control {{ $errors->has('free_shipping_min_amount') ? 'is-invalid' : '' }}" type="number" name="free_shipping_min_amount" id="free_shipping_min_amount" value="{{ old('free_shipping_min_amount', '') }}" step="1" min="0">
                @if($errors->has('free_shipping_min_amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('free_shipping_min_amount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.free_shipping_min_amount_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <label for="category_id">{{ trans('cruds.coupon.fields.category') }}</label>
                <select   class="form-control select2 {{ $errors->has('category_id[]') ? 'is-invalid' : '' }}" name="category_id" id="category_id">
                    @foreach($categories as $id => $entry)
                        <option value="{{ $id }}" {{ old('category_id[]') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('category') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.category_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_id">{{ trans('cruds.coupon.fields.product') }}</label>
                <select  class="form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="product_id" id="product_id">
                    @foreach($products as $id => $entry)
                        <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.product_helper') }}</span>
            </div>
            {{-- <div class="form-group">
                <div class="form-check {{ $errors->has('is_used') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_used" value="0">
                    <input class="form-check-input" type="checkbox" name="is_used" id="is_used" value="1" {{ old('is_used', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_used">{{ trans('cruds.coupon.fields.is_used') }}</label>
                </div>
                @if($errors->has('is_used'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_used') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.is_used_helper') }}</span>
            </div> --}}
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

@section('scripts')
<script>
    $(document).ready(function () {
        $('#free_shipping').change(function () {
            if ($(this).prop('checked')) {
                $('#free_shipping_input').show();
            } else {
                $('#free_shipping_input').hide();
            }
        });
    });
</script>
<script>
    var uploadedImageMap = {}
    Dropzone.options.imageDropzone = {
    url: '{{ route('admin.coupons.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
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
      $('form').append('<input type="hidden" name="image" value="' + response.name + '">')
      uploadedImageMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedImageMap[file.name]
      }
      $('form').find('input[name="image"][value="' + name + '"]').remove()
    },
    init: function () {
    @if(isset($coupon) && $coupon->image)
        var files = {!! json_encode($coupon->image) !!}
            for (var i in files) {
            var file = files[i]
            this.options.addedfile.call(this, file)
            this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
            file.previewElement.classList.add('dz-complete')
            $('form').append('<input type="hidden" name="image" value="' + file.file_name + '">')
            }
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
