@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@can('category_create')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route('admin.categories.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.category.title_singular') }}
        </a>
    </div>
</div>
@endcan
<div class="card">
    <div class="card-header">
        @if($is_child == true)
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
              <li class="breadcrumb-item active" aria-current="page"> Child-Categories {{ trans('global.list') }}</li>
            </ol>
        </nav>
        @else
        {{ trans('cruds.category.title_singular') }} {{ trans('global.list') }}
        @endif
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="category-table" class=" table table-bordered table-striped table-hover datatable datatable-Category display">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.category.fields.thumbnail_image') }}
                        </th>
                        <th width="10">
                            {{ trans('cruds.category.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.category.fields.name') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.category.fields.details') }}
                        </th> --}}

                        <th>
                            {{ trans('cruds.category.fields.position') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.category.fields.cover_image') }}
                        </th> --}}

                        <th>
                            {{ trans('cruds.category.fields.sub_category') }}
                        </th>
                        <th>
                            {{ trans('cruds.category.fields.enabled') }}
                        </th>

                        <th>
                            {{ trans('cruds.category.fields.new_arrival') }}
                        </th>
                        <th width="10">
                            {{ trans('cruds.category.fields.is_home') }}
                        </th>
                        <th>

                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>

                        </td>
                        <td width="10">
                            <input class="search input-id" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        {{-- <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td> --}}
                        <td>
                            <input class="search input-id" type="text" placeholder="{{ trans('global.search') }}">
                        </td>

                        <td width="10">
                            <input class="search input-id" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <th>
                            <select class="search-input" strict="true" id="is_enabled" data-column="6">
                                <option value>{{ trans('global.all') }}</option>
                                <option value="1">Active</option>
                                <option value="0">InActive</option>
                            </select>
                        </th>
                        <th>
                            <select class="search-input" strict="true" id="new_arrival" data-column="7">
                                <option value>{{ trans('global.all') }}</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </th>
                        <th width="10">
                            <select class="search-input" strict="true" id="is_home" data-column="8">
                                <option value>{{ trans('global.all') }}</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </th>

                        <td>
                        </td>
                    </tr>
                </thead>
                <tbody class="sortable-list">
                    @foreach($categories as $key => $category)
                        <tr data-entry-id="{{ $category->id }}" id="product_{{ $category->id }}" draggable="true">
                            <td>

                            </td>
                            <td>
                                @if($category->thumbnail_image && is_array($category->thumbnail_image))
                                    <a href="{{ $category->thumbnail_image['url'] }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $category->thumbnail_image['thumbnail'] }}" height="70px" width="70px">
                                    </a>
                                @elseif($category->thumbnail_image )
                                <a href="{{ $category->thumbnail_image->geturl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $category->thumbnail_image->getUrl('thumb') }}" height="70px" width="70px">
                                </a>
                                @endif
                            </td>
                            <td >
                                {{ $category->id ?? '' }}
                            </td>
                            <td>
                                {{ $category->name ?? '' }}
                            </td>
                            {{-- <td>
                                {!! $category->details ?? '' !!}
                            </td> --}}
                            <td class="position">
                                {{ $category->position ?? '' }}
                            </td>
                            {{-- <td>
                                @if($category->cover_image)
                                    <a href="{{ $category->cover_image->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $category->cover_image->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td> --}}

                            <td>
                                {{ $category->children->count() ?? '' }}
                            </td>
                            <td>
                                {{-- <span style="display:none">{{ $category->enabled ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $category->enabled ? 'checked' : '' }}> --}}
                                @if ($category->enabled)
                                <button class="border-0 text-success bg-transparent btn-active" data-id="{{$category->id}}"><i class="fa-solid fa-circle-check"></i></button>
                                @else
                                <button class="border-0 text-danger bg-transparent btn-inactive"  data-id="{{$category->id}}"><i class="fa-solid fa-circle-xmark"></i></button>
                                @endif
                            </td>
                            <td >
                                @if ($category->new_arrival)
                                <button class="border-0 text-success bg-transparent btn-enable" data-id="{{$category->id}}"><i class="fa-solid fa-circle-check"></i></button>
                                @else
                                <button class="border-0 text-danger bg-transparent btn-disable"  data-id="{{$category->id}}"><i class="fa-solid fa-circle-xmark"></i></button>
                                @endif
                            </td>
                            <td >
                                @if ($category->is_home)
                                <button class="border-0 text-success bg-transparent btn-home-true" data-id="{{$category->id}}"><i class="fa-solid fa-circle-check"></i></button>
                                @else
                                <button class="border-0 text-danger bg-transparent btn-home-false"  data-id="{{$category->id}}"><i class="fa-solid fa-circle-xmark"></i></button>
                                @endif
                            </td>
                            {{-- <td>
                                {{ $category->meta_title ?? '' }}
                            </td>
                            <td>
                                {{ $category->meta_keywords ?? '' }}
                            </td> --}}
                            <td>
                                <div class="text-nowrap text-theme-color">
                                    @can('category_show')
                                    <a class="text-theme-color" href="{{ route('admin.categories.show', $category->id) }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @endcan
                                    @can('category_edit')
                                    <a class="text-theme-color" href="{{ route('admin.categories.edit', $category->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    @endcan
                                    @can('category_delete')
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
    @can('category_delete')

        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.categories.massDestroy') }}",
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
    @endcan
        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            pageLength: 100,
        });
        let table = $('.datatable-Category:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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
        let enabledFilterValue = '';
        let newArrivalFilterValue = '';
        $('#is_enabled').on('change', function () {
            enabledFilterValue = this.value;
        applyCustomFilters();
        });

        $('#new_arrival').on('change', function () {
            newArrivalFilterValue = this.value;
            applyCustomFilters();
        });
        function applyCustomFilters() {
            table.column(6).data().each(function (value, index) {
                const statusButton = $(value);
                const isActive = statusButton.hasClass('btn-active');
                console.log(statusButton);
                const isSearchMatchEnabled = enabledFilterValue === '' || (enabledFilterValue === '1' && isActive) || (enabledFilterValue === '0' && !isActive);

                const newArrivalValue = table.column(7).data()[index];
                const newstatusButton = $(newArrivalValue);
                const isNewArrival = newstatusButton.hasClass('btn-enable');
                const isSearchMatchNewArrival = newArrivalFilterValue === '' || (newArrivalFilterValue === '1' && isNewArrival) || (newArrivalFilterValue === '0' && !isNewArrival);

                const isSearchMatch = isSearchMatchEnabled && isSearchMatchNewArrival;
                table.row(index).nodes().to$().css('display', isSearchMatch ? '' : 'none');
            });
        }
        function customEnableFilter(searchValue) {
            table.column(7).data().each(function (value, index) {
                const statusButton = $(value);
                const isActive = statusButton.hasClass('btn-active');
                const isSearchMatch = searchValue === '' || (searchValue === '1' && isActive) || (searchValue === '0' && !isActive);
                table.row(index).nodes().to$().css('display', isSearchMatch ? '' : 'none');
            });
        }

        function customNewArrivalFilter(searchValue) {
            table.column(8).data().each(function (value, index) {
                const statusButton = $(value);
                const isActive = statusButton.hasClass('btn-enable');
                const isSearchMatch = searchValue === '' || (searchValue === '1' && isActive) || (searchValue === '0' && !isActive);
                table.row(index).nodes().to$().css('display', isSearchMatch ? '' : 'none');
            });
        }
    });

</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>

    $(document).ready(function () {
        @can('category_edit')
          // Handle click event for IsHome buttons
          $(document).on('click', '.btn-active, .btn-inactive', function() {
                var id = $(this).data('id');

                var isTrue = $(this).hasClass('btn-active');
                var newStatus = isTrue ? 0 : 1;
                updateStatus(id, 'enabled', newStatus);
            });
            $(document).on('click', '.btn-home-true, .btn-home-false', function() {
                var id = $(this).data('id');

                var isTrue = $(this).hasClass('btn-home-true');
                var newStatus = isTrue ? 0 : 1;
                updateStatus(id, 'is_home', newStatus);
            });
            $(document).on('click', '.btn-enable, .btn-disable', function() {
                var id = $(this).data('id');

                var isTrue = $(this).hasClass('btn-enable');
                var newStatus = isTrue ? 0 : 1;
                updateStatus(id, 'new_arrival', newStatus);
            });

            // AJAX function to update status in the database
            function updateStatus(id, field, value) {
                $.ajax({
                    url: "{{ route('admin.categories.updateStatus') }}",
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
        @endcan
        @can('category_edit')

        $(".sortable-list").sortable({
            axis: 'y',
            update: function (event, ui) {
                var data = $(this).sortable('serialize');
                $.ajax({
                     headers: {
                         'X-CSRF-TOKEN': "{{ csrf_token() }}"
                     },
                    url: '{{ route("admin.categories.updatePositions") }}',
                    type: 'POST',
                    data: data,
                    success: function (response) {
                     updateDisplayedPositions(response.positions);
                    }
                });
            }
        });
        @endcan
    });
function updateDisplayedPositions(positions) {
        $('#category-table tbody tr').each(function(index) {
            // Get the product ID from the row data attribute
            var productId = $(this).data('entryId');
            // Find the position for the corresponding product ID
            var position = positions[productId];
            // Update the position number in the relevant column
            $(this).find('.position').text(position);
        });
    }
</script>
@endsection
