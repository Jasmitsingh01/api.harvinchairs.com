@extends('layouts.admin')
@section('content')
@can('product_attribute_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.product-attributes.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.productAttribute.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.productAttribute.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-ProductAttribute">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.productAttribute.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.productAttribute.fields.product') }}
                        </th>
                        <th>
                            {{ trans('cruds.productAttribute.fields.reference_code') }}
                        </th>
                        <th>
                            {{ trans('cruds.productAttribute.fields.price') }}
                        </th>
                        <th>
                            {{ trans('cruds.productAttribute.fields.minimum_quantity') }}
                        </th>
                        <th>
                            {{ trans('cruds.productAttribute.fields.quantity') }}
                        </th>
                        <th>
                            {{ trans('cruds.productAttribute.fields.bulk_buy_discount') }}
                        </th>
                        <th>
                            {{ trans('cruds.productAttribute.fields.bulk_buy_minimum_quantity') }}
                        </th>
                        <th>
                            {{ trans('cruds.productAttribute.fields.availability_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.productAttribute.fields.images') }}
                        </th>
                        <th>
                            {{ trans('cruds.productAttribute.fields.is_default') }}
                        </th>
                        <th>
                            {{ trans('cruds.productAttribute.fields.out_of_stock') }}
                        </th>
                        <th>
                            {{ trans('cruds.productAttribute.fields.is_visible') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productAttributes as $key => $productAttribute)
                        <tr data-entry-id="{{ $productAttribute->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $productAttribute->id ?? '' }}
                            </td>
                            <td>
                                {{ $productAttribute->product->name ?? '' }}
                            </td>
                            <td>
                                {{ $productAttribute->reference_code ?? '' }}
                            </td>
                            <td>
                                {{ $productAttribute->price ?? '' }}
                            </td>
                            <td>
                                {{ $productAttribute->minimum_quantity ?? '' }}
                            </td>
                            <td>
                                {{ $productAttribute->quantity ?? '' }}
                            </td>
                            <td>
                                {{ $productAttribute->bulk_buy_discount ?? '' }}
                            </td>
                            <td>
                                {{ $productAttribute->bulk_buy_minimum_quantity ?? '' }}
                            </td>
                            <td>
                                {{ $productAttribute->availability_date ?? '' }}
                            </td>
                            <td>
                                @if($productAttribute->images)
                                    <a href="{{ $productAttribute->images->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $productAttribute->images->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ App\Models\ProductAttribute::IS_DEFAULT_RADIO[$productAttribute->is_default] ?? '' }}
                            </td>
                            <td>
                                {{ $productAttribute->out_of_stock ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\ProductAttribute::IS_VISIBLE_RADIO[$productAttribute->is_visible] ?? '' }}
                            </td>
                            <td>
                                @can('product_attribute_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.product-attributes.show', $productAttribute->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('product_attribute_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.product-attributes.edit', $productAttribute->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('product_attribute_delete')
                                    <form action="{{ route('admin.product-attributes.destroy', $productAttribute->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('product_attribute_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.product-attributes.massDestroy') }}",
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
  let table = $('.datatable-ProductAttribute:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection