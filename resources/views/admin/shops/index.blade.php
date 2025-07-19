@extends('layouts.admin')
@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.shops.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.shop.title_singular') }}
            </a>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.shop.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Shop">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.shop.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.shop.fields.owner') }}
                        </th>
                        <th>
                            {{ trans('cruds.shop.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.shop.fields.slug') }}
                        </th>
                        <th>
                            {{ trans('cruds.shop.fields.cover_image') }}
                        </th>
                        <th>
                            {{ trans('cruds.shop.fields.logo') }}
                        </th>
                        <th>
                            {{ trans('cruds.shop.fields.is_active') }}
                        </th>
                        <th>
                            {{ trans('cruds.shop.fields.address') }}
                        </th>
                        <th>
                            {{ trans('cruds.shop.fields.settings') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shops as $key => $shop)
                        <tr data-entry-id="{{ $shop->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $shop->id ?? '' }}
                            </td>
                            <td>
                                {{ $shop->owner->name ?? '' }}
                            </td>
                            <td>
                                {{ $shop->name ?? '' }}
                            </td>
                            <td>
                                {{ $shop->slug ?? '' }}
                            </td>
                            <td>
                                @foreach($shop->cover_image as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $media->getUrl('thumb') }}">
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                @if($shop->logo)
                                    <a href="{{ $shop->logo->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $shop->logo->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                <span style="display:none">{{ $shop->is_active ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $shop->is_active ? 'checked' : '' }}>
                            </td>
                            <td>
                                {{ $shop->address ?? '' }}
                            </td>
                            <td>
                                {{ $shop->settings ?? '' }}
                            </td>
                            <td>

                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.shops.show', $shop->id) }}">
                                        {{ trans('global.view') }}
                                    </a>

                                    <a class="btn btn-xs btn-info" href="{{ route('admin.shops.edit', $shop->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>

                                    <form action="{{ route('admin.shops.destroy', $shop->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>

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

  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.shops.massDestroy') }}",
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


  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Shop:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
