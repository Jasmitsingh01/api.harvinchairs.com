@extends('layouts.admin')
@section('content')
<Style>
    .bootstrap-tagsinput .tag {
          margin-right: 2px;
          /* color: white !important; */
          background-color: #0d6efd;
          padding: 0.2rem;
        }
    </Style>
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.category.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.categories.update", [$category->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.category.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="details">{{ trans('cruds.category.fields.details') }}</label>
                <textarea class="form-control {{ $errors->has('details') ? 'is-invalid' : '' }}" name="details" id="details">{!! old('details', $category->details) !!}</textarea>
                @if($errors->has('details'))
                    <div class="invalid-feedback">
                        {{ $errors->first('details') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.details_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="slug">{{ trans('cruds.category.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}">
                @if($errors->has('slug'))
                    <div class="invalid-feedback">
                        {{ $errors->first('slug') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.slug_helper') }}</span>
            </div>
            {{-- <div class="form-group">
                <label for="language">{{ trans('cruds.category.fields.language') }}</label>
                <input class="form-control {{ $errors->has('language') ? 'is-invalid' : '' }}" type="text" name="language" id="language" value="{{ old('language', $category->language) }}">
                @if($errors->has('language'))
                    <div class="invalid-feedback">
                        {{ $errors->first('language') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.language_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="position">{{ trans('cruds.category.fields.position') }}</label>
                <input class="form-control {{ $errors->has('position') ? 'is-invalid' : '' }}" type="number" name="position" id="position" value="{{ old('position', $category->position) }}" step="1">
                @if($errors->has('position'))
                    <div class="invalid-feedback">
                        {{ $errors->first('position') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.position_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <label for="cover_image">{{ trans('cruds.category.fields.cover_image') }}</label>
                <div class="needsclick dropzone {{ $errors->has('cover_image') ? 'is-invalid' : '' }}" id="cover_image-dropzone">
                </div>
                @if($errors->has('cover_image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cover_image') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.cover_image_helper') }}</span>
                <div class="recommended-settings">
                    <b>Size Specification:</b><br>
                    1200 x 400 pixels [jpeg, jpg, png, gif], {{ config('constants.FILEMAXSIZE') }} MB<br>
                </div>
            </div>
            <div class="form-group">
                <label for="thumbnail_image">{{ trans('cruds.category.fields.thumbnail_image') }}</label>
                <div class="needsclick dropzone {{ $errors->has('thumbnail_image') ? 'is-invalid' : '' }}" id="thumbnail_image-dropzone">
                </div>
                @if($errors->has('thumbnail_image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('thumbnail_image') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.thumbnail_image_helper') }}</span>
                <div class="recommended-settings">
                    <b>Size Specification:</b><br>
                    160 x 130 pixels [jpeg, jpg, png, gif], {{ config('constants.FILEMAXSIZE') }} MB<br>
                </div>
            </div>
            <div class="form-group">
                <label for="parent">{{ trans('cruds.category.fields.parent') }}</label>
                <select id="parent" name="parent" class="form-control select2 {{ $errors->has('parent') ? 'is-invalid' : '' }}">
                    <option value="">Parent Category</option>
                    @foreach ($categories as $cat)
                        <option value="{{$cat->id}}" @if($category->parent == $cat->id)
                            selected
                        @endif>{{$cat->name}}</option>
                    @endforeach
                </select>
                {{-- <input class="form-control {{ $errors->has('parent') ? 'is-invalid' : '' }}" type="number" name="parent" id="parent" value="{{ old('parent', $category->parent) }}" step="1"> --}}
                @if($errors->has('parent'))
                    <div class="invalid-feedback">
                        {{ $errors->first('parent') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.parent_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="type_id">Type</label>
                <select id="type_id" name="type_id" class="form-control select2 {{ $errors->has('type_id') ? 'is-invalid' : '' }}" required>
                    <option value="">Select Type</option>
                    @foreach ($types as $type)
                        <option value="{{$type->id}}" @if($category->type_id == $type->id) selected @endif>{{$type->name}}</option>
                    @endforeach
                </select>
                @if($errors->has('type_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type_id') }}
                    </div>
                @endif
                <span class="help-block">Select the type for this category</span>
            </div>


            <div class="form-group">
                <label for="name">{{ trans('cruds.category.fields.cgst_rate') }}</label>
                <input class="form-control {{ $errors->has('cgst_rate') ? 'is-invalid' : '' }}" type="number" name="cgst_rate" id="cgst_rate" value="{{ old('cgst_rate', $category->cgst_rate) }}" min=0 max=100>
                @if($errors->has('cgst_rate'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cgst_rate') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.cgst_rate_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="name">{{ trans('cruds.category.fields.sgst_rate') }}</label>
                <input class="form-control {{ $errors->has('sgst_rate') ? 'is-invalid' : '' }}" type="number" name="sgst_rate" id="sgst_rate" value="{{ old('sgst_rate', $category->sgst_rate) }}" min=0 max=100>
                @if($errors->has('sgst_rate'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sgst_rate') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.sgst_rate_helper') }}</span>
            </div>

            <div class="form-group">
                <div class="form-check {{ $errors->has('enabled') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="enabled" value="0">
                    <input class="form-check-input" type="checkbox" name="enabled" id="enabled" value="1" {{ $category->enabled || old('enabled', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="enabled">{{ trans('cruds.category.fields.enabled') }}</label>
                </div>
                @if($errors->has('enabled'))
                    <div class="invalid-feedback">
                        {{ $errors->first('enabled') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.enabled_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_home') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_home" value="0">
                    <input class="form-check-input" type="checkbox" name="is_home" id="is_home" value="1" {{ $category->is_home || old('is_home', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_home">{{ trans('cruds.category.fields.is_home') }}</label>
                </div>
                @if($errors->has('is_home'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_home') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.is_home_helper') }}</span>
            </div>

            <div class="form-group">
                <div class="form-check {{ $errors->has('is_showcase') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_showcase" value="0">
                    <input class="form-check-input" type="checkbox" name="is_showcase" id="is_showcase" value="1" {{ $category->is_showcase || old('is_showcase', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_showcase">{{ trans('cruds.category.fields.is_showcase') }}</label>
                </div>
                @if($errors->has('is_showcase'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_showcase') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.is_showcase_helper') }}</span>
            </div>

            <div class="form-group" id="collection_image_div" {{ $category->is_home || old('is_home', 0) === 1 ? '' : 'style="display:none"' }}>
                <label for="collection_image">Collection Image</label>
                <div class="needsclick dropzone {{ $errors->has('collection_image') ? 'is-invalid' : '' }}" id="collection_image-dropzone">
                </div>
                @if($errors->has('collection_image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('collection_image') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.collection_image_helper') }}</span>
                <div class="recommended-settings">
                    <b>Size Specification:</b><br>
                    595 x 610 pixels [jpeg, jpg, png, gif], {{ config('constants.FILEMAXSIZE') }} MB<br>
                </div>
            </div>
            <div class="form-group">
                <label for="meta_description">{{ trans('cruds.category.fields.meta_description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" name="meta_description" id="meta_description">{!! old('meta_description', $category->meta_description) !!}</textarea>
                @if($errors->has('meta_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.meta_description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="meta_title">{{ trans('cruds.category.fields.meta_title') }}</label>
                <input class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $category->meta_title) }}">
                @if($errors->has('meta_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.meta_title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="meta_keywords">{{ trans('cruds.category.fields.meta_keywords') }}</label>
                <input
                type="text"
                class="form-control {{ $errors->has('meta_keywords') ? 'is-invalid' : '' }}"
                name="meta_keywords" id="meta_keywords"
                data-role="tagsinput"
                value="{{ old('meta_keywords', $category->meta_keywords) }}"
              />
                {{-- <textarea class="form-control {{ $errors->has('meta_keywords') ? 'is-invalid' : '' }}" name="meta_keywords" id="meta_keywords"></textarea> --}}
                @if($errors->has('meta_keywords'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_keywords') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.meta_keywords_helper') }}</span>
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
    $(function () {
      $('input')
        .on('change', function (event) {
          var $element = $(event.target);
          var $container = $element.closest('.example');

          if (!$element.data('tagsinput')) return;

          var val = $element.val();
          if (val === null) val = 'null';
          var items = $element.tagsinput('items');

          $('code', $('pre.val', $container)).html(
            $.isArray(val)
              ? JSON.stringify(val)
              : '"' + val.replace('"', '\\"') + '"'
          );
          $('code', $('pre.items', $container)).html(
            JSON.stringify($element.tagsinput('items'))
          );
        })
        .trigger('change');
    });
</script>
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.categories.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $category->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

<script>
    Dropzone.options.coverImageDropzone = {
    url: '{{ route('admin.categories.storeMedia') }}',
    maxFilesize: {{ config('constants.FILEMAXSIZE')}},
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
    @if(isset($category) && $category->cover_image)
        var file = {!! json_encode($category->cover_image) !!}
            this.options.addedfile.call(this, file)
        this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
        file.previewElement.classList.add('dz-complete')
        $('form').append('<input type="hidden" name="cover_image" value="' + file.file_name + '">')
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
    Dropzone.options.thumbnailImageDropzone = {
    url: '{{ route('admin.categories.storeMedia') }}',
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
      $('form').find('input[name="thumbnail_image"]').remove()
      $('form').append('<input type="hidden" name="thumbnail_image" value="' + response.name + '">')
      $(':input[type="submit"]').prop('disabled', false);
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="thumbnail_image"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
      $(':input[type="submit"]').prop('disabled', false);
    },
    init: function () {
    @if(isset($category) && $category->thumbnail_image)

        var file = {!! json_encode($category->thumbnail_image) !!}
        this.options.addedfile.call(this, file)
        this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
        file.previewElement.classList.add('dz-complete')
        $('form').append('<input type="hidden" name="thumbnail_image" value="' + file.file_name + '">')
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
    Dropzone.options.collectionImageDropzone = {
    url: '{{ route('admin.categories.storeMedia') }}',
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
      $('form').find('input[name="collection_image"]').remove()
      $('form').append('<input type="hidden" name="collection_image" value="' + response.name + '">')
      $(':input[type="submit"]').prop('disabled', false);
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="collection_image"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
      $(':input[type="submit"]').prop('disabled', false);
    },
    init: function () {
    @if(isset($category) && $category->collection_image_url)
        var file = {!! json_encode($category->collection_image_url) !!}
            this.options.addedfile.call(this, file)
        this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
        file.previewElement.classList.add('dz-complete')
        $('form').append('<input type="hidden" name="collection_image" value="' + file.file_name + '">')
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
    $("#is_home").on("change", function () {
        if ($(this).is(":checked")) {
            $("#collection_image_div").show();
        } else {
            $("#collection_image_div").hide();
        }
    });
</script>
@endsection
