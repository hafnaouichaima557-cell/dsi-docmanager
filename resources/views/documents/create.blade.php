@extends('layouts.app')

@section('content')
<div style="max-width:680px;margin:0 auto;padding:1.5rem 1rem">

  @if($errors->any())
  <div style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:9px;padding:10px 14px;font-size:13px;color:#dc2626;margin-bottom:1rem">
    <i class="bi bi-exclamation-circle me-1"></i>
    @foreach($errors->all() as $error)<div>• {{ $error }}</div>@endforeach
  </div>
  @endif

  {{-- Header --}}
  <div style="display:flex;align-items:center;gap:14px;margin-bottom:2rem;padding-bottom:1.25rem;border-bottom:2px solid #e8f0fe">
    <div style="width:48px;height:48px;border-radius:12px;background:#1d4ed8;display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <i class="bi bi-file-earmark-plus" style="font-size:22px;color:#fff"></i>
    </div>
    <div>
      <h1 style="font-size:20px;font-weight:700;color:#0d2b6b;margin:0 0 2px">Ajouter document</h1>
      <p style="font-size:13px;color:#64748b;margin:0">Remplissez les informations du document à créer</p>
    </div>
  </div>

  {{-- Card --}}
  <div style="background:#fff;border:1.5px solid #dbeafe;border-radius:16px;overflow:hidden">
    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      {{-- Informations --}}
      <div style="padding:1.5rem 1.75rem;border-bottom:1px solid #f0f5ff">
        <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:#93c5fd;margin:0 0 1.1rem;display:flex;align-items:center;gap:6px">
          <i class="bi bi-info-circle"></i> Informations générales
        </p>
        <div style="display:flex;flex-direction:column;gap:.9rem">
          <div>
            <label style="font-size:13px;font-weight:600;color:#1e3a5f;display:block;margin-bottom:5px">
              Titre <span style="color:#ef4444">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title') }}"
              placeholder="Ex: Rapport sécurité réseau — Mai 2026"
              style="padding:9px 13px;border:1.5px solid #dbeafe;border-radius:9px;font-size:14px;color:#111827;background:#f8fbff;width:100%;box-sizing:border-box;outline:none">
          </div>
          <div>
            <label style="font-size:13px;font-weight:600;color:#1e3a5f;display:block;margin-bottom:5px">Description</label>
            <textarea name="description" rows="4"
              placeholder="Décrivez brièvement l'objectif ou le contenu du document…"
              style="padding:9px 13px;border:1.5px solid #dbeafe;border-radius:9px;font-size:14px;color:#111827;background:#f8fbff;width:100%;box-sizing:border-box;resize:vertical;outline:none">{{ old('description') }}</textarea>
          </div>
        </div>
      </div>

      {{-- Priorité --}}
      <div style="padding:1.5rem 1.75rem;border-bottom:1px solid #f0f5ff">
        <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:#93c5fd;margin:0 0 1.1rem;display:flex;align-items:center;gap:6px">
          <i class="bi bi-flag"></i> Priorité
        </p>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px">
          @foreach([
            'low'    => ['Basse',   'bi-arrow-down-circle', '#059669', '#f0fdf4'],
            'normal' => ['Normale', 'bi-dash-circle',       '#1d4ed8', '#eff6ff'],
            'high'   => ['Haute',   'bi-arrow-up-circle',   '#d97706', '#fffbeb'],
            'urgent' => ['Urgente', 'bi-exclamation-circle','#dc2626', '#fef2f2'],
          ] as $val => $opt)
          <label style="cursor:pointer">
            <input type="radio" name="priority" value="{{ $val }}"
              {{ old('priority','normal') == $val ? 'checked' : '' }}
              style="display:none" class="prio-radio">
            <div class="prio-card" data-color="{{ $opt[2] }}" data-bg="{{ $opt[3] }}"
              style="border:1.5px solid {{ old('priority','normal')==$val ? $opt[2] : '#dbeafe' }};border-radius:10px;padding:10px 6px;text-align:center;background:{{ old('priority','normal')==$val ? $opt[3] : '#f8fbff' }};transition:all .15s">
              <i class="bi {{ $opt[1] }}" style="font-size:20px;display:block;margin-bottom:4px;color:{{ old('priority','normal')==$val ? $opt[2] : '#93c5fd' }}"></i>
              <span style="font-size:11px;font-weight:600;color:{{ old('priority','normal')==$val ? $opt[2] : '#64748b' }}">{{ $opt[0] }}</span>
            </div>
          </label>
          @endforeach
        </div>
      </div>

      {{-- Fichier --}}
      <div style="padding:1.5rem 1.75rem;border-bottom:1px solid #f0f5ff">
        <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:#93c5fd;margin:0 0 1.1rem;display:flex;align-items:center;gap:6px">
          <i class="bi bi-paperclip"></i> Fichier joint
          <span style="font-size:10px;background:#eff6ff;color:#1d4ed8;padding:2px 7px;border-radius:4px;text-transform:none;letter-spacing:0;font-weight:600">obligatoire</span>
        </p>
        <label id="fzone" style="display:block;border:2px dashed #bfdbfe;border-radius:12px;padding:2rem;text-align:center;cursor:pointer;background:#f8fbff;transition:all .15s;position:relative">
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
      <div style="padding:1.25rem 1.75rem;background:#f0f6ff;display:flex;align-items:center;gap:10px">
        <button type="submit"
          style="display:flex;align-items:center;gap:7px;padding:10px 22px;border-radius:9px;background:#1d4ed8;color:#fff;border:none;font-size:14px;font-weight:600;cursor:pointer">
          <i class="bi bi-check-circle"></i> Créer le document
        </button>
        <a href="{{ route('documents.index') }}"
          style="display:flex;align-items:center;gap:7px;padding:10px 16px;border-radius:9px;background:#fff;border:1.5px solid #dbeafe;color:#1e3a5f;font-size:14px;font-weight:500;text-decoration:none">
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