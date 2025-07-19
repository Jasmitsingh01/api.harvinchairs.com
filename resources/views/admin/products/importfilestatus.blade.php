@extends('layouts.admin')
<style>
    .dataTables_wrapper .dataTables_filter {
        display: none;
    }
</style>
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.import_file_detail.title') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <div class="row mb-4">
                    <div class="col">
                        {{-- <button id="btnRemoveFilters" class="btn btn-secondary">Clear Filters</button>
                        <a href="{{ route('admin.product.export-sample-file') }}" id="btnRemoveFilters" class="btn btn-primary">Export Sample File</a>
                        <button id="btnImportProduct" class="btn btn-primary" data-target="#openImportProduct" data-toggle="modal">Import Product</button>
                        <a href="{{ route('admin.product.export-sample-file') }}" id="btnRemoveFilters" class="btn btn-primary">Import File Status</a> --}}
                    </div>
                </div>

                <table id="datatable-Product-Import" class=" table table-bordered datatable datatable-Product">

                    <thead>
                        <tr>
                            <th width="10">
                            <input type="checkbox" id="select_all">
                            </th>

                            <th>
                                {{ trans('cruds.import_file_detail.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.import_file_detail.fields.original_file_name') }}
                            </th>
                            <th>
                                {{ trans('cruds.import_file_detail.fields.import_filename') }}
                            </th>
                            <th>
                                {{ trans('cruds.import_file_detail.fields.status') }}
                            </th>
                            <th>
                                {{ trans('cruds.import_file_detail.fields.error_message') }}
                            </th>
                        </tr>
                        {{-- <tr class="search-row">
                            <th></th>
                            <th>
                                <input class="search-input input-id" id="id" type="text" placeholder="{{ trans('global.search') }}" style="width: 100px;">
                            </th>
                            <th>
                                <input class="search-input" type="text" placeholder="{{ trans('global.search') }}">
                            </th>
                            <th>
                                <input class="search-input" type="text" placeholder="{{ trans('global.search') }}">
                            </th>
                            <th>
                                <input class="search-input" type="text" placeholder="{{ trans('global.search') }}">
                            </th>
                            <th>
                                <input class="search-input" type="text" placeholder="{{ trans('global.search') }}">
                            </th>
                        </tr> --}}
                    </thead>
                    <tbody>
                        @foreach($importfiledetails as $key => $detail)
                        <tr>
                            <td></td>
                            <td>{{$detail->id}}</td>
                            <td>{{$detail->original_file_name}}</td>
                            <td>{{$detail->import_filename}}</td>
                            <td>{{$detail->status}}</td>
                            <td>{{$detail->error_message}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="openImportProduct" tabindex="-1" role="dialog"
        aria-labelledby="addFeatureModalValueLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="openProductModalValueLabel">Import Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('admin.product.import-product') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="feature">Upload File:</label>
                            <input type="file" name="importfile" class="form-control" required/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(document).ready(function() {

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 20,
            });

            let visibleColumnsIndexes = null;

            $('.datatable thead').on('input', '.search', function () {
                let strict = $(this).attr('strict') || false;
                let value = strict && this.value ? "^" + this.value + "$" : this.value;
                let index = $(this).parent().index();

                if (visibleColumnsIndexes !== null) {
                    index = visibleColumnsIndexes[index];
                }

                table.column(index)
                    .search(value, strict)
                    .draw();
            });

            var table = $('#datatable-Product-Import').DataTable({
                buttons: [],
            });
            var table = $('#datatable-Product').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                dom: 'Bfrtip',
                buttons: [],
                // stateSave: true,
                ajax: '{!! route('admin.products.index') !!}',
                columns: [{
                        data: null,
                        name: '',
                        render: function(data, type, row) {
                            return '';
                        }
                    },
                    {
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'reference_code',
                        name: 'reference_code'
                    },
                    {
                        data: 'category_name',
                        name: 'categories.name'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        render: function(data, type, row) {
                            return getActiveButton(data, row.id);
                        }
                    },
                    {
                        data: 'is_home',
                        name: 'is_home',
                        render: function(data, type, row) {
                            return getIsHomeButton(data, row.id);
                        }
                    },
                    {
                        data: 'isFeatured',
                        name: 'isFeatured',
                        render: function(data, type, row) {
                            return getIsFeatureButton(data, row.id);
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                createdRow: function(row, data, dataIndex) {
                    // 'data' contains the row data received from the server
                    $(row).attr('data-entry-id', data
                        .id); // Add the custom attribute 'data-entry-id' to the row
                    $(row).attr('role', 'row'); // Add the 'role' attribute to the row
                    $(row).addClass(dataIndex % 2 ? 'odd' :
                        'even'); // Add the 'odd' or 'even' class based on the row index
                },
                // Use the Id of the button to open the dropdown on click
                initComplete: function() {
                    $('#bulkActionsDropdown').on('click', function(e) {
                        e.stopPropagation();
                        $(this).next('.dropdown-menu').toggle();
                    });

                    // Handle clicks outside the dropdown to close it
                    $(document).on('click', function(e) {
                        if (!$('#bulkActionsDropdown').is(e.target) && $('#bulkActionsDropdown')
                            .has(e.target).length === 0) {
                            $('.dropdown-menu').hide();
                        }
                    });

                    // Add click listener to the dropdown items
                    $('.dropdown-item').on('click', function() {
                        var action = $(this).data('action');
                        var value = $(this).data('value');

                        handleBulkAction(action, value, table);
                        $('.dropdown-menu').hide(); // Hide the dropdown after selection
                    });
                },
            });
            // Load and set the search state when the page loads
            table.on('init.dt', function () {
                var searchState = table.state.loaded();
                if (searchState && searchState.columns) {
                    for (var i = 0; i < searchState.columns.length; i++) {
                        var columnSearch = searchState.columns[i].search;
                        if (columnSearch) {
                            var columnIndex = i;
                            var columnInput = $('.search-row th:eq(' + columnIndex + ') .search-input');
                            if (columnInput.is('input') || columnInput.is('select')) {
                                if (typeof columnSearch === 'object' && columnSearch !== null) {
                                    // Extract the value from the jQuery object
                                    columnSearch = columnSearch.search;
                                }
                                columnInput.val(columnSearch);
                            }
                        }
                    }
                }
            });

            // Store the column search state in session
            table.on('stateSaveParams.dt', function (e, settings, data) {
                var searchState = [];
                $('.search-row th .search-input').each(function (index) {
                    var columnSearch = $(this).val();
                    searchState[index] = columnSearch;
                });
                data.search.columns = searchState;
            });
            // Remove Filters button click event
            $('#btnRemoveFilters').on('click', function () {
                $('.search-row th .search-input').each(function () {
                    var input = $(this);
                    if (input.is('input')) {
                        input.val('');
                    } else if (input.is('select')) {
                        input.val('').trigger('change');
                    }
                });
                table.columns().search('').draw();
            });
            $('#select_all').click(function() {
                var isChecked = $(this).prop('checked');
                table.rows().select(isChecked); // Select all rows in the table when "Select All" is checked
            });
            function handleBulkAction(action, value, table) {
                var ids = $.map(table.rows({
                    selected: true
                }).nodes(), function(entry) {
                    return $(entry).data('entry-id')
                });

                if (ids.length === 0) {
                    Swal.fire({
                        title: '{{ trans('global.datatables.zero_selected') }}!',
                        timer: 2000,
                        timerProgressBar: true,
                    });
                    return;
                }

                if (ids.length === 0) {
                    Swal.fire({
                        title: '{{ trans('global.datatables.zero_selected') }}!',
                        timer: 2000,
                        timerProgressBar: true,
                    });
                    return;
                }
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Update it!'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            headers: {
                                'x-csrf-token': _token,
                            },
                            method: 'POST',
                            url: "{{ route('admin.products.massUpdate') }}",
                            data: {
                                ids: ids,
                                action: action,
                                value: value,
                                _token: '{{ csrf_token() }}'
                            },
                        })
                        .done(function() {
                            location.reload();
                        });
                        Swal.fire(
                        'Updated!',
                        'Product has been Updated.',
                        'success'
                        )
                    }
                });
                // if (confirm('{{ trans('global.areYouSure') }}')) {
                //     $.ajax({
                //             headers: {
                //                 'x-csrf-token': _token,
                //             },
                //             method: 'POST',
                //             url: "{{ route('admin.products.massUpdate') }}",
                //             data: {
                //                 ids: ids,
                //                 action: action,
                //                 value: value,
                //                 _token: '{{ csrf_token() }}'
                //             },
                //         })
                //         .done(function() {
                //             location.reload();
                //         });
                // }
            }

            function getActiveButton(value, id) {
                return value ?
                    '<button class="border-0 text-success bg-transparent btn-active" data-id="' + id +
                    '"><i class="fa-solid fa-circle-check"></i></button>' :
                    '<button class="border-0 text-danger bg-transparent btn-inactive" data-id="' + id +
                    '"><i class="fa-solid fa-circle-xmark"></i></button>';
            }

            function getIsHomeButton(value, id) {
                return value ?
                    '<button class="border-0 text-success bg-transparent btn-true" data-id="' + id +
                    '"><i class="fa-solid fa-circle-check"></i></button>' :
                    '<button class="border-0 text-danger bg-transparent btn-false" data-id="' + id +
                    '"><i class="fa-solid fa-circle-xmark"></i></button>';
            }

            function getIsFeatureButton(value, id) {
                return value ?
                    '<button class="border-0 text-success bg-transparent btn-feature" data-id="' + id +
                    '"><i class="fa-solid fa-circle-check"></i></button>' :
                    '<button class="border-0 text-danger bg-transparent btn-feature-false" data-id="' + id +
                    '"><i class="fa-solid fa-circle-xmark"></i></button>';
            }

            // Handle click event for Active/Inactive buttons
            $(document).on('click', '.btn-active, .btn-inactive', function() {
                var id = $(this).data('id');
                var isActive = $(this).hasClass('btn-active');
                var newStatus = isActive ? 0 : 1;
                updateStatus(id, 'is_active', newStatus);
            });

            // Handle click event for IsHome buttons
            $(document).on('click', '.btn-true, .btn-false', function() {
                var id = $(this).data('id');
                var isTrue = $(this).hasClass('btn-true');
                var newStatus = isTrue ? 0 : 1;
                updateStatus(id, 'is_home', newStatus);
            });
             // Handle click event for IsFeature buttons
             $(document).on('click', '.btn-feature, .btn-feature-false', function() {
                var id = $(this).data('id');
                var isFeatured = $(this).hasClass('btn-feature');
                var newStatus = isFeatured ? 0 : 1;
                updateStatus(id, 'isFeatured', newStatus);
            });

            // AJAX function to update status in the database
            function updateStatus(id, field, value) {
                $.ajax({
                    url: "{{ route('admin.products.updateStatus') }}",
                    type: "POST",
                    data: {
                        id: id,
                        field: field,
                        value: value,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#datatable-Product').DataTable().ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }

            // //Restore state
            // var state = table.state.loaded();
            // if (state) {
            //     table.columns().every(function(columnIndex) {
            //         var colSearch = state.columns[columnIndex].search;
            //         console.log(colSearch);
            //         if (colSearch.search) {
            //             $('.search-input', table.column(columnIndex).header()).val(colSearch.search);
            //         }
            //     });

            //     table.draw();
            // }


            // Apply column search
            table.columns().every(function(columnIndex) {
                var that = this;

                $('.search-input', this.table().header()).on('keyup change', function() {
                    const columnIndex = $(this).closest('th').index();
                    if (that.column(columnIndex).search() !== this.value) {
                        that
                            .column(columnIndex)
                            .search(this.value)
                            .draw();
                    }
                }).on('click', function(e) {
                    e.stopPropagation();
                });
                // Prevent sorting when clicking on select options
                $('select', this.header()).on('click', function(e) {
                    e.stopPropagation();

                }).on('change', function() {
                    const columnIndex = $(this).closest('th').index();
                    if (that.column(columnIndex).search() !== this.value) {
                        that.column(columnIndex).search(this.value).draw();
                    }
                });
            });

        })
    </script>
@endsection
