@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.emailTemplate.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.email-templates.update", [$emailTemplate->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="template_code">{{ trans('cruds.emailTemplate.fields.template_code') }}</label>
                <input class="form-control {{ $errors->has('template_code') ? 'is-invalid' : '' }}" type="text" name="template_code" id="template_code" value="{{ old('template_code', $emailTemplate->template_code) }}">
                @if($errors->has('template_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('template_code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.emailTemplate.fields.template_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="template_description">{{ trans('cruds.emailTemplate.fields.template_description') }}</label>
                <textarea class="form-control {{ $errors->has('template_description') ? 'is-invalid' : '' }}" name="template_description" id="template_description">{{ old('template_description', $emailTemplate->template_description) }}</textarea>
                @if($errors->has('template_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('template_description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.emailTemplate.fields.template_description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="subject">{{ trans('cruds.emailTemplate.fields.subject') }}</label>
                <input class="form-control {{ $errors->has('subject') ? 'is-invalid' : '' }}" type="text" name="subject" id="subject" value="{{ old('subject', $emailTemplate->subject) }}">
                @if($errors->has('subject'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subject') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.emailTemplate.fields.subject_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="email_file_name">{{ trans('cruds.emailTemplate.fields.email_file_name') }}</label>
                <input class="form-control {{ $errors->has('email_file_name') ? 'is-invalid' : '' }}" type="text" name="email_file_name" id="email_file_name" value="{{ old('email_file_name', $emailTemplate->email_file_name) }}">
                @if($errors->has('email_file_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email_file_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.emailTemplate.fields.email_file_name_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="status" value="0">
                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ $emailTemplate->status || old('status', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="status">{{ trans('cruds.emailTemplate.fields.status') }}</label>
                </div>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.emailTemplate.fields.status_helper') }}</span>
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