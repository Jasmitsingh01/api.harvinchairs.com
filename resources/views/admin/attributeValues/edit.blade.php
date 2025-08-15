@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.attributeValue.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" id="updateAttrValue" action="{{ route("admin.attribute-values.update", [$attributeValue->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            {{-- <div class="form-group">
                <label for="slug">{{ trans('cruds.attributeValue.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', $attributeValue->slug) }}">
                @if($errors->has('slug'))
                    <div class="invalid-feedback">
                        {{ $errors->first('slug') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attributeValue.fields.slug_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <label for="attribute_id">{{ trans('cruds.attributeValue.fields.attribute') }}</label>
                <select class="form-control select2 {{ $errors->has('attribute') ? 'is-invalid' : '' }}" name="attribute_id" id="attribute_id" readonly>
                    <option value="{{  $attributeValue->attribute->id }}" >{{ $attributeValue->attribute->name }}</option>

                    {{-- @foreach($attributes as $id => $entry)
                        <option value="{{ $id }}" {{ (old('attribute_id') ? old('attribute_id') : $attributeValue->attribute->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach --}}
                </select>
                @if($errors->has('attribute'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attribute') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attributeValue.fields.attribute_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="value">{{ trans('cruds.attributeValue.fields.value') }}</label>
                <input required class="form-control {{ $errors->has('value') ? 'is-invalid' : '' }}" type="text" name="value" id="value" value="{{ old('value', $attributeValue->value) }}">
                @if($errors->has('value'))
                    <div class="invalid-feedback">
                        {{ $errors->first('value') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attributeValue.fields.value_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="meta_title">{{ trans('cruds.attributeValue.fields.meta_title') }}</label>
                <input class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $attributeValue->meta_title) }}">
                @if($errors->has('meta_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attributeValue.fields.meta_title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="meta_description">{{ trans('cruds.attributeValue.fields.meta_description') }}</label>
                <textarea class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" name="meta_description" id="meta_description">{{ old('meta_description', $attributeValue->meta_description) }}</textarea>
                @if($errors->has('meta_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attributeValue.fields.meta_description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="meta_keywords">{{ trans('cruds.attributeValue.fields.meta_keywords') }}</label>
                <textarea class="form-control {{ $errors->has('meta_keywords') ? 'is-invalid' : '' }}" name="meta_keywords" id="meta_keywords">{{ old('meta_keywords', $attributeValue->meta_keywords) }}</textarea>
                @if($errors->has('meta_keywords'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_keywords') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attributeValue.fields.meta_keywords_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cover_image">{{ trans('cruds.attributeValue.fields.cover_image') }}</label>
                <div class="needsclick dropzone {{ $errors->has('cover_image') ? 'is-invalid' : '' }}" id="cover_image-dropzone">
                </div>
                @if($errors->has('cover_image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cover_image') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attributeValue.fields.cover_image_helper') }}</span>
                <div class="recommended-settings">
                    <b>Recommended:</b> 97 x 64 pixels [jpeg, jpg, png, gif], {{ config('constants.FILEMAXSIZE') }} MB
                </div>
            </div>
            @if ($attributeValue->attribute->is_fabric)
            <div class="form-group">
                <label for="fabric_image">{{ trans('cruds.attribute.fields.fabric_image') }}</label>
                <div class="needsclick dropzone {{ $errors->has('fabric_image') ? 'is-invalid' : '' }}" id="fabric_image-dropzone">
                </div>
                @if($errors->has('fabric_image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fabric_image') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attribute.fields.fabric_image_helper') }}</span>
                <div class="recommended-settings">
                    <b>Recommended:</b> 97 x 64 pixels [jpeg, jpg, png, gif], {{ config('constants.FILEMAXSIZE') }} MB
                </div>
            </div>
            @endif

            {{-- <div class="form-group">
                <div class="form-check {{ $errors->has('is_color') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_color" value="0">
                    <input class="form-check-input" type="checkbox" name="is_color" id="is_color" value="1" {{ $attributeValue->is_color || old('is_color', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_color">{{ trans('cruds.attributeValue.fields.is_color') }}</label>
                </div>
                @if($errors->has('is_color'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_color') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attributeValue.fields.is_color_helper') }}</span>
            </div>

            <div class="form-group" id="colorCodeField" style="{{ $attributeValue->is_color || old('is_color', 0) === 1 ? '' : 'display:none' }}">
                <label class="required" for="color_code">{{ trans('cruds.attributeValue.fields.color_code') }}</label><br>
                <input type="text" class="coloris instance2 form-control {{ $errors->has('color_code') ? 'is-invalid' : '' }}" value="{{ $attributeValue->color_code ?? old('color_code', '') }}" name="color_code" id="color_code" {{ $attributeValue->is_color || old('is_color', 0) === 1 ? 'required' : '' }}>
                @if($errors->has('color_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('color_code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attributeValue.fields.color_code_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <label for="description">{{ trans('cruds.attributeValue.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', $attributeValue->description) }}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attributeValue.fields.description_helper') }}</span>
            </div>

            {{-- <div class="form-group">
                <label for="language">{{ trans('cruds.attributeValue.fields.language') }}</label>
                <input class="form-control {{ $errors->has('language') ? 'is-invalid' : '' }}" type="text" name="language" id="language" value="{{ old('language', $attributeValue->language) }}">
                @if($errors->has('language'))
                    <div class="invalid-feedback">
                        {{ $errors->first('language') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attributeValue.fields.language_helper') }}</span>
            </div> --}}
            {{-- <div class="form-group">
                <label for="position">{{ trans('cruds.attributeValue.fields.position') }}</label>
                <input class="form-control {{ $errors->has('position') ? 'is-invalid' : '' }}" type="number" name="position" id="position" value="{{ old('position', $attributeValue->position) }}" step="1">
                @if($errors->has('position'))
                    <div class="invalid-feedback">
                        {{ $errors->first('position') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attributeValue.fields.position_helper') }}</span>
            </div> --}}
            {{-- <div class="form-group">
                <label for="meta">{{ trans('cruds.attributeValue.fields.meta') }}</label>
                <input class="form-control {{ $errors->has('meta') ? 'is-invalid' : '' }}" type="text" name="meta" id="meta" value="{{ old('meta', $attributeValue->meta) }}">
                @if($errors->has('meta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attributeValue.fields.meta_helper') }}</span>
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
    Dropzone.options.coverImageDropzone = {
    url: '{{ route('admin.attribute-values.storeMedia') }}',
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
      $('form').find('input[name="cover_image"]').remove()
      $('form').append('<input type="hidden" name="cover_image" value="' + response.name + '">')
      $(':input[type="submit"]').prop('disabled', false);
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="cover_image"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
      $(':input[type="submit"]').prop('disabled', false);
    },
    init: function () {
    @if(isset($attributeValue) && $attributeValue->cover_image)
        var file = {!! json_encode($attributeValue->cover_image) !!}
            this.options.addedfile.call(this, file)
        this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
        file.previewElement.classList.add('dz-complete')
        $('form').append('<input type="hidden" name="cover_image" value="' + file.name + '">')
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
<script>
    Dropzone.options.fabricImageDropzone = {
    url: '{{ route('admin.attribute-values.storeMedia') }}',
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
      $('form').find('input[name="fabric_image"]').remove()
      $('form').append('<input type="hidden" name="fabric_image" value="' + response.name + '">')
      $(':input[type="submit"]').prop('disabled', false);
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="fabric_image"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
      $(':input[type="submit"]').prop('disabled', false);
    },
    init: function () {


    @if(isset($attributeValue) && $attributeValue->fabric_image)
        var file = {!! json_encode($attributeValue->fabric_image) !!}
            this.options.addedfile.call(this, file)
        this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
        file.previewElement.classList.add('dz-complete')
        $('form').append('<input type="hidden" name="fabric_image" value="' + file.name + '">')
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
<script>
    // Add an event listener to the checkbox to toggle the visibility and required attribute of the colorCodeField
    const isColorCheckbox = document.getElementById('is_color');
    const colorCodeField = document.getElementById('colorCodeField');
    const colorCodeInput = document.getElementById('color_code');

    isColorCheckbox.addEventListener('change', function () {
        colorCodeField.style.display = this.checked ? '' : 'none';
        colorCodeInput.required = this.checked;
    });
    document.getElementById('updateAttrValue').addEventListener('submit', function () {
            const isColorCheckbox = document.getElementById('is_color');
            const colorCodeInput = document.getElementById('color_code');

            // If is_color checkbox is not checked, set color_code value to null
            if (!isColorCheckbox.checked) {
                colorCodeInput.value = null;
            }
        });
</script>

@endsection
