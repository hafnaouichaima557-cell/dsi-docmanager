<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DSI DocManager — icosnet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-from: #0b1f4d;
            --sidebar-to: #1a4fa0;
            --accent: #4f8ef7;
        }

        * { font-family: 'Inter', 'Segoe UI', sans-serif; }

        body { overflow: hidden; }

        /* ============ SIDEBAR ============ */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(160deg, var(--sidebar-from) 0%, var(--sidebar-to) 100%);
            box-shadow: 2px 0 12px rgba(0,0,0,0.15);
            position: relative;
        }

        .sidebar-logo {
            padding: 20px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-logo-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent), #7db2ff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #fff;
            box-shadow: 0 4px 10px rgba(79,142,247,0.4);
            flex-shrink: 0;
        }

        .sidebar-brand-title {
            font-size: 14.5px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.2px;
            line-height: 1.2;
        }

        .sidebar-brand-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.45);
            font-weight: 500;
        }

        .sidebar-section {
            padding: 22px 22px 8px;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            color: rgba(255,255,255,0.35);
        }

        nav.sidebar-nav { padding: 6px 12px; }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            margin-bottom: 3px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.18s ease;
            position: relative;
        }

        .sidebar-link i {
            font-size: 16px;
            width: 18px;
            text-align: center;
            opacity: 0.85;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,0.07);
            color: #fff;
            transform: translateX(2px);
        }

        .sidebar-link.active {
            background: rgba(255,255,255,0.12);
            color: #fff;
            font-weight: 600;
            box-shadow: inset 3px 0 0 var(--accent);
        }

        .sidebar-link.active i { color: var(--accent); opacity: 1; }

        .sidebar-link .nav-badge {
            margin-left: auto;
            background: #ef4444;
            color: #fff;
            border-radius: 50%;
            width: 19px;
            height: 19px;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-divider {
            margin: 14px 22px 0;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
/* User footer */
        .sidebar-user {
            padding: 16px 18px;
            border-top: 1px solid rgba(255,255,255,0.08);
            background: rgba(0,0,0,0.12);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.2s;
        }

        .sidebar-user:hover { background: rgba(0,0,0,0.2); }

        .sidebar-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #7db2ff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        }

        .sidebar-user-name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
        }

        .sidebar-user-email {
            font-size: 10.5px;
            color: rgba(255,255,255,0.45);
        }

        .sidebar-logout-btn {
            color: rgba(255,255,255,0.5);
            transition: color 0.2s;
        }
        .sidebar-logout-btn:hover { color: #ef4444; }

        /* ============ TOPBAR ============ */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e9ecf2;
            padding: 12px 28px;
        }

        .topbar .breadcrumb-tag {
            font-size: 12.5px;
            color: #8b93a7;
            font-weight: 500;
        }

        .topbar .breadcrumb-current {
            font-size: 13px;
            font-weight: 600;
            color: #1a2233;
        }

        .search-box .input-group-text,
        .search-box .form-control {
            background: #f4f6fa;
            border: 1px solid #e9ecf2;
        }
        .search-box .form-control:focus {
            box-shadow: none;
            border-color: var(--accent);
            background: #fff;
        }

        .main-content {
            height: calc(100vh - 65px);
            overflow-y: auto;
            background: #f4f6f9;
            padding: 28px;
        }

        .notif-badge {
            position: absolute;
            top: -3px;
            right: -3px;
            width: 18px;
            height: 18px;
            background: #ef4444;
            border-radius: 50%;
            font-size: 10px;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 0 2px #fff;
        }

        .role-badge {
            background: #eaf1ff;
            color: #1a4fa0;
            font-size: 11px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 20px;
        }
    </style>
</head>
<body>
<div class="d-flex">

    {{-- SIDEBAR --}}
    <div class="sidebar d-flex flex-column">

        {{-- Logo --}}
        <div class="sidebar-logo d-flex align-items-center gap-2">
            <div class="sidebar-logo-icon">
                <i class="bi bi-file-earmark-richtext"></i>
            </div>
            <div>
                <div class="sidebar-brand-title">Doc Flow</div>
                <div class="sidebar-brand-sub">icosnet</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav flex-grow-1 py-1">
            <div class="sidebar-section">Principal</div>

            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Tableau de bord
            </a>

            <a href="{{ route('documents.index') }}"
               class="sidebar-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> Documents
            </a>
<a href="{{ route('workflow.index') }}"
               class="sidebar-link {{ request()->routeIs('workflow.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-repeat"></i> Workflow
            </a>

            <a href="{{ route('notifications.index') }}"
               class="sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Notifications
                @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                @if($unread > 0)
                    <span class="nav-badge">{{ $unread }}</span>
                @endif
            </a>

            @if(auth()->user()->isAdmin() || auth()->user()->isResponsable())
            <div class="sidebar-divider"></div>
            <div class="sidebar-section">Administration</div>

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
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="sidebar-user-name text-truncate">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-email text-truncate">{{ auth()->user()->email }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0 sidebar-logout-btn">
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
                <span class="breadcrumb-tag">icosnet</span>
                <i class="bi bi-chevron-right text-muted" style="font-size:10px"></i>
                <span class="breadcrumb-current">DSI DocManager</span>
            </div>

            {{-- Recherche globale --}}
            <div class="flex-grow-1 search-box" style="max-width:420px">
                <div class="input-group input-group-sm">
                    <span class="input-group-text border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0"
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
                <span class="role-badge">
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