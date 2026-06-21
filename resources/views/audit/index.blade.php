@extends('layouts.app')

@section('content')
<div style="max-width:1100px;margin:0 auto">

  {{-- Header --}}
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
    <div style="display:flex;align-items:center;gap:12px">
      <div style="width:44px;height:44px;border-radius:12px;background:#1d4ed8;display:flex;align-items:center;justify-content:center">
        <i class="bi bi-clipboard-check" style="font-size:20px;color:#fff"></i>
      </div>
      <div>
        <h1 style="font-size:20px;font-weight:700;color:#0d2b6b;margin:0 0 2px">Audit Logs</h1>
        <p style="font-size:13px;color:#64748b;margin:0">Historique des actions effectuées</p>
      </div>
    </div>
  </div>

  {{-- Filtres --}}
  <div style="background:#fff;border:1.5px solid #dbeafe;border-radius:12px;padding:1.25rem 1.5rem;margin-bottom:1.25rem">
    <form method="GET" action="{{ route('audit.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">

      <div style="display:flex;flex-direction:column;gap:4px;flex:1;min-width:150px">
        <label style="font-size:12px;font-weight:600;color:#1e3a5f">Module</label>
        <select name="module" style="padding:8px 12px;border:1.5px solid #dbeafe;border-radius:8px;font-size:13px;background:#f8fbff;color:#111827">
          <option value="">— Tous —</option>
          @foreach($modules as $mod)
            <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>
              {{ ucfirst($mod) }}
            </option>
          @endforeach
        </select>
      </div>

      <div style="display:flex;flex-direction:column;gap:4px;flex:1;min-width:150px">
        <label style="font-size:12px;font-weight:600;color:#1e3a5f">Date</label>
        <input type="date" name="date" value="{{ request('date') }}"
          style="padding:8px 12px;border:1.5px solid #dbeafe;border-radius:8px;font-size:13px;background:#f8fbff;color:#111827">
      </div>

      <button type="submit"
        style="padding:8px 18px;background:#1d4ed8;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px">
        <i class="bi bi-funnel"></i> Filtrer
      </button>

      @if(request()->hasAny(['module','user_id','date']))
      <a href="{{ route('audit.index') }}"
        style="padding:8px 14px;background:#f8fbff;border:1.5px solid #dbeafe;color:#64748b;border-radius:8px;font-size:13px;text-decoration:none;display:flex;align-items:center;gap:5px">
        <i class="bi bi-x"></i> Reset
      </a>
      @endif

    </form>
  </div>

  {{-- Table --}}
  <div style="background:#fff;border:1.5px solid #dbeafe;border-radius:12px;overflow:hidden">
    <table style="width:100%;border-collapse:collapse;font-size:13px">
      <thead>
        <tr style="background:#f0f6ff;border-bottom:1.5px solid #dbeafe">
          <th style="padding:12px 16px;text-align:left;font-weight:600;color:#1e3a5f">Date</th>
          <th style="padding:12px 16px;text-align:left;font-weight:600;color:#1e3a5f">Utilisateur</th>
          <th style="padding:12px 16px;text-align:left;font-weight:600;color:#1e3a5f">Module</th>
          <th style="padding:12px 16px;text-align:left;font-weight:600;color:#1e3a5f">Action</th>
          <th style="padding:12px 16px;text-align:left;font-weight:600;color:#1e3a5f">Description</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
        <tr style="border-bottom:1px solid #f0f5ff;transition:background .1s" onmouseover="this.style.background='#f8fbff'" onmouseout="this.style.background=''">
          <td style="padding:11px 16px;color:#64748b;white-space:nowrap">
            {{ $log->performed_at ? $log->performed_at->format('d/m/Y H:i') : '—' }}
          </td>
          <td style="padding:11px 16px">
            <div style="display:flex;align-items:center;gap:8px">
              <div style="width:28px;height:28px;border-radius:50%;background:#1d4ed8;display:flex;align-items:center;justify-content:center;color:#fff;font-size:11px;font-weight:700;flex-shrink:0">
                {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
              </div>
              <span style="color:#1e3a5f;font-weight:500">{{ $log->user->name ?? '—' }}</span>
            </div>
          </td>
          <td style="padding:11px 16px">
            @php
              $mColors = ['document'=>['#eff6ff','#1d4ed8'],'user'=>['#f0fdf4','#059669'],'workflow'=>['#fffbeb','#d97706'],'notification'=>['#fdf4ff','#9333ea']];
              $mc = $mColors[$log->module] ?? ['#f1f5f9','#64748b'];
            @endphp
            <span style="background:{{ $mc[0] }};color:{{ $mc[1] }};padding:2px 10px;border-radius:20px;font-size:11px;font-weight:600">
              {{ ucfirst($log->module ?? '—') }}
            </span>
          </td>
          <td style="padding:11px 16px">
            @php
              $aColors = ['created'=>['#f0fdf4','#059669'],'updated'=>['#eff6ff','#1d4ed8'],'deleted'=>['#fef2f2','#dc2626'],'published'=>['#fdf4ff','#9333ea'],'disabled'=>['#fff7ed','#ea580c']];
              $ac = $aColors[$log->action] ?? ['#f1f5f9','#64748b'];
            @endphp
            <span style="background:{{ $ac[0] }};color:{{ $ac[1] }};padding:2px 10px;border-radius:20px;font-size:11px;font-weight:600">
              {{ ucfirst($log->action ?? '—') }}
            </span>
          </td>
          <td style="padding:11px 16px;color:#64748b">
            {{ $log->description ?? '—' }}
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="padding:3rem;text-align:center;color:#94a3b8">
            <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:8px"></i>
            Aucun log pour le moment
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>

    {{-- Pagination --}}
    @if($logs->hasPages())
    <div style="padding:1rem 1.5rem;border-top:1px solid #f0f5ff;display:flex;justify-content:flex-end">
      {{ $logs->withQueryString()->links() }}
    </div>
    @endif
  </div>

</div>
@endsection