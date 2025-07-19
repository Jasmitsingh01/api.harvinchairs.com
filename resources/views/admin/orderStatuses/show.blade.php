@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.orderStatus.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.order-statuses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.orderStatus.fields.id') }}
                        </th>
                        <td>
                            {{ $orderStatus->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderStatus.fields.name') }}
                        </th>
                        <td>
                            {{ $orderStatus->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderStatus.fields.template') }}
                        </th>
                        <td>
                            {{ $orderStatus->template }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderStatus.fields.module_name') }}
                        </th>
                        <td>
                            {{ $orderStatus->module_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderStatus.fields.online_payment') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $orderStatus->online_payment ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderStatus.fields.invoice') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $orderStatus->invoice ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderStatus.fields.send_email') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $orderStatus->send_email ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderStatus.fields.unremovable') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $orderStatus->unremovable ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderStatus.fields.hidden') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $orderStatus->hidden ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderStatus.fields.logable') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $orderStatus->logable ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderStatus.fields.delivery') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $orderStatus->delivery ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderStatus.fields.shipped') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $orderStatus->shipped ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderStatus.fields.paid') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $orderStatus->paid ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderStatus.fields.pdf_invoice') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $orderStatus->pdf_invoice ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderStatus.fields.pdf_delivery') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $orderStatus->pdf_delivery ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.order-statuses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection