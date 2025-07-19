@extends('layouts.admin')
@section('content')
@can('print_medium_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.print-media.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.printMedium.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.printMedium.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-PrintMedium">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.printMedium.fields.id') }}
                        </th>
                        <th  width="200">
                            {{ trans('cruds.printMedium.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.printMedium.fields.image') }}
                        </th>
                        <th>
                            {{ trans('cruds.printMedium.fields.publish_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.printMedium.fields.is_print_media') }}
                        </th>
                        <th>
                            {{ trans('cruds.printMedium.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($printMedia as $key => $printMedium)
                        <tr data-entry-id="{{ $printMedium->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $printMedium->id ?? '' }}
                            </td>
                            <td>
                                {{ $printMedium->title ?? '' }}
                            </td>
                            <td>
                                @if($printMedium->image)
                                    <a href="{{ $printMedium->image->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $printMedium->image->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $printMedium->publish_date ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\PrintMedium::IS_PRINT_MEDIA_RADIO[$printMedium->is_print_media] ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\PrintMedium::STATUS_RADIO[$printMedium->status] ?? '' }}
                            </td>
                            <td>
                                @can('print_medium_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.print-media.show', $printMedium->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('print_medium_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.print-media.edit', $printMedium->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('print_medium_delete')
                                    <form action="{{ route('admin.print-media.destroy', $printMedium->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('print_medium_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.print-media.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-PrintMedium:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
