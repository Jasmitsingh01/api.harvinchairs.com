@extends('layouts.admin')
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.menus.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.menu.title_singular') }}
            </a>
        </div>
    </div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.menu.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Menu">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th width="10">
                        {{ trans('cruds.menu.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.menu.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.menu.fields.categories') }}
                    </th>

                    <th>
                        {{ trans('cruds.menu.fields.position') }}
                    </th>
                    <th>
                        {{ trans('cruds.menu.fields.active') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td width="10">
                        <input class="search input-id" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>

                    </td>

                    <td width="10">
                        {{-- <input class="search input-id" type="text" placeholder="{{ trans('global.search') }}"> --}}
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            <option value="1">Active</option>
                            <option value="0">InActive</option>
                        </select>
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
    url: "{{ route('admin.menus.massDestroy') }}",
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
    ajax: "{{ route('admin.menus.index') }}",
    columns: [
        { data: 'placeholder', name: 'placeholder' },
        { data: 'id', name: 'id' },
        { data: 'name', name: 'name' },
        { data: 'categories', name: 'categories.name', searchable: false },
        { data: 'position', name: 'position', searchable: false },
        { data: 'active', name: 'active',
            render: function(data, type, row) {
            return getActiveButton(data, row.id);
            }
        },
        { data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 5, 'asc' ]],
    pageLength: 100,
    rowReorder: {
        selector: 'tr td:not(:first-of-type, .skip-reorder-cell,:nth-last-child(2), :last-of-type)',
        dataSrc: 'position'
    },
  };
  let table = $('.datatable-Menu').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  table.on('row-reorder', function (e, details) {
        if(details.length) {
            let rows = [];
            details.forEach(element => {
                rows.push({
                    id: table.row(element.node).data().id,
                    position: element.newData
                });
            });

            $.ajax({
                headers: {'x-csrf-token': _token},
                method: 'POST',
                url: "{{ route('admin.menus.updatePositions') }}",
                data: { rows }
            }).done(function () {
                table.ajax.reload() });
        }
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
    });

    function getActiveButton(value, id) {
        console.log(value);
        return value ?
            '<button class="border-0 text-success bg-transparent btn-active" data-id="' + id +
            '"><i class="fa-solid fa-circle-check"></i></button>' :
            '<button class="border-0 text-danger bg-transparent btn-inactive" data-id="' + id +
            '"><i class="fa-solid fa-circle-xmark"></i></button>';
    }
     // Handle click event for Active/Inactive buttons
    $(document).on('click', '.btn-active, .btn-inactive', function() {
        var id = $(this).data('id');
        var isActive = $(this).hasClass('btn-active');
        var newStatus = isActive ? 0 : 1;
        updateStatus(id, 'active', newStatus);
    });
    // AJAX function to update status in the database
    function updateStatus(id, field, value) {
        $.ajax({
            url: "{{ route('admin.menus.updateStatus') }}",
            type: "POST",
            data: {
                id: id,
                field: field,
                value: value,
                _token: '{{ csrf_token() }}'
            },
            success: function() {
                $('.datatable-Menu').DataTable().ajax.reload();
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }
});

</script>
@endsection
