@extends('layouts.admin')
@section('content')
@can('advertisement_banner_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.advertisement-banners.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.advertisementBanner.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.advertisementBanner.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-AdvertisementBanner">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.advertisementBanner.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.advertisementBanner.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.advertisementBanner.fields.banner') }}
                        </th>
                        <th>
                            {{ trans('cruds.advertisementBanner.fields.category') }}
                        </th>
                        <th>
                            {{ trans('cruds.advertisementBanner.fields.active') }}
                        </th>
                        <th>
                            {{ trans('cruds.advertisementBanner.fields.link') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.advertisementBanner.fields.link_open') }}
                        </th> --}}
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search input-id" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                        <td>
                            <select class="search input-category">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($categories as $key => $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                        </td>
                        <td>
                            <input class="search input-category" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        {{-- <td>
                            <input class="search input-id" type="text" placeholder="{{ trans('global.search') }}">
                        </td> --}}
                        <td>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($advertisementBanners as $key => $advertisementBanner)
                        <tr data-entry-id="{{ $advertisementBanner->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $advertisementBanner->id ?? '' }}
                            </td>
                            <td>
                                {{ $advertisementBanner->title ?? '' }}
                            </td>
                            <td>
                                @if($advertisementBanner->banner)
                                    <a href="{{ $advertisementBanner->banner->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $advertisementBanner->banner->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $advertisementBanner->category->name ?? '' }}
                            </td>
                            <td>
                                <span style="display:none">{{ $advertisementBanner->active ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $advertisementBanner->active ? 'checked' : '' }}>
                            </td>
                            <td>
                                {{ $advertisementBanner->link ?? '' }}
                            </td>
                            {{-- <td>
                                {{ $advertisementBanner->link_open ?? '' }}
                            </td> --}}
                            <td>
                                @can('advertisement_banner_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.advertisement-banners.show', $advertisementBanner->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('advertisement_banner_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.advertisement-banners.edit', $advertisementBanner->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('advertisement_banner_delete')
                                    <form action="{{ route('admin.advertisement-banners.destroy', $advertisementBanner->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('advertisement_banner_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.advertisement-banners.massDestroy') }}",
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
  let table = $('.datatable-AdvertisementBanner:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
})

</script>
@endsection
