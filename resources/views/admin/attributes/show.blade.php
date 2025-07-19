@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.attribute.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.attributes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.attribute.fields.id') }}
                        </th>
                        <td>
                            {{ $attribute->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attribute.fields.slug') }}
                        </th>
                        <td>
                            {{ $attribute->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attribute.fields.language') }}
                        </th>
                        <td>
                            {{ $attribute->language }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attribute.fields.position') }}
                        </th>
                        <td>
                            {{ $attribute->position }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attribute.fields.name') }}
                        </th>
                        <td>
                            {{ $attribute->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attribute.fields.public_name') }}
                        </th>
                        <td>
                            {{ $attribute->public_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attribute.fields.group_type') }}
                        </th>
                        <td>
                            {{ $attribute->group_type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attribute.fields.shop') }}
                        </th>
                        <td>
                            {{ $attribute->shop->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.attributes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection