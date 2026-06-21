@extends('layouts.app')

@section('content')
<div style="max-width:600px;margin:0 auto;padding:1.5rem 1rem">

  @if($errors->any())
  <div style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:9px;padding:10px 14px;font-size:13px;color:#dc2626;margin-bottom:1rem">
    <i class="bi bi-exclamation-circle me-1"></i>
    @foreach($errors->all() as $error)<div>• {{ $error }}</div>@endforeach
  </div>
  @endif

  {{-- Header --}}
  <div style="display:flex;align-items:center;gap:14px;margin-bottom:2rem;padding-bottom:1.25rem;border-bottom:2px solid #e8f0fe">
    <div style="width:48px;height:48px;border-radius:12px;background:#1d4ed8;display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <i class="bi bi-person-plus" style="font-size:22px;color:#fff"></i>
    </div>
    <div>
      <h1 style="font-size:20px;font-weight:700;color:#0d2b6b;margin:0 0 2px">Nouvel utilisateur</h1>
      <p style="font-size:13px;color:#64748b;margin:0">Remplissez les informations du nouveau compte</p>
    </div>
  </div>

  {{-- Card --}}
  <div style="background:#fff;border:1.5px solid #dbeafe;border-radius:16px;overflow:hidden">
    <form action="{{ route('users.store') }}" method="POST">
      @csrf

      {{-- Identité --}}
      <div style="padding:1.5rem 1.75rem;border-bottom:1px solid #f0f5ff">
        <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:#93c5fd;margin:0 0 1.1rem;display:flex;align-items:center;gap:6px">
          <i class="bi bi-person"></i> Identité
        </p>
        <div style="display:flex;flex-direction:column;gap:.9rem">
          <div>
            <label style="font-size:13px;font-weight:600;color:#1e3a5f;display:block;margin-bottom:5px">
              Nom complet <span style="color:#ef4444">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name') }}"
              placeholder="Ex: Ahmed Benali"
              style="padding:9px 13px;border:1.5px solid #dbeafe;border-radius:9px;font-size:14px;color:#111827;background:#f8fbff;width:100%;box-sizing:border-box;outline:none">
          </div>
          <div>
            <label style="font-size:13px;font-weight:600;color:#1e3a5f;display:block;margin-bottom:5px">
              Email <span style="color:#ef4444">*</span>
            </label>
            <input type="email" name="email" value="{{ old('email') }}"
              placeholder="email@dsi.local"
              style="padding:9px 13px;border:1.5px solid #dbeafe;border-radius:9px;font-size:14px;color:#111827;background:#f8fbff;width:100%;box-sizing:border-box;outline:none">
          </div>
          <div>
            <label style="font-size:13px;font-weight:600;color:#1e3a5f;display:block;margin-bottom:5px">
              Mot de passe <span style="color:#ef4444">*</span>
            </label>
            <div style="position:relative">
              <input type="password" name="password" id="pwd"
                placeholder="Minimum 8 caractères"
                style="padding:9px 40px 9px 13px;border:1.5px solid #dbeafe;border-radius:9px;font-size:14px;color:#111827;background:#f8fbff;width:100%;box-sizing:border-box;outline:none">
              <button type="button" onclick="togglePwd()"
                style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#93c5fd">
                <i class="bi bi-eye" id="eyeIcon"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- Affectation --}}
      <div style="padding:1.5rem 1.75rem;border-bottom:1px solid #f0f5ff">
        <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:#93c5fd;margin:0 0 1.1rem;display:flex;align-items:center;gap:6px">
          <i class="bi bi-building"></i> Affectation
        </p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
          <div>
            <label style="font-size:13px;font-weight:600;color:#1e3a5f;display:block;margin-bottom:5px">Département</label>
            <input type="text" name="department" value="{{ old('department') }}"
              placeholder="DSI, RH, Finance…"
              style="padding:9px 13px;border:1.5px solid #dbeafe;border-radius:9px;font-size:14px;color:#111827;background:#f8fbff;width:100%;box-sizing:border-box;outline:none">
          </div>
          <div>
            <label style="font-size:13px;font-weight:600;color:#1e3a5f;display:block;margin-bottom:5px">
              Rôle <span style="color:#ef4444">*</span>
            </label>
            <select name="role"
              style="padding:9px 13px;border:1.5px solid #dbeafe;border-radius:9px;font-size:14px;color:#111827;background:#f8fbff;width:100%;box-sizing:border-box;outline:none">
              <option value="">— Choisir un rôle —</option>
              @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                  {{ ucfirst($role->name) }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div style="padding:1.25rem 1.75rem;background:#f0f6ff;display:flex;align-items:center;gap:10px">
        <button type="submit"
          style="display:flex;align-items:center;gap:7px;padding:10px 22px;border-radius:9px;background:#1d4ed8;color:#fff;border:none;font-size:14px;font-weight:600;cursor:pointer">
          <i class="bi bi-person-check"></i> Créer l'utilisateur
        </button>
        <a href="{{ route('users.index') }}"
          style="display:flex;align-items:center;gap:7px;padding:10px 16px;border-radius:9px;background:#fff;border:1.5px solid #dbeafe;color:#1e3a5f;font-size:14px;font-weight:500;text-decoration:none">
          <i class="bi bi-x"></i> Annuler
        </a>
      </div>

    </form>
  </div>
</div>

@push('scripts')
<script>
function togglePwd(){
  const p=document.getElementById('pwd');
  const i=document.getElementById('eyeIcon');
  if(p.type==='password'){p.type='text';i.className='bi bi-eye-slash';}
  else{p.type='password';i.className='bi bi-eye';}
}
</script>
@endpush

@endsection