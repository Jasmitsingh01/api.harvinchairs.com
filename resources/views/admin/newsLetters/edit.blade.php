@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.newsLetter.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.news-letters.update", [$newsLetter->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="email">{{ trans('cruds.newsLetter.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $newsLetter->email) }}">
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.newsLetter.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ip_registration_newsletter">{{ trans('cruds.newsLetter.fields.ip_registration_newsletter') }}</label>
                <input class="form-control {{ $errors->has('ip_registration_newsletter') ? 'is-invalid' : '' }}" type="text" name="ip_registration_newsletter" id="ip_registration_newsletter" value="{{ old('ip_registration_newsletter', $newsLetter->ip_registration_newsletter) }}">
                @if($errors->has('ip_registration_newsletter'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ip_registration_newsletter') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.newsLetter.fields.ip_registration_newsletter_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="http_referer">{{ trans('cruds.newsLetter.fields.http_referer') }}</label>
                <input class="form-control {{ $errors->has('http_referer') ? 'is-invalid' : '' }}" type="text" name="http_referer" id="http_referer" value="{{ old('http_referer', $newsLetter->http_referer) }}">
                @if($errors->has('http_referer'))
                    <div class="invalid-feedback">
                        {{ $errors->first('http_referer') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.newsLetter.fields.http_referer_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_active') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $newsLetter->is_active || old('is_active', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">{{ trans('cruds.newsLetter.fields.is_active') }}</label>
                </div>
                @if($errors->has('is_active'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_active') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.newsLetter.fields.is_active_helper') }}</span>
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
