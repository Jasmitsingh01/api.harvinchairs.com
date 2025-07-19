@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Best Seller Product Details</h3>
                        <div>
                            <a href="{{ route('admin.best-seller.edit', $product->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Product
                            </a>
                            <a href="{{ route('admin.best-seller.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Best Sellers
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Product Images -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Product Images</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Main Image -->
                                    <div class="mb-3">
                                        <label class="font-weight-bold">Main Image</label>
                                        <div class="text-center">
                                            @if($product->image)
                                                <img src="{{ $product->image }}" alt="{{ $product->name }}" 
                                                     class="img-fluid" style="max-height: 200px; object-fit: cover;">
                                            @else
                                                <div style="height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-image fa-3x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Gallery Images -->
                                    @if($product->gallery && is_array($product->gallery) && count($product->gallery) > 0)
                                        <div>
                                            <label class="font-weight-bold">Gallery Images</label>
                                            <div class="row">
                                                @foreach($product->gallery as $index => $image)
                                                    <div class="col-6 mb-2">
                                                        <img src="{{ $image['thumbnail'] ?? $image['original'] }}" 
                                                             alt="Gallery Image {{ $index + 1 }}" 
                                                             class="img-fluid" style="max-height: 80px; object-fit: cover;">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Product Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td class="font-weight-bold">Product ID:</td>
                                                    <td>{{ $product->id }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Name:</td>
                                                    <td>{{ $product->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">SKU:</td>
                                                    <td>{{ $product->sku ?: 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Status:</td>
                                                    <td>
                                                        <span class="badge badge-{{ $product->status == 'publish' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($product->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Price:</td>
                                                    <td>${{ number_format($product->price, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Sale Price:</td>
                                                    <td>
                                                        @if($product->sale_price)
                                                            ${{ number_format($product->sale_price, 2) }}
                                                        @else
                                                            <span class="text-muted">No sale price</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td class="font-weight-bold">Quantity:</td>
                                                    <td>{{ $product->quantity }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Unit:</td>
                                                    <td>{{ $product->unit ?: 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Categories:</td>
                                                    <td>
                                                        @if($product->categories->count() > 0)
                                                            @foreach($product->categories as $category)
                                                                <span class="badge badge-info">{{ $category->name }}</span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted">No categories</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Shop:</td>
                                                    <td>{{ $product->shop->name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Best Seller:</td>
                                                    <td>
                                                        <span class="badge badge-{{ $product->is_best_seller ? 'success' : 'secondary' }}">
                                                            {{ $product->is_best_seller ? 'Yes' : 'No' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Created:</td>
                                                    <td>{{ $product->created_at->format('M d, Y H:i') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    @if($product->description)
                                        <div class="mt-3">
                                            <label class="font-weight-bold">Description</label>
                                            <div class="border rounded p-3 bg-light">
                                                {!! nl2br(e($product->description)) !!}
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Dimensions -->
                                    @if($product->height || $product->width || $product->length || $product->weight)
                                        <div class="mt-3">
                                            <label class="font-weight-bold">Dimensions</label>
                                            <div class="row">
                                                @if($product->height)
                                                    <div class="col-md-3">
                                                        <small class="text-muted">Height</small>
                                                        <div>{{ $product->height }}</div>
                                                    </div>
                                                @endif
                                                @if($product->width)
                                                    <div class="col-md-3">
                                                        <small class="text-muted">Width</small>
                                                        <div>{{ $product->width }}</div>
                                                    </div>
                                                @endif
                                                @if($product->length)
                                                    <div class="col-md-3">
                                                        <small class="text-muted">Length</small>
                                                        <div>{{ $product->length }}</div>
                                                    </div>
                                                @endif
                                                @if($product->weight)
                                                    <div class="col-md-3">
                                                        <small class="text-muted">Weight</small>
                                                        <div>{{ $product->weight }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Product Features -->
                            @if($product->productFeatures && $product->productFeatures->count() > 0)
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title">Product Features</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($product->productFeatures as $feature)
                                                <div class="col-md-6 mb-2">
                                                    <strong>{{ $feature->featureValue->feature->name ?? 'Feature' }}:</strong>
                                                    <span>{{ $feature->featureValue->value ?? 'N/A' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Best Seller Statistics -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">Best Seller Statistics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <div class="border rounded p-3">
                                                <h4 class="text-primary">{{ $product->orders->count() }}</h4>
                                                <small class="text-muted">Total Orders</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="border rounded p-3">
                                                <h4 class="text-success">
                                                    ${{ number_format($product->orders->sum('pivot.subtotal'), 2) }}
                                                </h4>
                                                <small class="text-muted">Total Revenue</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="border rounded p-3">
                                                <h4 class="text-info">{{ $product->reviews->count() }}</h4>
                                                <small class="text-muted">Reviews</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 