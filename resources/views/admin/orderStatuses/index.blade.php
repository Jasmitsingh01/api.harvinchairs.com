@extends('layouts.admin')
@section('content')
{{-- @can('order_status_create') --}}
    {{-- <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.order-statuses.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.orderStatus.title_singular') }}
            </a>
        </div>
    </div> --}}
{{-- @endcan --}}
<div class="card">
    <div class="card-header">
        {{ trans('cruds.orderStatus.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-OrderStatus">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.orderStatus.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderStatus.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderStatus.fields.template') }}
                    </th>
                    {{-- <th>
                        {{ trans('cruds.orderStatus.fields.module_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderStatus.fields.online_payment') }}
                    </th> --}}
                    <th>
                        {{ trans('cruds.orderStatus.fields.invoice') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderStatus.fields.send_email') }}
                    </th>
                    {{-- <th>
                        {{ trans('cruds.orderStatus.fields.unremovable') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderStatus.fields.hidden') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderStatus.fields.logable') }}
                    </th> --}}
                    <th>
                        {{ trans('cruds.orderStatus.fields.delivery') }}
                    </th>
                    {{-- <th>
                        {{ trans('cruds.orderStatus.fields.shipped') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderStatus.fields.paid') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderStatus.fields.pdf_invoice') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderStatus.fields.pdf_delivery') }}
                    </th> --}}
                    {{-- <th>
                        &nbsp;
                    </th> --}}
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
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    {{-- <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td> --}}
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    {{-- <td>
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
                    </td> --}}
                    {{-- <td>
                    </td> --}}
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.order-statuses.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
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
//   dtButtons.push(deleteButton)

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.order-statuses.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'name', name: 'name' },
{ data: 'template', name: 'template' },
// { data: 'module_name', name: 'module_name' },
// { data: 'online_payment', name: 'online_payment' },
{ data: 'invoice', name: 'invoice' },
{ data: 'send_email', name: 'send_email' },
// { data: 'unremovable', name: 'unremovable' },
// { data: 'hidden', name: 'hidden' },
// { data: 'logable', name: 'logable' },
{ data: 'delivery', name: 'delivery' },
// { data: 'shipped', name: 'shipped' },
// { data: 'paid', name: 'paid' },
// { data: 'pdf_invoice', name: 'pdf_invoice' },
// { data: 'pdf_delivery', name: 'pdf_delivery' },
// { data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
  };
  let table = $('.datatable-OrderStatus').DataTable(dtOverrideGlobals);
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

             $(document).on('click', '.btn-invoice, .btn-invoice-false', function() {
                var id = $(this).data('id');
                var isActive = $(this).hasClass('btn-invoice');
                var newStatus = isActive ? 0 : 1;
                updateStatus(id, 'invoice', newStatus);
            });

            // Handle click event for IsHome buttons
            $(document).on('click', '.btn-send-email, .btn-send-email-false', function() {
                var id = $(this).data('id');
                var isTrue = $(this).hasClass('btn-send-email');
                var newStatus = isTrue ? 0 : 1;
                updateStatus(id, 'send_email', newStatus);
            });
             // Handle click event for IsFeature buttons
             $(document).on('click', '.btn-delivery, .btn-delivery-false', function() {
                var id = $(this).data('id');
                var isFeatured = $(this).hasClass('btn-delivery');
                var newStatus = isFeatured ? 0 : 1;
                updateStatus(id, 'delivery', newStatus);
            });

            // AJAX function to update status in the database
            function updateStatus(id, field, value) {
                $.ajax({
                    url: "{{ route('admin.order-statuse.updateStatus') }}",
                    type: "POST",
                    data: {
                        id: id,
                        field: field,
                        value: value,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('.datatable-OrderStatus').DataTable().ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }
});

</script>
@endsection
