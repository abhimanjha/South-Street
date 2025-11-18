@extends('layouts.admin')

@section('title', 'Reviews')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User Reviews</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>User</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                    <tr>
                                        <td>{{ $review->id }}</td>
                                        <td>
                                            <a href="{{ route('products.show', $review->product) }}" class="text-decoration-none">
                                                {{ $review->product->name }}
                                            </a>
                                        </td>
                                        <td>{{ $review->user->name }}</td>
                                        <td>
                                            <div class="star-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </div>
                                            <small class="text-muted">({{ $review->rating }}/5)</small>
                                        </td>
                                        <td>{{ Str::limit($review->comment, 100) }}</td>
                                        <td>
                                            <span class="badge {{ $review->is_approved ? 'bg-success' : 'bg-warning' }}">
                                                {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td>{{ $review->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @if(!$review->is_approved)
                                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No reviews found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($reviews->hasPages())
                    <div class="card-footer">
                        {{ $reviews->links() }}
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
