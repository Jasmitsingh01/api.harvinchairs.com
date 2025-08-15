@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.banner.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.banners.update", [$banner->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label>{{ trans('cruds.banner.fields.type') }}</label>
                <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                    <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Banner::TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type', $banner->type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.banner.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="title">{{ trans('cruds.banner.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $banner->title) }}">
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.banner.fields.title_helper') }}</span>
            </div>
            {{-- <div class="form-group">
                <label for="dis_index">{{ trans('cruds.banner.fields.dis_index') }}</label>
                <input class="form-control {{ $errors->has('dis_index') ? 'is-invalid' : '' }}" type="number" name="dis_index" id="dis_index" value="{{ old('dis_index', $banner->dis_index) }}" step="1">
                @if($errors->has('dis_index'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dis_index') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.banner.fields.dis_index_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <label for="banner">{{ trans('cruds.banner.fields.banner') }}</label>
                <div class="needsclick dropzone {{ $errors->has('banner') ? 'is-invalid' : '' }}" id="banner-dropzone">
                </div>
                @if($errors->has('banner'))
                    <div class="invalid-feedback">
                        {{ $errors->first('banner') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.banner.fields.banner_helper') }}</span>
                <div class="recommended-settings">
                    <div id="banner-dimensions" style="display: none;">
                        <!-- Banner dimensions will be dynamically inserted here -->
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="category_id">{{ trans('cruds.banner.fields.category') }}</label>
                <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id">
                    @foreach($categories as $id => $entry)
                        <option value="{{ $id }}" {{ (old('category_id') ? old('category_id') : $banner->category->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('category') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.banner.fields.category_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="active" value="0">
                    <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ $banner->active || old('active', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">{{ trans('cruds.banner.fields.active') }}</label>
                </div>
                @if($errors->has('active'))
                    <div class="invalid-feedback">
                        {{ $errors->first('active') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.banner.fields.active_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="link">{{ trans('cruds.banner.fields.link') }}</label>
                <textarea class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" name="link" id="link">{{ old('link', $banner->link) }}</textarea>
                @if($errors->has('link'))
                    <div class="invalid-feedback">
                        {{ $errors->first('link') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.banner.fields.link_helper') }}</span>
            </div>
            {{-- <div class="form-group">
                <label for="link_open">{{ trans('cruds.banner.fields.link_open') }}</label>
                <input class="form-control {{ $errors->has('link_open') ? 'is-invalid' : '' }}" type="text" name="link_open" id="link_open" value="{{ old('link_open', $banner->link_open) }}">
                @if($errors->has('link_open'))
                    <div class="invalid-feedback">
                        {{ $errors->first('link_open') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.banner.fields.link_open_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <label for="link_open">{{ trans('cruds.banner.fields.link_open') }}</label>

                <div class="form-check">
                    <input class="form-check-input" type="radio" value="_self"
                        name="link_open" id="link_open_true"
                        {{ $banner->link_open == '_self' || old('link_open') == '_self' ? 'checked' : '' }}>
                    <label class="form-check-label" for="link_open_true">
                        Within Page
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="_blank"
                        name="link_open" id="link_open_false"
                        {{ $banner->link_open == '_blank' || old('link_open') == '_blank' ? 'checked' : '' }}>
                    <label class="form-check-label" for="link_open_false">
                        New Tab
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="display-text">{{ trans('cruds.banner.fields.display_text') }}</label>
                <input class="form-control {{ $errors->has('display_text') ? 'is-invalid' : '' }}" type="text" name="display_text" id="display_text" value="{{ old('display_text', $banner->display_text) }}">
                @if($errors->has('display_text'))
                    <div class="invalid-feedback">
                        {{ $errors->first('display_text') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.banner.fields.display_text_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="text-colour">{{ trans('cruds.banner.fields.text_colour') }}</label><br>
                  <input type="text" class="coloris instance2 form-control {{ $errors->has('text_colour') ? 'is-invalid' : '' }}" value="{{ $banner->text_colour ?? old('text_colour', '') }}" name="text_colour" id="text_colour">
                  @if($errors->has('text_colour'))
                  <div class="invalid-feedback">
                      {{ $errors->first('text_colour') }}
                  </div>
              @endif
              <span class="help-block">{{ trans('cruds.banner.fields.text_colour_helper') }}</span>

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
    url: '{{ route('admin.banners.storeMedia') }}',
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
@if(isset($banner) && $banner->banner)
      var file = {!! json_encode($banner->banners) !!}
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
<script>
    // Define an object to map banner types to their dimensions
    var bannerDimensions = {
        "Home Banner 1": { "dimensions": "1200x500", "fileType": "jpeg, jpg, png, gif", "fileSize": "{{ config('constants.FILEMAXSIZE') }} MB" },
        "Home Banner 2": { "dimensions": "600x400", "fileType": "jpeg, jpg, png, gif", "fileSize": "{{ config('constants.FILEMAXSIZE') }} MB" },
        "Home Banner 3": { "dimensions": "600x400", "fileType": "jpeg, jpg, png, gif", "fileSize": "{{ config('constants.FILEMAXSIZE') }} MB" }
    };


    // Function to update the banner dimensions based on the selected type
    function updateBannerDetails() {
        var selectedType = $('#type').val();
        var details = bannerDimensions[selectedType];
        if (details) {
            var dimensions = details.dimensions;
            var fileType = details.fileType;
            var fileSize = details.fileSize;
            $('#banner-dimensions').html('<b>Size Specification: </b>' + dimensions + ' [' + fileType + '], ' + fileSize).show();
        } else {
            $('#banner-dimensions').hide();
        }
    }

    // Call the function on page load and whenever the type selection changes
    $(document).ready(function() {
        updateBannerDetails();
        $('#type').change(updateBannerDetails);
    });
</script>
@endsection
