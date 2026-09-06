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

    .audit-wrap{max-width:1100px;margin:0 auto}

    .audit-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem}
    .audit-icon{
        width:44px;height:44px;border-radius:12px;
        background:linear-gradient(135deg, var(--accent-light), var(--navy));
        display:flex;align-items:center;justify-content:center;
        color:#fff;font-size:19px;flex-shrink:0;
        box-shadow:0 4px 10px rgba(29,78,216,0.32);
    }
    .audit-title{font-size:21px;font-weight:800;color:var(--navy);margin:0 0 2px}
    .audit-subtitle{font-size:13px;color:var(--slate-500);margin:0}

    .btn-back{
        display:inline-flex;align-items:center;gap:7px;
        padding:9px 16px;border-radius:9px;
        background:var(--slate-100);border:1.5px solid var(--slate-300);color:var(--navy);
        font-size:13px;font-weight:600;text-decoration:none;
        transition:background .12s ease;
    }
    .btn-back:hover{background:var(--accent-100);border-color:var(--accent-light);color:var(--navy)}

    .audit-filters{
        background:#fff;border:1px solid var(--slate-300);border-radius:14px;
        padding:1.25rem 1.5rem;margin-bottom:1.25rem;position:relative;overflow:hidden;
        box-shadow:0 1px 3px rgba(15,23,42,0.04);
    }
    .audit-filters::before{
        content:"";position:absolute;top:0;left:0;right:0;height:3px;
        background:linear-gradient(90deg, var(--accent-light), var(--navy));
    }
    .filters-row{display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end}
    .filter-field{display:flex;flex-direction:column;gap:5px;flex:1;min-width:160px}
    .filter-label{font-size:12px;font-weight:700;color:var(--navy)}
    .filter-input{
        padding:9px 12px;border:1.5px solid var(--slate-300);border-radius:9px;
        font-size:13px;background:var(--slate-100);color:#111827;
        outline:none;transition:border-color .12s ease, background .12s ease;
    }
    .filter-input:focus{border-color:var(--accent-light);background:#fff}

    .btn-filter{
        display:inline-flex;align-items:center;gap:7px;
        padding:9px 20px;border-radius:9px;border:none;cursor:pointer;
        background:linear-gradient(135deg, var(--accent-light), var(--accent));
        color:#fff;font-size:13px;font-weight:700;
        box-shadow:0 6px 14px -6px rgba(29,78,216,0.45);
        transition:filter .15s ease, transform .15s ease;
    }
    .btn-filter:hover{filter:brightness(1.06);transform:translateY(-1px);color:#fff}

    .btn-reset{
        display:inline-flex;align-items:center;gap:6px;
        padding:9px 16px;border-radius:9px;
        background:var(--slate-100);border:1.5px solid var(--slate-300);color:var(--slate-500);
        font-size:13px;font-weight:600;text-decoration:none;
        transition:background .12s ease;
    }
    .btn-reset:hover{background:var(--slate-300);color:var(--slate-500)}

    .audit-panel{
        background:#fff;border:1px solid var(--slate-300);border-radius:16px;
        overflow:hidden;position:relative;
        box-shadow:0 1px 3px rgba(15,23,42,0.04), 0 10px 30px -18px rgba(15,23,42,0.12);
    }
    .audit-panel::before{
        content:"";position:absolute;top:0;left:0;right:0;height:4px;z-index:1;
        background:linear-gradient(90deg, var(--accent-light), var(--navy));
    }

    .audit-table{width:100%;border-collapse:collapse;font-size:13.5px}
    .audit-table thead th{
        padding:13px 18px;text-align:left;font-weight:700;
        color:var(--navy);font-size:11.5px;letter-spacing:.03em;text-transform:uppercase;
        background:var(--accent-100);border-bottom:1px solid var(--accent-200);
    }
    .audit-table tbody tr{border-bottom:1px solid var(--slate-100);transition:background .12s ease;border-left:3px solid transparent}
    .audit-table tbody tr:last-child{border-bottom:none}
    .audit-table tbody tr:hover{background:#fafbfd;border-left-color:var(--accent-light)}
    .audit-table td{padding:12px 18px;vertical-align:middle}

    .log-date{color:var(--slate-500);white-space:nowrap;font-size:12.5px}

    .log-user{display:flex;align-items:center;gap:9px}
    .log-avatar{
        width:29px;height:29px;border-radius:50%;flex-shrink:0;
        background:linear-gradient(135deg, var(--accent-light), var(--navy));
        display:flex;align-items:center;justify-content:center;
        color:#fff;font-size:11px;font-weight:700;
    }
    .log-username{color:var(--navy);font-weight:600}

    .badge-pill{
        display:inline-flex;align-items:center;padding:3px 11px;
        border-radius:999px;font-size:11px;font-weight:700;
    }

    .log-desc{color:var(--slate-500)}

    .audit-empty{padding:3.5rem 1rem;text-align:center}
    .audit-empty-icon{
        width:56px;height:56px;border-radius:50%;background:var(--accent-100);color:var(--accent-light);
        display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:22px;
    }
    .audit-empty p{font-size:13.5px;color:var(--slate-500);margin:0}

    .audit-pagination{
        padding:1rem 1.5rem;border-top:1px solid var(--slate-100);
        display:flex;justify-content:flex-end;
    }    .audit-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:1.5rem}
    .stat-card{
        background:#fff;border:1px solid var(--slate-300);border-radius:14px;
        padding:1.1rem 1.3rem;position:relative;overflow:hidden;
        box-shadow:0 1px 3px rgba(15,23,42,0.04);
        transition:transform .12s ease, box-shadow .12s ease, border-color .12s ease;
    }
    .stat-card::before{
        content:"";position:absolute;top:0;left:0;right:0;height:3px;
        background:linear-gradient(90deg, var(--accent-light), var(--navy));
    }
    a.stat-card{cursor:pointer}
    a.stat-card:hover{
        transform:translateY(-2px);
        box-shadow:0 8px 20px -8px rgba(15,23,42,0.18);
        border-color:var(--accent-light);
    }
    a.stat-card.active-filter{
        border-color:var(--accent-light);
        box-shadow:0 0 0 2px var(--accent-200);
    }
    .stat-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px}
    .stat-icon{
        width:34px;height:34px;border-radius:10px;
        display:flex;align-items:center;justify-content:center;font-size:15px;color:#fff;
        background:linear-gradient(135deg, var(--accent-light), var(--navy));
        box-shadow:0 3px 8px -2px rgba(29,78,216,0.4);
    }
    .stat-icon.green{background:linear-gradient(135deg,#34d399,#059669);box-shadow:0 3px 8px -2px rgba(5,150,105,0.4)}
    .stat-icon.amber{background:linear-gradient(135deg,#fbbf24,#d97706);box-shadow:0 3px 8px -2px rgba(217,119,6,0.4)}
    .stat-icon.purple{background:linear-gradient(135deg,#c084fc,#9333ea);box-shadow:0 3px 8px -2px rgba(147,51,234,0.4)}
    .stat-value{font-size:23px;font-weight:800;color:var(--navy);line-height:1}
    .stat-label{font-size:12px;color:var(--slate-500);font-weight:600;margin-top:5px}

    @media (max-width:768px){
        .audit-stats{grid-template-columns:repeat(2,1fr)}
    }
</style>

<div class="audit-wrap">

  {{-- Header --}}
  <div class="audit-header">
    <div class="d-flex align-items-center gap-3">
      <div class="audit-icon">
        <i class="bi bi-clipboard-check"></i>
      </div>
      <div>
        <h1 class="audit-title">Audit Logs</h1>
        <p class="audit-subtitle">
            Historique des actions effectuées{{ isset($selectedDepartment) && $selectedDepartment ? ' - ' . $selectedDepartment : '' }}
        </p>
      </div>
    </div>
    @if(auth()->user()->hasRole('administrateur') && isset($selectedDepartment) && $selectedDepartment)
        <a href="{{ route('audit.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Retour aux départements
        </a>
    @endif
  </div>

  {{-- Stats --}}
  <div class="audit-stats">

    {{-- Total : reset les filtres action/actions mais garde le département --}}
    <a href="{{ route('audit.index', request()->only('department')) }}"
       class="stat-card {{ !request()->filled('action') && !request()->filled('actions') ? 'active-filter' : '' }}">
      <div class="stat-top">
        <div class="stat-icon"><i class="bi bi-clipboard-data"></i></div>
      </div>
      <div class="stat-value">{{ $logs->total() }}</div>
      <div class="stat-label">Actions au total</div>
    </a>

    @php
      $pageCreated = $logs->where('action','created')->count();
      $pageUpdated = $logs->where('action','updated')->count();
      $pageDeleted = $logs->whereIn('action',['deleted','disabled','rejected'])->count();
    @endphp

    <a href="{{ route('audit.index', array_merge(request()->only('department'), ['action' => 'created'])) }}"
       class="stat-card {{ request('action') == 'created' ? 'active-filter' : '' }}">
      <div class="stat-top">
        <div class="stat-icon green"><i class="bi bi-plus-circle"></i></div>
      </div>
      <div class="stat-value">{{ $pageCreated }}</div>
      <div class="stat-label">Créations (page)</div>
    </a>

    <a href="{{ route('audit.index', array_merge(request()->only('department'), ['action' => 'updated'])) }}"
       class="stat-card {{ request('action') == 'updated' ? 'active-filter' : '' }}">
      <div class="stat-top">
        <div class="stat-icon amber"><i class="bi bi-pencil-square"></i></div>
      </div>
      <div class="stat-value">{{ $pageUpdated }}</div>
      <div class="stat-label">Modifications (page)</div>
    </a>

    <a href="{{ route('audit.index', array_merge(request()->only('department'), ['actions' => 'deleted,disabled,rejected'])) }}"
       class="stat-card {{ request('actions') == 'deleted,disabled,rejected' ? 'active-filter' : '' }}">
      <div class="stat-top">
        <div class="stat-icon purple"><i class="bi bi-exclamation-diamond"></i></div>
      </div>
      <div class="stat-value">{{ $pageDeleted }}</div>
      <div class="stat-label">Suppr. / Désact. / Rejets (page)</div>
    </a>

  </div>

  {{-- Filtres --}}
  <div class="audit-filters">
    <form method="GET" action="{{ route('audit.index') }}" class="filters-row">

      @if(isset($selectedDepartment) && $selectedDepartment && auth()->user()->hasRole('administrateur'))
        <input type="hidden" name="department" value="{{ $selectedDepartment }}">
      @endif

      {{-- On garde le filtre action/actions actif quand on soumet le formulaire Module/Date --}}
      @if(request()->filled('action'))
        <input type="hidden" name="action" value="{{ request('action') }}">
      @endif
      @if(request()->filled('actions'))
        <input type="hidden" name="actions" value="{{ request('actions') }}">
      @endif

      <div class="filter-field">
        <label class="filter-label">Module</label>
        <select name="module" class="filter-input">
          <option value="">— Tous —</option>
          @foreach($modules as $mod)
            <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>
              {{ ucfirst($mod) }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="filter-field">
        <label class="filter-label">Date</label>
        <input type="date" name="date" value="{{ request('date') }}" class="filter-input">
      </div>

      <button type="submit" class="btn-filter">
        <i class="bi bi-funnel"></i> Filtrer
      </button>

      @if(request()->hasAny(['module','user_id','date','action','actions']))
      <a href="{{ route('audit.index', $selectedDepartment ?? null ? ['department' => $selectedDepartment] : []) }}" class="btn-reset">
        <i class="bi bi-x"></i> Reset
      </a>
      @endif

    </form>
  </div>

  {{-- Table --}}
  <div class="audit-panel">
    <table class="audit-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Utilisateur</th>
          <th>Module</th>
          <th>Action</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
        @php
          $moduleLabels = [
              'document'     => 'Document',
              'user'         => 'Utilisateur',
              'workflow'     => 'Workflow',
              'notification' => 'Notification',
              'audit'        => 'Audit',
              'permission'   => 'Permission',
              'auth'         => 'Authentification',
              'cache'        => 'Cache',
          ];

          $actionLabels = [
              'created'      => 'Créé',
              'updated'      => 'Modifié',
              'deleted'      => 'Supprimé',
              'published'    => 'Publié',
              'disabled'     => 'Désactivé',
              'approved'     => 'Approuvé',
              'rejected'     => 'Rejeté',
              'role_changed' => 'Rôle modifié',
              'submitted'    => 'Soumis',
              'restored'     => 'Restauré',
              'login'        => 'Connexion',
              'logout'       => 'Déconnexion',
          ];
        @endphp
        @forelse($logs as $log)
        <tr>
          <td class="log-date">
            {{ $log->performed_at ? $log->performed_at->format('d/m/Y H:i:s') : '—' }}
          </td>
          <td>
            <div class="log-user">
              <div class="log-avatar">{{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}</div>
              <span class="log-username">{{ $log->user->name ?? '—' }}</span>
            </div>
          </td>
          <td>
            @php
              $mColors = [
                'document'     => ['#eff6ff','#1d4ed8','bi-file-earmark-text'],
                'user'         => ['#f0fdf4','#059669','bi-person'],
                'workflow'     => ['#fffbeb','#d97706','bi-diagram-3'],
                'notification' => ['#fdf4ff','#9333ea','bi-bell'],
                'audit'        => ['#f0fdfa','#0d9488','bi-clipboard-check'],
                'permission'   => ['#fef2f2','#e11d48','bi-shield-lock'],
                'auth'         => ['#eef2ff','#4f46e5','bi-key'],
                'cache'        => ['#f8fafc','#475569','bi-database'],
              ];
              $mc = $mColors[$log->module] ?? ['#f1f5f9','#64748b','bi-folder2'];
            @endphp
            <span class="badge-pill" style="background:{{ $mc[0] }};color:{{ $mc[1] }}">
              <i class="bi {{ $mc[2] }}" style="margin-right:5px"></i>{{ $moduleLabels[$log->module] ?? ucfirst($log->module ?? '—') }}
            </span>
          </td>
          <td>
            @php
              $aColors = [
                'created'      => ['#f0fdf4','#059669','bi-plus-circle'],
                'updated'      => ['#eff6ff','#1d4ed8','bi-pencil-square'],
                'deleted'      => ['#fef2f2','#dc2626','bi-trash'],
                'published'    => ['#fdf4ff','#9333ea','bi-send-check'],
                'disabled'     => ['#fff7ed','#ea580c','bi-slash-circle'],
                'approved'     => ['#ecfdf5','#047857','bi-check-circle'],
                'rejected'     => ['#fef2f2','#b91c1c','bi-x-circle'],
                'role_changed' => ['#fffbeb','#b45309','bi-arrow-repeat'],
                'submitted'    => ['#eff6ff','#0284c7','bi-upload'],
                'restored'     => ['#f0fdfa','#0d9488','bi-arrow-counterclockwise'],
                'login'        => ['#eef2ff','#4338ca','bi-box-arrow-in-right'],
                'logout'       => ['#f8fafc','#475569','bi-box-arrow-right'],
              ];
              $ac = $aColors[$log->action] ?? ['#f1f5f9','#64748b','bi-dot'];
            @endphp
            <span class="badge-pill" style="background:{{ $ac[0] }};color:{{ $ac[1] }}">
              <i class="bi {{ $ac[2] }}" style="margin-right:5px"></i>{{ $actionLabels[$log->action] ?? ucfirst($log->action ?? '—') }}
            </span>
          </td>
          <td class="log-desc">
            {{ $log->description ?? '—' }}
            @if(!empty($log->old_values) || !empty($log->new_values))
                <div style="margin-top:4px;font-size:11.5px;color:#94a3b8">
                    @foreach(($log->new_values ?? []) as $field => $newVal)
                        @php $oldVal = $log->old_values[$field] ?? null; @endphp
                        @if($oldVal != $newVal && !is_array($newVal) && !is_array($oldVal))
                            <div>
                                <strong>{{ $field }}</strong> :
                                <span style="text-decoration:line-through">{{ $oldVal }}</span>
                                → <span style="color:#1d4ed8">{{ $newVal }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5">
            <div class="audit-empty">
              <div class="audit-empty-icon"><i class="bi bi-inbox"></i></div>
              <p>Aucun log pour le moment</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>

    {{-- Pagination --}}
    @if($logs->hasPages())
    <div class="audit-pagination">
      {{ $logs->withQueryString()->links() }}
    </div>
    @endif
  </div>

</div>
@endsection