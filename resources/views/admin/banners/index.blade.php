@extends('layouts.admin')
@section('content')
    @can('banner_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.banners.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.banner.title_singular') }}
            </a>
        </div>
    </div>
    @endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.banner.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Banner">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th  width="10">
                            {{ trans('cruds.banner.fields.id') }}
                        </th>
                        <th width="10">
                            {{ trans('cruds.banner.fields.type') }}
                        </th>
                        <th width="10">
                            {{ trans('cruds.banner.fields.title') }}
                        </th>
                        <th width="10">
                            {{ trans('cruds.banner.fields.banner') }}
                        </th>
                        <th width="10">
                            {{ trans('cruds.banner.fields.dis_index') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.banner.fields.category') }}
                        </th> --}}
                        <th width="10">
                            {{ trans('cruds.banner.fields.active') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.banner.fields.link') }}
                        </th>
                        <th>
                            {{ trans('cruds.banner.fields.link_open') }}
                        </th> --}}
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr class="search-row">
                        <td>
                        </td>
                        <td>
                            <input class="search input-id" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach(App\Models\Banner::TYPE_SELECT as $key => $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input class="search " type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search input-id" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        {{-- <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($categories as $key => $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td> --}}
                        <td>
                        </td>
                        {{-- <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td> --}}
                        <td>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $key => $banner)
                        <tr data-entry-id="{{ $banner->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $banner->id ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Banner::TYPE_SELECT[$banner->type] ?? '' }}
                            </td>
                            <td>
                                {{ $banner->title ?? '' }}
                            </td>
                            <td>
                                @if($banner->banners)
                                    <a href="{{ $banner->banners['url'] }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $banner->banners['thumbnail'] }}" height="50px" width="50px">
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $banner->dis_index ?? '' }}
                            </td>
                            {{-- <td>
                                {{ $banner->category->name ?? '' }}
                            </td> --}}
                            <td>

                                @if ($banner->active )
                                <button class="border-0 text-success bg-transparent btn-active" data-id="{{$banner->id}}"><i class="fa-solid fa-circle-check"></i></button>
                                @else
                                <button class="border-0 text-danger bg-transparent btn-inactive"  data-id="{{$banner->id}}"><i class="fa-solid fa-circle-xmark"></i></button>
                                @endif
                            </td>
                            {{-- <td>
                                {{ $banner->link ?? '' }}
                            </td>
                            <td>
                                {{ $banner->link_open ?? '' }}
                            </td> --}}
                            <td>
                                <div class="text-nowrap text-theme-color">
                                    @can('banner_show')
                                    <a class="text-theme-color" href="{{ route('admin.banners.show', $banner->id) }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @endcan
                                    @can('banner_edit')
                                    <a class="text-theme-color" href="{{ route('admin.banners.edit', $banner->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    @endcan
                                    @can('banner_delete')
                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="text-theme-color border-0 bg-transparent px-0" ><i class="fa-solid fa-trash-can"></i> </button>
                                    </form>
                                    @endcan
                                </div>
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
  @can('banner_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.banners.massDestroy') }}",
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
  let table = $('.datatable-Banner:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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

$(document).ready(function () {

    @can('banner_edit')
    $(document).on('click', '.btn-active, .btn-inactive', function() {
        var id = $(this).data('id');

        var isTrue = $(this).hasClass('btn-active');
        var newStatus = isTrue ? 0 : 1;
        updateStatus(id, 'active', newStatus);
    });
    @endcan
    // AJAX function to update status in the database
    function updateStatus(id, field, value) {
        console.log(id, field, value);
        $.ajax({
            url: "{{ route('admin.banners.updateStatus') }}",
            type: "POST",
            data: {
                id: id,
                field: field,
                value: value,
                _token: '{{ csrf_token() }}'
            },
            success: function() {
                location.reload()
                // $('#datatable-Category').DataTable().ajax.reload();
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }
    })
</script>
@endsection
