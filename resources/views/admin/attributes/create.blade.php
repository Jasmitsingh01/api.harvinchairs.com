@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.attribute.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.attributes.store") }}" enctype="multipart/form-data">
            @csrf
            {{-- <div class="form-group">
                <label for="slug">{{ trans('cruds.attribute.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', '') }}">
                @if($errors->has('slug'))
                    <div class="invalid-feedback">
                        {{ $errors->first('slug') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attribute.fields.slug_helper') }}</span>
            </div> --}}
            {{-- <div class="form-group">
                <label for="language">{{ trans('cruds.attribute.fields.language') }}</label>
                <input class="form-control {{ $errors->has('language') ? 'is-invalid' : '' }}" type="text" name="language" id="language" value="{{ old('language', 'en') }}">
                @if($errors->has('language'))
                    <div class="invalid-feedback">
                        {{ $errors->first('language') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attribute.fields.language_helper') }}</span>
            </div> --}}
            {{-- <div class="form-group">
                <label for="position">{{ trans('cruds.attribute.fields.position') }}</label>
                <input class="form-control {{ $errors->has('position') ? 'is-invalid' : '' }}" type="number" name="position" id="position" value="{{ old('position', '') }}" step="1">
                @if($errors->has('position'))
                    <div class="invalid-feedback">
                        {{ $errors->first('position') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attribute.fields.position_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.attribute.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attribute.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="public_name">{{ trans('cruds.attribute.fields.public_name') }}</label>
                <input class="form-control {{ $errors->has('public_name') ? 'is-invalid' : '' }}" type="text" name="public_name" id="public_name" value="{{ old('public_name', '') }}">
                @if($errors->has('public_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('public_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attribute.fields.public_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="group_type">{{ trans('cruds.attribute.fields.group_type') }}</label>
                <select class="form-control select2 {{ $errors->has('group_type') ? 'is-invalid' : '' }}"  name="group_type" id="group_type" >
                    <option  value="select" {{ old('group_type') == 'select' ? 'selected' : '' }}>Dropdown</option>
                    <option value="radio" {{ old('group_type')  == 'radio' ? 'selected' : '' }}>Radio</option>
                    <option value="image_radio" {{ old('group_type')  == 'image_radio' ? 'selected' : '' }}>Image Radio</option>
                </select>
                @if($errors->has('group_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('group_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attribute.fields.group_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="shop_id">{{ trans('cruds.attribute.fields.shop') }}</label>
                <select class="form-control select2 {{ $errors->has('shop') ? 'is-invalid' : '' }}" name="shop_id" id="shop_id">
                    @foreach($shops as $id => $entry)
                        <option value="{{ $id }}" {{ old('shop_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('shop'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shop') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attribute.fields.shop_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_fabric') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_fabric" value="0">
                    <input class="form-check-input" type="checkbox" name="is_fabric" id="is_fabric" value="1" {{ old('is_fabric', request('is_fabric', 0)) == 1 ? 'checked' : '' }} onchange="toggleColorCodeField()">
                    <label class="form-check-label" for="is_fabric">{{ trans('cruds.attribute.fields.is_fabric') }}</label>
                </div>
                @if($errors->has('is_fabric'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_fabric') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attribute.fields.is_fabric_helper') }}</span>
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
