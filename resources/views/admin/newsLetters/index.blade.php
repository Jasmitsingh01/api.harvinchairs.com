@extends('layouts.admin')
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.news-letters.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.newsLetter.title_singular') }}
            </a>
        </div>
    </div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.newsLetter.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-NewsLetter">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.newsLetter.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.newsLetter.fields.email') }}
                    </th>
                    {{-- <th>
                        {{ trans('cruds.newsLetter.fields.ip_registration_newsletter') }}
                    </th>
                    <th>
                        {{ trans('cruds.newsLetter.fields.http_referer') }}
                    </th> --}}
                    <th>
                        {{ trans('cruds.newsLetter.fields.subscribed') }}
                    </th>
                    <th>
                        {{ trans('cruds.newsLetter.fields.subscribed_on') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        {{-- <input class="search" type="text" placeholder="{{ trans('global.search') }}"> --}}
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        {{-- <input class="search" type="text" placeholder="{{ trans('global.search') }}"> --}}
                    </td>
                    <td>
                        {{-- <input class="search" type="text" placeholder="{{ trans('global.search') }}"> --}}
                    </td>
                    {{-- <td>
                    </td> --}}
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
    url: "{{ route('admin.news-letters.massDestroy') }}",
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
    ajax: "{{ route('admin.news-letters.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'email', name: 'email' },
// { data: 'ip_registration_newsletter', name: 'ip_registration_newsletter' },
// { data: 'http_referer', name: 'http_referer' },
{ data: 'is_active', name: 'is_active' },
{ data: 'created_at', name: 'created_at' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-NewsLetter').DataTable(dtOverrideGlobals);
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
  });

table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
});

$(document).on('click', '.btn-active, .btn-inactive', function() {
                var id = $(this).data('id');
                var isActive = $(this).hasClass('btn-active');
                var newStatus = isActive ? 0 : 1;
                updateStatus(id, 'is_active', newStatus);
            });

            function updateStatus(id, field, value) {
                $.ajax({
                    url: "{{ route('admin.news-letters.updateStatus') }}",
                    type: "POST",
                    data: {
                        id: id,
                        field: field,
                        value: value,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('.datatable-NewsLetter').DataTable().ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }

</script>
@endsection
