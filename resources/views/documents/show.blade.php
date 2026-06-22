@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Success Alert --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 rounded-3" role="alert"
         style="background:rgba(34,197,94,0.1);border-left:4px solid #22c55e !important;color:#166534;">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('documents.index') }}"
           class="btn btn-sm btn-outline-secondary rounded-3 px-3">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
        <div class="d-flex align-items-center gap-3">
            {{-- Icône selon type --}}
            @php
                $ext = $document->versions->first()?->file_type ?? 'doc';
                $iconClass = match(strtolower($ext)) {
                    'pdf'  => 'bi-file-earmark-pdf-fill text-danger',
                    'xlsx' => 'bi-file-earmark-excel-fill text-success',
                    'docx','doc' => 'bi-file-earmark-word-fill text-primary',
                    default => 'bi-file-earmark-text-fill text-secondary',
                };
            @endphp
            <i class="bi {{ $iconClass }}" style="font-size:2rem;"></i>
            <div>
                <h4 class="fw-bold mb-0 text-dark">{{ $document->title }}</h4>
                <small class="text-muted font-monospace">{{ $document->reference }}</small>
            </div>
        </div>

        {{-- Badge statut --}}
        <div class="ms-auto">
            @php
                $statusConfig = [
                    'draft'        => ['label'=>'Brouillon',   'class'=>'bg-secondary'],
                    'submitted'    => ['label'=>'Soumis',      'class'=>'bg-info text-dark'],
                    'under_review' => ['label'=>'En relecture','class'=>'bg-warning text-dark'],
                    'approved'     => ['label'=>'Approuvé',    'class'=>'bg-success'],
                    'published'    => ['label'=>'Publié',      'class'=>'bg-primary'],
                    'disabled'     => ['label'=>'Désactivé',   'class'=>'bg-danger'],
                ];
                $sc = $statusConfig[$document->status] ?? ['label'=>$document->status,'class'=>'bg-secondary'];
            @endphp
            <span class="badge {{ $sc['class'] }} rounded-pill px-3 py-2 fs-6">
                {{ $sc['label'] }}
            </span>
        </div>
    </div>

    <div class="row g-4">

        {{-- Colonne principale --}}
        <div class="col-lg-8">

            {{-- Informations --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted" style="letter-spacing:.08em;font-size:.75rem;">
                        <i class="bi bi-info-circle me-2 text-primary"></i>Informations
                    </h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Référence</p>
                            <p class="fw-semibold font-monospace mb-0">{{ $document->reference }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Catégorie</p>
                            <p class="fw-semibold mb-0">{{ $document->category->name ?? '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Créé par</p>
                            <p class="fw-semibold mb-0">
                                <i class="bi bi-person-circle me-1 text-primary"></i>
                                {{ $document->creator->name ?? '—' }}
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Priorité</p>
                            @php
                                $prioConfig = [
                                    'low'    => ['label'=>'Faible',  'class'=>'text-success', 'icon'=>'bi-arrow-down-circle'],
                                    'normal' => ['label'=>'Normal',  'class'=>'text-primary', 'icon'=>'bi-dash-circle'],
                                    'high'   => ['label'=>'Haute',   'class'=>'text-warning', 'icon'=>'bi-arrow-up-circle'],
                                    'urgent' => ['label'=>'Urgent',  'class'=>'text-danger',  'icon'=>'bi-exclamation-circle-fill'],
                                ];
                                $pc = $prioConfig[$document->priority] ?? ['label'=>ucfirst($document->priority),'class'=>'text-secondary','icon'=>'bi-dash-circle'];
                            @endphp
                            <p class="fw-semibold mb-0 {{ $pc['class'] }}">
                                <i class="bi {{ $pc['icon'] }} me-1"></i>{{ $pc['label'] }}
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Créé le</p>
                            <p class="fw-semibold mb-0">{{ $document->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Dernière modification</p>
                            <p class="fw-semibold mb-0">{{ $document->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    @if($document->description)
                    <hr class="my-3 opacity-25">
                    <p class="text-muted small mb-1">Description</p>
                    <p class="mb-0">{{ $document->description }}</p>
                    @endif
                </div>
            </div>

            {{-- Workflow --}}
            @if($document->workflowSteps->count() > 0)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted" style="letter-spacing:.08em;font-size:.75rem;">
                        <i class="bi bi-arrow-repeat me-2 text-primary"></i>Workflow de validation
                    </h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="d-flex flex-column gap-3">
                        @foreach($document->workflowSteps as $index => $step)
                        @php
                            $stepConfig = [
                                'approved'    => ['icon'=>'bi-check-circle-fill', 'color'=>'#22c55e', 'bg'=>'rgba(34,197,94,0.08)',  'border'=>'rgba(34,197,94,0.3)'],
                                'rejected'    => ['icon'=>'bi-x-circle-fill',     'color'=>'#ef4444', 'bg'=>'rgba(239,68,68,0.08)',   'border'=>'rgba(239,68,68,0.3)'],
                                'in_progress' => ['icon'=>'bi-arrow-repeat',       'color'=>'#3b82f6', 'bg'=>'rgba(59,130,246,0.08)', 'border'=>'rgba(59,130,246,0.3)'],
                                'pending'     => ['icon'=>'bi-clock',              'color'=>'#94a3b8', 'bg'=>'rgba(148,163,184,0.08)','border'=>'rgba(148,163,184,0.2)'],
                            ];
                            $sc2 = $stepConfig[$step->status] ?? $stepConfig['pending'];
                        @endphp
                        <div class="d-flex align-items-start gap-3 p-3 rounded-3"
                             style="background:{{ $sc2['bg'] }};border:1px solid {{ $sc2['border'] }};">
                            <div class="mt-1">
                                <i class="bi {{ $sc2['icon'] }} fs-5" style="color:{{ $sc2['color'] }};"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="fw-semibold small">{{ $step->step_name }}</span>
                                    <span class="badge rounded-pill px-2"
                                          style="background:{{ $sc2['color'] }}22;color:{{ $sc2['color'] }};font-size:.7rem;">
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
                                    <button type="submit" class="btn btn-success btn-sm rounded-3">
                                        <i class="bi bi-check-lg me-1"></i>Approuver
                                    </button>
                                </form>
                                <form action="{{ route('workflow.reject', $step) }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    <input type="text" name="comment" placeholder="Raison..."
                                        class="form-control form-control-sm rounded-3" style="width:120px;">
                                    <button type="submit" class="btn btn-danger btn-sm rounded-3">
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
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted" style="letter-spacing:.08em;font-size:.75rem;">
                        <i class="bi bi-lightning me-2 text-primary"></i>Actions
                    </h6>
                </div>
                <div class="card-body px-4 pb-4 d-flex flex-column gap-2">

                    @can('update', $document)
                    <a href="{{ route('documents.edit', $document) }}"
                       class="btn btn-warning rounded-3 fw-semibold">
                        <i class="bi bi-pencil me-2"></i>Modifier
                    </a>
                    @endcan

                    @if($document->canBeSubmitted())
                    <form action="{{ route('workflow.submit', $document) }}" method="POST">
                        @csrf
                        <input type="hidden" name="steps[0][name]" value="Validation">
                        <input type="hidden" name="steps[0][user_id]" value="{{ auth()->id() }}">
                        <button type="submit" class="btn btn-primary rounded-3 fw-semibold w-100">
                            <i class="bi bi-send me-2"></i>Soumettre
                        </button>
                    </form>
                    @endif

                    @can('publish', $document)
                    <form action="{{ route('documents.publish', $document) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-purple rounded-3 fw-semibold w-100"
                                style="background:#7c3aed;color:#fff;border:none;">
                            <i class="bi bi-globe me-2"></i>Publier
                        </button>
                    </form>
                    @endcan

                    @can('disable', $document)
                    <form action="{{ route('documents.disable', $document) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-danger rounded-3 fw-semibold w-100"
                                onclick="return confirm('Désactiver ce document ?')">
                            <i class="bi bi-slash-circle me-2"></i>Désactiver
                        </button>
                    </form>
                    @endcan

                </div>
            </div>

            {{-- Versions --}}
            @if($document->versions->count() > 0)
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted" style="letter-spacing:.08em;font-size:.75rem;">
                        <i class="bi bi-paperclip me-2 text-primary"></i>Versions
                        <span class="badge bg-primary rounded-pill ms-1">{{ $document->versions->count() }}</span>
                    </h6>
                </div>
                <div class="card-body px-4 pb-4 d-flex flex-column gap-2">
                    @foreach($document->versions->sortByDesc('version_number') as $version)
                    <div class="d-flex align-items-center gap-3 p-2 rounded-3"
                         style="background:{{ $version->is_current ? 'rgba(59,130,246,0.07)' : '#f8f9fa' }};
                                border:1px solid {{ $version->is_current ? 'rgba(59,130,246,0.2)' : '#e9ecef' }};">
                        <div class="rounded-2 d-flex align-items-center justify-content-center"
                             style="width:36px;height:36px;background:{{ $version->is_current ? '#dbeafe' : '#e9ecef' }};">
                            <i class="bi bi-file-earmark-text {{ $version->is_current ? 'text-primary' : 'text-secondary' }}"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="fw-semibold small mb-0 text-truncate">{{ $version->file_name }}</p>
                            <p class="text-muted mb-0" style="font-size:.7rem;">
                                v{{ $version->version_number }}
                                @if($version->is_current)
                                    <span class="badge bg-primary ms-1" style="font-size:.65rem;">Actuelle</span>
                                @endif
                            </p>
                        </div>
                        <span class="text-muted" style="font-size:.7rem;white-space:nowrap;">
                            {{ $version->file_size_formatted ?? '' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection