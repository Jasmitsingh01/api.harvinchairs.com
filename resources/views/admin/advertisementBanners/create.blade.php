@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.advertisementBanner.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.advertisement-banners.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.advertisementBanner.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.advertisementBanner.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="banner">{{ trans('cruds.advertisementBanner.fields.banner') }}</label>
                <div class="needsclick dropzone {{ $errors->has('banner') ? 'is-invalid' : '' }}" id="banner-dropzone">
                </div>
                @if($errors->has('banner'))
                    <div class="invalid-feedback">
                        {{ $errors->first('banner') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.advertisementBanner.fields.banner_helper') }}</span>
                <div class="recommended-settings">
                    <b>Recommended: </b> 1200x100 [jpeg,jpg,png,gif], {{ config('constants.FILEMAXSIZE') }} MB
                </div>
            </div>
            <div class="form-group">
                <label for="category_id">{{ trans('cruds.advertisementBanner.fields.category') }}</label>
                <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id">
                    @foreach($categories as $id => $entry)
                        <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('category') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.advertisementBanner.fields.category_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="active" value="0">
                    <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">{{ trans('cruds.advertisementBanner.fields.active') }}</label>
                </div>
                @if($errors->has('active'))
                    <div class="invalid-feedback">
                        {{ $errors->first('active') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.advertisementBanner.fields.active_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="link">{{ trans('cruds.advertisementBanner.fields.link') }}</label>
                <textarea class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" name="link" id="link">{{ old('link') }}</textarea>
                @if($errors->has('link'))
                    <div class="invalid-feedback">
                        {{ $errors->first('link') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.advertisementBanner.fields.link_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="link_open">{{ trans('cruds.advertisementBanner.fields.link_open') }}</label><br/>
                {{-- <input class="form-control {{ $errors->has('link_open') ? 'is-invalid' : '' }}" type="text" name="link_open" id="link_open" value="{{ old('link_open', '') }}"> --}}
                <input type="radio" name="link_open" id="link_open" value="_blank" {{ old('link_open') == "_blank" ? 'checked' : '' }} required> Open in new tab &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="link_open" id="link_open" value="_self" {{ old('link_open') == "_self" ? 'checked' : '' }} required> Open in same tab
                @if($errors->has('link_open'))
                    <div class="invalid-feedback">
                        {{ $errors->first('link_open') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.advertisementBanner.fields.link_open_helper') }}</span>
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
    Dropzone.options.bannerDropzone = {
    url: '{{ route('admin.advertisement-banners.storeMedia') }}',
    maxFilesize: {{ config('constants.FILEMAXSIZE')}}, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
        size: {{ config('constants.FILEMAXSIZE')}},
        width: {{ config('constants.FILEWIDTH')}},
        height: {{ config('constants.FILEWIDTH')}}
    },
    success: function (file, response) {
      $('form').find('input[name="banner"]').remove()
      $('form').append('<input type="hidden" name="banner" value="' + response.name + '">')
      $(':input[type="submit"]').prop('disabled', false);
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="banner"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
      $(':input[type="submit"]').prop('disabled', false);
    },
    init: function () {
@if(isset($advertisementBanner) && $advertisementBanner->banner)
      var file = {!! json_encode($advertisementBanner->banner) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="banner" value="' + file.file_name + '">')
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
