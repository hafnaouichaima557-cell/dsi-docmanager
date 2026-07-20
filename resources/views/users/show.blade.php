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
        --green:#059669;
        --green-bg:#f0fdf4;
        --green-border:#bbf7d0;
        --red:#dc2626;
        --red-bg:#fef2f2;
        --red-border:#fecaca;
    }

    .profile-wrap{max-width:1100px;margin:0 auto}

    .profile-header{display:flex;align-items:center;gap:12px;margin-bottom:1.5rem}
    .btn-back{
        display:inline-flex;align-items:center;gap:6px;
        padding:8px 14px;border-radius:9px;
        background:#fff;border:1.5px solid var(--slate-300);color:var(--navy);
        font-size:13px;font-weight:600;text-decoration:none;
        transition:background .12s ease;
    }
    .btn-back:hover{background:var(--slate-100);color:var(--navy)}
    .profile-title{font-size:20px;font-weight:800;color:var(--navy);margin:0;display:flex;align-items:center;gap:9px}

    .profile-card{
        background:#fff;border:1px solid var(--slate-300);border-radius:16px;
        overflow:hidden;position:relative;text-align:center;padding:2rem 1.5rem;
        box-shadow:0 1px 3px rgba(15,23,42,0.04), 0 10px 30px -18px rgba(15,23,42,0.12);
    }
    .profile-card::before{
        content:"";position:absolute;top:0;left:0;right:0;height:4px;
        background:linear-gradient(90deg, var(--accent-light), var(--navy));
    }

    .profile-avatar{
        width:88px;height:88px;border-radius:50%;margin:0 auto 1.1rem;
        display:flex;align-items:center;justify-content:center;
        background:linear-gradient(135deg, var(--accent-light), var(--navy));
        color:#fff;font-size:34px;font-weight:700;
        box-shadow:0 6px 16px -4px rgba(29,78,216,0.45);
        overflow:hidden;
    }
    .profile-avatar img{width:100%;height:100%;object-fit:cover}

    .profile-name{font-size:17px;font-weight:800;color:var(--navy);margin:0 0 3px}
    .profile-email{font-size:13px;color:var(--slate-500);margin:0 0 2px}
    .profile-dept{font-size:12.5px;color:var(--slate-500);margin:0 0 1rem}

    .profile-role-badge{
        display:inline-flex;align-items:center;padding:4px 14px;
        border-radius:999px;font-size:11.5px;font-weight:700;
        background:var(--accent-100);color:var(--accent);
        margin:0 3px 8px;
    }

    .profile-status-badge{
        display:inline-flex;align-items:center;gap:6px;
        padding:5px 14px;border-radius:999px;font-size:11.5px;font-weight:700;
    }
    .status-dot{width:6px;height:6px;border-radius:50%}
    .status-active{background:var(--green-bg);color:var(--green)}
    .status-active .status-dot{background:var(--green)}
    .status-inactive{background:var(--red-bg);color:var(--red)}
    .status-inactive .status-dot{background:var(--red)}

    .docs-panel{
        background:#fff;border:1px solid var(--slate-300);border-radius:16px;
        overflow:hidden;position:relative;
        box-shadow:0 1px 3px rgba(15,23,42,0.04), 0 10px 30px -18px rgba(15,23,42,0.12);
    }
    .docs-panel::before{
        content:"";position:absolute;top:0;left:0;right:0;height:4px;z-index:1;
        background:linear-gradient(90deg, var(--accent-light), var(--navy));
    }
    .docs-header{
        padding:16px 20px;border-bottom:1px solid var(--slate-100);
        display:flex;align-items:center;gap:9px;
    }
    .docs-header i{color:var(--accent);font-size:16px}
    .docs-header h6{font-size:14.5px;font-weight:800;color:var(--navy);margin:0}

    .docs-table{width:100%;border-collapse:collapse;font-size:13.5px}
    .docs-table thead th{
        padding:12px 20px;text-align:left;font-weight:700;
        color:var(--navy);font-size:11.5px;letter-spacing:.03em;text-transform:uppercase;
        background:var(--accent-100);border-bottom:1px solid var(--accent-200);
    }
    .docs-table tbody tr{border-bottom:1px solid var(--slate-100);transition:background .12s ease}
    .docs-table tbody tr:last-child{border-bottom:none}
    .docs-table tbody tr:hover{background:#fafbfd}
    .docs-table td{padding:13px 20px;vertical-align:middle}
    .doc-title{font-weight:700;color:var(--navy)}
    .doc-date{color:var(--slate-500);font-size:12.5px}

    .status-pill{
        display:inline-flex;align-items:center;padding:4px 12px;
        border-radius:999px;font-size:11px;font-weight:700;color:#fff;
    }
    .status-published{background:var(--green)}
    .status-draft{background:#94a3b8}
    .status-submitted{background:#0ea5e9}
    .status-under_review{background:#d97706}
    .status-approved{background:var(--accent)}
    .status-disabled, .status-rejected{background:var(--red)}

    .docs-empty{padding:3.5rem 1rem;text-align:center}
    .docs-empty-icon{
        width:56px;height:56px;border-radius:50%;background:var(--accent-100);color:var(--accent-light);
        display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:22px;
    }
    .docs-empty p{font-size:13.5px;color:var(--slate-500);margin:0}
</style>

<div class="profile-wrap">
    <div class="profile-header">
        <a href="{{ route('users.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Retour</a>
        <h4 class="profile-title"><i class="bi bi-person-circle" style="color:var(--accent)"></i> Profil de {{ $user->name }}</h4>
    </div>

    <div class="row g-4">
        {{-- Infos utilisateur --}}
        <div class="col-md-4">
            <div class="profile-card">
                <div class="profile-avatar">
                    @if($user->photo)
                        <img src="{{ asset('storage/'.$user->photo) }}" alt="{{ $user->name }}">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <p class="profile-name">{{ $user->name }}</p>
                <p class="profile-email">{{ $user->email }}</p>
                <p class="profile-dept">{{ $user->department ?? '—' }}</p>

                <div>
                    @foreach($user->roles as $role)
                        <span class="profile-role-badge">{{ ucfirst($role->name) }}</span>
                    @endforeach
                </div>

                <div class="mt-2">
                    @if($user->is_active)
                        <span class="profile-status-badge status-active"><span class="status-dot"></span> Actif</span>
                    @else
                        <span class="profile-status-badge status-inactive"><span class="status-dot"></span> Désactivé</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Documents de l'utilisateur --}}
        <div class="col-md-8">
            <div class="docs-panel">
                <div class="docs-header">
                    <i class="bi bi-file-earmark-text"></i>
                    <h6>Documents ({{ $user->documents->count() }})</h6>
                </div>

                @if($user->documents->isEmpty())
                    <div class="docs-empty">
                        <div class="docs-empty-icon"><i class="bi bi-file-earmark-text"></i></div>
                        <p>Aucun document créé</p>
                    </div>
                @else
                    <table class="docs-table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Statut</th>
                                <th>Priorité</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->documents as $doc)
                            <tr>
                                <td class="doc-title">{{ $doc->title }}</td>
                                <td>
                                    <span class="status-pill status-{{ $doc->status }}">
                                        {{ $doc->status }}
                                    </span>
                                </td>
                                <td>{{ $doc->priority }}</td>
                                <td class="doc-date">{{ $doc->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection