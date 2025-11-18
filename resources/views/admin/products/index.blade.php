@extends('layouts.admin')

@section('title', 'Products Management')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-box me-2"></i>Products Management</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Product
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Products Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    @if($product->primaryImage)
                                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" 
                                             alt="{{ $product->name }}" 
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px; border-radius: 5px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong><br>
                                    <small class="text-muted">SKU: {{ $product->sku }}</small>
                                </td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>
                                    @if($product->discount_price)
                                        <span class="text-decoration-line-through text-muted">₹{{ number_format($product->price, 2) }}</span><br>
                                        <strong class="text-danger">₹{{ number_format($product->discount_price, 2) }}</strong>
                                    @else
                                        <strong>₹{{ number_format($product->price, 2) }}</strong>
                                    @endif
                                </td>
                                <td>
                                    @if($product->stock_quantity > 0)
                                        <span class="badge bg-success">{{ $product->stock_quantity }} in stock</span>
                                    @else
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Stock Management Dropdown -->
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info dropdown-toggle" 
                                                    data-bs-toggle="dropdown" title="Stock Management">
                                                <i class="fas fa-warehouse"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="#" 
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#stockModal{{ $product->id }}">
                                                        <i class="fas fa-edit me-2"></i>Update Stock
                                                    </a>
                                                </li>
                                                @if($product->stock_quantity > 0)
                                                    <li>
                                                        <form method="POST" 
                                                              action="{{ route('admin.products.mark-out-of-stock', $product) }}" 
                                                              class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-times-circle me-2"></i>Mark Out of Stock
                                                            </button>
                                                        </form>
                                                    </li>
                                                @else
                                                    <li>
                                                        <form method="POST" 
                                                              action="{{ route('admin.products.mark-in-stock', $product) }}" 
                                                              class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="fas fa-check-circle me-2"></i>Mark In Stock
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Stock Update Modal -->
                            <div class="modal fade" id="stockModal{{ $product->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Stock - {{ $product->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.products.update-stock', $product) }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Current Stock: <strong>{{ $product->stock_quantity }}</strong></label>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Action</label>
                                                    <select name="action" class="form-select" required>
                                                        <option value="set">Set Stock Quantity</option>
                                                        <option value="add">Add to Stock</option>
                                                        <option value="subtract">Subtract from Stock</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Quantity</label>
                                                    <input type="number" name="stock_quantity" class="form-control" 
                                                           min="0" required placeholder="Enter quantity">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Update Stock</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No products found</p>
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Add First Product
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection







