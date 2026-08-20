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
        --green:#178a4c;
        --amber:#b3670c;
        --red:#c62828;
        --purple:#7c3aed;
    }

    .btn-back{
        border:1.5px solid var(--slate-300);border-radius:10px;background:#fff;color:var(--navy);
        font-weight:600;transition:background .15s ease, border-color .15s ease;
    }
    .btn-back:hover{background:var(--accent-100);border-color:var(--accent-light);color:var(--navy)}

    .doc-icon-square{
        width:46px;height:46px;border-radius:12px;
        display:flex;align-items:center;justify-content:center;flex-shrink:0;
    }
    .doc-title{font-weight:800;color:var(--navy);font-size:19px}
    .doc-ref-pill{
        font-family:'Courier New',monospace;font-size:11.5px;color:var(--slate-500);
        background:var(--slate-100);border:1px solid var(--slate-300);border-radius:6px;padding:2px 8px;
    }

    .soft-badge{display:inline-block;font-size:12px;font-weight:700;padding:6px 16px;border-radius:999px;white-space:nowrap}
    .badge-status-draft        {background:#eef0f4;color:#5a6473}
    .badge-status-submitted    {background:var(--accent-100);color:#2258c9}
    .badge-status-under_review {background:#fff3e0;color:var(--amber)}
    .badge-status-approved     {background:#e6f7ee;color:var(--green)}
    .badge-status-published    {background:#efe7fd;color:var(--purple)}
    .badge-status-disabled     {background:#fde9e9;color:var(--red)}

    .badge-priority-low     {color:var(--green)}
    .badge-priority-normal  {color:var(--accent)}
    .badge-priority-high    {color:var(--amber)}
    .badge-priority-urgent  {color:var(--red)}

    .panel{border:1px solid #ecf0f5;border-radius:16px;overflow:hidden;position:relative}
    .panel::before{
        content:"";position:absolute;top:0;left:0;right:0;height:3px;
        background:linear-gradient(90deg, var(--accent-light), var(--purple));
    }
    .panel-header{padding:1.15rem 1.4rem 0.4rem}
    .panel-header h6{
        font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.09em;color:var(--slate-500);
        display:flex;align-items:center;gap:8px;margin:0;
    }
    .panel-header h6 i{
        width:20px;height:20px;border-radius:6px;background:var(--accent-100);color:var(--accent);
        display:flex;align-items:center;justify-content:center;font-size:11px;
    }
    .panel-body{padding:1rem 1.4rem 1.4rem}

    .info-item p.label{font-size:12px;color:var(--slate-500);margin:0 0 3px}
    .info-item p.value{font-size:14px;font-weight:700;color:#1f2937;margin:0}

    .btn-action{
        display:flex;align-items:center;justify-content:center;gap:8px;width:100%;
        padding:10px 16px;border-radius:10px;font-size:14px;font-weight:700;border:none;
        transition:filter .15s ease, transform .15s ease;
    }
    .btn-action:hover{transform:translateY(-1px)}
    .btn-modifier {background:linear-gradient(135deg,#f5b93d,#d97706);color:#fff;box-shadow:0 6px 14px -6px rgba(217,119,6,0.45)}
    .btn-soumettre{background:linear-gradient(135deg,var(--accent-light),var(--accent));color:#fff;box-shadow:0 6px 14px -6px rgba(29,78,216,0.45)}
    .btn-publier  {background:linear-gradient(135deg,#a78bfa,var(--purple));color:#fff;box-shadow:0 6px 14px -6px rgba(124,58,237,0.45)}
    .btn-desactiver{background:#fff;color:var(--red);border:1.5px solid #f6c6c6}
    .btn-desactiver:hover{background:#fde9e9;color:var(--red)}
    .btn-modifier:hover, .btn-soumettre:hover, .btn-publier:hover{filter:brightness(1.06);color:#fff}

    .wf-step{border-radius:12px;padding:14px;display:flex;gap:12px;align-items:flex-start}
    .wf-step-badge{font-size:10.5px;font-weight:700;padding:2px 10px;border-radius:999px}

    .version-item{
        display:flex;align-items:center;gap:12px;padding:10px;border-radius:10px;
        background:var(--slate-100);border:1px solid #ecf0f5;
    }
    .version-item.is-current{background:var(--accent-100);border-color:var(--accent-200)}
    .version-icon{
        width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;
        background:#e6ecf5;color:var(--slate-500);
    }
    .version-item.is-current .version-icon{background:var(--accent-200);color:var(--accent)}
    .badge-current{background:var(--accent);color:#fff;font-size:10px;font-weight:700;border-radius:999px;padding:2px 8px}
    .badge-count{background:var(--accent);color:#fff;font-size:10.5px;font-weight:700;border-radius:999px;padding:2px 8px;margin-left:4px}
    .version-voir-btn{
        width:30px;height:30px;border-radius:8px;border:1px solid var(--slate-300);background:#fff;
        display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--accent);
        transition:background .15s ease, border-color .15s ease;
    }
    .version-voir-btn:hover{background:var(--accent-100);border-color:var(--accent-light);color:var(--accent)}
</style>

<div class="container-fluid px-4 py-4">

    {{-- Success Alert --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 rounded-3" role="alert"
         style="background:rgba(34,197,94,0.1);border-left:4px solid #22c55e !important;color:#166534;">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Error Alert --}}
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 rounded-3" role="alert"
         style="background:rgba(239,68,68,0.1);border-left:4px solid #ef4444 !important;color:#991b1b;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('documents.index') }}"
           class="btn btn-sm btn-back px-3">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
        <div class="d-flex align-items-center gap-3">
            {{-- Icône selon type --}}
            @php
                $ext = $document->versions->first()?->file_type ?? 'doc';
                $iconClass = match(strtolower($ext)) {
                    'pdf'  => 'bi-file-earmark-pdf-fill',
                    'xlsx' => 'bi-file-earmark-excel-fill',
                    'docx','doc' => 'bi-file-earmark-word-fill',
                    default => 'bi-file-earmark-text-fill',
                };
                $iconTheme = match(strtolower($ext)) {
                    'pdf'  => ['bg' => '#fde9e9', 'color' => '#c62828'],
                    'xlsx' => ['bg' => '#e6f7ee', 'color' => '#178a4c'],
                    'docx','doc' => ['bg' => '#dbeafe', 'color' => '#1d4ed8'],
                    default => ['bg' => '#eef0f4', 'color' => '#5a6473'],
                };
            @endphp
            <div class="doc-icon-square" style="background:{{ $iconTheme['bg'] }}">
                <i class="bi {{ $iconClass }}" style="font-size:1.4rem;color:{{ $iconTheme['color'] }}"></i>
            </div>
            <div>
                <h4 class="doc-title mb-0">{{ $document->title }}</h4>
                <span class="doc-ref-pill">{{ $document->reference }}</span>
            </div>
        </div>

        {{-- Badge statut --}}
        <div class="ms-auto">
            @php
                $statusConfig = [
                    'draft'        => 'Brouillon',
                    'submitted'    => 'Soumis',
                    'under_review' => 'En relecture',
                    'approved'     => 'Approuvé',
                    'published'    => 'Publié',
                    'disabled'     => 'Désactivé',
                ];
                $sc = $statusConfig[$document->status] ?? $document->status;
            @endphp
            <span class="soft-badge badge-status-{{ $document->status }}">
                {{ $sc }}
            </span>
        </div>
    </div>

    <div class="row g-4">

        {{-- Colonne principale --}}
        <div class="col-lg-8">

            {{-- Informations --}}
            <div class="card border-0 shadow-sm panel mb-4">
                <div class="panel-header">
                    <h6><i class="bi bi-info-circle"></i>Informations</h6>
                </div>
                <div class="panel-body">
                    <div class="row g-3">
                        <div class="col-sm-6 info-item">
                            <p class="label">Référence</p>
                            <p class="value font-monospace">{{ $document->reference }}</p>
                        </div>
                        <div class="col-sm-6 info-item">
                            <p class="label">Catégorie</p>
                            <p class="value">{{ $document->category->name ?? '—' }}</p>
                        </div>
                        <div class="col-sm-6 info-item">
                            <p class="label">Créé par</p>
                            <p class="value">
                                <i class="bi bi-person-circle me-1" style="color:var(--accent)"></i>
                                {{ $document->creator->name ?? '—' }}
                            </p>
                        </div>
                        <div class="col-sm-6 info-item">
                            <p class="label">Priorité</p>
                            @php
                                $prioConfig = [
                                    'low'    => ['label'=>'Faible',  'class'=>'badge-priority-low',    'icon'=>'bi-arrow-down-circle'],
                                    'normal' => ['label'=>'Normal',  'class'=>'badge-priority-normal',  'icon'=>'bi-dash-circle'],
                                    'high'   => ['label'=>'Haute',   'class'=>'badge-priority-high',    'icon'=>'bi-arrow-up-circle'],
                                    'urgent' => ['label'=>'Urgent',  'class'=>'badge-priority-urgent',  'icon'=>'bi-exclamation-circle-fill'],
                                ];
                                $pc = $prioConfig[$document->priority] ?? ['label'=>ucfirst($document->priority),'class'=>'badge-priority-normal','icon'=>'bi-dash-circle'];
                            @endphp
                            <p class="value {{ $pc['class'] }}">
                                <i class="bi {{ $pc['icon'] }} me-1"></i>{{ $pc['label'] }}
                            </p>
                        </div>
                        <div class="col-sm-6 info-item">
                            <p class="label">Créé le</p>
                            <p class="value">{{ $document->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-sm-6 info-item">
                            <p class="label">Dernière modification</p>
                            <p class="value">{{ $document->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    @if($document->description)
                    <hr class="my-3 opacity-25">
                    <p class="label mb-1" style="font-size:12px;color:var(--slate-500)">Description</p>
                    <p class="mb-0">{{ $document->description }}</p>
                    @endif
                </div>
            </div>

            {{-- Workflow --}}
            @if($document->workflowSteps->count() > 0)
            <div class="card border-0 shadow-sm panel mb-4">
                <div class="panel-header">
                    <h6><i class="bi bi-arrow-repeat"></i>Workflow de validation</h6>
                </div>
                <div class="panel-body">
                    <div class="d-flex flex-column gap-3">
                        @foreach($document->workflowSteps as $index => $step)
                        @php
                            $stepConfig = [
                                'approved'    => ['icon'=>'bi-check-circle-fill', 'color'=>'#178a4c', 'bg'=>'rgba(23,138,76,0.08)',  'border'=>'rgba(23,138,76,0.25)'],
                                'rejected'    => ['icon'=>'bi-x-circle-fill',     'color'=>'#c62828', 'bg'=>'rgba(198,40,40,0.08)',  'border'=>'rgba(198,40,40,0.25)'],
                                'in_progress' => ['icon'=>'bi-arrow-repeat',       'color'=>'#1d4ed8', 'bg'=>'rgba(29,78,216,0.08)', 'border'=>'rgba(29,78,216,0.25)'],
                                'pending'     => ['icon'=>'bi-clock',              'color'=>'#64748b', 'bg'=>'rgba(100,116,139,0.08)','border'=>'rgba(100,116,139,0.2)'],
                            ];
                            $sc2 = $stepConfig[$step->status] ?? $stepConfig['pending'];
                        @endphp
                        <div class="wf-step" style="background:{{ $sc2['bg'] }};border:1px solid {{ $sc2['border'] }};">
                            <div class="mt-1">
                                <i class="bi {{ $sc2['icon'] }} fs-5" style="color:{{ $sc2['color'] }};"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="fw-semibold small">{{ $step->step_name }}</span>
                                    <span class="wf-step-badge" style="background:{{ $sc2['color'] }}22;color:{{ $sc2['color'] }};">
                                        Étape {{ $index + 1 }}
                                    </span>
                                </div>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-person me-1"></i>{{ $step->assignedUser->name ?? '—' }}
                                </p>
                                @if($step->comment)
                                <p class="small mb-0 mt-1 fst-italic text-muted">"{{ $step->comment }}"</p>
                                @endif
                            </div>
                            {{-- Actions workflow --}}
                            @if($step->status === 'in_progress' && auth()->id() === $step->assigned_to)
                            <div class="d-flex gap-2 ms-auto">
                                <form action="{{ route('workflow.approve', $step) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm rounded-3" style="background:linear-gradient(135deg,#22b06b,#178a4c);color:#fff;border:none;font-weight:700">
                                        <i class="bi bi-check-lg me-1"></i>Approuver
                                    </button>
                                </form>
                                <form action="{{ route('workflow.reject', $step) }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    <input type="text" name="comment" placeholder="Raison..."
                                        class="form-control form-control-sm rounded-3" style="width:120px;">
                                    <button type="submit" class="btn btn-sm rounded-3" style="background:#fff;color:var(--red);border:1.5px solid #f6c6c6;font-weight:700">
                                        <i class="bi bi-x-lg me-1"></i>Rejeter
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Colonne actions --}}
        <div class="col-lg-4">

            {{-- Actions --}}
            <div class="card border-0 shadow-sm panel mb-4">
                <div class="panel-header">
                    <h6><i class="bi bi-lightning"></i>Actions</h6>
                </div>
                <div class="panel-body d-flex flex-column gap-2">

                    @can('update', $document)
                    <a href="{{ route('documents.edit', $document) }}" class="btn-action btn-modifier">
                        <i class="bi bi-pencil"></i>Modifier
                    </a>
                    @endcan

                    @if($document->canBeSubmitted())
                    <form action="{{ route('workflow.submit', $document) }}" method="POST">
                        @csrf
                        <input type="hidden" name="steps[0][name]" value="Validation">
                        <input type="hidden" name="steps[0][user_id]" value="{{ auth()->id() }}">
                        <button type="submit" class="btn-action btn-soumettre">
                            <i class="bi bi-send"></i>Soumettre
                        </button>
                    </form>
                    @endif

                    @if(auth()->user()->isAdmin() && in_array($document->status, ['draft', 'submitted', 'under_review', 'rejected']))
                    <form action="{{ route('workflow.quick-approve', $document) }}" method="POST"
                          onsubmit="return confirm('Valider et publier ce document directement ?')">
                        @csrf
                        <button type="submit" class="btn-action" style="background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;box-shadow:0 6px 14px -6px rgba(22,163,74,0.45)">
                            <i class="bi bi-lightning-charge-fill"></i>Valider et publier
                        </button>
                    </form>
                    @endif

                    @can('publish', $document)
                    <form action="{{ route('documents.publish', $document) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-action btn-publier">
                            <i class="bi bi-globe"></i>Publier
                        </button>
                    </form>
                    @endcan

                    @can('disable', $document)
                    <form action="{{ route('documents.disable', $document) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-action btn-desactiver"
                                onclick="return confirm('Désactiver ce document ?')">
                            <i class="bi bi-slash-circle"></i>Désactiver
                        </button>
                    </form>
                    @endcan

                </div>
            </div>

            {{-- Versions --}}
            @if($document->versions->count() > 0)
            <div class="card border-0 shadow-sm panel">
                <div class="panel-header">
                    <h6><i class="bi bi-paperclip"></i>Versions
                        <span class="badge-count">{{ $document->versions->count() }}</span>
                    </h6>
                </div>
                <div class="panel-body d-flex flex-column gap-2">
                    @foreach($document->versions->sortByDesc('version_number') as $version)
                    <div class="version-item {{ $version->is_current ? 'is-current' : '' }}">
                        <div class="version-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="fw-semibold small mb-0 text-truncate">{{ $version->file_name }}</p>
                            <p class="text-muted mb-0" style="font-size:.7rem;">
                                v{{ $version->version_number }}
                                @if($version->is_current)
                                    <span class="badge-current ms-1">Actuelle</span>
                                @endif
                            </p>
                        </div>
                        <span class="text-muted" style="font-size:.7rem;white-space:nowrap;">
                            {{ $version->file_size_formatted ?? '' }}
                        </span>
                        <a href="{{ route('documents.voir.version', $version) }}"
                           target="_blank"
                           class="version-voir-btn"
                           title="Voir cette version">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection