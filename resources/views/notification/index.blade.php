@auth
    <div class="dropdown">
        <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
            🔔 {{ auth()->user()->unreadNotifications->count() }}
        </button>
        <ul class="dropdown-menu">
            @forelse (auth()->user()->unreadNotifications as $notification)
                <li class="dropdown-item">
                    <a href="{{ $notification->data['url'] }}">
                        {{ $notification->data['message'] }}
                    </a>
                </li>
            @empty
                <li class="dropdown-item text-muted">Tiada notifications</li>
            @endforelse
        </ul>
    </div>
    $notification->markAsRead();
@endauth
