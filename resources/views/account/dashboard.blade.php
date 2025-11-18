@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">My Account Dashboard</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-user fa-3x text-primary mb-3"></i>
                    <h5>{{ auth()->user()?->name ?? 'User' }}</h5>
                    <p class="text-muted">{{ auth()->user()?->email ?? 'No email' }}</p>
                    <a href="{{ route('account.profile') }}" class="btn btn-outline-primary">Edit Profile</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5><i class="fas fa-shopping-bag text-success me-2"></i>Recent Orders</h5>
                            <p class="mb-0">{{ $recentOrders->count() }} recent orders</p>
                            <a href="{{ route('account.orders') }}" class="btn btn-sm btn-success mt-2">View All Orders</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5><i class="fas fa-map-marker-alt text-info me-2"></i>Addresses</h5>
                            <p class="mb-0">{{ auth()->user()?->addresses?->count() ?? 0 }} saved addresses</p>
                            <a href="{{ route('account.addresses') }}" class="btn btn-sm btn-info mt-2">Manage Addresses</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5><i class="fas fa-heart text-danger me-2"></i>Wishlist</h5>
                            <p class="mb-0">{{ auth()->user()?->wishlist?->count() ?? 0 }} items in wishlist</p>
                            <a href="{{ route('wishlist.index') }}" class="btn btn-sm btn-danger mt-2">View Wishlist</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5><i class="fas fa-cog text-secondary me-2"></i>Account Settings</h5>
                            <p class="mb-0">Manage your account preferences</p>
                            <a href="{{ route('account.profile') }}" class="btn btn-sm btn-secondary mt-2">Settings</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logout Section -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5><i class="fas fa-sign-out-alt text-danger me-2"></i>Logout</h5>
                    <p class="mb-3 text-muted">Sign out from your account</p>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>

            @if($recentOrders->count())
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Recent Orders</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'completed' ? 'success' : 'secondary') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                            <td>
                                                <a href="{{ route('account.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
