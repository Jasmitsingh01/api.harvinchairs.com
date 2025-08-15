@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.shop.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.shops.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.shop.fields.id') }}
                        </th>
                        <td>
                            {{ $shop->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shop.fields.owner') }}
                        </th>
                        <td>
                            {{ $shop->owner->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shop.fields.name') }}
                        </th>
                        <td>
                            {{ $shop->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shop.fields.slug') }}
                        </th>
                        <td>
                            {{ $shop->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shop.fields.description') }}
                        </th>
                        <td>
                            {!! $shop->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shop.fields.cover_image') }}
                        </th>
                        <td>
                            @foreach($shop->cover_image as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shop.fields.logo') }}
                        </th>
                        <td>
                            @if($shop->logo)
                                <a href="{{ $shop->logo->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $shop->logo->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shop.fields.is_active') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $shop->is_active ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shop.fields.address') }}
                        </th>
                        <td>
                            {{ $shop->address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shop.fields.settings') }}
                        </th>
                        <td>
                            {{ $shop->settings }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.shops.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection