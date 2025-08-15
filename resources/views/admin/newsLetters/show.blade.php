@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.newsLetter.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.news-letters.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.newsLetter.fields.id') }}
                        </th>
                        <td>
                            {{ $newsLetter->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newsLetter.fields.email') }}
                        </th>
                        <td>
                            {{ $newsLetter->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newsLetter.fields.ip_registration_newsletter') }}
                        </th>
                        <td>
                            {{ $newsLetter->ip_registration_newsletter }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newsLetter.fields.http_referer') }}
                        </th>
                        <td>
                            {{ $newsLetter->http_referer }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newsLetter.fields.is_active') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $newsLetter->is_active ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.news-letters.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection