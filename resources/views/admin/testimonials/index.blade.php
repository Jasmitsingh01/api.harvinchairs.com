@extends('layouts.admin')
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.testimonials.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.testimonial.title_singular') }}
            </a>
        </div>
    </div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.testimonial.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Testimonial">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.testimonial.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.testimonial.fields.customer') }}
                        </th>
                        <th>
                            {{ trans('cruds.testimonial.fields.author_name') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.testimonial.fields.author_info') }}
                        </th> --}}
                        <th>
                            {{ trans('cruds.testimonial.fields.author_url') }}
                        </th>
                        <th>
                            {{ trans('cruds.testimonial.fields.author_email') }}
                        </th>
                        <th>
                            {{ trans('cruds.testimonial.fields.rating') }}
                        </th>
                        <th>
                            {{ trans('cruds.testimonial.fields.content') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.testimonial.fields.is_featured') }}
                        </th> --}}
                        <th>
                            {{ trans('cruds.testimonial.fields.active') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search input-id" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            {{-- <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($users as $key => $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select> --}}
                        </td>
                        <td>
                            <input class="search input-category" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search input-category" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        {{-- <td>
                            <input class="search input-category" type="text" placeholder="{{ trans('global.search') }}">
                        </td> --}}
                        <td>
                            <input class="search input-category" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search input-qty" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search input-category" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        {{-- <td>
                        </td> --}}
                        <td>
                        </td>
                        <td>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($testimonials as $key => $testimonial)
                        <tr data-entry-id="{{ $testimonial->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $testimonial->id ?? '' }}
                            </td>
                            <td>
                                {{ $testimonial->customer->first_name ?? '' }}
                            </td>
                            <td>
                                {{ $testimonial->author_name ?? '' }}
                            </td>
                            {{-- <td>
                                {{ $testimonial->author_info ?? '' }}
                            </td> --}}
                            <td>
                                {{ $testimonial->author_url ?? '' }}
                            </td>
                            <td>
                                {{ $testimonial->author_email ?? '' }}
                            </td>
                            <td>
                                {{ $testimonial->rating ?? '' }}
                            </td>
                            <td>
                                {{ $testimonial->content ?? '' }}
                            </td>
                            {{-- <td>
                                <span style="display:none">{{ $testimonial->is_featured ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $testimonial->is_featured ? 'checked' : '' }}>
                            </td> --}}
                            <td>

                                @if ( $testimonial->active )
                                <button class="border-0 text-success bg-transparent btn-active" data-id="{{$testimonial->id}}"><i class="fa-solid fa-circle-check"></i></button>
                                @else
                                <button class="border-0 text-danger bg-transparent btn-inactive"  data-id="{{$testimonial->id}}"><i class="fa-solid fa-circle-xmark"></i></button>
                                @endif
                            </td>
                            <td>
                                <div class="text-nowrap text-theme-color">

                                    <a class="text-theme-color" href="{{ route('admin.testimonials.show', $testimonial->id) }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <a class="text-theme-color" href="{{ route('admin.testimonials.edit', $testimonial->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    url: "{{ route('admin.testimonials.massDestroy') }}",
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
  let table = $('.datatable-Testimonial:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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
$(document).ready(function () {
$(document).on('click', '.btn-active, .btn-inactive', function() {
                var id = $(this).data('id');

                var isTrue = $(this).hasClass('btn-active');
                var newStatus = isTrue ? 0 : 1;
                updateStatus(id, 'active', newStatus);
            });

            // AJAX function to update status in the database
            function updateStatus(id, field, value) {
                console.log(id, field, value);
                $.ajax({
                    url: "{{ route('admin.testimonials.updateStatus') }}",
                    type: "POST",
                    data: {
                        id: id,
                        field: field,
                        value: value,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        location.reload()
                        // $('#datatable-Category').DataTable().ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }
})

</script>
@endsection
