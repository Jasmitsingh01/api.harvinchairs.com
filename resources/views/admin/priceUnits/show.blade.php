@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.priceUnit.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.price-units.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.priceUnit.fields.id') }}
                        </th>
                        <td>
                            {{ $priceUnit->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.priceUnit.fields.title') }}
                        </th>
                        <td>
                            {{ $priceUnit->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.priceUnit.fields.is_active') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $priceUnit->is_active ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.priceUnit.fields.description') }}
                        </th>
                        <td>
                            {{ $priceUnit->description }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.price-units.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection