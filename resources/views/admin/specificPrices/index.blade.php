@extends('layouts.admin')
@section('content')
@can('specific_price_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.specific-prices.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.specificPrice.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.specificPrice.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-SpecificPrice">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.specificPrice.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.specificPrice.fields.product') }}
                        </th>
                        <th>
                            {{ trans('cruds.specificPrice.fields.customer') }}
                        </th>
                        <th>
                            {{ trans('cruds.specificPrice.fields.product_attribute') }}
                        </th>
                        <th>
                            {{ trans('cruds.specificPrice.fields.from_quantity') }}
                        </th>
                        <th>
                            {{ trans('cruds.specificPrice.fields.from') }}
                        </th>
                        <th>
                            {{ trans('cruds.specificPrice.fields.to') }}
                        </th>
                        <th>
                            {{ trans('cruds.specificPrice.fields.price') }}
                        </th>
                        <th>
                            {{ trans('cruds.specificPrice.fields.reduction') }}
                        </th>
                        <th>
                            {{ trans('cruds.specificPrice.fields.reduction_type') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($specificPrices as $key => $specificPrice)
                        <tr data-entry-id="{{ $specificPrice->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $specificPrice->id ?? '' }}
                            </td>
                            <td>
                                {{ $specificPrice->product->name ?? '' }}
                            </td>
                            <td>
                                {{ $specificPrice->customer->first_name ?? '' }}
                            </td>
                            <td>
                                {{ $specificPrice->product_attribute->reference_code ?? '' }}
                            </td>
                            <td>
                                {{ $specificPrice->from_quantity ?? '' }}
                            </td>
                            <td>
                                {{ $specificPrice->from ?? '' }}
                            </td>
                            <td>
                                {{ $specificPrice->to ?? '' }}
                            </td>
                            <td>
                                {{ $specificPrice->price ?? '' }}
                            </td>
                            <td>
                                {{ $specificPrice->reduction ?? '' }}
                            </td>
                            <td>
                                {{ $specificPrice->reduction_type ?? '' }}
                            </td>
                            <td>
                                @can('specific_price_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.specific-prices.show', $specificPrice->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('specific_price_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.specific-prices.edit', $specificPrice->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('specific_price_delete')
                                    <form action="{{ route('admin.specific-prices.destroy', $specificPrice->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('specific_price_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.specific-prices.massDestroy') }}",
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
  let table = $('.datatable-SpecificPrice:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
