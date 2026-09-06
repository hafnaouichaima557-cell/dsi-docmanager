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

    .users-page-icon{
        width:44px;height:44px;border-radius:12px;
        background:linear-gradient(135deg, var(--accent-light), var(--navy));
        display:flex;align-items:center;justify-content:center;
        color:#fff;font-size:19px;
        box-shadow:0 4px 10px rgba(29,78,216,0.32);
        flex-shrink:0;
    }
    .users-page-title{font-size:21px;font-weight:800;color:var(--navy);margin:0 0 2px}
    .users-page-subtitle{font-size:13px;color:var(--slate-500);margin:0}

    .btn-new-user{
        display:inline-flex;align-items:center;gap:8px;
        padding:11px 22px;border-radius:10px;
        background:linear-gradient(135deg, var(--accent-light), var(--accent));
        color:#fff;text-decoration:none;font-size:14px;font-weight:700;
        box-shadow:0 6px 14px -6px rgba(29,78,216,0.45);
        transition:filter .15s ease, transform .15s ease;
        border:none;
    }
    .btn-new-user:hover{filter:brightness(1.06);transform:translateY(-1px);color:#fff}

    .alert-success{
        display:flex;align-items:center;gap:9px;
        background:var(--green-bg);border:1.5px solid var(--green-border);
        color:#15803d;border-radius:10px;padding:12px 16px;
        font-size:13.5px;font-weight:600;margin-bottom:1.25rem;
    }

    .users-panel{
        background:#fff;border:1px solid var(--slate-300);border-radius:16px;
        overflow:hidden;position:relative;
        box-shadow:0 1px 3px rgba(15,23,42,0.04), 0 10px 30px -18px rgba(15,23,42,0.12);
    }
    .users-panel::before{
        content:"";position:absolute;top:0;left:0;right:0;height:4px;z-index:1;
        background:linear-gradient(90deg, var(--accent-light), var(--navy));
    }

    .users-table{width:100%;border-collapse:collapse;font-size:13.5px}
    .users-table thead th{
        padding:13px 18px;text-align:left;font-weight:700;
        color:var(--navy);font-size:12px;letter-spacing:.03em;text-transform:uppercase;
        background:var(--accent-100);border-bottom:1px solid var(--accent-200);
    }
    .users-table tbody tr{
        border-bottom:1px solid var(--slate-100);
        transition:background .12s ease;
    }
    .users-table tbody tr:last-child{border-bottom:none}
    .users-table tbody tr:hover{background:#fafbfd}
    .users-table td{padding:14px 18px;vertical-align:middle}

    .user-cell{display:flex;align-items:center;gap:12px}
    .user-avatar{
        width:38px;height:38px;border-radius:50%;flex-shrink:0;
        background:linear-gradient(135deg, var(--accent-light), var(--navy));
        display:flex;align-items:center;justify-content:center;
        color:#fff;font-size:13.5px;font-weight:700;
        box-shadow:0 3px 8px -2px rgba(29,78,216,0.4);
        overflow:hidden;
    }
    .user-name{font-weight:700;color:var(--navy);font-size:14px}
    .user-email{font-size:12px;color:var(--slate-500);margin-top:1px}

    .dept-cell{color:var(--slate-500);font-weight:500}

    .role-badge{
        display:inline-flex;align-items:center;padding:4px 12px;
        border-radius:999px;font-size:11.5px;font-weight:700;
        letter-spacing:.01em;
    }

    .status-badge{
        display:inline-flex;align-items:center;gap:6px;
        padding:4px 12px;border-radius:999px;font-size:11.5px;font-weight:700;
    }
    .status-dot{width:6px;height:6px;border-radius:50%;flex-shrink:0}
    .status-active{background:var(--green-bg);color:var(--green)}
    .status-active .status-dot{background:var(--green)}
    .status-inactive{background:var(--red-bg);color:var(--red)}
    .status-inactive .status-dot{background:var(--red)}

    .actions-cell{display:flex;align-items:center;gap:8px;flex-wrap:wrap}

    .btn-view{
        display:inline-flex;align-items:center;gap:6px;
        padding:7px 14px;border-radius:8px;
        background:var(--accent-100);color:var(--accent);
        border:1px solid var(--accent-200);
        font-size:12.5px;font-weight:700;text-decoration:none;
        transition:background .12s ease;
    }
    .btn-view:hover{background:var(--accent-200);color:var(--accent)}

    .role-form{display:flex;align-items:center;gap:7px}
    .role-select{
        padding:6px 10px;border:1px solid var(--slate-300);border-radius:8px;
        font-size:12.5px;background:#fff;color:var(--navy);font-weight:600;
        outline:none;cursor:pointer;
        transition:border-color .12s ease;
    }
    .role-select:focus{border-color:var(--accent-light)}

    .btn-change{
        display:inline-flex;align-items:center;gap:5px;
        padding:7px 14px;border-radius:8px;cursor:pointer;
        background:var(--green-bg);color:var(--green);
        border:1px solid var(--green-border);
        font-size:12.5px;font-weight:700;
        transition:background .12s ease;
    }
    .btn-change:hover{background:#dcfce7}

    .btn-disable{
        display:inline-flex;align-items:center;gap:5px;
        padding:7px 14px;border-radius:8px;cursor:pointer;
        background:var(--red-bg);color:var(--red);
        border:1px solid var(--red-border);
        font-size:12.5px;font-weight:700;
        transition:background .12s ease;
    }
    .btn-disable:hover{background:#fee2e2}

    .users-empty{padding:4rem 1rem;text-align:center}
    .users-empty-icon{
        width:64px;height:64px;border-radius:50%;background:var(--accent-100);color:var(--accent-light);
        display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:26px;
    }
    .users-empty p.title{font-size:14px;font-weight:700;color:var(--navy);margin:0}

    .users-pagination{
        padding:1rem 1.5rem;border-top:1px solid var(--slate-100);
        display:flex;justify-content:flex-end;
    }
</style>

<div style="max-width:1100px;margin:0 auto">

  {{-- Header --}}
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
      <div class="users-page-icon">
        <i class="bi bi-people"></i>
      </div>
      <div>
        <h1 class="users-page-title">Utilisateurs</h1>
        <p class="users-page-subtitle">Gestion des comptes et des rôles</p>
      </div>
    </div>
    <a href="{{ route('users.create') }}" class="btn-new-user">
      <i class="bi bi-person-plus"></i> Nouvel utilisateur
    </a>
  </div>

  {{-- Success --}}
  @if(session('success'))
  <div class="alert-success">
    <i class="bi bi-check-circle"></i> {{ session('success') }}
  </div>
  @endif

  {{-- Table --}}
  <div class="users-panel">
    <table class="users-table">
      <thead>
        <tr>
          <th>Utilisateur</th>
          <th>Département</th>
          <th>Rôle</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        <tr>

          {{-- Utilisateur --}}
          <td>
            <div class="user-cell">
              <div class="user-avatar">
                @if($user->photo)
                  <img src="{{ Storage::url($user->photo) }}" alt="{{ $user->name }}" style="width:100%;height:100%;border-radius:50%;object-fit:cover">
                @else
                  {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
              </div>
              <div>
                <div class="user-name">{{ $user->name }}</div>
                <div class="user-email">{{ $user->email }}</div>
              </div>
            </div>
          </td>

          {{-- Département --}}
          <td class="dept-cell">{{ $user->department ?? '—' }}</td>

          {{-- Rôle --}}
          <td>
            @foreach($user->roles as $role)
              @php
                $rc = ['administrateur'=>['#eff6ff','#1d4ed8'],'responsable'=>['#f0fdf4','#059669'],'redacteur'=>['#fffbeb','#d97706'],'validateur'=>['#fdf4ff','#9333ea'],'utilisateur'=>['#f1f5f9','#475569']];
                $c = $rc[$role->name] ?? ['#f1f5f9','#64748b'];
              @endphp
              <span class="role-badge" style="background:{{ $c[0] }};color:{{ $c[1] }}">
                {{ ucfirst($role->name) }}
              </span>
            @endforeach
          </td>

          {{-- Statut --}}
          <td>
            @if($user->is_active)
              <span class="status-badge status-active">
                <span class="status-dot"></span> Actif
              </span>
            @else
              <span class="status-badge status-inactive">
                <span class="status-dot"></span> Désactivé
              </span>
            @endif
          </td>

          {{-- Actions --}}
          <td>
            <div class="actions-cell">

              {{-- Voir --}}
              <a href="{{ route('users.show', $user) }}" class="btn-view">
                <i class="bi bi-eye"></i> Voir
              </a>

              {{-- Changer rôle — réservé à l'administrateur --}}
              @if(auth()->user()->isAdmin())
              <form action="{{ route('users.role', $user) }}" method="POST" class="role-form">
                @csrf
                @method('PATCH')
                <select name="role" class="role-select">
                  <option value="utilisateur" {{ $user->hasRole('utilisateur') ? 'selected' : '' }}>Utilisateur</option>
                  <option value="responsable" {{ $user->hasRole('responsable') ? 'selected' : '' }}>Responsable</option>
                  <option value="administrateur" {{ $user->hasRole('administrateur') ? 'selected' : '' }}>Admin</option>
                </select>
                <button type="submit" class="btn-change">
                  <i class="bi bi-check2"></i> Changer
                </button>
              </form>
              @endif

              {{-- Désactiver --}}
              @if($user->is_active && $user->id !== auth()->id())
              <form action="{{ route('users.disable', $user) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" onclick="return confirm('Désactiver cet utilisateur ?')" class="btn-disable">
                  <i class="bi bi-slash-circle"></i> Désactiver
                </button>
              </form>
              @endif

            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5">
            <div class="users-empty">
              <div class="users-empty-icon">
                <i class="bi bi-people"></i>
              </div>
              <p class="title">Aucun utilisateur trouvé</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="users-pagination">
      {{ $users->withQueryString()->links() }}
    </div>
    @endif
  </div>

</div>
@endsection