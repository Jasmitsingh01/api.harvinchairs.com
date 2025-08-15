@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.creativeCutsEnquire.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.creative-cuts-enquires.update", [$creativeCutsEnquire->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="name">{{ trans('cruds.creativeCutsEnquire.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $creativeCutsEnquire->name) }}">
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.creativeCutsEnquire.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="email">{{ trans('cruds.creativeCutsEnquire.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" name="email" id="email" value="{{ old('email', $creativeCutsEnquire->email) }}">
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.creativeCutsEnquire.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.creativeCutsEnquire.fields.description') }}</label>
                <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description', $creativeCutsEnquire->description) }}">
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.creativeCutsEnquire.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="upload_file">{{ trans('cruds.creativeCutsEnquire.fields.upload_file') }}</label>
                <input class="form-control {{ $errors->has('upload_file') ? 'is-invalid' : '' }}" type="text" name="upload_file" id="upload_file" value="{{ old('upload_file', $creativeCutsEnquire->upload_file) }}">
                @if($errors->has('upload_file'))
                    <div class="invalid-feedback">
                        {{ $errors->first('upload_file') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.creativeCutsEnquire.fields.upload_file_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_name">{{ trans('cruds.creativeCutsEnquire.fields.product_name') }}</label>
                <input class="form-control {{ $errors->has('product_name') ? 'is-invalid' : '' }}" type="text" name="product_name" id="product_name" value="{{ old('product_name', $creativeCutsEnquire->product_name) }}">
                @if($errors->has('product_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.creativeCutsEnquire.fields.product_name_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_active') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $creativeCutsEnquire->is_active || old('is_active', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">{{ trans('cruds.creativeCutsEnquire.fields.is_active') }}</label>
                </div>
                @if($errors->has('is_active'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_active') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.creativeCutsEnquire.fields.is_active_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('notification') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="notification" value="0">
                    <input class="form-check-input" type="checkbox" name="notification" id="notification" value="1" {{ $creativeCutsEnquire->notification || old('notification', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="notification">{{ trans('cruds.creativeCutsEnquire.fields.notification') }}</label>
                </div>
                @if($errors->has('notification'))
                    <div class="invalid-feedback">
                        {{ $errors->first('notification') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.creativeCutsEnquire.fields.notification_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection