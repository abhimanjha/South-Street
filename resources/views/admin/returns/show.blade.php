@extends('layouts.admin')

@section('title', 'Return Details - ' . $return->return_number)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-undo me-2"></i>Return Request Details</h2>
        <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Returns
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Return Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Return Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Return Number:</strong><br>
                            <span class="text-primary fs-5">{{ $return->return_number }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Current Status:</strong><br>
                            <span class="badge bg-{{ $return->status_badge_color }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $return->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Customer:</strong><br>
                            {{ $return->user->name }}<br>
                            <small class="text-muted">{{ $return->user->email }}</small>
                        </div>
                        <div class="col-md-6">
                            <strong>Requested On:</strong><br>
                            {{ $return->requested_at->format('M d, Y h:i A') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Reason:</strong><br>
                            {{ ucfirst(str_replace('_', ' ', $return->reason)) }}
                        </div>
                        <div class="col-md-6">
                            <strong>Refund Amount:</strong><br>
                            <span class="fw-bold text-success fs-5">₹{{ number_format($return->refund_amount, 2) }}</span>
                        </div>
                    </div>

                    @if($return->description)
                    <div class="mb-3">
                        <strong>Description:</strong><br>
                        <p class="text-muted">{{ $return->description }}</p>
                    </div>
                    @endif

                    @if($return->images && count($return->images) > 0)
                    <div class="mb-3">
                        <strong>Product Photos:</strong><br>
                        <div class="row mt-2">
                            @foreach($return->images as $image)
                            <div class="col-md-3 mb-2">
                                <a href="{{ asset('storage/' . $image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded border" alt="Return photo">
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Order ID:</strong> #{{ $return->order->id }}</p>
                    <p><strong>Order Date:</strong> {{ $return->order->created_at->format('M d, Y') }}</p>
                    <p><strong>Delivered On:</strong> {{ $return->order->delivered_at ? $return->order->delivered_at->format('M d, Y') : 'N/A' }}</p>
                    
                    <h6 class="mt-3">Order Items:</h6>
                    @foreach($return->order->items as $item)
                    <div class="d-flex align-items-center border-bottom py-2">
                        <div class="flex-grow-1">
                            <strong>{{ $item->product->name ?? 'Product' }}</strong><br>
                            <small class="text-muted">Qty: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}</small>
                        </div>
                        <div>
                            <strong>₹{{ number_format($item->quantity * $item->price, 2) }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Bank Details -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Refund Bank Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Account Holder:</strong><br>{{ $return->account_holder_name }}</p>
                            <p><strong>Account Number:</strong><br>{{ $return->bank_account }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>IFSC Code:</strong><br>{{ $return->ifsc_code }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Update Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.returns.update-status', $return) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="requested" {{ $return->status == 'requested' ? 'selected' : '' }}>Requested</option>
                                <option value="approved" {{ $return->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $return->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="picked_up" {{ $return->status == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                                <option value="received" {{ $return->status == 'received' ? 'selected' : '' }}>Received</option>
                                <option value="refund_processed" {{ $return->status == 'refund_processed' ? 'selected' : '' }}>Refund Processed</option>
                                <option value="completed" {{ $return->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Admin Notes</label>
                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="4" placeholder="Add notes about this return...">{{ $return->admin_notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Status Guide -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Status Guide</h6>
                </div>
                <div class="card-body">
                    <small>
                        <p><strong>Requested:</strong> Customer submitted return request</p>
                        <p><strong>Approved:</strong> Return request approved by admin</p>
                        <p><strong>Rejected:</strong> Return request rejected</p>
                        <p><strong>Picked Up:</strong> Product picked up by courier</p>
                        <p><strong>Received:</strong> Product received at warehouse</p>
                        <p><strong>Refund Processed:</strong> Refund initiated (within 24hrs)</p>
                        <p class="mb-0"><strong>Completed:</strong> Return process completed</p>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
