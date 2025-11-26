@extends('layouts.admin')

@section('title', 'Returns & Refunds Management')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-undo me-2"></i>Returns & Refunds</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded p-3">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Pending Requests</div>
                            <div class="h4 mb-0">{{ $returns->where('status', 'requested')->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <i class="fas fa-check fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Approved</div>
                            <div class="h4 mb-0">{{ $returns->where('status', 'approved')->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="fas fa-truck fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">In Transit</div>
                            <div class="h4 mb-0">{{ $returns->whereIn('status', ['picked_up', 'received'])->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Refund Processed</div>
                            <div class="h4 mb-0">{{ $returns->where('status', 'refund_processed')->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Returns Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">All Return Requests</h5>
        </div>
        <div class="card-body">
            @if($returns->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Return #</th>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Reason</th>
                                <th>Amount</th>
                                <th>Requested</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returns as $return)
                                <tr>
                                    <td><strong>{{ $return->return_number }}</strong></td>
                                    <td>#{{ $return->order->id }}</td>
                                    <td>{{ $return->user->name }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $return->reason)) }}</td>
                                    <td>₹{{ number_format($return->refund_amount, 2) }}</td>
                                    <td>{{ $return->requested_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $return->status_badge_color }}">
                                            {{ ucfirst(str_replace('_', ' ', $return->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.returns.show', $return) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $returns->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No return requests yet</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
