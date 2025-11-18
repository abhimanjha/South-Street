@extends('layouts.app')

@section('title', 'My Notifications')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">My Notifications</h1>
                <a href="{{ route('account.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>

            @if($notifications->count() > 0)
                <div class="card">
                    <div class="card-body p-0">
                        @foreach($notifications as $notification)
                        <div class="notification-item border-bottom p-3 {{ $notification->read_at ? 'bg-light' : 'bg-white' }}">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 {{ $notification->read_at ? 'text-muted' : 'fw-bold' }}">
                                                {{ $notification->data['title'] ?? 'Notification' }}
                                            </h6>
                                            <p class="mb-2 text-muted small">
                                                {{ $notification->data['message'] ?? '' }}
                                            </p>
                                            @if(isset($notification->data['discount_code']))
                                            <div class="alert alert-success py-2 mb-2">
                                                <strong>Discount Code:</strong> {{ $notification->data['discount_code'] }}
                                                <br><small>Use this code to get {{ $notification->data['discount_percentage'] }}% off!</small>
                                            </div>
                                            @endif
                                            <small class="text-muted">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        @if(!$notification->read_at)
                                        <span class="badge bg-primary ms-2">New</span>
                                        @endif
                                    </div>
                                </div>
                                @if(!$notification->read_at)
                                <div class="ms-3">
                                    <button onclick="markAsRead({{ $notification->id }})" class="btn btn-sm btn-outline-primary">
                                        Mark as Read
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-bell-slash text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-muted">No notifications yet</h4>
                    <p class="text-muted">We'll notify you when there are updates on your orders or new offers.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/account/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(response => {
        if (response.ok) {
            location.reload();
        }
    });
}
</script>
@endsection
