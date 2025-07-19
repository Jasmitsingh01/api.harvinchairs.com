@extends('layouts.admin')
@section('content')
    {{-- <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.orders.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.order.title_singular') }}
            </a>
        </div>
    </div> --}}
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.order.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-order">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th width="10">

                            {{ trans('cruds.order.fields.id') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.order.fields.tracking_number') }}
                        </th> --}}
                        <th>
                            {{ trans('cruds.order.fields.customer_name') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.order.fields.customer_contact') }}
                        </th> --}}

                        <th>
                            {{ trans('cruds.order.fields.total') }}
                        </th>

                        <th>
                            {{ trans('cruds.order.fields.payment_gateway') }}
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.shipping_address') }}
                        </th>

                        <th>
                            {{ trans('cruds.order.fields.created_at') }}
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.order_status') }}
                        </th>

                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search input-order-id" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        {{-- <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td> --}}
                        <td>
                            <input class="search input-category" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        {{-- <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td> --}}
                        <td>
                            <input class="search input-id" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search input-category" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search input-category" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search input-category" type="text" placeholder="{{ trans('global.search') }}">
                        </td>

                        <td>
                            <select class="search input-category" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach (App\Models\Order::ORDER_STATUS_RADIO as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
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
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('order_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.orders.massDestroy') }}",
                className: 'btn-danger',
                action: function(e, dt, node, config) {
                    var ids = $.map(dt.rows({
                        selected: true
                    }).data(), function(entry) {
                        return entry.id
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

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.orders.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    // {
                    //     data: 'tracking_number',
                    //     name: 'tracking_number'
                    // },
                    {
                        data: 'customer_name',
                        name: 'customer.first_name'
                    },
                    // {
                    //     data: 'customer_contact',
                    //     name: 'customer_contact'
                    // },

                    {
                        data: 'total',
                        name: 'total'
                    },

                    {
                        data: 'payment_gateway',
                        name: 'payment_gateway'
                    },
                    {
                        data: 'shipping_address',
                        name: 'shipping_address'
                    },

                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'order_status',
                        name: 'order_status'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 25,
            };
            let table = $('.datatable-order').DataTable(dtOverrideGlobals);
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
        });
    </script>
@endsection
