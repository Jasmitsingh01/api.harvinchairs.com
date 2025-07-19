@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.creativeCutsCategory.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.creative-cuts-categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.creativeCutsCategory.fields.id') }}
                        </th>
                        <td>
                            {{ $creativeCutsCategory->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.creativeCutsCategory.fields.name') }}
                        </th>
                        <td>
                            {{ $creativeCutsCategory->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.creativeCutsCategory.fields.description') }}
                        </th>
                        <td>
                            {{ $creativeCutsCategory->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.creativeCutsCategory.fields.active') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $creativeCutsCategory->active ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.creativeCutsCategory.fields.image') }}
                        </th>
                        <td>
                            @if($creativeCutsCategory->image)
                                <a href="{{ $creativeCutsCategory->image->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $creativeCutsCategory->image->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.creative-cuts-categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection