@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.contactUs.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.contact-uss.update", [$contactUs->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="name">{{ trans('cruds.contactUs.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $contactUs->name) }}">
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.contactUs.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="email">{{ trans('cruds.contactUs.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $contactUs->email) }}">
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.contactUs.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="subject">{{ trans('cruds.contactUs.fields.subject') }}</label>
                <input class="form-control {{ $errors->has('subject') ? 'is-invalid' : '' }}" type="text" name="subject" id="subject" value="{{ old('subject', $contactUs->subject) }}">
                @if($errors->has('subject'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subject') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.contactUs.fields.subject_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="message">{{ trans('cruds.contactUs.fields.message') }}</label>
                <input class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}" type="text" name="message" id="message" value="{{ old('message', $contactUs->message) }}">
                @if($errors->has('message'))
                    <div class="invalid-feedback">
                        {{ $errors->first('message') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.contactUs.fields.message_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="attach_file">{{ trans('cruds.contactUs.fields.attach_file') }}</label>
                <div class="needsclick dropzone {{ $errors->has('attach_file') ? 'is-invalid' : '' }}" id="attach_file-dropzone">
                </div>
                @if($errors->has('attach_file'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attach_file') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.contactUs.fields.attach_file_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('notification') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="notification" value="0">
                    <input class="form-check-input" type="checkbox" name="notification" id="notification" value="1" {{ $contactUs->notification || old('notification', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="notification">{{ trans('cruds.contactUs.fields.notification') }}</label>
                </div>
                @if($errors->has('notification'))
                    <div class="invalid-feedback">
                        {{ $errors->first('notification') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.contactUs.fields.notification_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
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
    Dropzone.options.attachFileDropzone = {
    url: '{{ route('admin.contact-uss.storeMedia') }}',
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
      $('form').find('input[name="attach_file"]').remove()
      $('form').append('<input type="hidden" name="attach_file" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="attach_file"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($contactUs) && $contactUs->attach_file)
      var file = {!! json_encode($contactUs->attach_file) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="attach_file" value="' + file.file_name + '">')
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
