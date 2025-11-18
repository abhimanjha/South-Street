@extends('layouts.admin')

@section('title', 'Custom Designs Management')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-palette me-2"></i>Custom Designs Management</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.custom-designs.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" 
                           value="{{ request('search') }}" placeholder="Title, description, customer name/email">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="in_production" {{ request('status') == 'in_production' ? 'selected' : '' }}>In Production</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom Designs Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($designs as $design)
                            <tr>
                                <td>#{{ $design->id }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $design->user->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $design->user->email ?? '' }}</small>
                                    </div>
                                </td>
                                <td>{{ $design->title ?? 'Untitled' }}</td>
                                <td>{{ $design->design_type ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $design->status_color }}">
                                        {{ ucfirst(str_replace('_', ' ', $design->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $design->created_at->format('M d, Y') }}</small><br>
                                    <small class="text-muted">{{ $design->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.custom-designs.show', $design) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-palette fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No custom designs found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $designs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
