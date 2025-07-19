@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.review.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.reviews.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="order_id">{{ trans('cruds.review.fields.order') }}</label>
                <select class="form-control select2 {{ $errors->has('order') ? 'is-invalid' : '' }}" name="order_id" id="order_id">
                    @foreach($orders as $id => $entry)
                        <option value="{{ $id }}" {{ old('order_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('order'))
                    <div class="invalid-feedback">
                        {{ $errors->first('order') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.review.fields.order_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="user_id">{{ trans('cruds.review.fields.user') }}</label>
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
                <span class="help-block">{{ trans('cruds.review.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_id">{{ trans('cruds.review.fields.product') }}</label>
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
                <span class="help-block">{{ trans('cruds.review.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_attributes_id">{{ trans('cruds.review.fields.product_attributes') }}</label>
                <select class="form-control select2 {{ $errors->has('product_attributes') ? 'is-invalid' : '' }}" name="product_attributes_id" id="product_attributes_id">
                    @foreach($product_attributes as $id => $entry)
                        <option value="{{ $id }}" {{ old('product_attributes_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product_attributes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product_attributes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.review.fields.product_attributes_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="title">{{ trans('cruds.review.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}">
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.review.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="comment">{{ trans('cruds.review.fields.comment') }}</label>
                <textarea class="form-control {{ $errors->has('comment') ? 'is-invalid' : '' }}" name="comment" id="comment">{{ old('comment') }}</textarea>
                @if($errors->has('comment'))
                    <div class="invalid-feedback">
                        {{ $errors->first('comment') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.review.fields.comment_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="rating">{{ trans('cruds.review.fields.rating') }}</label>
                <input class="form-control {{ $errors->has('rating') ? 'is-invalid' : '' }}" type="number" name="rating" id="rating" value="{{ old('rating', '') }}" step="0" min="0">
                @if($errors->has('rating'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rating') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.review.fields.rating_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_active') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">{{ trans('cruds.review.fields.is_active') }}</label>
                </div>
                @if($errors->has('is_active'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_active') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.review.fields.is_active_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="customer_name">{{ trans('cruds.review.fields.customer_name') }}</label>
                <input class="form-control {{ $errors->has('customer_name') ? 'is-invalid' : '' }}" type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', '') }}">
                @if($errors->has('customer_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('customer_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.review.fields.customer_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="photos">{{ trans('cruds.review.fields.photos') }}</label>
                <div class="needsclick dropzone {{ $errors->has('photos') ? 'is-invalid' : '' }}" id="photos-dropzone">
                </div>
                @if($errors->has('photos'))
                    <div class="invalid-feedback">
                        {{ $errors->first('photos') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.review.fields.photos_helper') }}</span>
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

@section('scripts')
<script>
    var uploadedPhotosMap = {}
Dropzone.options.photosDropzone = {
    url: '{{ route('admin.reviews.storeMedia') }}',
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
      $('form').append('<input type="hidden" name="photos[]" value="' + response.name + '">')
      uploadedPhotosMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedPhotosMap[file.name]
      }
      $('form').find('input[name="photos[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($review) && $review->photos)
      var files = {!! json_encode($review->photos) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="photos[]" value="' + file.file_name + '">')
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
