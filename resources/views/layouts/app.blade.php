<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DSI DocManager — icosnet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { overflow: hidden; }
        .sidebar { width: 250px; min-height: 100vh; background: linear-gradient(180deg, #0d2b6b 0%, #1a4fa0 100%); }
        .sidebar-link { display: flex; align-items: center; gap: 10px; padding: 10px 20px; color: rgba(255,255,255,0.75); text-decoration: none; font-size: 14px; transition: all 0.2s; border-left: 3px solid transparent; }
        .sidebar-link:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .sidebar-link.active { background: rgba(255,255,255,0.15); border-left: 3px solid #fff; color: #fff; }
        .sidebar-section { padding: 10px 20px 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.4); }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; padding: 8px 24px; }
        .main-content { height: calc(100vh - 57px); overflow-y: auto; background: #f4f6f9; padding: 24px; }
        .notif-badge { position: absolute; top: -4px; right: -4px; width: 18px; height: 18px; background: #ef4444; border-radius: 50%; font-size: 10px; color: #fff; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>
<div class="d-flex">

    {{-- SIDEBAR --}}
    <div class="sidebar d-flex flex-column">

        {{-- Logo --}}
        <div class="p-3 border-bottom border-white border-opacity-10 d-flex align-items-center gap-2">
            <img src="{{ asset('images/logo-icosnet.png') }}" alt="icosnet" height="32"
                 onerror="this.style.display='none'">
            <div>
                <div class="text-white fw-semibold" style="font-size:13px">Doc Flow</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.5)">icosnet</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-grow-1 py-2">
            <div class="sidebar-section">Principal</div>

            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Tableau de bord
            </a>

            <a href="{{ route('documents.index') }}"
               class="sidebar-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> Documents
            </a>

            {{-- Workflow --}}
            <a href="{{ route('workflow.index') }}"
               class="sidebar-link {{ request()->routeIs('workflow.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-repeat"></i> Workflow
            </a>

            {{-- Notifications --}}
            <a href="{{ route('notifications.index') }}"
               class="sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}"
               style="position:relative">
                <i class="bi bi-bell"></i> Notifications
                @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                @if($unread > 0)
                <span style="margin-left:auto;background:#ef4444;color:#fff;border-radius:50%;width:18px;height:18px;font-size:10px;display:flex;align-items:center;justify-content:center">
                    {{ $unread }}
                </span>
                @endif
            </a>

            @if(auth()->user()->isAdmin() || auth()->user()->isResponsable())
            <div class="sidebar-section mt-2">Administration</div>

            <a href="{{ route('users.index') }}"
               class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Utilisateurs
            </a>

            <a href="{{ route('audit.index') }}"
               class="sidebar-link {{ request()->routeIs('audit.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check"></i> Audit logs
            </a>
            @endif
        </nav>

        {{-- User --}}
        <div class="p-3 border-top border-white border-opacity-10 d-flex align-items-center gap-2">
            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                 style="width:36px;height:36px;font-size:14px">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="text-white fw-medium text-truncate" style="font-size:13px">{{ auth()->user()->name }}</div>
                <div class="text-truncate" style="font-size:11px;color:rgba(255,255,255,0.5)">{{ auth()->user()->email }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0" style="color:rgba(255,255,255,0.5)">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- MAIN --}}
    <div class="flex-grow-1 d-flex flex-column overflow-hidden">

        {{-- Topbar --}}
        <div class="topbar d-flex align-items-center justify-content-between gap-3">

            {{-- Breadcrumb --}}
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <span class="text-muted" style="font-size:13px">icosnet</span>
                <span class="text-muted">/</span>
                <span class="fw-medium" style="font-size:13px">DSI DocManager</span>
            </div>

            {{-- Recherche globale --}}
            <div class="flex-grow-1" style="max-width:400px">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control bg-light border-start-0"
                           placeholder="Rechercher un document..."
                           onkeydown="if(event.key==='Enter'){window.location='/documents?search='+this.value}">
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted" style="font-size:12px">{{ now()->format('d/m/Y') }}</span>

                {{-- Notifications dropdown --}}
                <div class="position-relative">
                    <button class="btn btn-light btn-sm position-relative" data-bs-toggle="dropdown">
                        <i class="bi bi-bell" style="font-size:16px"></i>
                        @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                        @if($unread > 0)
                        <span class="notif-badge">{{ $unread }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow" style="width:320px;max-height:400px;overflow-y:auto">
                        <div class="dropdown-header d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Notifications</span>
                            @if($unread > 0)
                            <form method="POST" action="/notifications/mark-read">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm p-0 text-primary" style="font-size:12px">
                                    Tout lire
                                </button>
                            </form>
                            @endif
                        </div>
                        @forelse(auth()->user()->notifications->take(5) as $notif)
                        <div class="dropdown-item py-2 {{ $notif->read_at ? '' : 'bg-light' }}" style="white-space:normal">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-bell-fill text-primary mt-1" style="font-size:12px"></i>
                                <div>
                                    <p class="mb-0" style="font-size:13px">{{ $notif->data['message'] ?? 'Notification' }}</p>
                                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="dropdown-item text-center text-muted py-3" style="font-size:13px">
                            <i class="bi bi-bell-slash"></i> Aucune notification
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Rôle --}}
                <span class="badge" style="background:#e8f0fe;color:#1a4fa0;font-size:11px">
                    {{ auth()->user()->getRoleNames()->first() ?? 'utilisateur' }}
                </span>
            </div>
        </div>

        {{-- Content --}}
        <div class="main-content">
            @yield('content')
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>