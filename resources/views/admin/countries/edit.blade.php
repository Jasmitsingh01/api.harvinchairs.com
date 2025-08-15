@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.country.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.countries.update", [$country->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="shortname">{{ trans('cruds.country.fields.shortname') }}</label>
                <input class="form-control {{ $errors->has('shortname') ? 'is-invalid' : '' }}" type="text" name="shortname" id="shortname" value="{{ old('shortname', $country->shortname) }}">
                @if($errors->has('shortname'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shortname') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.shortname_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="name">{{ trans('cruds.country.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $country->name) }}">
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="zone_id">{{ trans('cruds.country.fields.zone') }}</label>
                <select class="form-control select2 {{ $errors->has('zone') ? 'is-invalid' : '' }}" name="zone_id" id="zone_id">
                    @foreach($zones as $id => $entry)
                        <option value="{{ $id }}" {{ (old('zone_id') ? old('zone_id') : $country->zone->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('zone'))
                    <div class="invalid-feedback">
                        {{ $errors->first('zone') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.zone_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="phonecode">{{ trans('cruds.country.fields.phonecode') }}</label>
                <input class="form-control {{ $errors->has('phonecode') ? 'is-invalid' : '' }}" type="number" name="phonecode" id="phonecode" value="{{ old('phonecode', $country->phonecode) }}" step="1">
                @if($errors->has('phonecode'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phonecode') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.phonecode_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="zip_code_format">{{ trans('cruds.country.fields.zip_code_format') }}</label>
                <input class="form-control {{ $errors->has('zip_code_format') ? 'is-invalid' : '' }}" type="text" name="zip_code_format" id="zip_code_format" value="{{ old('zip_code_format', $country->zip_code_format) }}">
                @if($errors->has('zip_code_format'))
                    <div class="invalid-feedback">
                        {{ $errors->first('zip_code_format') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.zip_code_format_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('need_zip_code') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="need_zip_code" value="0">
                    <input class="form-check-input" type="checkbox" name="need_zip_code" id="need_zip_code" value="1" {{ $country->need_zip_code || old('need_zip_code', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="need_zip_code">{{ trans('cruds.country.fields.need_zip_code') }}</label>
                </div>
                @if($errors->has('need_zip_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('need_zip_code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.need_zip_code_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('need_identification_number') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="need_identification_number" value="0">
                    <input class="form-check-input" type="checkbox" name="need_identification_number" id="need_identification_number" value="1" {{ $country->need_identification_number || old('need_identification_number', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="need_identification_number">{{ trans('cruds.country.fields.need_identification_number') }}</label>
                </div>
                @if($errors->has('need_identification_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('need_identification_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.need_identification_number_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('contains_states') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="contains_states" value="0">
                    <input class="form-check-input" type="checkbox" name="contains_states" id="contains_states" value="1" {{ $country->contains_states || old('contains_states', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="contains_states">{{ trans('cruds.country.fields.contains_states') }}</label>
                </div>
                @if($errors->has('contains_states'))
                    <div class="invalid-feedback">
                        {{ $errors->first('contains_states') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.contains_states_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="active" value="0">
                    <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ $country->active || old('active', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">{{ trans('cruds.country.fields.active') }}</label>
                </div>
                @if($errors->has('active'))
                    <div class="invalid-feedback">
                        {{ $errors->first('active') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.active_helper') }}</span>
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
