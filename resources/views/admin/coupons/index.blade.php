@extends('layouts.admin')

@section('title', 'Coupons Management')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-tags me-2"></i>Coupons Management</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Coupon
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

    <!-- Coupons Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Usage</th>
                            <th>Valid Period</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $coupon->code }}</strong>
                                </td>
                                <td>
                                    {{ $coupon->description ?? 'No description' }}
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst($coupon->discount_type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($coupon->discount_type === 'percentage')
                                        {{ $coupon->discount_percentage ?? $coupon->discount_value }}%
                                    @else
                                        ₹{{ number_format($coupon->discount_value, 2) }}
                                    @endif
                                    
                                    @if($coupon->max_discount_amount)
                                        <br><small class="text-muted">Max: ₹{{ number_format($coupon->max_discount_amount, 2) }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $coupon->used_count }}
                                    @if($coupon->usage_limit)
                                        / {{ $coupon->usage_limit }}
                                    @else
                                        / ∞
                                    @endif
                                    
                                    @if($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit)
                                        <br><span class="badge bg-danger">Limit Reached</span>
                                    @endif
                                </td>
                                <td>
                                    @if($coupon->valid_from)
                                        <small class="text-muted">From: {{ $coupon->valid_from->format('M d, Y') }}</small><br>
                                    @endif
                                    @if($coupon->valid_until)
                                        <small class="text-muted">Until: {{ $coupon->valid_until->format('M d, Y') }}</small>
                                    @else
                                        <small class="text-muted">No expiry</small>
                                    @endif
                                </td>
                                <td>
                                    @if($coupon->isValid())
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        @if(!$coupon->is_active)
                                            <span class="badge bg-secondary">Inactive</span>
                                        @elseif($coupon->valid_until && $coupon->valid_until->isPast())
                                            <span class="badge bg-danger">Expired</span>
                                        @elseif($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit)
                                            <span class="badge bg-danger">Used Up</span>
                                        @else
                                            <span class="badge bg-warning">Invalid</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $coupon->id }}" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $coupon->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete Coupon</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete the coupon <strong>{{ $coupon->code }}</strong>?</p>
                                            <p class="text-muted">This action cannot be undone.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No coupons found</p>
                                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create First Coupon
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $coupons->links() }}
            </div>
        </div>
    </div>
</div>
@endsection