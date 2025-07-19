@extends('layouts.admin')
@section('content')

        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.feature-values.create',['id' =>$featureId]) }}">
                    {{ trans('global.add') }} {{ trans('cruds.productFeature.fields.feature_value') }}
                </a>
            </div>
        </div>

    <div class="card">
        <div class="card-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.features.index') }}">Features</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Features Values {{ trans('global.list') }}</li>
                </ol>
            </nav>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Feature">
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
                            {{-- <th>
                                {{ trans('cruds.feature.fields.language') }}
                            </th> --}}
                            {{-- <th>
                                {{ trans('cruds.feature.fields.position') }}
                            </th> --}}
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
                            {{-- <td>
                                <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                            </td>
                            <td>
                                <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                            </td> --}}
                            <td>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($features_values as $key => $feature)
                            <tr data-entry-id="{{ $feature->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $feature->id ?? '' }}
                                </td>
                                <td>
                                    {{ $feature->value ?? '' }}
                                </td>
                                {{-- <td>
                                    {{ $feature->language ?? '' }}
                                </td> --}}
                                {{-- <td>
                                    {{ $feature->position ?? '' }}
                                </td> --}}
                                <td>
                                    <div class="text-nowrap text-theme-color">
                                    <a class="text-theme-color" href="{{ route('admin.feature-values.edit', $feature->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                     </a>

                                    <form action="{{ route('admin.feature-values.destroy', $feature->id) }}" method="POST"
                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                        style="display: inline-block;">
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
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.feature-values.massDestroy') }}",
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
@endsection
