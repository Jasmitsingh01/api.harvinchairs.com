@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.ourStore.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.our-stores.update", [$ourStore->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.ourStore.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $ourStore->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ourStore.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="short_line">{{ trans('cruds.ourStore.fields.short_line') }}</label>
                <input class="form-control {{ $errors->has('short_line') ? 'is-invalid' : '' }}" type="text" name="short_line" id="short_line" value="{{ old('short_line', $ourStore->short_line) }}">
                @if($errors->has('short_line'))
                    <div class="invalid-feedback">
                        {{ $errors->first('short_line') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ourStore.fields.short_line_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="address">{{ trans('cruds.ourStore.fields.address') }}</label>
                <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text" name="address" id="address" value="{{ old('address', $ourStore->address) }}">
                @if($errors->has('address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ourStore.fields.address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="opening_hours">{{ trans('cruds.ourStore.fields.opening_hours') }}</label>
                <input class="form-control {{ $errors->has('opening_hours') ? 'is-invalid' : '' }}" type="text" name="opening_hours" id="opening_hours" value="{{ old('opening_hours', $ourStore->opening_hours) }}">
                @if($errors->has('opening_hours'))
                    <div class="invalid-feedback">
                        {{ $errors->first('opening_hours') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ourStore.fields.opening_hours_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="contact_number">{{ trans('cruds.ourStore.fields.contact_number') }}</label>
                <input class="form-control {{ $errors->has('contact_number') ? 'is-invalid' : '' }}" type="text" name="contact_number" id="contact_number" value="{{ old('contact_number', $ourStore->contact_number) }}">
                @if($errors->has('contact_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('contact_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ourStore.fields.contact_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="gallery">{{ trans('cruds.ourStore.fields.gallery') }}</label>
                <div class="needsclick dropzone {{ $errors->has('gallery') ? 'is-invalid' : '' }}" id="gallery-dropzone">
                </div>
                @if($errors->has('gallery'))
                    <div class="invalid-feedback">
                        {{ $errors->first('gallery') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ourStore.fields.gallery_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="city">{{ trans('cruds.ourStore.fields.city') }}</label>
                <input class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" type="text" name="city" id="city" value="{{ old('city', $ourStore->city) }}" required>
                @if($errors->has('city'))
                    <div class="invalid-feedback">
                        {{ $errors->first('city') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ourStore.fields.city_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="pincode">{{ trans('cruds.ourStore.fields.pincode') }}</label>
                <input class="form-control {{ $errors->has('pincode') ? 'is-invalid' : '' }}" type="text" name="pincode" id="pincode" value="{{ old('pincode', $ourStore->pincode) }}" required>
                @if($errors->has('pincode'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pincode') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ourStore.fields.pincode_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="latitude">{{ trans('cruds.ourStore.fields.latitude') }}</label>
                <input class="form-control {{ $errors->has('latitude') ? 'is-invalid' : '' }}" type="text" name="latitude" id="latitude" value="{{ old('latitude', $ourStore->latitude) }}" >
                @if($errors->has('latitude'))
                    <div class="invalid-feedback">
                        {{ $errors->first('latitude') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ourStore.fields.latitude_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="longitude">{{ trans('cruds.ourStore.fields.longitude') }}</label>
                <input class="form-control {{ $errors->has('longitude') ? 'is-invalid' : '' }}" type="text" name="longitude" id="longitude" value="{{ old('longitude', $ourStore->longitude) }}" >
                @if($errors->has('longitude'))
                    <div class="invalid-feedback">
                        {{ $errors->first('longitude') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ourStore.fields.longitude_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.ourStore.fields.status') }}</label>
                @foreach(App\Models\OurStore::STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', $ourStore->status) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ourStore.fields.status_helper') }}</span>
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
    var uploadedGalleryMap = {}
Dropzone.options.galleryDropzone = {
    url: '{{ route('admin.our-stores.storeMedia') }}',
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
      $('form').append('<input type="hidden" name="gallery[]" value="' + response.name + '">')
      uploadedGalleryMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedGalleryMap[file.name]
      }
      $('form').find('input[name="gallery[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($ourStore) && $ourStore->gallery)
      var files = {!! json_encode($ourStore->gallery) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="gallery[]" value="' + file.file_name + '">')
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
