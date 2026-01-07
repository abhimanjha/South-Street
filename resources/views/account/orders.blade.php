@extends('layouts.app')

@section('content')
<style>
/* Disable all card hover effects on orders page to prevent jumping */
.card:hover {
    transform: none !important;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08) !important;
}

.card:hover .card-img-top,
.card:hover .bg-light {
    transform: none !important;
}

.card:hover::before {
    opacity: 0 !important;
}

.card:hover .fw-bold {
    color: inherit !important;
    transform: none !important;
}

/* Disable button hover effects that might cause movement */
.card .btn:hover {
    transform: none !important;
}

/* Ensure stable form elements */
.card form,
.card .form-control,
.card .btn {
    transition: none !important;
}
</style>
<div class="container">
    <h2 class="mb-4">My Orders</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($orders->count())
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>{{ $order->items->count() }} item(s)</td>
                            <td>₹{{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'shipped' ? 'primary' : ($order->status === 'delivered' ? 'success' : 'secondary'))) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('account.orders.show', $order) }}" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                <a href="{{ route('account.orders.track', $order) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-truck me-1"></i>Track
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $orders->links() }}
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-bag text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3 text-muted">No Orders Yet</h4>
            <p class="text-muted">Start shopping to see your orders here.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-shopping-cart me-2"></i>Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection
