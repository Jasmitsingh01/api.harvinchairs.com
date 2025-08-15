@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.emailTemplate.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.email-templates.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.emailTemplate.fields.id') }}
                        </th>
                        <td>
                            {{ $emailTemplate->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.emailTemplate.fields.template_code') }}
                        </th>
                        <td>
                            {{ $emailTemplate->template_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.emailTemplate.fields.template_description') }}
                        </th>
                        <td>
                            {{ $emailTemplate->template_description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.emailTemplate.fields.subject') }}
                        </th>
                        <td>
                            {{ $emailTemplate->subject }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.emailTemplate.fields.email_file_name') }}
                        </th>
                        <td>
                            {{ $emailTemplate->email_file_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.emailTemplate.fields.status') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $emailTemplate->status ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.email-templates.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection