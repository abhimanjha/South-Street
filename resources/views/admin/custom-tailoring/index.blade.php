@extends('layouts.admin')

@section('title', 'Custom Tailoring Requests')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Custom Tailoring Requests</h1>
            <p class="text-muted">Manage custom tailoring requests from customers</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Material</th>
                            <th>Status</th>
                            <th>Work Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                        <tr>
                            <td class="fw-bold">#{{ $request->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $request->name }}</div>
                                <small class="text-muted">{{ $request->email }}</small>
                            </td>
                            <td>{{ $request->cloth_material }}</td>
                            <td>
                                <span class="badge
                                    @if($request->status === 'pending') bg-warning
                                    @elseif($request->status === 'accepted') bg-success
                                    @else bg-danger @endif">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ $request->work_status ?: 'Not Set' }}</td>
                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.custom-tailoring.show', $request) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No custom tailoring requests found.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($requests->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $requests->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
