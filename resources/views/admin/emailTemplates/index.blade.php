@extends('layouts.admin')
@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.email-templates.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.emailTemplate.title_singular') }}
            </a>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.emailTemplate.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-EmailTemplate">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.emailTemplate.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.emailTemplate.fields.template_code') }}
                    </th>
                    <th>
                        {{ trans('cruds.emailTemplate.fields.template_description') }}
                    </th>
                    <th>
                        {{ trans('cruds.emailTemplate.fields.subject') }}
                    </th>
                    <th>
                        {{ trans('cruds.emailTemplate.fields.email_file_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.emailTemplate.fields.status') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
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
@can('email_template_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.email-templates.massDestroy') }}",
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
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.email-templates.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'template_code', name: 'template_code' },
{ data: 'template_description', name: 'template_description' },
{ data: 'subject', name: 'subject' },
{ data: 'email_file_name', name: 'email_file_name' },
{ data: 'status', name: 'status' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-EmailTemplate').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});
    $(document).on('click', '.btn-active, .btn-inactive', function() {
        var id = $(this).data('id');
        var isActive = $(this).hasClass('btn-active');
        var newStatus = isActive ? "Inactive" : "Active";
        updateStatus(id, 'status', newStatus);
    });

    function updateStatus(id, field, value) {
        $.ajax({
            url: "{{ route('admin.email-templates.updateStatus') }}",
            type: "POST",
            data: {
                id: id,
                field: field,
                value: value,
                _token: '{{ csrf_token() }}'
            },
            success: function() {
                $('.datatable-EmailTemplate').DataTable().ajax.reload();
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }
</script>
@endsection
