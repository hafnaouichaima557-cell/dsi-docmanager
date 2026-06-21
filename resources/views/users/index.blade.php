@extends('layouts.app')

@section('content')
<div style="max-width:1100px;margin:0 auto">

  {{-- Header --}}
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
    <div style="display:flex;align-items:center;gap:12px">
      <div style="width:44px;height:44px;border-radius:12px;background:#1d4ed8;display:flex;align-items:center;justify-content:center">
        <i class="bi bi-people" style="font-size:20px;color:#fff"></i>
      </div>
      <div>
        <h1 style="font-size:20px;font-weight:700;color:#0d2b6b;margin:0 0 2px">Utilisateurs</h1>
        <p style="font-size:13px;color:#64748b;margin:0">Gestion des comptes et des rôles</p>
      </div>
    </div>
    <a href="{{ route('users.create') }}"
      style="display:flex;align-items:center;gap:7px;padding:10px 20px;border-radius:9px;background:#1d4ed8;color:#fff;text-decoration:none;font-size:14px;font-weight:600">
      <i class="bi bi-person-plus"></i> Nouvel utilisateur
    </a>
  </div>

  {{-- Success --}}
  @if(session('success'))
  <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:9px;padding:10px 14px;font-size:13px;color:#15803d;margin-bottom:1rem;display:flex;align-items:center;gap:7px">
    <i class="bi bi-check-circle"></i> {{ session('success') }}
  </div>
  @endif

  {{-- Table --}}
  <div style="background:#fff;border:1.5px solid #dbeafe;border-radius:14px;overflow:hidden">
    <table style="width:100%;border-collapse:collapse;font-size:13px">
      <thead>
        <tr style="background:#f0f6ff;border-bottom:1.5px solid #dbeafe">
          <th style="padding:12px 16px;text-align:left;font-weight:600;color:#1e3a5f">Utilisateur</th>
          <th style="padding:12px 16px;text-align:left;font-weight:600;color:#1e3a5f">Département</th>
          <th style="padding:12px 16px;text-align:left;font-weight:600;color:#1e3a5f">Rôle</th>
          <th style="padding:12px 16px;text-align:left;font-weight:600;color:#1e3a5f">Statut</th>
          <th style="padding:12px 16px;text-align:left;font-weight:600;color:#1e3a5f">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        <tr style="border-bottom:1px solid #f0f5ff" onmouseover="this.style.background='#f8fbff'" onmouseout="this.style.background=''">

          {{-- Utilisateur --}}
          <td style="padding:12px 16px">
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:36px;height:36px;border-radius:50%;background:#1d4ed8;display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;flex-shrink:0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
              </div>
              <div>
                <div style="font-weight:600;color:#0d2b6b">{{ $user->name }}</div>
                <div style="font-size:12px;color:#64748b">{{ $user->email }}</div>
              </div>
            </div>
          </td>

          {{-- Département --}}
          <td style="padding:12px 16px;color:#64748b">{{ $user->department ?? '—' }}</td>

          {{-- Rôle --}}
          <td style="padding:12px 16px">
            @foreach($user->roles as $role)
              @php
                $rc = ['administrateur'=>['#eff6ff','#1d4ed8'],'responsable'=>['#f0fdf4','#059669'],'redacteur'=>['#fffbeb','#d97706'],'validateur'=>['#fdf4ff','#9333ea'],'utilisateur'=>['#f1f5f9','#475569']];
                $c = $rc[$role->name] ?? ['#f1f5f9','#64748b'];
              @endphp
              <span style="background:{{ $c[0] }};color:{{ $c[1] }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">
                {{ ucfirst($role->name) }}
              </span>
            @endforeach
          </td>

          {{-- Statut --}}
          <td style="padding:12px 16px">
            @if($user->is_active)
              <span style="background:#f0fdf4;color:#15803d;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">
                <i class="bi bi-circle-fill" style="font-size:7px;vertical-align:1px"></i> Actif
              </span>
            @else
              <span style="background:#fef2f2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">
                <i class="bi bi-circle-fill" style="font-size:7px;vertical-align:1px"></i> Désactivé
              </span>
            @endif
          </td>

          {{-- Actions --}}
          <td style="padding:12px 16px">
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">

              {{-- Voir --}}
              <a href="{{ route('users.show', $user) }}"
                style="display:flex;align-items:center;gap:5px;padding:5px 12px;border-radius:7px;background:#eff6ff;color:#1d4ed8;font-size:12px;font-weight:600;text-decoration:none">
                <i class="bi bi-eye"></i> Voir
              </a>

              {{-- Changer rôle --}}
              <form action="{{ route('users.role', $user) }}" method="POST" style="display:flex;align-items:center;gap:5px">
                @csrf
                @method('PATCH')
                <select name="role"
                  style="padding:5px 8px;border:1.5px solid #dbeafe;border-radius:7px;font-size:12px;background:#f8fbff;color:#1e3a5f;outline:none">
                  <option value="utilisateur" {{ $user->hasRole('utilisateur') ? 'selected' : '' }}>Utilisateur</option>
                  <option value="redacteur" {{ $user->hasRole('redacteur') ? 'selected' : '' }}>Rédacteur</option>
                  <option value="validateur" {{ $user->hasRole('validateur') ? 'selected' : '' }}>Validateur</option>
                  <option value="responsable" {{ $user->hasRole('responsable') ? 'selected' : '' }}>Responsable</option>
                  <option value="administrateur" {{ $user->hasRole('administrateur') ? 'selected' : '' }}>Admin</option>
                </select>
                <button type="submit"
                  style="display:flex;align-items:center;gap:4px;padding:5px 12px;border-radius:7px;background:#f0fdf4;color:#15803d;border:1.5px solid #bbf7d0;font-size:12px;font-weight:600;cursor:pointer">
                  <i class="bi bi-check2"></i> Changer
                </button>
              </form>

              {{-- Désactiver --}}
              @if($user->is_active && $user->id !== auth()->id())
              <form action="{{ route('users.disable', $user) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit"
                  onclick="return confirm('Désactiver cet utilisateur ?')"
                  style="display:flex;align-items:center;gap:4px;padding:5px 12px;border-radius:7px;background:#fef2f2;color:#dc2626;border:1.5px solid #fecaca;font-size:12px;font-weight:600;cursor:pointer">
                  <i class="bi bi-slash-circle"></i> Désactiver
                </button>
              </form>
              @endif

            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="padding:3rem;text-align:center;color:#94a3b8">
            <i class="bi bi-people" style="font-size:32px;display:block;margin-bottom:8px"></i>
            Aucun utilisateur trouvé
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div style="padding:1rem 1.5rem;border-top:1px solid #f0f5ff;display:flex;justify-content:flex-end">
      {{ $users->withQueryString()->links() }}
    </div>
    @endif
  </div>

</div>
@endsection