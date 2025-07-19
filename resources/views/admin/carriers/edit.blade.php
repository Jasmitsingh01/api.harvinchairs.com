@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.carrier.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.carriers.update", [$carrier->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="reference">{{ trans('cruds.carrier.fields.reference') }}</label>
                <input class="form-control {{ $errors->has('reference_id') ? 'is-invalid' : '' }}" type="number" name="reference_id" id="reference_id" value="{{ old('reference_id', $carrier->reference_id) }}" step="1">
                @if($errors->has('reference_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reference_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.reference_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="name">{{ trans('cruds.carrier.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $carrier->name) }}">
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="url">{{ trans('cruds.carrier.fields.url') }}</label>
                <input class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" type="text" name="url" id="url" value="{{ old('url', $carrier->url) }}">
                @if($errors->has('url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.url_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="active" value="0">
                    <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ $carrier->active || old('active', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">{{ trans('cruds.carrier.fields.active') }}</label>
                </div>
                @if($errors->has('active'))
                    <div class="invalid-feedback">
                        {{ $errors->first('active') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.active_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('shipping_handling') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="shipping_handling" value="0">
                    <input class="form-check-input" type="checkbox" name="shipping_handling" id="shipping_handling" value="1" {{ $carrier->shipping_handling || old('shipping_handling', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="shipping_handling">{{ trans('cruds.carrier.fields.shipping_handling') }}</label>
                </div>
                @if($errors->has('shipping_handling'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipping_handling') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.shipping_handling_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('range_behavior') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="range_behavior" value="0">
                    <input class="form-check-input" type="checkbox" name="range_behavior" id="range_behavior" value="1" {{ $carrier->range_behavior || old('range_behavior', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="range_behavior">{{ trans('cruds.carrier.fields.range_behavior') }}</label>
                </div>
                @if($errors->has('range_behavior'))
                    <div class="invalid-feedback">
                        {{ $errors->first('range_behavior') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.range_behavior_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_module') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_module" value="0">
                    <input class="form-check-input" type="checkbox" name="is_module" id="is_module" value="1" {{ $carrier->is_module || old('is_module', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_module">{{ trans('cruds.carrier.fields.is_module') }}</label>
                </div>
                @if($errors->has('is_module'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_module') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.is_module_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_free') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_free" value="0">
                    <input class="form-check-input" type="checkbox" name="is_free" id="is_free" value="1" {{ $carrier->is_free || old('is_free', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_free">{{ trans('cruds.carrier.fields.is_free') }}</label>
                </div>
                @if($errors->has('is_free'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_free') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.is_free_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('shipping_external') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="shipping_external" value="0">
                    <input class="form-check-input" type="checkbox" name="shipping_external" id="shipping_external" value="1" {{ $carrier->shipping_external || old('shipping_external', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="shipping_external">{{ trans('cruds.carrier.fields.shipping_external') }}</label>
                </div>
                @if($errors->has('shipping_external'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipping_external') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.shipping_external_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('need_range') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="need_range" value="0">
                    <input class="form-check-input" type="checkbox" name="need_range" id="need_range" value="1" {{ $carrier->need_range || old('need_range', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="need_range">{{ trans('cruds.carrier.fields.need_range') }}</label>
                </div>
                @if($errors->has('need_range'))
                    <div class="invalid-feedback">
                        {{ $errors->first('need_range') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.need_range_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="external_module_name">{{ trans('cruds.carrier.fields.external_module_name') }}</label>
                <input class="form-control {{ $errors->has('external_module_name') ? 'is-invalid' : '' }}" type="text" name="external_module_name" id="external_module_name" value="{{ old('external_module_name', $carrier->external_module_name) }}">
                @if($errors->has('external_module_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('external_module_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.external_module_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="shipping_method">{{ trans('cruds.carrier.fields.shipping_method') }}</label>
                <input class="form-control {{ $errors->has('shipping_method') ? 'is-invalid' : '' }}" type="number" name="shipping_method" id="shipping_method" value="{{ old('shipping_method', $carrier->shipping_method) }}" step="1">
                @if($errors->has('shipping_method'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipping_method') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.shipping_method_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="position">{{ trans('cruds.carrier.fields.position') }}</label>
                <input class="form-control {{ $errors->has('position') ? 'is-invalid' : '' }}" type="number" name="position" id="position" value="{{ old('position', $carrier->position) }}" step="1">
                @if($errors->has('position'))
                    <div class="invalid-feedback">
                        {{ $errors->first('position') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.position_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="max_width">{{ trans('cruds.carrier.fields.max_width') }}</label>
                <input class="form-control {{ $errors->has('max_width') ? 'is-invalid' : '' }}" type="number" name="max_width" id="max_width" value="{{ old('max_width', $carrier->max_width) }}" step="0.01">
                @if($errors->has('max_width'))
                    <div class="invalid-feedback">
                        {{ $errors->first('max_width') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.max_width_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="max_height">{{ trans('cruds.carrier.fields.max_height') }}</label>
                <input class="form-control {{ $errors->has('max_height') ? 'is-invalid' : '' }}" type="number" name="max_height" id="max_height" value="{{ old('max_height', $carrier->max_height) }}" step="0.01">
                @if($errors->has('max_height'))
                    <div class="invalid-feedback">
                        {{ $errors->first('max_height') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.max_height_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="max_depth">{{ trans('cruds.carrier.fields.max_depth') }}</label>
                <input class="form-control {{ $errors->has('max_depth') ? 'is-invalid' : '' }}" type="number" name="max_depth" id="max_depth" value="{{ old('max_depth', $carrier->max_depth) }}" step="0.01">
                @if($errors->has('max_depth'))
                    <div class="invalid-feedback">
                        {{ $errors->first('max_depth') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.max_depth_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="max_weight">{{ trans('cruds.carrier.fields.max_weight') }}</label>
                <input class="form-control {{ $errors->has('max_weight') ? 'is-invalid' : '' }}" type="number" name="max_weight" id="max_weight" value="{{ old('max_weight', $carrier->max_weight) }}" step="0.01">
                @if($errors->has('max_weight'))
                    <div class="invalid-feedback">
                        {{ $errors->first('max_weight') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.max_weight_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="grade">{{ trans('cruds.carrier.fields.grade') }}</label>
                <input class="form-control {{ $errors->has('grade') ? 'is-invalid' : '' }}" type="number" name="grade" id="grade" value="{{ old('grade', $carrier->grade) }}" step="1">
                @if($errors->has('grade'))
                    <div class="invalid-feedback">
                        {{ $errors->first('grade') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.carrier.fields.grade_helper') }}</span>
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
