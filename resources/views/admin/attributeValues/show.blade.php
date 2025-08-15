@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.attributeValue.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default"  href="{{ route('admin.attributes.show',$attributeValue->attribute->id) }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.attributeValue.fields.id') }}
                        </th>
                        <td>
                            {{ $attributeValue->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attributeValue.fields.slug') }}
                        </th>
                        <td>
                            {{ $attributeValue->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attributeValue.fields.attribute') }}
                        </th>
                        <td>
                            {{ $attributeValue->attribute->slug ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attributeValue.fields.value') }}
                        </th>
                        <td>
                            {{ $attributeValue->value }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attributeValue.fields.meta_title') }}
                        </th>
                        <td>
                            {{ $attributeValue->meta_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attributeValue.fields.meta_description') }}
                        </th>
                        <td>
                            {{ $attributeValue->meta_description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attributeValue.fields.meta_keywords') }}
                        </th>
                        <td>
                            {{ $attributeValue->meta_keywords }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attributeValue.fields.cover_image') }}
                        </th>
                        <td>
                            {{ $attributeValue->cover_image }}
                        </td>
                    </tr>
                    @if ($attributeValue->is_color == true)
                    <tr>
                        <th>
                            {{ trans('cruds.attributeValue.fields.color_code') }}
                        </th>
                        <td>
                           <input type="color" value="{{ $attributeValue->color_code }}" readonly>
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <th>
                            {{ trans('cruds.attributeValue.fields.description') }}
                        </th>
                        <td>
                            {{ $attributeValue->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attributeValue.fields.language') }}
                        </th>
                        <td>
                            {{ $attributeValue->language }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attributeValue.fields.position') }}
                        </th>
                        <td>
                            {{ $attributeValue->position }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.attributeValue.fields.meta') }}
                        </th>
                        <td>
                            {{ $attributeValue->meta }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.attributes.show',$attributeValue->attribute->id) }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
