@extends('layouts.admin')

@section('title', 'Custom Design Details - #' . $customDesign->id)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-palette me-2"></i>Custom Design Details</h2>
        <a href="{{ route('admin.custom-designs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Custom Designs
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Design Information -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Design Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Design ID:</strong></td>
                            <td>#{{ $customDesign->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Title:</strong></td>
                            <td>{{ $customDesign->title ?? 'Untitled' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Design Type:</strong></td>
                            <td>{{ $customDesign->design_type ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge bg-{{ $customDesign->status_color }}">
                                    {{ ucfirst(str_replace('_', ' ', $customDesign->status)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Submitted:</strong></td>
                            <td>{{ $customDesign->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    </table>

                    @if($customDesign->description)
                        <div class="mt-3">
                            <strong>Description:</strong>
                            <p class="mt-2">{{ $customDesign->description }}</p>
                        </div>
                    @endif

                    @if($customDesign->notes)
                        <div class="mt-3">
                            <strong>Customer Notes:</strong>
                            <p class="mt-2 text-muted">{{ $customDesign->notes }}</p>
                        </div>
                    @endif

                    @if($customDesign->images && count($customDesign->images) > 0)
                        <div class="mt-3">
                            <strong>Design Images:</strong>
                            <div class="row g-2 mt-2">
                                @foreach($customDesign->images as $image)
                                    <div class="col-md-3">
                                        <img src="{{ asset('storage/' . $image) }}" 
                                             alt="Design Image" 
                                             class="img-thumbnail" 
                                             style="width: 100%; height: 150px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Update Status -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Update Status</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.custom-designs.update-status', $customDesign) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="submitted" {{ $customDesign->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="under_review" {{ $customDesign->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="approved" {{ $customDesign->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $customDesign->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="in_production" {{ $customDesign->status == 'in_production' ? 'selected' : '' }}>In Production</option>
                                <option value="completed" {{ $customDesign->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Admin Notes</label>
                            <textarea name="admin_notes" class="form-control" rows="3" 
                                      placeholder="Internal notes about this design...">{{ $customDesign->admin_notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Customer</h5>
                </div>
                <div class="card-body">
                    @if($customDesign->user)
                        <p><strong>{{ $customDesign->user->name }}</strong></p>
                        <p class="mb-1"><i class="fas fa-envelope me-2"></i>{{ $customDesign->user->email }}</p>
                        @if($customDesign->user->phone)
                            <p class="mb-0"><i class="fas fa-phone me-2"></i>{{ $customDesign->user->phone }}</p>
                        @endif
                    @else
                        <p class="text-muted">Customer information not available</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.custom-designs.destroy', $customDesign) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this custom design?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Delete Design
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


