@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.zone.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.zones.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.zone.fields.id') }}
                        </th>
                        <td>
                            {{ $zone->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.zone.fields.name') }}
                        </th>
                        <td>
                            {{ $zone->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.zone.fields.ship_amt') }}
                        </th>
                        <td>
                            {{ $zone->Ship_Amt }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.zone.fields.min_ship_amt') }}
                        </th>
                        <td>
                            {{ $zone->MinShip_Amt }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.zone.fields.active') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $zone->active ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.zones.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
