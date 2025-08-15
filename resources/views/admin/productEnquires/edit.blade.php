@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.productEnquire.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.product-enquires.update", [$productEnquire->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="customer_name">{{ trans('cruds.productEnquire.fields.customer_name') }}</label>
                <input class="form-control {{ $errors->has('customer_name') ? 'is-invalid' : '' }}" type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $productEnquire->customer_name) }}">
                @if($errors->has('customer_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('customer_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productEnquire.fields.customer_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="customer_email">{{ trans('cruds.productEnquire.fields.customer_email') }}</label>
                <input class="form-control {{ $errors->has('customer_email') ? 'is-invalid' : '' }}" type="email" name="customer_email" id="customer_email" value="{{ old('customer_email', $productEnquire->customer_email) }}">
                @if($errors->has('customer_email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('customer_email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productEnquire.fields.customer_email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="subject">{{ trans('cruds.productEnquire.fields.subject') }}</label>
                <input class="form-control {{ $errors->has('subject') ? 'is-invalid' : '' }}" type="text" name="subject" id="subject" value="{{ old('subject', $productEnquire->subject) }}">
                @if($errors->has('subject'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subject') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productEnquire.fields.subject_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="message">{{ trans('cruds.productEnquire.fields.message') }}</label>
                <input class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}" type="text" name="message" id="message" value="{{ old('message', $productEnquire->message) }}">
                @if($errors->has('message'))
                    <div class="invalid-feedback">
                        {{ $errors->first('message') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productEnquire.fields.message_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_title">{{ trans('cruds.productEnquire.fields.product_title') }}</label>
                <input class="form-control {{ $errors->has('product_title') ? 'is-invalid' : '' }}" type="text" name="product_title" id="product_title" value="{{ old('product_title', $productEnquire->product_title) }}">
                @if($errors->has('product_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product_title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productEnquire.fields.product_title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_price">{{ trans('cruds.productEnquire.fields.product_price') }}</label>
                <input class="form-control {{ $errors->has('product_price') ? 'is-invalid' : '' }}" type="number" name="product_price" id="product_price" value="{{ old('product_price', $productEnquire->product_price) }}" step="0.01">
                @if($errors->has('product_price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product_price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productEnquire.fields.product_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_img">{{ trans('cruds.productEnquire.fields.product_img') }}</label>
                <div class="needsclick dropzone {{ $errors->has('product_img') ? 'is-invalid' : '' }}" id="product_img-dropzone">
                </div>
                @if($errors->has('product_img'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product_img') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productEnquire.fields.product_img_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_id">{{ trans('cruds.productEnquire.fields.product') }}</label>
                <select class="form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="product_id" id="product_id">
                    @foreach($products as $id => $entry)
                        <option value="{{ $id }}" {{ (old('product_id') ? old('product_id') : $productEnquire->product->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productEnquire.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_attributes_id">{{ trans('cruds.productEnquire.fields.product_attributes') }}</label>
                <select class="form-control select2 {{ $errors->has('product_attributes') ? 'is-invalid' : '' }}" name="product_attributes_id" id="product_attributes_id">
                    @foreach($product_attributes as $id => $entry)
                        <option value="{{ $id }}" {{ (old('product_attributes_id') ? old('product_attributes_id') : $productEnquire->product_attributes->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product_attributes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product_attributes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productEnquire.fields.product_attributes_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="url">{{ trans('cruds.productEnquire.fields.url') }}</label>
                <input class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" type="text" name="url" id="url" value="{{ old('url', $productEnquire->url) }}">
                @if($errors->has('url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productEnquire.fields.url_helper') }}</span>
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

@section('scripts')
<script>
    Dropzone.options.productImgDropzone = {
    url: '{{ route('admin.product-enquires.storeMedia') }}',
    maxFilesize: 2, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').find('input[name="product_img"]').remove()
      $('form').append('<input type="hidden" name="product_img" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="product_img"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($productEnquire) && $productEnquire->product_img)
      var file = {!! json_encode($productEnquire->product_img) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="product_img" value="' + file.file_name + '">')
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