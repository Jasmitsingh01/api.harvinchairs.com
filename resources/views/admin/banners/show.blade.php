@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.banner.title') }}
    </div>
    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.banners.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.banner.fields.id') }}
                        </th>
                        <td>
                            {{ $banner->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.banner.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\Banner::TYPE_SELECT[$banner->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.banner.fields.title') }}
                        </th>
                        <td>
                            {{ $banner->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.banner.fields.banner') }}
                        </th>
                        <td>
                            @if($banner->banner)
                                    <a href="{{ $banner->banners['url'] }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $banner->banners['thumbnail']}}" height="50px" width="50px">
                                    </a>
                                @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.banner.fields.dis_index') }}
                        </th>
                        <td>
                            {{ $banner->dis_index }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.banner.fields.category') }}
                        </th>
                        <td>
                            {{ $banner->category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.banner.fields.active') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $banner->active ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.banner.fields.link') }}
                        </th>
                        <td>
                            {{ $banner->link }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.banner.fields.link_open') }}
                        </th>
                        <td>
                            {{ $banner->link_open }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.banner.fields.display_text') }}
                        </th>
                        <td>
                            {{ $banner->display_text }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.banner.fields.text_colour') }}
                        </th>
                        <td>
                           <input type="color"  value="{{ $banner->text_colour }}">
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.banners.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
