@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.printMedium.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.print-media.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.printMedium.fields.id') }}
                        </th>
                        <td>
                            {{ $printMedium->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.printMedium.fields.title') }}
                        </th>
                        <td>
                            {{ $printMedium->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.printMedium.fields.image') }}
                        </th>
                        <td>
                            @if($printMedium->image)
                                <a href="{{ $printMedium->image->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $printMedium->image->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.printMedium.fields.publish_date') }}
                        </th>
                        <td>
                            {{ $printMedium->publish_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.printMedium.fields.is_print_media') }}
                        </th>
                        <td>
                            {{ App\Models\PrintMedium::IS_PRINT_MEDIA_RADIO[$printMedium->is_print_media] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.printMedium.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\PrintMedium::STATUS_RADIO[$printMedium->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.print-media.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection