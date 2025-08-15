@extends('layouts.admin')
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            {{-- <a class="btn btn-success" href="{{ route('admin.product-enquires.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.productEnquire.title_singular') }}
            </a> --}}
        </div>
    </div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.productEnquire.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ProductEnquire">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.productEnquire.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.productEnquire.fields.customer_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.productEnquire.fields.customer_email') }}
                    </th>
                    {{-- <th>
                        {{ trans('cruds.productEnquire.fields.subject') }}
                    </th>
                    <th>
                        {{ trans('cruds.productEnquire.fields.message') }}
                    </th>
                    <th>
                        {{ trans('cruds.productEnquire.fields.product_title') }}
                    </th>
                    <th>
                        {{ trans('cruds.productEnquire.fields.product_price') }}
                    </th>
                    <th>
                        {{ trans('cruds.productEnquire.fields.product_img') }}
                    </th> --}}
                    <th>
                        {{ trans('cruds.productEnquire.fields.product') }}
                    </th>
                    {{-- <th>
                        {{ trans('cruds.productEnquire.fields.product_attributes') }}
                    </th>
                    <th>
                        {{ trans('cruds.productEnquire.fields.url') }}
                    </th> --}}
                    <th>
                        {{ trans('cruds.contactUs.fields.created_at') }}
                    </th>
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
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td> --}}
                    {{-- <td>
                    </td> --}}
                    <td>
                          <input class="search" type="text" placeholder="{{ trans('global.search') }}">

                        {{-- <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($products as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select> --}}
                    </td>
                    {{-- <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($product_attributes as $key => $item)
                                <option value="{{ $item->reference_code }}">{{ $item->reference_code }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td> --}}
                    <td>

                    </td>
                    <td>
                    </td>
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
    url: "{{ route('admin.product-enquires.massDestroy') }}",
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
  dtButtons.push(deleteButton)

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.product-enquires.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'customer_name', name: 'customer_name' },
{ data: 'customer_email', name: 'customer_email' },
// { data: 'subject', name: 'subject' },
// { data: 'message', name: 'message' },
// { data: 'product_title', name: 'product_title' },
// { data: 'product_price', name: 'product_price' },
// { data: 'product_img', name: 'product_img', sortable: false, searchable: false },
{ data: 'product_name', name: 'product.name' },
// { data: 'product_attributes_reference_code', name: 'product_attributes.reference_code' },
// { data: 'url', name: 'url' },
{ data: 'created_at', name: 'created_at' },

{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-ProductEnquire').DataTable(dtOverrideGlobals);
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
});

</script>
@endsection
