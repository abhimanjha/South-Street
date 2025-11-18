@props(['notifications' => []])

<div class="dropdown">
    <button class="btn btn-outline-secondary dropdown-toggle position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        @if($notifications->whereNull('read_at')->count() > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $notifications->whereNull('read_at')->count() }}
            </span>
        @endif
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="min-width: 300px;">
        <li><h6 class="dropdown-header">Notifications</h6></li>
        @forelse($notifications->take(5) as $notification)
        <li>
            <a class="dropdown-item {{ $notification->read_at ? '' : 'fw-bold' }}" href="#" onclick="markAsRead({{ $notification->id }})">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        <p class="mb-1">{{ $notification->data['message'] ?? $notification->data['title'] ?? 'New notification' }}</p>
                        @if(isset($notification->data['discount_code']))
                            <small class="text-success">Code: {{ $notification->data['discount_code'] }}</small>
                        @endif
                    </div>
                    @if(!$notification->read_at)
                        <div class="align-self-start">
                            <span class="badge bg-primary rounded-pill">New</span>
                        </div>
                    @endif
                </div>
            </a>
        </li>
        @empty
        <li><span class="dropdown-item text-muted">No notifications</span></li>
        @endforelse
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-center" href="{{ route('account.notifications') }}">View All Notifications</a></li>
    </ul>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
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
