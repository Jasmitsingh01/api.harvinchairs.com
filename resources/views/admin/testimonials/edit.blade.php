@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.testimonial.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.testimonials.update", [$testimonial->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="customer_id">{{ trans('cruds.testimonial.fields.customer') }}</label>
                <select class="form-control select2 {{ $errors->has('customer') ? 'is-invalid' : '' }}" name="customer_id" id="customer_id">
                    @foreach($customers as $id => $entry)
                        <option value="{{ $id }}" {{ (old('customer_id') ? old('customer_id') : $testimonial->customer->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('customer'))
                    <div class="invalid-feedback">
                        {{ $errors->first('customer') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.testimonial.fields.customer_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="author_name">{{ trans('cruds.testimonial.fields.author_name') }}</label>
                <input class="form-control {{ $errors->has('author_name') ? 'is-invalid' : '' }}" type="text" name="author_name" id="author_name" value="{{ old('author_name', $testimonial->author_name) }}" required>
                @if($errors->has('author_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('author_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.testimonial.fields.author_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="author_info">{{ trans('cruds.testimonial.fields.author_info') }}</label>
                <input class="form-control {{ $errors->has('author_info') ? 'is-invalid' : '' }}" type="text" name="author_info" id="author_info" value="{{ old('author_info', $testimonial->author_info) }}">
                @if($errors->has('author_info'))
                    <div class="invalid-feedback">
                        {{ $errors->first('author_info') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.testimonial.fields.author_info_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="author_url">{{ trans('cruds.testimonial.fields.author_url') }}</label>
                <input class="form-control {{ $errors->has('author_url') ? 'is-invalid' : '' }}" type="text" name="author_url" id="author_url" value="{{ old('author_url', $testimonial->author_url) }}">
                @if($errors->has('author_url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('author_url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.testimonial.fields.author_url_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="author_email">{{ trans('cruds.testimonial.fields.author_email') }}</label>
                <input class="form-control {{ $errors->has('author_email') ? 'is-invalid' : '' }}" type="text" name="author_email" id="author_email" value="{{ old('author_email', $testimonial->author_email) }}">
                @if($errors->has('author_email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('author_email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.testimonial.fields.author_email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="author_image">{{ trans('cruds.testimonial.fields.author_image') }}</label>
                <div class="needsclick dropzone {{ $errors->has('author_image') ? 'is-invalid' : '' }}" id="author_image-dropzone">
                </div>
                @if($errors->has('author_image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('author_image') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.testimonial.fields.author_image_helper') }}</span>
                {{-- <div class="recommended-settings">
                    <b>Size Specification:</b><br>
                    160 x 130 pixels [jpeg, jpg, png, gif], {{ config('constants.FILEMAXSIZE') }} MB<br>
                </div> --}}
            </div>
            <div class="form-group">
                <label for="rating">{{ trans('cruds.testimonial.fields.rating') }}</label>
                <input class="form-control {{ $errors->has('rating') ? 'is-invalid' : '' }}" type="number" name="rating" id="rating" value="{{ old('rating', $testimonial->rating) }}" step="1">
                @if($errors->has('rating'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rating') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.testimonial.fields.rating_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="content">{{ trans('cruds.testimonial.fields.content') }}</label>
                <textarea class="form-control {{ $errors->has('content') ? 'is-invalid' : '' }}" type="text" name="content" id="content" required>{{ old('content', $testimonial->content) }}</textarea>
                @if($errors->has('content'))
                    <div class="invalid-feedback">
                        {{ $errors->first('content') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.testimonial.fields.content_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_featured') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_featured" value="0">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ $testimonial->is_featured || old('is_featured', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_featured">{{ trans('cruds.testimonial.fields.is_featured') }}</label>
                </div>
                @if($errors->has('is_featured'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_featured') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.testimonial.fields.is_featured_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="active" value="0">
                    <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ $testimonial->active || old('active', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">{{ trans('cruds.testimonial.fields.active') }}</label>
                </div>
                @if($errors->has('active'))
                    <div class="invalid-feedback">
                        {{ $errors->first('active') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.testimonial.fields.active_helper') }}</span>
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
    Dropzone.options.authorImageDropzone = {
    url: '{{ route('admin.testimonials.storeMedia') }}',
    maxFilesize: {{ config('constants.FILEMAXSIZE')}}, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    // params: {
    //     size: {{ config('constants.FILEMAXSIZE')}},
    //     width: {{ config('constants.FILEWIDTH')}},
    //     height: {{ config('constants.FILEWIDTH')}}
    // },
    success: function (file, response) {
      $('form').find('input[name="author_image"]').remove()
      $('form').append('<input type="hidden" name="author_image" value="' + response.name + '">')
      $(':input[type="submit"]').prop('disabled', false);
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="author_image"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
      $(':input[type="submit"]').prop('disabled', false);
    },
    init: function () {
    @if(isset($testimonial) && $testimonial->author_image)

        var file = {!! json_encode($testimonial->author_image) !!}
        this.options.addedfile.call(this, file)
        this.options.author.call(this, file, file.preview ?? file.preview_url)
        file.previewElement.classList.add('dz-complete')
        $('form').append('<input type="hidden" name="author_image" value="' + file.file_name + '">')
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
        $(':input[type="submit"]').prop('disabled', true);
        return _results
    }
}

</script>
@endsection
