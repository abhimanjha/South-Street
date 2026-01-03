@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
        <div class="text-muted">
            <i class="fas fa-calendar me-2"></i>{{ now()->format('l, F d, Y') }}
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Total Orders</div>
                            <div class="h4 mb-0">{{ number_format($totalOrders) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i class="fas fa-rupee-sign fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Total Revenue</div>
                            <div class="h4 mb-0">₹{{ number_format($totalRevenue, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <i class="fas fa-box fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Total Products</div>
                            <div class="h4 mb-0">{{ number_format($totalProducts) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded p-3">
                                <i class="fas fa-users fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Total Customers</div>
                            <div class="h4 mb-0">{{ number_format($totalCustomers) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-shopping-cart me-2"></i>View Orders
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.products.create') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-plus me-2"></i>Add Product
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-users me-2"></i>View Customers
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.custom-tailoring.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-cut me-2"></i>Custom Tailoring
                                @if($pendingTailoringRequests > 0)
                                    <span class="badge bg-secondary ms-2">{{ $pendingTailoringRequests }}</span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Recent Orders</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td><strong>#{{ $order->id }}</strong></td>
                                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                                            <td>₹{{ number_format($order->total, 2) }}</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'confirmed' => 'info',
                                                        'processing' => 'primary',
                                                        'shipped' => 'primary',
                                                        'delivered' => 'success',
                                                        'cancelled' => 'danger',
                                                    ];
                                                    $color = $statusColors[$order->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No orders yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Summary</h5>
                </div>
                <div class="card-body">
                    @php
                        $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->count();
                        $deliveredOrdersCount = \App\Models\Order::where('status', 'delivered')->count();
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Pending Orders</span>
                            <strong>{{ $pendingOrdersCount }}</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" 
                                 style="width: {{ $totalOrders > 0 ? ($pendingOrdersCount / $totalOrders * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Completed Orders</span>
                            <strong>{{ $deliveredOrdersCount }}</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ $totalOrders > 0 ? ($deliveredOrdersCount / $totalOrders * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Pending Designs</span>
                            <strong>{{ $pendingDesigns }}</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info"
                                 style="width: {{ $pendingDesigns > 0 ? min(100, ($pendingDesigns / 10 * 100)) : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Pending Tailoring</span>
                            <strong>{{ $pendingTailoringRequests }}</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-secondary"
                                 style="width: {{ $pendingTailoringRequests > 0 ? min(100, ($pendingTailoringRequests / 10 * 100)) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock Products -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert</h5>
                </div>
                <div class="card-body">
                    @php
                        $lowStockProducts = \App\Models\Product::where('stock_quantity', '<=', 10)
                            ->where('stock_quantity', '>', 0)
                            ->limit(5)
                            ->get();
                    @endphp
                    @if($lowStockProducts->count() > 0)
                        <ul class="list-unstyled mb-0">
                            @foreach($lowStockProducts as $product)
                                <li class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small">{{ $product->name }}</span>
                                        <span class="badge bg-warning">{{ $product->stock_quantity }} left</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-warning w-100 mt-3">
                            View All Products
                        </a>
                    @else
                        <p class="text-muted small mb-0">All products have sufficient stock</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

