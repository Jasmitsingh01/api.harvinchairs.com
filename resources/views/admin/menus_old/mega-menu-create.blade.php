@extends('layouts.admin')
@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">
    <style>
        a {
            color: #6bd1bb;
        }
        a:focus, a:hover{
            color: #339580;
        }
        .btn-primary {
            color: #fff;
            background: #6bd1bb;
            border-color: #6bd1bb;
        }
        .navbar{
            min-height:20px;
            margin-bottom:0px;
            height:58px;
        }
        li{
            list-style-type: none;
        }
        .item-list,
        .info-box {
            background: #fff;
            padding: 10px;
        }

        .item-list-body {
            max-height: 300px;
            overflow-y: scroll;
        }

        .panel-body p {
            margin-bottom: 5px;
        }

        .info-box {
            margin-bottom: 15px;
        }

        .item-list-footer {
            padding-top: 10px;
        }

        .panel-heading a {
            display: block;
        }

        .form-inline {
            display: inline;
        }

        .form-inline select {
            padding: 4px 10px;
        }

        .btn-menu-select {
            padding: 4px 10px
        }

        .disabled {
            pointer-events: none;
            opacity: 0.7;
        }

        .menu-item-bar {
            background: #eee;
            padding: 5px 10px;
            border: 1px solid #d7d7d7;
            margin-bottom: 5px;
            width: 75%;
            cursor: move;
            display: block;
        }

        #serialize_output {
            display: block;
        }

        .menulocation label {
            font-weight: normal;
            display: block;
        }

        body.dragging,
        body.dragging * {
            cursor: move !important;
        }

        .dragged {
            position: absolute;
            z-index: 1;
        }

        ol.example li.placeholder {
            position: relative;
        }

        ol.example li.placeholder:before {
            position: absolute;
        }

        #menuitem {
            list-style: none;
        }

        #menuitem ul {
            list-style: none;
        }

        /* .input-box {
            width: 75%;
            background: #fff;
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 5px;
        } */

        .input-box .form-control {
            width: 50%
        }
    </style>
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.menu.title_singular') }} {{ trans('global.list') }}
        </div>
        <div class="container-fluid">
            <div class="row" id="main-row">
                <div class="col-sm-3 cat-form @if (count($menus) == 0) disabled @endif">
                    <h3><span>Add Menu Items</span></h3>

                    <div class="panel-group" id="menu-items">
                        <div class="panel panel-default">
                            <div class="panel-heading panel-div">
                                <a href="#categories-list" data-toggle="collapse" data-parent="#menu-items">Categories <span
                                        class="caret pull-right"></span></a>
                            </div>
                            <div class="panel-collapse collapse" id="categories-list">
                                <div class="panel-body">
                                    <div class="item-list-body">
                                        @foreach ($categories as $cat)
                                            <p><input type="checkbox" name="select-category[]" value="{{ $cat->id }}">
                                                {{ $cat->name }}</p>
                                        @endforeach
                                    </div>
                                    <div class="item-list-footer">
                                        <label class="btn btn-sm btn-default"><input type="checkbox" id="select-all-categories">
                                            Select All</label>
                                        <button type="button" class="pull-right btn btn-default btn-sm" id="add-categories">Add
                                            to Menu</button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading panel-div">
                                <a href="#custom-links" data-toggle="collapse" data-parent="#menu-items">Custom Links <span
                                        class="caret pull-right"></span></a>
                            </div>
                            <div class="panel-collapse collapse" id="custom-links">
                                <div class="panel-body">
                                    <div class="item-list-body">
                                        <div class="form-group">
                                            <label>URL</label>
                                            <input type="url" id="url" class="form-control" placeholder="https://">
                                        </div>
                                        <div class="form-group">
                                            <label>Link Text</label>
                                            <input type="text" id="linktext" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                    <div class="item-list-footer">
                                        <button type="button" class="pull-right btn btn-default btn-sm"
                                            id="add-custom-link">Add to Menu</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-9 cat-view">
                    <h3><span>Menu Structure</span></h3>
                    @if ($desiredMenu == '')
                        <h4>Create New Menu</h4>
                        <form method="post" action="{{ route('admin.create-menu') }}">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Name</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="title" class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-6 text-right">
                                    <button class="btn btn-sm btn-primary">Create Menu</button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div id="menu-content">
                            <div id="result"></div>
                            <div style="min-height: 240px;">
                                <p>Select categories, pages or add custom links to menus.</p>
                                @if ($desiredMenu != '')
                                    <ul class="menu ui-sortable" id="menuitems">
                                        @if (!empty($menuitems))
                                            @foreach ($menuitems as $key => $item)
                                                <li data-id="{{ $item->id }}"><span class="menu-item-bar panel-div"><i
                                                            class="fa fa-arrows"></i>
                                                        @if (empty($item->name))
                                                            {{ $item->title }}
                                                        @else
                                                            {{ $item->name }}
                                                        @endif <a href="#collapse{{ $item->id }}" class="pull-right" data-toggle="collapse" data-target="#collapse{{ $item->id }}">
                                                            <i class="caret"></i>
                                                        </a>
                                                    </span>
                                                    <div class="collapse menu-add-box" id="collapse{{ $item->id }}" style="border: 1px solid #333;
                                                        border-radius: 8px;padding: 20px;margin-bottom: 10px;">
                                                        <div class="input-box">
                                                            <form method="post"
                                                                action="{{ route('admin.update-menuitem',$item->id) }}"
                                                                enctype="multipart/form-data">
                                                                {{ csrf_field() }}
                                                                <div class="form-group">
                                                                    <label>Link Name</label>
                                                                    <input type="text" name="name"
                                                                        value="@if (empty($item->name)) {{ $item->title }} @else {{ $item->name }} @endif"
                                                                        class="form-control">
                                                                </div>

                                                                <div class="form-group" id="is_mega_menu">
                                                                    <select class="form-control" name="is_mega_menu" required>

                                                                        <option value="0"
                                                                            @if (!$item->is_mega_menu) selected="selected" @endif>
                                                                            DropDown Menu</option>
                                                                        <option
                                                                            value="1"@if ($item->is_mega_menu && $item->is_mega_menu == 1) selected="selected" @endif>
                                                                            Mega Menu</option>
                                                                    </select>
                                                                </div>
                                                                {{-- <div class="form-group image_upload_field"
                                                                    style="display: none;">
                                                                    <label>Upload Image</label>
                                                                    <input type="file" name="banner_image"
                                                                        class="form-control">
                                                                </div> --}}
                                                                <div class="form-group image_upload_field" id="banner-field">
                                                                    <label class="required" for="banner">{{ trans('cruds.banner.fields.banner') }}</label>

                                                                    <div class="needsclick dropzone {{ $errors->has('banner') ? 'is-invalid' : '' }}" id="banner-dropzone">
                                                                        <div class="dz-preview dz-file-preview">
                                                                            <!-- Display the existing image, if available -->
                                                                            @if (($item->banner_image))
                                                                                <div class="dz-image">
                                                                                    <img data-dz-thumbnail src="{{ $item->banner_image['thumbnail'] }}" alt="Existing Image">
                                                                                </div>
                                                                            @endif

                                                                            <!-- Display any additional file details or controls here -->
                                                                            <div class="dz-details">
                                                                                <div class="dz-filename"><span data-dz-name></span></div>
                                                                                <!-- Additional details... -->
                                                                            </div>

                                                                            <!-- Display a remove button if an existing image is present -->
                                                                            @if (isset($item->banners))
                                                                                <div class="dz-remove">
                                                                                    <button data-dz-remove class="btn btn-danger delete-image">Remove</button>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    @if($errors->has('banner'))
                                                                        <div class="invalid-feedback">
                                                                            {{ $errors->first('banner') }}
                                                                        </div>
                                                                    @endif
                                                                    <span class="help-block">{{ trans('cruds.banner.fields.banner_helper') }}</span>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div
                                                                        class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                                                                        <input type="hidden" name="active" value="0">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="active" id="active" value="1"
                                                                            {{ (isset($item->active) && $item->active) == 1 ? 'checked' : '' }}>
                                                                        <label class="form-check-label"
                                                                            for="active">Active</label>
                                                                    </div>
                                                                    @if ($errors->has('active'))
                                                                        <div class="invalid-feedback">
                                                                            {{ $errors->first('active') }}
                                                                        </div>
                                                                    @endif
                                                                    <span class="help-block"></span>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="color_code">Text color</label><br>
                                                                    <input type="text"
                                                                        class="coloris instance2 form-control {{ $errors->has('color_code') ? 'is-invalid' : '' }}"
                                                                        value="@if (!empty($item->color_code)) {{ $item->color_code }} @endif"
                                                                        name="color_code" id="color_code">
                                                                    @if ($errors->has('color_code'))
                                                                        <div class="invalid-feedback">
                                                                            {{ $errors->first('color_code') }}
                                                                        </div>
                                                                    @endif
                                                                    <span class="help-block"></span>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div
                                                                        class="form-check {{ $errors->has('text_bold') ? 'is-invalid' : '' }}">
                                                                        <input type="hidden" name="text_bold"
                                                                            value="0">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="text_bold" id="text_bold" value="1"
                                                                            {{ (isset($item->text_bold) && $item->text_bold) == 1 ? 'checked' : '' }}>
                                                                        <label class="form-check-label" for="text_bold">Is
                                                                            Bold</label>
                                                                    </div>
                                                                    @if ($errors->has('text_bold'))
                                                                        <div class="invalid-feedback">
                                                                            {{ $errors->first('text_bold') }}
                                                                        </div>
                                                                    @endif
                                                                    <span class="help-block"></span>
                                                                </div>
                                                                @if ($item->type == 'custom')
                                                                    <div class="form-group">
                                                                        <label>URL</label>
                                                                        <input type="text" name="slug"
                                                                            value="{{ $item->slug }}" class="form-control">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <input type="checkbox" name="target" value="_blank"
                                                                            @if ($item->target == '_blank') checked @endif>
                                                                        Open in a new tab
                                                                    </div>
                                                                @endif
                                                                <div class="form-group">
                                                                    <button class="btn btn-sm btn-primary">Save</button>
                                                                    <a href="{{ url('admin/delete-menuitem') }}/{{ $item->id }}/{{ $key }}"
                                                                        class="btn btn-sm btn-danger">Delete</a>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <ul>
                                                        @if (isset($item->children))
                                                            @foreach ($item->children as $m)
                                                                @foreach ($m as $in => $data)

                                                                    <li data-id="{{ $data->id }}" class="menu-item">
                                                                        <span class="menu-item-bar"><i
                                                                                class="fa fa-arrows"></i>
                                                                            @if (empty($data->name))
                                                                                {{ $data->title }}
                                                                            @else
                                                                                {{ $data->name }}
                                                                            @endif <a
                                                                                href="#collapse{{ $data->id }}"
                                                                                class="pull-right" data-toggle="collapse"><i
                                                                                    class="caret"></i></a>
                                                                        </span>
                                                                        <div class="collapse"
                                                                            id="collapse{{ $data->id }}">
                                                                            <div class="input-box">
                                                                                <form method="post"
                                                                                    action="{{ route('admin.update-menuitem',$data->id) }}">
                                                                                    {{ csrf_field() }}
                                                                                    <div class="form-group">
                                                                                        <label>Link Name</label>
                                                                                        <input type="text" name="name"
                                                                                            value="@if (empty($data->name)) {{ $data->title }} @else {{ $data->name }} @endif"
                                                                                            class="form-control">
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <div
                                                                                            class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                                                                                            <input type="hidden"
                                                                                                name="active" value="0">
                                                                                            {{ $data->active }}
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox" name="active"
                                                                                                id="active" value="1"
                                                                                                {{ (isset($data->active) && $data->active) == 1 ? 'checked' : '' }}>
                                                                                            <label class="form-check-label"
                                                                                                for="active">Active</label>
                                                                                        </div>
                                                                                        @if ($errors->has('active'))
                                                                                            <div class="invalid-feedback">
                                                                                                {{ $errors->first('active') }}
                                                                                            </div>
                                                                                        @endif
                                                                                        <span class="help-block"></span>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label for="color_code">Text
                                                                                            color</label><br>
                                                                                        <input type="text"
                                                                                            class="coloris instance2 form-control {{ $errors->has('color_code') ? 'is-invalid' : '' }}"
                                                                                            value="@if (empty($data->color_code)) {{ $data->color_code }} @endif"
                                                                                            name="color_code" id="color_code">
                                                                                        @if ($errors->has('color_code'))
                                                                                            <div class="invalid-feedback">
                                                                                                {{ $errors->first('color_code') }}
                                                                                            </div>
                                                                                        @endif
                                                                                        <span class="help-block"></span>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <div
                                                                                            class="form-check {{ $errors->has('text_bold') ? 'is-invalid' : '' }}">
                                                                                            <input type="hidden"
                                                                                                name="text_bold"
                                                                                                value="0">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox"
                                                                                                name="text_bold"
                                                                                                id="text_bold" value="1"
                                                                                                {{ (isset($data->text_bold) && $data->text_bold) == 1 ? 'checked' : '' }}>
                                                                                            <label class="form-check-label"
                                                                                                for="text_bold">Is Bold</label>
                                                                                        </div>
                                                                                        @if ($errors->has('text_bold'))
                                                                                            <div class="invalid-feedback">
                                                                                                {{ $errors->first('text_bold') }}
                                                                                            </div>
                                                                                        @endif
                                                                                        <span
                                                                                            class="help-block">{{ trans('cruds.menu.fields.active_helper') }}</span>
                                                                                    </div>
                                                                                    @if ($data->type == 'custom')
                                                                                        <div class="form-group">
                                                                                            <label>URL</label>
                                                                                            <input type="text"
                                                                                                name="slug"
                                                                                                value="{{ $data->slug }}"
                                                                                                class="form-control">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <input type="checkbox"
                                                                                                name="target" value="_blank"
                                                                                                @if ($data->target == '_blank') checked @endif>
                                                                                            Open in a new tab
                                                                                        </div>
                                                                                    @endif
                                                                                    <div class="form-group">
                                                                                        <button
                                                                                            class="btn btn-sm btn-primary">Save</button>
                                                                                        <a href="{{ url('admin/delete-menuitem') }}/{{ $data->id }}/{{ $key }}/{{ $in }}"
                                                                                            class="btn btn-sm btn-danger">Delete</a>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                        <ul></ul>
                                                                    </li>
                                                                    <ul>
                                                                    @if (isset($data->children))
                                                                        @foreach ($data->children as $m2)
                                                                            @foreach ($m2 as $in2 => $data2)

                                                                                <li data-id="{{ $data2->id }}" class="menu-item">
                                                                                    <span class="menu-item-bar"><i
                                                                                            class="fa fa-arrows"></i>
                                                                                        @if (empty($data2->name))
                                                                                            {{ $data2->title }}
                                                                                        @else
                                                                                            {{ $data2->name }}
                                                                                        @endif <a
                                                                                            href="#collapse_sub{{ $data2->id }}"
                                                                                            class="pull-right" data-toggle="collapse"><i
                                                                                                class="caret"></i></a>
                                                                                    </span>
                                                                                    <div class="collapse"
                                                                                        id="collapse_sub{{ $data2->id }}">
                                                                                        <div class="input-box">
                                                                                            <form method="post"
                                                                                                action="{{ route('admin.update-menuitem',$data2->id) }}">
                                                                                                {{ csrf_field() }}
                                                                                                <div class="form-group">
                                                                                                    <label>Link Name</label>
                                                                                                    <input type="text" name="name"
                                                                                                        value="@if (empty($data2->name)) {{ $data2->title }} @else {{ $data2->name }} @endif"
                                                                                                        class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group">
                                                                                                    <div
                                                                                                        class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                                                                                                        <input type="hidden"
                                                                                                            name="active" value="0">
                                                                                                        {{ $data2->active }}
                                                                                                        <input class="form-check-input"
                                                                                                            type="checkbox" name="active"
                                                                                                            id="active" value="1"
                                                                                                            {{ (isset($data2->active) && $data2->active) == 1 ? 'checked' : '' }}>
                                                                                                        <label class="form-check-label"
                                                                                                            for="active">Active</label>
                                                                                                    </div>
                                                                                                    @if ($errors->has('active'))
                                                                                                        <div class="invalid-feedback">
                                                                                                            {{ $errors->first('active') }}
                                                                                                        </div>
                                                                                                    @endif
                                                                                                    <span class="help-block"></span>
                                                                                                </div>
                                                                                                <div class="form-group">
                                                                                                    <label for="color_code">Text
                                                                                                        color</label><br>
                                                                                                    <input type="text"
                                                                                                        class="coloris instance2 form-control {{ $errors->has('color_code') ? 'is-invalid' : '' }}"
                                                                                                        value="@if (empty($data2->color_code)) {{ $data2->color_code }} @endif"
                                                                                                        name="color_code" id="color_code">
                                                                                                    @if ($errors->has('color_code'))
                                                                                                        <div class="invalid-feedback">
                                                                                                            {{ $errors->first('color_code') }}
                                                                                                        </div>
                                                                                                    @endif
                                                                                                    <span class="help-block"></span>
                                                                                                </div>
                                                                                                <div class="form-group">
                                                                                                    <div
                                                                                                        class="form-check {{ $errors->has('text_bold') ? 'is-invalid' : '' }}">
                                                                                                        <input type="hidden"
                                                                                                            name="text_bold"
                                                                                                            value="0">
                                                                                                        <input class="form-check-input"
                                                                                                            type="checkbox"
                                                                                                            name="text_bold"
                                                                                                            id="text_bold" value="1"
                                                                                                            {{ (isset($data2->text_bold) && $data2->text_bold) == 1 ? 'checked' : '' }}>
                                                                                                        <label class="form-check-label"
                                                                                                            for="text_bold">Is Bold</label>
                                                                                                    </div>
                                                                                                    @if ($errors->has('text_bold'))
                                                                                                        <div class="invalid-feedback">
                                                                                                            {{ $errors->first('text_bold') }}
                                                                                                        </div>
                                                                                                    @endif
                                                                                                    <span
                                                                                                        class="help-block">{{ trans('cruds.menu.fields.active_helper') }}</span>
                                                                                                </div>
                                                                                                @if ($data2->type == 'custom')
                                                                                                    <div class="form-group">
                                                                                                        <label>URL</label>
                                                                                                        <input type="text"
                                                                                                            name="slug"
                                                                                                            value="{{ $data2->slug }}"
                                                                                                            class="form-control">
                                                                                                    </div>
                                                                                                    <div class="form-group">
                                                                                                        <input type="checkbox"
                                                                                                            name="target" value="_blank"
                                                                                                            @if ($data2->target == '_blank') checked @endif>
                                                                                                        Open in a new tab
                                                                                                    </div>
                                                                                                @endif
                                                                                                <div class="form-group">
                                                                                                    <button
                                                                                                        class="btn btn-sm btn-primary">Save</button>
                                                                                                        <a href="{{ url('admin/delete-menuitem/' . $data2->id . '/' . $key . '/' .$in.'/'.$in2) }}" class="btn btn-sm btn-danger">Delete</a>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                    <ul></ul>
                                                                                </li>
                                                                            @endforeach
                                                                        @endforeach
                                                                    @endif
                                                                    </ul>
                                                                @endforeach
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                @endif
                            </div>
                            @if ($desiredMenu != '')
                                {{-- <div class="form-group menulocation">
            <label><b>Menu Location</b></label>
            <p><label><input type="radio" name="location" value="1" @if ($desiredMenu->location == 1) checked @endif> Header</label></p>
            <p><label><input type="radio" name="location" value="2" @if ($desiredMenu->location == 2) checked @endif> Main Navigation</label></p>
            </div> --}}
                                <div class="text-right mb-4">
                                    <button class="btn btn-sm btn-primary btn-lg" id="saveMenu">Save Menu</button>
                                </div>
                                {{-- <p><a href="{{ url('delete-menu') }}/{{ $desiredMenu->id }}">Delete Menu</a></p> --}}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
        <div id="serialize_output" style="display: none;">@if($desiredMenu){{$desiredMenu->content}}@endif</div>
@endsection
@section('scripts')
@parent
<script>
    Dropzone.options.bannerDropzone = {
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
      $('form').find('input[name="banner"]').remove()
      $('form').append('<input type="hidden" name="banner" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="banner"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($menu) && $menu->banners)
      var file = {!! json_encode($menu->banners) !!}
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

        return _results
    }
}

</script>
    <script src="{{ url('js/sortable.js') }}"></script>
{{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.1/Sortable.js"></script> --}}
    <script>
        $('#select-all-categories').click(function(event) {
            if (this.checked) {
                $('#categories-list :checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('#categories-list :checkbox').each(function() {
                    this.checked = false;
                });
            }
        });
    </script>
    <script>
        // jQuery.noConflict();
        @if ($desiredMenu)

            $('#add-categories').click(function() {
                var menuid = {{ $desiredMenu->id }};
                var n = $('input[name="select-category[]"]:checked').length;
                var array = $('input[name="select-category[]"]:checked');
                var ids = [];

                for (var i = 0; i < n; i++) {
                    ids[i] = array.eq(i).val();
                }

                if (ids.length === 0) {
                    return false;
                }

                $.ajax({
                    type: "get", // Assuming you want to send data through POST method
                    data: {
                        menuid: menuid,
                        ids: ids
                    },
                    url: "{{ route('admin.add-categories-to-menu') }}",
                    success: function(res) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            $("#add-custom-link").click(function() {
                var menuid = {{ $desiredMenu->id }};
                var url = $('#url').val();
                var link = $('#linktext').val();

                if (url.length > 0 && link.length > 0) {
                    $.ajax({
                        type: "get", // Assuming you want to send data through POST method
                        data: {
                            menuid: menuid,
                            url: url,
                            link: link
                        },
                        url: "{{ route('admin.add-custom-link') }}",
                        success: function(res) {
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });

            var group = $("#menuitems").sortable({
                group: 'serialization',
                onDrop: function($item, container, _super) {
                    var data = group.sortable("serialize").get();
                    var jsonString = JSON.stringify(data, null, ' ');
                    $('#serialize_output').text(jsonString);
                    _super($item, container);
                }
            });

            $('#saveMenu').click(function() {
                var menuid = {{ $desiredMenu->id }};
                var location = $('input[name="location"]:checked').val();
                var newText = $("#serialize_output").text();
                console.log(newText);
                var data = JSON.parse($("#serialize_output").text());
                var _token = "{{ csrf_token() }}";
                $.ajax({
                    type: "POST", // Assuming you want to send data through POST method
                    data: {
                        menuid: menuid,
                        data: data,
                        location: location
                    },
                    headers: {
                        'x-csrf-token': _token
                    },
                    url: "{{ route('admin.update.mega-menu') }}",
                    success: function(res) {
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        @endif
    </script>
    <script>

        $(document).ready(function() {
            // Function to toggle image upload field based on is_mega_menu selection
            function toggleImageUpload(selectElement) {
                var isMegaMenu = $(selectElement).val();
                var imageUploadField = $(selectElement).closest('li').find('.image_upload_field');

                if (isMegaMenu == 1) {
                    // Show image upload field
                    imageUploadField.show();
                } else {
                    // Hide image upload field
                    imageUploadField.hide();
                }
            }

            // Initial state check on page load
            $('select[name="is_mega_menu"]').each(function() {
                toggleImageUpload(this);
            });

            // Event listener for is_mega_menu change
            $(document).on('change', 'select[name="is_mega_menu"]', function() {
                toggleImageUpload(this);
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            // Capture click event on panel heading
            $('a.pull-right').on('click', function (e) {
                e.preventDefault();

                // Find the panel ID from the clicked element
                var panelId = $(this).attr('href').substring(1); // Remove the '#' character

                // Close the corresponding panel
                if ($('#' + panelId).hasClass('show')) {
                    // Remove the 'show' class to hide the panel
                    $('#' + panelId).removeClass('show');
                } else {
                    // If it doesn't have the 'show' class, add it to show the panel
                    $('#' + panelId).addClass('show');
                }


            });

        });
    </script>
@endsection

