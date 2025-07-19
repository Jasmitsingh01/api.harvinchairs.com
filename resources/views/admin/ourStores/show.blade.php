@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ourStore.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.our-stores.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ourStore.fields.id') }}
                        </th>
                        <td>
                            {{ $ourStore->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ourStore.fields.name') }}
                        </th>
                        <td>
                            {{ $ourStore->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ourStore.fields.short_line') }}
                        </th>
                        <td>
                            {{ $ourStore->short_line }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ourStore.fields.address') }}
                        </th>
                        <td>
                            {{ $ourStore->address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ourStore.fields.opening_hours') }}
                        </th>
                        <td>
                            {{ $ourStore->opening_hours }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ourStore.fields.contact_number') }}
                        </th>
                        <td>
                            {{ $ourStore->contact_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ourStore.fields.gallery') }}
                        </th>
                        <td>
                            @foreach($ourStore->gallery as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ourStore.fields.city') }}
                        </th>
                        <td>
                            {{ $ourStore->city }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ourStore.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\OurStore::STATUS_RADIO[$ourStore->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.our-stores.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection