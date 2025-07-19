@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.contactUs.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.contact-uss.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.contactUs.fields.id') }}
                        </th>
                        <td>
                            {{ $contactUs->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contactUs.fields.name') }}
                        </th>
                        <td>
                            {{ $contactUs->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contactUs.fields.email') }}
                        </th>
                        <td>
                            {{ $contactUs->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contactUs.fields.subject') }}
                        </th>
                        <td>
                            {{ $contactUs->subject }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contactUs.fields.message') }}
                        </th>
                        <td>
                            {{ $contactUs->message }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contactUs.fields.attach_file') }}
                        </th>
                        <td>
                            @if($contactUs->attach_file)
                                <a href="{{ $contactUs->attach_file->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contactUs.fields.notification') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $contactUs->notification ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.contact-uss.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
