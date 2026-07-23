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
        --red:#dc2626;
        --red-bg:#fef2f2;
        --red-border:#fecaca;
    }

    .newuser-wrap{max-width:600px;margin:0 auto;padding:1.5rem 1rem}

    .alert-error{
        background:var(--red-bg);border:1.5px solid var(--red-border);
        border-radius:10px;padding:12px 16px;font-size:13px;color:var(--red);
        margin-bottom:1.25rem;
    }
    .alert-error .err-line{display:flex;align-items:flex-start;gap:7px;padding:2px 0}

    .newuser-header{
        display:flex;align-items:center;gap:14px;
        margin-bottom:1.75rem;
    }
    .newuser-icon{
        width:48px;height:48px;border-radius:12px;flex-shrink:0;
        background:linear-gradient(135deg, var(--accent-light), var(--navy));
        display:flex;align-items:center;justify-content:center;
        box-shadow:0 4px 10px rgba(29,78,216,0.32);
    }
    .newuser-icon i{font-size:21px;color:#fff}
    .newuser-title{font-size:21px;font-weight:800;color:var(--navy);margin:0 0 2px}
    .newuser-subtitle{font-size:13px;color:var(--slate-500);margin:0}

    .newuser-card{
        background:#fff;border:1px solid var(--slate-300);border-radius:16px;
        overflow:hidden;position:relative;
        box-shadow:0 1px 3px rgba(15,23,42,0.04), 0 10px 30px -18px rgba(15,23,42,0.12);
    }
    .newuser-card::before{
        content:"";position:absolute;top:0;left:0;right:0;height:4px;z-index:1;
        background:linear-gradient(90deg, var(--accent-light), var(--navy));
    }

    .form-section{padding:1.6rem 1.75rem;border-bottom:1px solid var(--slate-100)}
    .section-label{
        font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;
        color:var(--accent-light);margin:0 0 1.15rem;
        display:flex;align-items:center;gap:7px;
    }

    .field-group{display:flex;flex-direction:column;gap:1rem}
    .field-label{
        font-size:13px;font-weight:700;color:var(--navy);
        display:block;margin-bottom:6px;
    }
    .field-required{color:var(--red)}

    .field-input{
        padding:10px 14px;border:1.5px solid var(--slate-300);border-radius:9px;
        font-size:14px;color:#111827;background:var(--slate-100);
        width:100%;box-sizing:border-box;outline:none;
        transition:border-color .12s ease, background .12s ease;
    }
    .field-input:focus{border-color:var(--accent-light);background:#fff}
    .field-input::placeholder{color:#a3aebd}

    .field-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.1rem}

    .pwd-wrap{position:relative}
    .pwd-wrap .field-input{padding-right:42px}
    .pwd-toggle{
        position:absolute;right:11px;top:50%;transform:translateY(-50%);
        background:none;border:none;cursor:pointer;color:var(--accent-light);
        font-size:15px;display:flex;align-items:center;
    }

    .form-actions{
        padding:1.35rem 1.75rem;background:var(--accent-100);
        display:flex;align-items:center;gap:10px;
    }

    .btn-submit{
        display:inline-flex;align-items:center;gap:8px;
        padding:11px 24px;border-radius:10px;border:none;cursor:pointer;
        background:linear-gradient(135deg, var(--accent-light), var(--accent));
        color:#fff;font-size:14px;font-weight:700;
        box-shadow:0 6px 14px -6px rgba(29,78,216,0.45);
        transition:filter .15s ease, transform .15s ease;
    }
    .btn-submit:hover{filter:brightness(1.06);transform:translateY(-1px);color:#fff}

    .btn-cancel{
        display:inline-flex;align-items:center;gap:7px;
        padding:11px 18px;border-radius:10px;
        background:#fff;border:1.5px solid var(--accent-200);color:var(--navy);
        font-size:14px;font-weight:600;text-decoration:none;
        transition:background .12s ease;
    }
    .btn-cancel:hover{background:var(--accent-100);color:var(--navy)}

    @media (max-width:480px){
        .field-grid{grid-template-columns:1fr}
    }

    .avatar-upload-row{display:flex;align-items:center;gap:16px;margin-bottom:1.1rem}
    .avatar-preview{
        width:64px;height:64px;border-radius:50%;flex-shrink:0;
        background:var(--slate-100);border:1.5px dashed var(--slate-300);
        display:flex;align-items:center;justify-content:center;
        overflow:hidden;position:relative;
    }
    .avatar-preview img{width:100%;height:100%;object-fit:cover;display:none}
    .avatar-preview i{font-size:22px;color:#a3aebd}
    .avatar-upload-btn{
        display:inline-flex;align-items:center;gap:7px;
        padding:8px 16px;border-radius:9px;cursor:pointer;
        background:#fff;border:1.5px solid var(--accent-200);color:var(--accent);
        font-size:12.5px;font-weight:700;
        transition:background .12s ease;
    }
    .avatar-upload-btn:hover{background:var(--accent-100)}
    .avatar-upload-btn input[type=file]{display:none}
    .avatar-hint{font-size:11.5px;color:var(--slate-500);margin-top:5px}
</style>

<div class="newuser-wrap">

  @if($errors->any())
  <div class="alert-error">
    @foreach($errors->all() as $error)
    <div class="err-line"><i class="bi bi-exclamation-circle"></i> {{ $error }}</div>
    @endforeach
  </div>
  @endif

  {{-- Header --}}
  <div class="newuser-header">
    <div class="newuser-icon">
      <i class="bi bi-person-plus"></i>
    </div>
    <div>
      <h1 class="newuser-title">Nouvel utilisateur</h1>
      <p class="newuser-subtitle">Remplissez les informations du nouveau compte</p>
    </div>
  </div>

  {{-- Card --}}
  <div class="newuser-card">
    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      {{-- Identité --}}
      <div class="form-section">
        <p class="section-label"><i class="bi bi-person"></i> Identité</p>

        <div class="avatar-upload-row">
          <div class="avatar-preview" id="avatarPreview">
            <img id="avatarImg" src="" alt="Aperçu">
            <i class="bi bi-person" id="avatarPlaceholder"></i>
          </div>
          <div>
            <label class="avatar-upload-btn">
              <i class="bi bi-upload"></i> Choisir une photo
              <input type="file" name="photo" id="photoInput" accept="image/png,image/jpeg,image/webp" onchange="previewPhoto(this)">
            </label>
            <p class="avatar-hint">JPG, PNG ou WEBP — 2 Mo max</p>
          </div>
        </div>

        <div class="field-group">
          <div>
            <label class="field-label">
              Nom complet <span class="field-required">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name') }}"
              placeholder="Ex: Ahmed Benali" class="field-input">
          </div>
          <div>
            <label class="field-label">
              Email <span class="field-required">*</span>
            </label>
            <input type="email" name="email" value="{{ old('email') }}"
              placeholder="email@dsi.local" class="field-input">
          </div>
          <div>
            <label class="field-label">
              Mot de passe <span class="field-required">*</span>
            </label>
            <div class="pwd-wrap">
              <input type="password" name="password" id="pwd"
                placeholder="Minimum 8 caractères" class="field-input">
              <button type="button" onclick="togglePwd()" class="pwd-toggle">
                <i class="bi bi-eye" id="eyeIcon"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- Affectation --}}
      <div class="form-section">
        <p class="section-label"><i class="bi bi-building"></i> Affectation</p>
        <div class="field-grid">
         <div>
    <label class="field-label">
        Département <span class="field-required">*</span>
    </label>

    @if(auth()->user()->hasRole('responsable'))

        <input type="text"
               class="field-input"
               value="{{ auth()->user()->department }}"
               readonly>

        <input type="hidden"
               name="department"
               value="{{ auth()->user()->department }}">

    @else

        <select name="department" class="field-input" required>
            <option value="">-- Choisir un département --</option>

            <option value="Développement"
                {{ old('department') == 'Développement' ? 'selected' : '' }}>
                Développement
            </option>

            <option value="Support"
                {{ old('department') == 'Support' ? 'selected' : '' }}>
                Support
            </option>

            <option value="Cloud"
                {{ old('department') == 'Cloud' ? 'selected' : '' }}>
                Cloud
            </option>

            <option value="Sécurité Réseaux"
                {{ old('department') == 'Sécurité Réseaux' ? 'selected' : '' }}>
                Sécurité Réseaux
            </option>
        </select>

    @endif
</div>
          <div>
            <label class="field-label">
              Rôle <span class="field-required">*</span>
            </label>
            <select name="role" class="field-input">
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
      <div class="form-actions">
        <button type="submit" class="btn-submit">
          <i class="bi bi-person-check"></i> Créer l'utilisateur
        </button>
        <a href="{{ route('users.index') }}" class="btn-cancel">
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

function previewPhoto(input){
  const img = document.getElementById('avatarImg');
  const placeholder = document.getElementById('avatarPlaceholder');
  if(input.files && input.files[0]){
    const reader = new FileReader();
    reader.onload = function(e){
      img.src = e.target.result;
      img.style.display = 'block';
      placeholder.style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endpush

@endsection