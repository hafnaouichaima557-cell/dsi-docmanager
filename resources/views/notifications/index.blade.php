@extends('layouts.app')

@section('content')

<style>
    :root{
        --accent:#1d4ed8;
        --accent-light:#3b6ff0;
        --accent-100:#eff6ff;
        --accent-200:#dbeafe;
        --navy:#0d2b6b;
        --slate-500:#64748b;
        --slate-300:#dfe3ea;
        --slate-100:#f4f6f9;
    }

    .page-icon{
        width:42px;height:42px;border-radius:12px;
        background:linear-gradient(135deg, var(--accent-light), var(--navy));
        display:flex;align-items:center;justify-content:center;
        color:#fff;font-size:18px;
        box-shadow:0 4px 10px rgba(29,78,216,0.32);
        flex-shrink:0;
    }
    .page-title{font-weight:800;color:var(--navy);margin-bottom:2px;font-size:21px}
    .unread-pill{
        display:inline-flex;align-items:center;gap:6px;font-size:12.5px;font-weight:700;
        color:var(--accent);background:var(--accent-100);padding:3px 12px;border-radius:999px;
    }
    .unread-pill .dot{width:6px;height:6px;border-radius:50%;background:var(--accent)}

    .btn-mark-read{
        display:inline-flex;align-items:center;gap:7px;border:none;border-radius:10px;
        padding:9px 16px;font-size:13px;font-weight:700;color:#fff;
        background:linear-gradient(135deg, var(--accent-light), var(--accent));
        box-shadow:0 6px 14px -6px rgba(29,78,216,0.45);
        transition:filter .15s ease, transform .15s ease;
    }
    .btn-mark-read:hover{filter:brightness(1.06);transform:translateY(-1px);color:#fff}

    .notif-panel{border:1px solid var(--slate-300);border-radius:16px;overflow:hidden;position:relative}
    .notif-panel::before{
        content:"";position:absolute;top:0;left:0;right:0;height:4px;z-index:1;
        background:linear-gradient(90deg, var(--accent-light), var(--navy));
    }

    .notif-row{
        display:flex;align-items:flex-start;gap:14px;padding:16px 18px;
        border-bottom:1px solid var(--slate-300);position:relative;
        border-left:3px solid transparent;
        transition:background .12s ease;
    }
    .notif-row:hover{background:#fafbfd}
    .notif-row.is-unread{background:var(--accent-100);border-left-color:var(--accent)}
    .notif-row.is-unread:hover{background:#e4edff}

    .notif-icon{
        width:40px;height:40px;border-radius:50%;flex-shrink:0;
        display:flex;align-items:center;justify-content:center;
    }
    .notif-icon.unread{background:linear-gradient(135deg,var(--accent-light),var(--navy));color:#fff;box-shadow:0 4px 10px -3px rgba(29,78,216,0.4)}
    .notif-icon.read{background:var(--slate-100);color:#9ca3af;border:1px solid var(--slate-300)}

    .notif-message{font-size:14px;font-weight:600;color:#1f2937;margin:0 0 4px}
    .notif-doc-chip{
        display:inline-flex;align-items:center;gap:5px;font-size:12px;color:var(--slate-500);
        background:var(--slate-100);border:1px solid var(--slate-300);border-radius:999px;
        padding:2px 10px;margin-bottom:4px;
    }
    .notif-time{font-size:12px;color:var(--slate-500)}

    .badge-new{
        background:var(--accent);color:#fff;font-size:10px;font-weight:700;
        border-radius:999px;padding:3px 10px;flex-shrink:0;white-space:nowrap;
    }

    .notif-empty{padding:3.75rem 1rem;text-align:center}
    .notif-empty-icon{
        width:64px;height:64px;border-radius:50%;background:var(--accent-100);color:var(--accent-light);
        display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:26px;
    }
    .notif-empty p.title{font-size:14px;font-weight:700;color:var(--navy);margin:0 0 4px}
    .notif-empty p.subtitle{font-size:12.5px;color:var(--slate-500);margin:0}
</style>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="page-icon">
            <i class="bi bi-bell"></i>
        </div>
        <div>
            <div class="page-title">Notifications</div>
            <span class="unread-pill">
                <span class="dot"></span>{{ auth()->user()->unreadNotifications->count() }} non lues
            </span>
        </div>
    </div>
    @if(auth()->user()->unreadNotifications->count() > 0)
    <form method="POST" action="{{ route('notifications.mark-read') }}">
        @csrf
        <button type="submit" class="btn-mark-read">
            <i class="bi bi-check-all"></i>Tout marquer comme lu
        </button>
    </form>
    @endif
</div>

<div class="card border-0 shadow-sm notif-panel">
    <div class="card-body p-0">
        @forelse(auth()->user()->notifications as $notif)
        <div class="notif-row {{ $notif->read_at ? '' : 'is-unread' }}">
            <div class="notif-icon {{ $notif->read_at ? 'read' : 'unread' }}">
                <i class="bi bi-bell{{ $notif->read_at ? '' : '-fill' }}"></i>
            </div>
            <div class="flex-grow-1">
                <p class="notif-message">
                    {{ $notif->data['message'] ?? 'Notification' }}
                </p>
                @if(isset($notif->data['document_title']))
                <div>
                    <span class="notif-doc-chip">
                        <i class="bi bi-file-earmark-text"></i>
                        {{ $notif->data['document_title'] }}
                    </span>
                </div>
                @endif
                <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
            </div>
            @if(!$notif->read_at)
            <span class="badge-new">Nouveau</span>
            @endif
        </div>
        @empty
        <div class="notif-empty">
            <div class="notif-empty-icon">
                <i class="bi bi-bell-slash"></i>
            </div>
            <p class="title">Aucune notification</p>
            <p class="subtitle">Vous serez notifié ici des mises à jour importantes</p>
        </div>
        @endforelse
    </div>
</div>

@endsection