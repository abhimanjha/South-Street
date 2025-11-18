@extends('layouts.admin')

@section('title', 'Tailoring Requests')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tailoring Requests</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Material</th>
                                    <th>Color</th>
                                    <th>Style</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $request)
                                    <tr>
                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->name }}</td>
                                        <td>{{ $request->cloth_material }}</td>
                                        <td>{{ $request->color }}</td>
                                        <td>{{ $request->style_type }}</td>
                                        <td>
                                            <span class="badge
                                                @if($request->status == 'Pending') badge-warning
                                                @elseif($request->status == 'Approved') badge-success
                                                @elseif($request->status == 'Rejected') badge-danger
                                                @elseif($request->status == 'In Progress') badge-info
                                                @else badge-secondary @endif">
                                                {{ $request->status }}
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <form action="{{ route('admin.tailoring.update-status', $request) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-control form-control-sm d-inline-block w-auto" onchange="this.form.submit()">
                                                    <option value="Pending" {{ $request->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="Approved" {{ $request->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="Rejected" {{ $request->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                                    <option value="In Progress" {{ $request->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="Completed" {{ $request->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No tailoring requests found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($requests->hasPages())
                    <div class="card-footer">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <script>
        $(document).ready(function() {
            toastr.success('{{ session('success') }}');
        });
    </script>
@endif
@endsection
