@extends('layouts.admin')
@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.carriers.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.carrier.title_singular') }}
            </a>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.carrier.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Carrier">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.id') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.carrier.fields.reference') }}
                        </th> --}}
                        <th>
                            {{ trans('cruds.carrier.fields.name') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.carrier.fields.url') }}
                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.active') }}
                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.shipping_handling') }}
                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.range_behavior') }}
                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.is_module') }}
                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.is_free') }}
                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.shipping_external') }}
                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.need_range') }}
                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.external_module_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.shipping_method') }}
                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.position') }}
                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.max_width') }}
                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.max_height') }}
                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.max_depth') }}
                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.max_weight') }}
                        </th>
                        <th>
                            {{ trans('cruds.carrier.fields.grade') }}
                        </th> --}}
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        {{-- <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
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
                    @foreach($carriers as $key => $carrier)
                        <tr data-entry-id="{{ $carrier->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $carrier->id ?? '' }}
                            </td>
                            {{-- <td>
                                {{ $carrier->reference ?? '' }}
                            </td> --}}
                            <td>
                                {{ $carrier->name ?? '' }}
                            </td>
                            {{-- <td>
                                {{ $carrier->url ?? '' }}
                            </td>
                            <td>
                                <span style="display:none">{{ $carrier->active ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $carrier->active ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $carrier->shipping_handling ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $carrier->shipping_handling ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $carrier->range_behavior ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $carrier->range_behavior ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $carrier->is_module ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $carrier->is_module ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $carrier->is_free ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $carrier->is_free ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $carrier->shipping_external ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $carrier->shipping_external ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $carrier->need_range ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $carrier->need_range ? 'checked' : '' }}>
                            </td>
                            <td>
                                {{ $carrier->external_module_name ?? '' }}
                            </td>
                            <td>
                                {{ $carrier->shipping_method ?? '' }}
                            </td>
                            <td>
                                {{ $carrier->position ?? '' }}
                            </td>
                            <td>
                                {{ $carrier->max_width ?? '' }}
                            </td>
                            <td>
                                {{ $carrier->max_height ?? '' }}
                            </td>
                            <td>
                                {{ $carrier->max_depth ?? '' }}
                            </td>
                            <td>
                                {{ $carrier->max_weight ?? '' }}
                            </td>
                            <td>
                                {{ $carrier->grade ?? '' }}
                            </td> --}}
                            <td>
                                <div class="text-nowrap text-theme-color">
                                    <a class="text-theme-color" href="{{ route('admin.carriers.show', $carrier->id) }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>



                                    <a class="text-theme-color" href="{{ route('admin.carriers.edit', $carrier->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>



                                    <form action="{{ route('admin.carriers.destroy', $carrier->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="text-theme-color border-0 bg-transparent px-0" ><i class="fa-solid fa-trash-can"></i> </button>
                                    </form>
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

  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.carriers.massDestroy') }}",
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
  let table = $('.datatable-Carrier:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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
