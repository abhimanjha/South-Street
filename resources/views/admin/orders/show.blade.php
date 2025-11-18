@extends('layouts.admin')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-shopping-cart me-2"></i>Order Details</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Orders
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Order Information -->
        <div class="col-md-8">
            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Variant</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->product_name }}</strong>
                                        </td>
                                        <td>{{ $item->variant_details ?? 'N/A' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>₹{{ number_format($item->price, 2) }}</td>
                                        <td>₹{{ number_format($item->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Update Status -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Update Order Status</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Order Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="out_for_delivery" {{ $order->status == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="out_of_stock" {{ $order->status == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="returned" {{ $order->status == 'returned' ? 'selected' : '' }}>Returned</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tracking Number</label>
                                <input type="text" name="tracking_number" class="form-control" 
                                       value="{{ $order->tracking_number }}" placeholder="Enter tracking number">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Courier Service</label>
                                <input type="text" name="courier_service" class="form-control" 
                                       value="{{ $order->courier_service }}" placeholder="e.g., FedEx, DHL">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Admin Notes</label>
                            <textarea name="admin_notes" class="form-control" rows="3" 
                                      placeholder="Internal notes about this order...">{{ $order->admin_notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Order Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Order Number:</strong></td>
                            <td>{{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date:</strong></td>
                            <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge bg-{{ $order->status_color }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Payment:</strong></td>
                            <td>
                                @if($order->payment_status == 'completed')
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Payment Method:</strong></td>
                            <td>{{ strtoupper($order->payment_method) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Customer</h5>
                </div>
                <div class="card-body">
                    <p><strong>{{ $order->user->name }}</strong></p>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i>{{ $order->user->email }}</p>
                    @if($order->user->phone)
                        <p class="mb-0"><i class="fas fa-phone me-2"></i>{{ $order->user->phone }}</p>
                    @endif
                </div>
            </div>

            <!-- Shipping Address -->
            @if($order->address)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Shipping Address</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $order->address->name }}</strong></p>
                    <p class="mb-1">{{ $order->address->street }}</p>
                    <p class="mb-1">{{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}</p>
                    <p class="mb-0"><i class="fas fa-phone me-2"></i>{{ $order->address->phone }}</p>
                </div>
            </div>
            @endif

            <!-- Order Summary -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Order Summary</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td>Subtotal:</td>
                            <td class="text-end">₹{{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        @if($order->discount > 0)
                        <tr>
                            <td>Discount:</td>
                            <td class="text-end text-danger">-₹{{ number_format($order->discount, 2) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td>Shipping:</td>
                            <td class="text-end">₹{{ number_format($order->shipping_charge, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Tax:</td>
                            <td class="text-end">₹{{ number_format($order->tax, 2) }}</td>
                        </tr>
                        <tr class="table-primary">
                            <td><strong>Total:</strong></td>
                            <td class="text-end"><strong>₹{{ number_format($order->total, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection







