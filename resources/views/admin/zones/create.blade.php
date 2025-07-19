@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.zone.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.zones.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">{{ trans('cruds.zone.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}">
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.zone.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ship_amt">{{ trans('cruds.zone.fields.ship_amt') }}</label>
                <input class="form-control {{ $errors->has('Ship_Amt') ? 'is-invalid' : '' }}" type="number" name="Ship_Amt" id="Ship_Amt" value="{{ old('Ship_Amt', '') }}" step="0.01">
                @if($errors->has('Ship_Amt'))
                    <div class="invalid-feedback">
                        {{ $errors->first('Ship_Amt') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.zone.fields.ship_amt_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="min_ship_amt">{{ trans('cruds.zone.fields.min_ship_amt') }}</label>
                <input class="form-control {{ $errors->has('MinShip_Amt') ? 'is-invalid' : '' }}" type="number" name="MinShip_Amt" id="MinShip_Amt" value="{{ old('MinShip_Amt', '') }}" step="0.01">
                @if($errors->has('MinShip_Amt'))
                    <div class="invalid-feedback">
                        {{ $errors->first('MinShip_Amts') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.zone.fields.min_ship_amt_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="active" value="0">
                    <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', 0) == 1 || old('active') === null ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">{{ trans('cruds.zone.fields.active') }}</label>
                </div>
                @if($errors->has('active'))
                    <div class="invalid-feedback">
                        {{ $errors->first('active') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.zone.fields.active_helper') }}</span>
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
