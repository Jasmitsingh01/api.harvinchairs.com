@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.menu.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.menus.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.id') }}
                        </th>
                        <td>
                            {{ $menu->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.name') }}
                        </th>
                        <td>
                            {{ $menu->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.categories') }}
                        </th>
                        <td>
                            @foreach($menu->categories as $key => $categories)
                                <span class="label label-info">{{ $categories->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.parent') }}
                        </th>
                        <td>
                            {{ $menu->parent }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.position') }}
                        </th>
                        <td>
                            {{ $menu->position }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.active') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $menu->active ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.is_cms') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $menu->is_cms ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.is_category') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $menu->is_category ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.menus.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection