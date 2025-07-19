@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Best Seller Products</h3>
                        <div>
                            <a href="{{ route('admin.best-seller.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Best Seller Products
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($bestSellerProducts->count() > 0)
                        <form id="mass-destroy-form" action="{{ route('admin.best-seller.massDestroy') }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="10">
                                                <input type="checkbox" id="select-all">
                                            </th>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Sale Price</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bestSellerProducts as $product)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="ids[]" value="{{ $product->id }}" class="select-item">
                                                </td>
                                                <td>{{ $product->id }}</td>
                                                <td>
                                                    @if($product->image)
                                                        <img src="{{ $product->image }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div style="width: 50px; height: 50px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $product->name }}</td>
                                                <td>
                                                    @if($product->categories->count() > 0)
                                                        {{ $product->categories->first()->name }}
                                                    @else
                                                        <span class="text-muted">No Category</span>
                                                    @endif
                                                </td>
                                                <td>${{ number_format($product->price, 2) }}</td>
                                                <td>
                                                    @if($product->sale_price)
                                                        ${{ number_format($product->sale_price, 2) }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $product->status == 'publish' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($product->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.best-seller.show', $product->id) }}" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.best-seller.edit', $product->id) }}" class="btn btn-sm btn-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger remove-best-seller" data-product-id="{{ $product->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove selected products from best sellers?')">
                                    <i class="fas fa-trash"></i> Remove Selected
                                </button>
                            </div>
                        </form>
                        <div class="mt-3">
                            {{ $bestSellerProducts->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No Best Seller Products</h4>
                            <p class="text-muted">You haven't added any products as best sellers yet.</p>
                            <a href="{{ route('admin.best-seller.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Best Seller Products
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select all functionality
    $('#select-all').change(function() {
        $('.select-item').prop('checked', $(this).is(':checked'));
    });

    // Remove individual best seller
    $('.remove-best-seller').click(function() {
        const productId = $(this).data('product-id');
        if (confirm('Are you sure you want to remove this product from best sellers?')) {
            $.ajax({
                url: '{{ route("admin.best-seller.updateStatus") }}',
                method: 'POST',
                data: {
                    product_id: productId,
                    status: false,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function() {
                    alert('An error occurred while removing the product from best sellers.');
                }
            });
        }
    });
});
</script>
@endpush 