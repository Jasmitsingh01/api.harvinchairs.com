@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.zipcode.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.zipcodes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.zipcode.fields.id') }}
                        </th>
                        <td>
                            {{ $zipcode->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.zipcode.fields.zip_code') }}
                        </th>
                        <td>
                            {{ $zipcode->zip_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.zipcode.fields.country') }}
                        </th>
                        <td>
                            {{ $zipcode->country->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.zipcode.fields.amount') }}
                        </th>
                        <td>
                            {{ $zipcode->amount }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.zipcodes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection