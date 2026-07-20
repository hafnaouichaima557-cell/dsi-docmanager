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
    --green:#059669;
  }

  .form-wrap{max-width:700px;margin:0 auto;padding:1.5rem 1rem}

  .form-header{
    display:flex;align-items:center;gap:16px;
    margin-bottom:1.75rem;padding-bottom:1.25rem;
    border-bottom:2px solid var(--accent-100);
  }
  .form-header-icon{
    width:50px;height:50px;border-radius:14px;flex-shrink:0;
    background:linear-gradient(135deg, var(--accent-light), var(--navy));
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 6px 16px rgba(29,78,216,0.32);
  }
  .form-header-icon i{font-size:22px;color:#fff}
  .form-header h1{font-size:21px;font-weight:800;color:var(--navy);margin:0 0 3px}
  .form-header p{font-size:13px;color:var(--slate-500);margin:0}

  .error-box{
    background:#fef2f2;border:1.5px solid #fecaca;border-radius:10px;
    padding:11px 15px;font-size:13px;color:#dc2626;margin-bottom:1.25rem;
  }

  .form-card{
    background:#fff;border:1.5px solid var(--accent-200);border-radius:18px;
    overflow:hidden;box-shadow:0 10px 30px -14px rgba(29,78,216,0.25);
    position:relative;
  }
  .form-card::before{
    content:"";position:absolute;top:0;left:0;right:0;height:4px;
    background:linear-gradient(90deg, var(--accent-light), var(--green), var(--accent-light));
  }

  .section-block{padding:1.6rem 1.85rem;border-bottom:1px solid #f0f5ff}

  .section-label{
    display:inline-flex;align-items:center;gap:8px;
    font-size:11.5px;font-weight:800;text-transform:uppercase;letter-spacing:.09em;
    margin:0 0 1.15rem;padding:5px 12px 5px 8px;border-radius:999px;
  }
  .section-label i{
    width:20px;height:20px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;font-size:11px;color:#fff;
  }
  .section-label.-info{background:var(--accent-100);color:var(--accent);}
  .section-label.-info i{background:var(--accent);}
  .section-label.-priority{background:#fff7e6;color:#b3670c;}
  .section-label.-priority i{background:#d97706;}
  .section-label.-file{background:#e9f9f0;color:var(--green);}
  .section-label.-file i{background:var(--green);}

  .field-group{display:flex;flex-direction:column;gap:1rem}
  .field label.field-label{
    font-size:13px;font-weight:700;color:var(--navy);display:block;margin-bottom:6px;
  }
  .required-star{color:#ef4444}

  .input-field{
    padding:10px 14px;border:1.5px solid var(--accent-200);border-radius:10px;
    font-size:14px;color:#111827;background:var(--accent-100);
    width:100%;box-sizing:border-box;outline:none;
    transition:border-color .15s ease, box-shadow .15s ease, background .15s ease;
  }
  .input-field:focus{
    border-color:var(--accent);background:#fff;
    box-shadow:0 0 0 4px var(--accent-200);
  }
  textarea.input-field{resize:vertical}

  .prio-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:9px}
  .prio-card{transition:all .15s ease; cursor:pointer;}
  .prio-card:hover{transform:translateY(-2px); box-shadow:0 6px 14px -6px rgba(29,78,216,0.25);}

  .dropzone{
    display:block;border:2px dashed #bfdbfe;border-radius:14px;
    padding:2.1rem;text-align:center;cursor:pointer;
    background:linear-gradient(180deg, var(--accent-100), #fff);
    transition:all .18s ease;position:relative;
  }
  .dropzone:hover{border-color:var(--accent-light); background:var(--accent-100);}

  .badge-required{
    font-size:10px;background:var(--accent);color:#fff;
    padding:3px 9px;border-radius:999px;text-transform:none;letter-spacing:0;font-weight:700;
  }

  .actions-bar{
    padding:1.3rem 1.85rem;background:linear-gradient(135deg, var(--accent-100), #f5f9ff);
    display:flex;align-items:center;gap:12px;
  }
  .btn-submit{
    display:flex;align-items:center;gap:8px;padding:11px 24px;border-radius:10px;
    background:linear-gradient(135deg, var(--accent-light), var(--accent));
    color:#fff;border:none;font-size:14px;font-weight:700;cursor:pointer;
    box-shadow:0 8px 18px -6px rgba(29,78,216,0.45);
    transition:filter .15s ease, transform .15s ease;
  }
  .btn-submit:hover{filter:brightness(1.07); transform:translateY(-1px); color:#fff;}
  .btn-cancel{
    display:flex;align-items:center;gap:7px;padding:11px 18px;border-radius:10px;
    background:#fff;border:1.5px solid var(--accent-200);color:var(--navy);
    font-size:14px;font-weight:600;text-decoration:none;transition:background .15s ease, border-color .15s ease;
  }
  .btn-cancel:hover{background:var(--accent-100); border-color:var(--accent-light); color:var(--navy);}
</style>

<div class="form-wrap">

  @if($errors->any())
  <div class="error-box">
    <i class="bi bi-exclamation-circle me-1"></i>
    @foreach($errors->all() as $error)<div>• {{ $error }}</div>@endforeach
  </div>
  @endif

  {{-- Header --}}
  <div class="form-header">
    <div class="form-header-icon">
      <i class="bi bi-file-earmark-plus"></i>
    </div>
    <div>
      <h1>Ajouter document</h1>
      <p>Remplissez les informations du document à créer</p>
    </div>
  </div>

  {{-- Card --}}
  <div class="form-card">
    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      {{-- Informations --}}
      <div class="section-block">
        <p class="section-label -info"><i class="bi bi-info-circle"></i> Informations générales</p>
        <div class="field-group">
          <div class="field">
            <label class="field-label">Titre <span class="required-star">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}"
              placeholder="Ex: Rapport sécurité réseau — Mai 2026"
              class="input-field">
          </div>

          <div class="field">
            <label class="field-label">Description</label>
            <textarea name="description" rows="4"
              placeholder="Décrivez brièvement l'objectif ou le contenu du document…"
              class="input-field">{{ old('description') }}</textarea>
          </div>

          {{-- Catégorie --}}
          <div class="field">
            <label class="field-label">Catégorie <span class="required-star">*</span></label>
            <select name="category_id" required class="input-field">
              <option value="">-- Choisir une catégorie --</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>

        </div>
      </div>
      
      {{-- Fichier --}}
      <div class="section-block" style="border-bottom:none">
        <p class="section-label -file">
          <i class="bi bi-paperclip"></i> Fichier joint
        </p>
        <span class="badge-required" style="margin:-1rem 0 1.15rem;display:inline-block">obligatoire</span>
        <label id="fzone" class="dropzone">
          <input type="file" name="file" accept=".pdf,.docx,.xlsx"
            style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%"
            onchange="handleFile(this)">
          <i class="bi bi-cloud-upload" id="ficon" style="font-size:32px;color:#93c5fd;display:block;margin-bottom:8px"></i>
          <p id="flabel" style="font-size:13px;color:#1e3a5f;font-weight:500;margin:0">
            Glissez votre fichier ici ou <strong>cliquez pour parcourir</strong>
          </p>
          <small style="font-size:11px;color:#93c5fd">PDF, DOCX, XLSX — max 10 MB</small>
        </label>
      </div>

      {{-- Actions --}}
      <div class="actions-bar">
        <button type="submit" class="btn-submit">
          <i class="bi bi-check-circle"></i> Créer le document
        </button>
        <a href="{{ route('documents.index') }}" class="btn-cancel">
          <i class="bi bi-x"></i> Annuler
        </a>
      </div>

    </form>
  </div>
</div>

@push('scripts')
<script>
function handleFile(input){
  const f=input.files[0];if(!f)return;
  const mb=(f.size/1024/1024).toFixed(1);
  document.getElementById('flabel').innerHTML='<strong>'+f.name+'</strong> — '+mb+' MB';
  document.getElementById('ficon').className='bi bi-file-earmark-check';
  document.getElementById('ficon').style.color='#059669';
  const z=document.getElementById('fzone');
  z.style.borderColor='#059669';z.style.background='#f0fdf4';
}
document.querySelectorAll('.prio-radio').forEach(r=>{
  r.addEventListener('change',function(){
    document.querySelectorAll('.prio-card').forEach(c=>{
      c.style.borderColor='#dbeafe';c.style.background='#f8fbff';
      c.querySelector('i').style.color='#93c5fd';
      c.querySelector('span').style.color='#64748b';
    });
    const c=this.nextElementSibling;
    c.style.borderColor=c.dataset.color;
    c.style.background=c.dataset.bg;
    c.querySelector('i').style.color=c.dataset.color;
    c.querySelector('span').style.color=c.dataset.color;
  });
});
</script>
@endpush

@endsection