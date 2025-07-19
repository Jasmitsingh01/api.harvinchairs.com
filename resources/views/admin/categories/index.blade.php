@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@can('category_create')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a class="btn btn-success" href="{{ route('admin.categories.create') }}">
                    <i class="fas fa-plus"></i> {{ trans('global.add') }} {{ trans('cruds.category.title_singular') }}
                </a>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importModal">
                    <i class="fas fa-upload"></i> Import Categories
                </button>
                <a class="btn btn-secondary" href="{{ route('admin.categories.export') }}">
                    <i class="fas fa-download"></i> Export Categories
                </a>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#bulkActionModal">
                    <i class="fas fa-tasks"></i> Bulk Actions
                </button>
            </div>
        </div>
    </div>
</div>
@endcan

<!-- Category Statistics -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Categories</h5>
                <h3 id="total-categories">-</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Enabled Categories</h5>
                <h3 id="enabled-categories">-</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">With Products</h5>
                <h3 id="categories-with-products">-</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">With Subcategories</h5>
                <h3 id="categories-with-children">-</h3>
            </div>
        </div>
    </div>
</div>

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
        <!-- Search and Filter Section -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="category-search" placeholder="Search categories...">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="status-filter">
                    <option value="">All Status</option>
                    <option value="1">Enabled</option>
                    <option value="0">Disabled</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="type-filter">
                    <option value="">All Types</option>
                    <option value="with_products">With Products</option>
                    <option value="with_children">With Subcategories</option>
                    <option value="empty">Empty Categories</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table id="category-table" class=" table table-bordered table-striped table-hover datatable datatable-Category display">
                <thead>
                    <tr>
                        <th width="10">
                            <input type="checkbox" id="select-all-categories">
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
                        <th>
                            {{ trans('cruds.category.fields.position') }}
                        </th>
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
                            Actions
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
                                <input type="checkbox" class="category-checkbox" value="{{ $category->id }}">
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
                            <td class="position">
                                {{ $category->position ?? '' }}
                            </td>
                            <td>
                                {{ $category->children->count() ?? '' }}
                            </td>
                            <td>
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
                            <td>
                                <div class="btn-group" role="group">
                                    @can('category_show')
                                    <a class="btn btn-sm btn-info" href="{{ route('admin.categories.show', $category->id) }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @endcan
                                    @can('category_edit')
                                    <a class="btn btn-sm btn-warning" href="{{ route('admin.categories.edit', $category->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    @endcan
                                    @can('category_create')
                                    <a class="btn btn-sm btn-secondary" href="{{ route('admin.categories.duplicate', $category->id) }}" title="Duplicate">
                                        <i class="fa-solid fa-copy"></i>
                                    </a>
                                    @endcan
                                    @can('category_delete')
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
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

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Action:</label>
                    <select class="form-control" id="bulk-action">
                        <option value="">Select Action</option>
                        <option value="enable">Enable Categories</option>
                        <option value="disable">Disable Categories</option>
                        <option value="delete">Delete Categories</option>
                        <option value="move">Move Categories</option>
                    </select>
                </div>
                <div class="form-group" id="parent-select-group" style="display: none;">
                    <label>Move to Parent Category:</label>
                    <select class="form-control" id="parent-category">
                        <option value="">Select Parent Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="alert alert-info">
                    <strong>Selected Categories:</strong> <span id="selected-count">0</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="apply-bulk-action">Apply Action</button>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Categories</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.categories.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>CSV File:</label>
                        <input type="file" class="form-control" name="csv_file" accept=".csv,.txt" required>
                        <small class="form-text text-muted">Upload a CSV file with columns: Name, Slug, Parent, Position, Enabled</small>
                    </div>
                    <div class="alert alert-info">
                        <strong>CSV Format:</strong><br>
                        Name, Slug, Parent, Position, Enabled<br>
                        Furniture, furniture, 0, 1, Yes<br>
                        Chairs, chairs, 1, 2, Yes
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Load category statistics
    loadCategoryStats();
    
    // Select all functionality
    $('#select-all-categories').change(function() {
        $('.category-checkbox').prop('checked', $(this).is(':checked'));
        updateSelectedCount();
    });
    
    // Update selected count
    $('.category-checkbox').change(function() {
        updateSelectedCount();
    });
    
    function updateSelectedCount() {
        const count = $('.category-checkbox:checked').length;
        $('#selected-count').text(count);
    }
    
    // Bulk action functionality
    $('#bulk-action').change(function() {
        const action = $(this).val();
        if (action === 'move') {
            $('#parent-select-group').show();
        } else {
            $('#parent-select-group').hide();
        }
    });
    
    $('#apply-bulk-action').click(function() {
        const selectedCategories = $('.category-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
        
        if (selectedCategories.length === 0) {
            alert('Please select at least one category.');
            return;
        }
        
        const action = $('#bulk-action').val();
        if (!action) {
            alert('Please select an action.');
            return;
        }
        
        const data = {
            category_ids: selectedCategories,
            action: action,
            _token: '{{ csrf_token() }}'
        };
        
        if (action === 'move') {
            const parentId = $('#parent-category').val();
            if (!parentId) {
                alert('Please select a parent category.');
                return;
            }
            data.parent_id = parentId;
        }
        
        $.ajax({
            url: '{{ route("admin.categories.bulkUpdate") }}',
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                }
            },
            error: function() {
                alert('An error occurred while performing the bulk action.');
            }
        });
    });
    
    // Search functionality
    $('#search-btn').click(function() {
        const query = $('#category-search').val();
        if (query) {
            $.ajax({
                url: '{{ route("admin.categories.search") }}',
                method: 'GET',
                data: { q: query },
                success: function(categories) {
                    updateCategoryTable(categories);
                }
            });
        }
    });
    
    function updateCategoryTable(categories) {
        const tbody = $('#category-table tbody');
        tbody.empty();
        
        categories.forEach(function(category) {
            const row = `
                <tr data-entry-id="${category.id}">
                    <td><input type="checkbox" class="category-checkbox" value="${category.id}"></td>
                    <td>
                        ${category.thumbnail_image ? 
                            `<a href="${category.thumbnail_image.url}" target="_blank"><img src="${category.thumbnail_image.thumbnail}" height="70px" width="70px"></a>` : 
                            '<div style="width: 70px; height: 70px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;"><i class="fas fa-image"></i></div>'
                        }
                    </td>
                    <td>${category.id}</td>
                    <td>${category.name}</td>
                    <td>${category.position}</td>
                    <td>${category.children ? category.children.length : 0}</td>
                    <td>
                        <button class="border-0 text-${category.enabled ? 'success' : 'danger'} bg-transparent btn-${category.enabled ? 'active' : 'inactive'}" data-id="${category.id}">
                            <i class="fa-solid fa-circle-${category.enabled ? 'check' : 'xmark'}"></i>
                        </button>
                    </td>
                    <td>
                        <button class="border-0 text-${category.new_arrival ? 'success' : 'danger'} bg-transparent btn-${category.new_arrival ? 'enable' : 'disable'}" data-id="${category.id}">
                            <i class="fa-solid fa-circle-${category.new_arrival ? 'check' : 'xmark'}"></i>
                        </button>
                    </td>
                    <td>
                        <button class="border-0 text-${category.is_home ? 'success' : 'danger'} bg-transparent btn-home-${category.is_home ? 'true' : 'false'}" data-id="${category.id}">
                            <i class="fa-solid fa-circle-${category.is_home ? 'check' : 'xmark'}"></i>
                        </button>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a class="btn btn-sm btn-info" href="/admin/categories/${category.id}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a class="btn btn-sm btn-warning" href="/admin/categories/${category.id}/edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a class="btn btn-sm btn-secondary" href="/admin/categories/${category.id}/duplicate">
                                <i class="fa-solid fa-copy"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }
    
    function loadCategoryStats() {
        $.ajax({
            url: '{{ route("admin.categories.stats") }}',
            method: 'GET',
            success: function(stats) {
                $('#total-categories').text(stats.total_categories);
                $('#enabled-categories').text(stats.enabled_categories);
                $('#categories-with-products').text(stats.categories_with_products);
                $('#categories-with-children').text(stats.categories_with_children);
            }
        });
    }
    
    // Status update functionality
    $('.btn-active, .btn-inactive').click(function() {
        const categoryId = $(this).data('id');
        const currentStatus = $(this).hasClass('btn-active');
        const newStatus = !currentStatus;
        
        $.ajax({
            url: '{{ route("admin.categories.updateStatus") }}',
            method: 'POST',
            data: {
                category_id: categoryId,
                field: 'enabled',
                value: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            }
        });
    });
});
</script>
@endpush
