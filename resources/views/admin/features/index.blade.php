@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    @can('feature_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.features.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.feature.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.feature.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="feature-table" class="table table-bordered table-striped table-hover datatable datatable-Feature display">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.feature.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.feature.fields.title') }}
                            </th>

                            <th>
                                {{ trans('cruds.feature.fields.position') }}
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
                            <td>
                            </td>
                        </tr>
                    </thead>
                    <tbody class="sortable-list">
                        @foreach ($features as $key => $feature)
                            <tr data-entry-id="{{ $feature->id }}" id="product_{{ $feature->id }}" draggable="true">
                                <td>

                                </td>
                                <td>
                                    {{ $feature->id ?? '' }}
                                </td>
                                <td>
                                    {{ $feature->title ?? '' }}
                                </td>
                                <td class="position">
                                    {{ $feature->position ?? '' }}
                                </td>
                                <td>
                                    <div class="text-nowrap text-theme-color">
                                        @can('feature_show')
                                        <a class="text-theme-color" href="{{ route('admin.features.show', $feature->id) }}">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        @endcan

                                        @can('feature_edit')
                                        <a class="text-theme-color" href="{{ route('admin.features.edit', $feature->id) }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        @endcan
                                        @can('feature_delete')
                                        <form action="{{ route('admin.features.destroy', $feature->id) }}" method="POST"
                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
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
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('feature_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.features.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id')
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
                @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,

                pageLength: 100,
            });
            let table = $('.datatable-Feature:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            let visibleColumnsIndexes = null;
            $('.datatable thead').on('input', '.search', function() {
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
    </script>
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script>

       $(document).ready(function () {
        @can('feature_edit')
           $(".sortable-list").sortable({
               axis: 'y',
               update: function (event, ui) {
                   var data = $(this).sortable('serialize');
                   $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                       url: '{{ route("admin.features.updatePositions") }}',
                       type: 'POST',
                       data: data,
                       success: function (response) {
                        updateDisplayedPositions(response.positions);
                       }
                   });
               }
           });
       });
       @endcan
       function updateDisplayedPositions(positions) {
            $('#feature-table tbody tr').each(function(index) {
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
