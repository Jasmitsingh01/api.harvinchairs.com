@extends('layouts.admin')
@section('content')
@can('our_store_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.our-stores.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.ourStore.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.ourStore.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-OurStore">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.ourStore.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.ourStore.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.ourStore.fields.short_line') }}
                        </th>
                        <th>
                            {{ trans('cruds.ourStore.fields.address') }}
                        </th>
                        <th>
                            {{ trans('cruds.ourStore.fields.opening_hours') }}
                        </th>
                        <th>
                            {{ trans('cruds.ourStore.fields.contact_number') }}
                        </th>
                        <th>
                            {{ trans('cruds.ourStore.fields.gallery') }}
                        </th>
                        <th>
                            {{ trans('cruds.ourStore.fields.city') }}
                        </th>
                        <th>
                            {{ trans('cruds.ourStore.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ourStores as $key => $ourStore)
                        <tr data-entry-id="{{ $ourStore->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $ourStore->id ?? '' }}
                            </td>
                            <td>
                                {{ $ourStore->name ?? '' }}
                            </td>
                            <td>
                                {{ $ourStore->short_line ?? '' }}
                            </td>
                            <td>
                                {{ $ourStore->address ?? '' }}
                            </td>
                            <td>
                                {{ $ourStore->opening_hours ?? '' }}
                            </td>
                            <td>
                                {{ $ourStore->contact_number ?? '' }}
                            </td>
                            <td>
                                @foreach($ourStore->gallery as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $media->getUrl('thumb') }}">
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                {{ $ourStore->city ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\OurStore::STATUS_RADIO[$ourStore->status] ?? '' }}
                            </td>
                            <td>
                                @can('our_store_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.our-stores.show', $ourStore->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('our_store_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.our-stores.edit', $ourStore->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('our_store_delete')
                                    <form action="{{ route('admin.our-stores.destroy', $ourStore->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('our_store_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.our-stores.massDestroy') }}",
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
  let table = $('.datatable-OurStore:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection