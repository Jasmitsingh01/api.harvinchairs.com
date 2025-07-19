@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Add Best Seller Products</h3>
                        <div>
                            <a href="{{ route('admin.best-seller.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Best Sellers
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.best-seller.store') }}" method="POST" id="best-seller-form">
                        @csrf
                        
                        <!-- Search and Filter Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search-product" placeholder="Search products...">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="search-btn">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="category-filter">
                                    <option value="">All Categories</option>
                                    @foreach(\App\Models\Category::where('enabled', true)->get() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="status-filter">
                                    <option value="">All Status</option>
                                    <option value="publish">Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>

                        <!-- Selected Products Summary -->
                        <div class="alert alert-info" id="selected-summary" style="display: none;">
                            <strong>Selected Products:</strong> <span id="selected-count">0</span>
                            <button type="button" class="btn btn-sm btn-outline-info ml-2" id="clear-selection">Clear Selection</button>
                        </div>

                        <!-- Products Grid -->
                        <div class="row" id="products-grid">
                            @foreach($products as $product)
                                <div class="col-md-4 col-lg-3 mb-3 product-item" 
                                     data-category="{{ $product->categories->first()->id ?? '' }}"
                                     data-status="{{ $product->status }}">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input product-checkbox" type="checkbox" 
                                                       name="product_ids[]" value="{{ $product->id }}" 
                                                       id="product-{{ $product->id }}">
                                                <label class="form-check-label" for="product-{{ $product->id }}">
                                                    <div class="text-center mb-2">
                                                        @if($product->image)
                                                            <img src="{{ $product->image }}" alt="{{ $product->name }}" 
                                                                 class="img-fluid" style="max-height: 100px; object-fit: cover;">
                                                        @else
                                                            <div style="height: 100px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                                                <i class="fas fa-image fa-2x text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <h6 class="card-title">{{ $product->name }}</h6>
                                                    <p class="card-text text-muted small">
                                                        @if($product->categories->count() > 0)
                                                            {{ $product->categories->first()->name }}
                                                        @else
                                                            No Category
                                                        @endif
                                                    </p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="text-primary font-weight-bold">
                                                            ${{ number_format($product->price, 2) }}
                                                        </span>
                                                        <span class="badge badge-{{ $product->status == 'publish' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($product->status) }}
                                                        </span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $products->links() }}
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="submit-btn" disabled>
                                <i class="fas fa-star"></i> Add Selected Products as Best Sellers
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let selectedProducts = [];

    // Product selection
    $('.product-checkbox').change(function() {
        const productId = $(this).val();
        const isChecked = $(this).is(':checked');
        
        if (isChecked) {
            if (!selectedProducts.includes(productId)) {
                selectedProducts.push(productId);
            }
        } else {
            selectedProducts = selectedProducts.filter(id => id !== productId);
        }
        
        updateSelectedSummary();
        updateSubmitButton();
    });

    // Update selected summary
    function updateSelectedSummary() {
        const count = selectedProducts.length;
        $('#selected-count').text(count);
        
        if (count > 0) {
            $('#selected-summary').show();
        } else {
            $('#selected-summary').hide();
        }
    }

    // Update submit button
    function updateSubmitButton() {
        const submitBtn = $('#submit-btn');
        if (selectedProducts.length > 0) {
            submitBtn.prop('disabled', false);
            submitBtn.text(`Add ${selectedProducts.length} Product${selectedProducts.length > 1 ? 's' : ''} as Best Sellers`);
        } else {
            submitBtn.prop('disabled', true);
            submitBtn.text('Add Selected Products as Best Sellers');
        }
    }

    // Clear selection
    $('#clear-selection').click(function() {
        $('.product-checkbox').prop('checked', false);
        selectedProducts = [];
        updateSelectedSummary();
        updateSubmitButton();
    });

    // Category filter
    $('#category-filter').change(function() {
        const category = $(this).val();
        if (category) {
            $('.product-item').hide();
            $(`.product-item[data-category="${category}"]`).show();
        } else {
            $('.product-item').show();
        }
    });

    // Status filter
    $('#status-filter').change(function() {
        const status = $(this).val();
        if (status) {
            $('.product-item').hide();
            $(`.product-item[data-status="${status}"]`).show();
        } else {
            $('.product-item').show();
        }
    });

    // Search functionality
    $('#search-btn').click(function() {
        const query = $('#search-product').val();
        if (query) {
            $.ajax({
                url: '{{ route("admin.best-seller.search") }}',
                method: 'GET',
                data: { q: query },
                success: function(products) {
                    updateProductsGrid(products);
                }
            });
        }
    });

    // Update products grid with search results
    function updateProductsGrid(products) {
        const grid = $('#products-grid');
        grid.empty();
        
        products.forEach(function(product) {
            const productHtml = `
                <div class="col-md-4 col-lg-3 mb-3 product-item" 
                     data-category="${product.categories && product.categories.length > 0 ? product.categories[0].id : ''}"
                     data-status="${product.status}">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input product-checkbox" type="checkbox" 
                                       name="product_ids[]" value="${product.id}" 
                                       id="product-${product.id}">
                                <label class="form-check-label" for="product-${product.id}">
                                    <div class="text-center mb-2">
                                        ${product.image ? 
                                            `<img src="${product.image}" alt="${product.name}" class="img-fluid" style="max-height: 100px; object-fit: cover;">` :
                                            `<div style="height: 100px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-image fa-2x text-muted"></i>
                                            </div>`
                                        }
                                    </div>
                                    <h6 class="card-title">${product.name}</h6>
                                    <p class="card-text text-muted small">
                                        ${product.categories && product.categories.length > 0 ? product.categories[0].name : 'No Category'}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary font-weight-bold">
                                            $${parseFloat(product.price).toFixed(2)}
                                        </span>
                                        <span class="badge badge-${product.status == 'publish' ? 'success' : 'warning'}">
                                            ${product.status.charAt(0).toUpperCase() + product.status.slice(1)}
                                        </span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            grid.append(productHtml);
        });
        
        // Reattach event handlers
        $('.product-checkbox').change(function() {
            const productId = $(this).val();
            const isChecked = $(this).is(':checked');
            
            if (isChecked) {
                if (!selectedProducts.includes(productId)) {
                    selectedProducts.push(productId);
                }
            } else {
                selectedProducts = selectedProducts.filter(id => id !== productId);
            }
            
            updateSelectedSummary();
            updateSubmitButton();
        });
    }

    // Form submission
    $('#best-seller-form').submit(function(e) {
        if (selectedProducts.length === 0) {
            e.preventDefault();
            alert('Please select at least one product to add as best seller.');
            return false;
        }
    });
});
</script>
@endpush 