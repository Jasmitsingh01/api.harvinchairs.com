@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.carrier.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.carriers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.id') }}
                        </th>
                        <td>
                            {{ $carrier->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.reference') }}
                        </th>
                        <td>
                            {{ $carrier->reference_id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.name') }}
                        </th>
                        <td>
                            {{ $carrier->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.url') }}
                        </th>
                        <td>
                            {{ $carrier->url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.active') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $carrier->active ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.shipping_handling') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $carrier->shipping_handling ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.range_behavior') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $carrier->range_behavior ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.is_module') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $carrier->is_module ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.is_free') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $carrier->is_free ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.shipping_external') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $carrier->shipping_external ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.need_range') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $carrier->need_range ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.external_module_name') }}
                        </th>
                        <td>
                            {{ $carrier->external_module_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.shipping_method') }}
                        </th>
                        <td>
                            {{ $carrier->shipping_method }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.position') }}
                        </th>
                        <td>
                            {{ $carrier->position }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.max_width') }}
                        </th>
                        <td>
                            {{ $carrier->max_width }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.max_height') }}
                        </th>
                        <td>
                            {{ $carrier->max_height }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.max_depth') }}
                        </th>
                        <td>
                            {{ $carrier->max_depth }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.max_weight') }}
                        </th>
                        <td>
                            {{ $carrier->max_weight }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.carrier.fields.grade') }}
                        </th>
                        <td>
                            {{ $carrier->grade }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.carriers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
