@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.category.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.id') }}
                        </th>
                        <td>
                            {{ $category->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.name') }}
                        </th>
                        <td>
                            {{ $category->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.details') }}
                        </th>
                        <td>
                            {!! $category->details !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.slug') }}
                        </th>
                        <td>
                            {{ $category->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.language') }}
                        </th>
                        <td>
                            {{ $category->language }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.position') }}
                        </th>
                        <td>
                            {{ $category->position }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.cover_image') }}
                        </th>
                        <td>
                            @if($category->cover_image)
                                <a href="{{ $category->cover_image->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $category->cover_image->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.thumbnail_image') }}
                        </th>
                        <td>
                            @if($category->thumbnail_image && is_array($category->thumbnail_image))
                            <a href="{{ $category->thumbnail_image['url'] }}" target="_blank" style="display: inline-block">
                                <img src="{{ $category->thumbnail_image['thumbnail'] }}">
                            </a>
                            @elseif($category->thumbnail_image )
                            <a href="{{ $category->thumbnail_image->geturl() }}" target="_blank" style="display: inline-block">
                                <img src="{{ $category->thumbnail_image->getUrl('thumb') }}">
                            </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.parent') }}
                        </th>
                        <td>
                            {{ $category->parent }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.enabled') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $category->enabled ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.meta_description') }}
                        </th>
                        <td>
                            {!! $category->meta_description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.meta_title') }}
                        </th>
                        <td>
                            {{ $category->meta_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.meta_keywords') }}
                        </th>
                        <td>
                            {{ $category->meta_keywords }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection