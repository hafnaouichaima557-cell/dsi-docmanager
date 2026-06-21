@extends('layouts.app')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#0d2b6b">
            <i class="bi bi-bell me-2"></i>Notifications
        </h4>
        <small class="text-muted">{{ auth()->user()->unreadNotifications->count() }} non lues</small>
    </div>
    @if(auth()->user()->unreadNotifications->count() > 0)
    <form method="POST" action="{{ route('notifications.mark-read') }}">
        @csrf
        <button type="submit" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-check-all me-1"></i>Tout marquer comme lu
        </button>
    </form>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @forelse(auth()->user()->notifications as $notif)
        <div class="d-flex align-items-start gap-3 p-3 border-bottom {{ $notif->read_at ? '' : 'bg-light' }}">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:40px;height:40px;background:{{ $notif->read_at ? '#f3f4f6' : '#e8f0fe' }}">
                <i class="bi bi-bell{{ $notif->read_at ? '' : '-fill' }}"
                   style="color:{{ $notif->read_at ? '#9ca3af' : '#1a4fa0' }}"></i>
            </div>
            <div class="flex-grow-1">
                <p class="mb-1 fw-medium" style="font-size:14px">
                    {{ $notif->data['message'] ?? 'Notification' }}
                </p>
                @if(isset($notif->data['document_title']))
                <p class="mb-1 text-muted" style="font-size:13px">
                    <i class="bi bi-file-earmark-text me-1"></i>
                    {{ $notif->data['document_title'] }}
                </p>
                @endif
                <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
            </div>
            @if(!$notif->read_at)
            <span class="badge bg-primary" style="font-size:10px">Nouveau</span>
            @endif
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-bell-slash" style="font-size:32px"></i>
            <p class="mt-2">Aucune notification</p>
        </div>
        @endforelse
    </div>
</div>

@endsection