@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.creativeCutsEnquire.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.creative-cuts-enquires.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.creativeCutsEnquire.fields.id') }}
                        </th>
                        <td>
                            {{ $creativeCutsEnquire->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.creativeCutsEnquire.fields.name') }}
                        </th>
                        <td>
                            {{ $creativeCutsEnquire->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.creativeCutsEnquire.fields.email') }}
                        </th>
                        <td>
                            {{ $creativeCutsEnquire->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.creativeCutsEnquire.fields.description') }}
                        </th>
                        <td>
                            {{ $creativeCutsEnquire->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.creativeCutsEnquire.fields.upload_file') }}
                        </th>
                        <td>
                            {{ $creativeCutsEnquire->upload_file }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.creativeCutsEnquire.fields.product_name') }}
                        </th>
                        <td>
                            {{ $creativeCutsEnquire->product_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.creativeCutsEnquire.fields.is_active') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $creativeCutsEnquire->is_active ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.creativeCutsEnquire.fields.notification') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $creativeCutsEnquire->notification ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.creative-cuts-enquires.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection