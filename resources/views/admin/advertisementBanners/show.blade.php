@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.advertisementBanner.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.advertisement-banners.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.advertisementBanner.fields.id') }}
                        </th>
                        <td>
                            {{ $advertisementBanner->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advertisementBanner.fields.title') }}
                        </th>
                        <td>
                            {{ $advertisementBanner->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advertisementBanner.fields.banner') }}
                        </th>
                        <td>
                            @if($advertisementBanner->banner)
                                <a href="{{ $advertisementBanner->banner->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $advertisementBanner->banner->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advertisementBanner.fields.category') }}
                        </th>
                        <td>
                            {{ $advertisementBanner->category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advertisementBanner.fields.active') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $advertisementBanner->active ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advertisementBanner.fields.link') }}
                        </th>
                        <td>
                            {{ $advertisementBanner->link }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advertisementBanner.fields.link_open') }}
                        </th>
                        <td>
                            {{ $advertisementBanner->link_open }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.advertisement-banners.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection