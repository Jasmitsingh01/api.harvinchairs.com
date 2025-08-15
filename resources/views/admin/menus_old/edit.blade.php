@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.menu.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.menus.update', [$menu->id]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.menu.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                        id="name" value="{{ old('name', $menu->name) }}" required>
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.menu.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <div class="form-check {{ $errors->has('is_category') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="is_category" value="0">
                        <input class="form-check-input" type="checkbox" name="is_category" id="is_category" value="1"
                            {{ $menu->is_category || old('is_category', 0) === 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_category"> Is Main Category </label>
                    </div>
                    @if ($errors->has('is_category'))
                        <div class="invalid-feedback">
                            {{ $errors->first('is_category') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.menu.fields.active_helper') }}</span>
                </div>
                <div class="form-group mt-4">
                    <label for="is_cms">Menu Type</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="1" name="is_cms" id="is_cms_true"  {{ $menu->is_cms == '1' || old('is_cms') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_cms_true">
                            Is CMS
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="0" name="is_cms"
                            id="is_cms_false"  {{ $menu->is_cms == '0' || old('is_cms') == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_cms_false">
                            Is Category
                        </label>
                    </div>
                </div>
                {{-- @if($menu->is_cms == true) --}}
                <div class="form-group" id="category_div">
                    <label for="categories">{{ trans('cruds.menu.fields.categories') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all"
                            style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all"
                            style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('categories') ? 'is-invalid' : '' }}"
                        name="categories[]" id="categories" multiple>
                        @foreach ($categories as $id => $category)
                            <option value="{{ $id }}"
                                {{ in_array($id, old('categories', [])) || $menu->categories->contains($id) ? 'selected' : '' }}>
                                {{ $category }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('categories'))
                        <div class="invalid-feedback">
                            {{ $errors->first('categories') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.menu.fields.categories_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="image">{{ trans('cruds.menu.fields.image') }}</label>
                    <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}" id="image-dropzone">
                    </div>
                    @if($errors->has('image'))
                        <div class="invalid-feedback">
                            {{ $errors->first('image') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.menu.fields.image_helper') }}</span>
                </div>
                {{-- @else --}}
                <div class="form-group" id="cms_div">
                    <label for="parent_id">{{ trans('cruds.menu.fields.parent') }}</label>
                    <select class="form-control" name="parent_id">
                        <option value="0">Parent Page</option>
                        @foreach ($all_menus as $m_id => $p_menu)
                            <option value="{{ $m_id }}">{{ $p_menu }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- @endif --}}
                <div class="form-group">
                    <label for="value">Page Link</label>
                    <input type="text" class="form-control" name="page_link" value="{{ old('page_link', $menu->page_link) }}">
                </div>
                <div class="form-group">
                    <div class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="active" value="0">
                        <input class="form-check-input" type="checkbox" name="active" id="active" value="1"
                            {{ $menu->active || old('active', 0) === 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">{{ trans('cruds.menu.fields.active') }}</label>
                    </div>
                    @if ($errors->has('active'))
                        <div class="invalid-feedback">
                            {{ $errors->first('active') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.menu.fields.active_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="color_code">{{ trans('cruds.banner.fields.text_colour') }}</label><br>
                      <input type="text" class="coloris instance2 form-control {{ $errors->has('color_code') ? 'is-invalid' : '' }}" value="{{ $menu->color_code ?? old('color_code', '') }}" name="color_code" id="color_code">
                      @if($errors->has('color_code'))
                      <div class="invalid-feedback">
                          {{ $errors->first('color_code') }}
                      </div>
                  @endif
                  <span class="help-block">{{ trans('cruds.banner.fields.text_colour_helper') }}</span>

                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
      @if($menu->is_cms == true)
            $("#cms_div").show();
            $("#category_div").hide();
      @else
            $("#cms_div").hide();
            $("#category_div").show();
      @endif
        $(document).ready(function() {
            $("#is_cms_true").click(function() {
                $("#cms_div").show();
                $("#category_div").hide();
            });

            $("#is_cms_false").click(function() {
                console.log('in');
                $("#cms_div").hide();
                $("#category_div").show();
            });
        });
    </script>
    <script>
        Dropzone.options.imageDropzone = {
        url: '{{ route('admin.menus.storeMedia') }}',
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
            @if(isset($menu) && $menu->images)
            var file = {!! json_encode($menu->image) !!}
            this.options.addedfile.call(this, file)
            this.options.thumbnail.call(this,  file, file.preview ?? file.preview_url)
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

            return _results
        }
    }

    </script>
@endsection
