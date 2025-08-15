@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@can('attribute_value_create')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route('admin.attribute-values.create',['id'=>$attributeId])}}">
            {{ trans('global.add') }} {{ trans('cruds.attributeValue.title_singular') }}
        </a>
    </div>
</div>
@endcan
<div class="card">
    <div class="card-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
          <li class="breadcrumb-item active" aria-current="page"> {{ trans('cruds.attributeValue.title_singular') }} {{ trans('global.list') }}</li>
        </ol>
    </nav>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="attribute-value-table" class=" table table-bordered table-striped table-hover datatable datatable-AttributeValue">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.attributeValue.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.attributeValue.fields.slug') }}
                        </th>
                        <th>
                            {{ trans('cruds.attributeValue.fields.attribute') }}
                        </th>
                        <th>
                            {{ trans('cruds.attributeValue.fields.value') }}
                        </th>
                        <th>
                            {{ trans('cruds.attributeValue.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.attributeValue.fields.position') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody class="sortable-list">
                    @foreach($attributeValues as $key => $attributeValue)
                        <tr data-entry-id="{{ $attributeValue->id }}" id="attributevalue_{{ $attributeValue->id }}" draggable="true">
                            <td>

                            </td>
                            <td>
                                {{ $attributeValue->id ?? '' }}
                            </td>
                            <td>
                                {{ $attributeValue->slug ?? '' }}
                            </td>
                            <td>
                                {{ $attributeValue->attribute->slug ?? '' }}
                            </td>
                            <td>
                                {{ $attributeValue->value ?? '' }}
                            </td>
                            <td>
                                {{ $attributeValue->description ?? '' }}
                            </td>
                            <td class="position">
                                {{ $attributeValue->position ?? '' }}
                            </td>
                            <td>
                                <div class="text-nowrap text-theme-color">
                                    @can('attribute_value_show')
                                    <a class="text-theme-color" href="{{ route('admin.attribute-values.show', $attributeValue->id) }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @endcan
                                    @can('attribute_value_edit')
                                    <a class="text-theme-color" href="{{ route('admin.attribute-values.edit', $attributeValue->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    @endcan
                                    @can('attribute_value_delete')
                                    <form action="{{ route('admin.attribute-values.destroy', $attributeValue->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
  @can('attribute_value_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.attribute-values.massDestroy') }}",
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
    pageLength: 100,
  });
  let table = $('.datatable-AttributeValue:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>

    $(document).ready(function () {
        $(".sortable-list").sortable({
            axis: 'y',
            update: function (event, ui) {
                var data = $(this).sortable('serialize');
                $.ajax({
                     headers: {
                         'X-CSRF-TOKEN': "{{ csrf_token() }}"
                     },
                    url: '{{ route("admin.attribute-values.updatePositions") }}',
                    type: 'POST',
                    data: data,
                    success: function (response) {
                     updateDisplayedPositions(response.positions);
                    }
                });
            }
        });
    });
    function updateDisplayedPositions(positions) {
         $('#attribute-value-table tbody tr').each(function(index) {
             // Get the product ID from the row data attribute
             var productId = $(this).data('entryId');

             // Find the position for the corresponding product ID
             var position = positions[productId];
             console.log(position);
             // // Update the position number in the relevant column
             $(this).find('.position').text(position);
         });
     }
</script>
@endsection
