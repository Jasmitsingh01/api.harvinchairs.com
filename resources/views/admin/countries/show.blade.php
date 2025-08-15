@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.country.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.countries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.country.fields.id') }}
                        </th>
                        <td>
                            {{ $country->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.country.fields.shortname') }}
                        </th>
                        <td>
                            {{ $country->shortname }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.country.fields.name') }}
                        </th>
                        <td>
                            {{ $country->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.country.fields.zone') }}
                        </th>
                        <td>
                            {{ $country->zone->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.country.fields.phonecode') }}
                        </th>
                        <td>
                            {{ $country->phonecode }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.country.fields.zip_code_format') }}
                        </th>
                        <td>
                            {{ $country->zip_code_format }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.country.fields.need_zip_code') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $country->need_zip_code ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.country.fields.need_identification_number') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $country->need_identification_number ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.country.fields.contains_states') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $country->contains_states ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.country.fields.active') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $country->active ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.countries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection