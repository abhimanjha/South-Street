@extends('layouts.admin')

@section('title', 'Custom Tailoring Request Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Custom Tailoring Request #{{ $customTailoring->id }}</h1>
                    <p class="text-muted mb-0">Request submitted on {{ $customTailoring->created_at->format('M d, Y H:i') }}</p>
                </div>
                <a href="{{ route('admin.custom-tailoring.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Information -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-circle me-2"></i>Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <strong>Name:</strong><br>
                            {{ $customTailoring->name }}
                        </div>
                        <div class="col-sm-6">
                            <strong>Email:</strong><br>
                            <a href="mailto:{{ $customTailoring->email }}">{{ $customTailoring->email }}</a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <strong>Phone:</strong><br>
                            <a href="tel:{{ $customTailoring->phone }}">{{ $customTailoring->phone }}</a>
                        </div>
                        <div class="col-sm-6">
                            <strong>Status:</strong><br>
                            <span class="badge
                                @if($customTailoring->status === 'pending') bg-warning
                                @elseif($customTailoring->status === 'accepted') bg-success
                                @else bg-danger @endif">
                                {{ ucfirst($customTailoring->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Specifications -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-gear me-2"></i>Product Specifications
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <strong>Material:</strong><br>
                            {{ $customTailoring->cloth_material }}
                        </div>
                        <div class="col-sm-6">
                            <strong>Color:</strong><br>
                            {{ $customTailoring->color }}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Style:</strong><br>
                            {{ $customTailoring->style ?: 'Not specified' }}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Measurements:</strong><br>
                            @if($customTailoring->sizes)
                                <div class="row">
                                    <div class="col-sm-6">
                                        Chest: {{ $customTailoring->sizes['chest'] ?? 'N/A' }}"<br>
                                        Waist: {{ $customTailoring->sizes['waist'] ?? 'N/A' }}"
                                    </div>
                                    <div class="col-sm-6">
                                        Hips: {{ $customTailoring->sizes['hips'] ?? 'N/A' }}"<br>
                                        Length: {{ $customTailoring->sizes['length'] ?? 'N/A' }}"
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">No measurements provided</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Update Status
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.custom-tailoring.update-status', $customTailoring) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Request Status</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="pending" {{ $customTailoring->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="accepted" {{ $customTailoring->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                    <option value="rejected" {{ $customTailoring->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="work_status" class="form-label">Work Status</label>
                                <input type="text" id="work_status" name="work_status" value="{{ $customTailoring->work_status }}"
                                       class="form-control" placeholder="e.g., In Progress, Completed">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Admin Notes</label>
                            <textarea id="notes" name="notes" rows="3" class="form-control"
                                      placeholder="Internal notes...">{{ $customTailoring->notes }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Update Status
                            </button>
                            <a href="{{ route('admin.custom-tailoring.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="row mt-3">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
