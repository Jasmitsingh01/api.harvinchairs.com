@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.printMedium.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.print-media.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.printMedium.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.printMedium.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="page_link">{{ trans('cruds.printMedium.fields.page_link') }}</label>
                <input class="form-control {{ $errors->has('page_link') ? 'is-invalid' : '' }}" type="text" name="page_link" id="page_link" value="{{ old('page_link', '') }}">
                @if($errors->has('page_link'))
                    <div class="invalid-feedback">
                        {{ $errors->first('page_link') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.printMedium.fields.page_link_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="image">{{ trans('cruds.printMedium.fields.image') }}</label>
                <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}" id="image-dropzone">
                </div>
                @if($errors->has('image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.printMedium.fields.image_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="publish_date">{{ trans('cruds.printMedium.fields.publish_date') }}</label>
                <input class="form-control date {{ $errors->has('publish_date') ? 'is-invalid' : '' }}" type="text" name="publish_date" id="publish_date" value="{{ old('publish_date') }}" required>
                @if($errors->has('publish_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('publish_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.printMedium.fields.publish_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.printMedium.fields.is_print_media') }}</label>
                @foreach(App\Models\PrintMedium::IS_PRINT_MEDIA_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('is_print_media') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="is_print_media_{{ $key }}" name="is_print_media" value="{{ $key }}" {{ old('is_print_media', '0') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_print_media_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('is_print_media'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_print_media') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.printMedium.fields.is_print_media_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.printMedium.fields.status') }}</label>
                @foreach(App\Models\PrintMedium::STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', '1') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.printMedium.fields.status_helper') }}</span>
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
    Dropzone.options.imageDropzone = {
    url: '{{ route('admin.print-media.storeMedia') }}',
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
      $('form').find('input[name="image"]').remove()
      $('form').append('<input type="hidden" name="image" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="image"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($printMedium) && $printMedium->image)
      var file = {!! json_encode($printMedium->image) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="image" value="' + file.file_name + '">')
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
