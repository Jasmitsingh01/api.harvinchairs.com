@extends('layouts.admin')
@section('content')
@can('product_attribute_combination_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.product-attribute-combinations.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.productAttributeCombination.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.productAttributeCombination.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-ProductAttributeCombination">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.productAttributeCombination.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.productAttributeCombination.fields.product_attribute') }}
                        </th>
                        <th>
                            {{ trans('cruds.productAttributeCombination.fields.attribute') }}
                        </th>
                        <th>
                            {{ trans('cruds.productAttributeCombination.fields.attribute_value') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productAttributeCombinations as $key => $productAttributeCombination)
                        <tr data-entry-id="{{ $productAttributeCombination->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $productAttributeCombination->id ?? '' }}
                            </td>
                            <td>
                                {{ $productAttributeCombination->product_attribute->reference_code ?? '' }}
                            </td>
                            <td>
                                {{ $productAttributeCombination->attribute->name ?? '' }}
                            </td>
                            <td>
                                {{ $productAttributeCombination->attribute_value->slug ?? '' }}
                            </td>
                            <td>
                                @can('product_attribute_combination_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.product-attribute-combinations.show', $productAttributeCombination->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('product_attribute_combination_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.product-attribute-combinations.edit', $productAttributeCombination->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('product_attribute_combination_delete')
                                    <form action="{{ route('admin.product-attribute-combinations.destroy', $productAttributeCombination->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('product_attribute_combination_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.product-attribute-combinations.massDestroy') }}",
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
  let table = $('.datatable-ProductAttributeCombination:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection