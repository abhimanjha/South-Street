@extends('layouts.app')

@section('title', 'Return Request - ' . $return->return_number)

@section('content')
<style>
/* Disable card hover jump effect */
.card:hover {
    transform: none !important;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08) !important;
}
</style>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="fas fa-undo me-2"></i>Return Request Details</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Return Number:</strong><br>
                            <span class="text-primary">{{ $return->return_number }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong><br>
                            <span class="badge bg-{{ $return->status_badge_color }}">
                                {{ ucfirst(str_replace('_', ' ', $return->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Requested On:</strong><br>
                            {{ $return->requested_at->format('M d, Y h:i A') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Refund Amount:</strong><br>
                            <span class="fw-bold text-success">₹{{ number_format($return->refund_amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Reason:</strong><br>
                        {{ ucfirst(str_replace('_', ' ', $return->reason)) }}
                    </div>

                    @if($return->description)
                    <div class="mb-3">
                        <strong>Description:</strong><br>
                        {{ $return->description }}
                    </div>
                    @endif

                    @if($return->images && count($return->images) > 0)
                    <div class="mb-3">
                        <strong>Uploaded Photos:</strong><br>
                        <div class="row mt-2">
                            @foreach($return->images as $image)
                            <div class="col-md-3 mb-2">
                                <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Return photo">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($return->admin_notes)
                    <div class="alert alert-info">
                        <strong>Admin Notes:</strong><br>
                        {{ $return->admin_notes }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Return Timeline -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Return Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ $return->requested_at ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Return Requested</h6>
                                <p class="text-muted">{{ $return->requested_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        <div class="timeline-item {{ $return->approved_at ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Request Approved</h6>
                                <p class="text-muted">{{ $return->approved_at ? $return->approved_at->format('M d, Y h:i A') : 'Pending' }}</p>
                            </div>
                        </div>

                        <div class="timeline-item {{ $return->picked_up_at ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Product Picked Up</h6>
                                <p class="text-muted">{{ $return->picked_up_at ? $return->picked_up_at->format('M d, Y h:i A') : 'Pending' }}</p>
                            </div>
                        </div>

                        <div class="timeline-item {{ $return->received_at ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Product Received</h6>
                                <p class="text-muted">{{ $return->received_at ? $return->received_at->format('M d, Y h:i A') : 'Pending' }}</p>
                            </div>
                        </div>

                        <div class="timeline-item {{ $return->refund_processed_at ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Refund Processed</h6>
                                <p class="text-muted">{{ $return->refund_processed_at ? $return->refund_processed_at->format('M d, Y h:i A') : 'Pending' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Order ID:</strong> #{{ $return->order->id }}</p>
                    <p><strong>Order Date:</strong> {{ $return->order->created_at->format('M d, Y') }}</p>
                    <a href="{{ route('account.orders.show', $return->order) }}" class="btn btn-sm btn-outline-primary w-100">
                        View Order Details
                    </a>
                </div>
            </div>

            <!-- Bank Details -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Refund Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Account Holder:</strong><br>{{ $return->account_holder_name }}</p>
                    <p><strong>Account Number:</strong><br>{{ $return->bank_account }}</p>
                    <p><strong>IFSC Code:</strong><br>{{ $return->ifsc_code }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
    border-color: #28a745;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #e9ecef;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

.timeline-content p {
    margin-bottom: 0;
    font-size: 0.9rem;
}
</style>
@endsection
