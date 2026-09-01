@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('documents.show', $document) }}"
           class="btn btn-sm btn-outline-secondary rounded-3 px-3">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center"
                 style="width:42px;height:42px;background:rgba(255,193,7,0.15);">
                <i class="bi bi-pencil-fill text-warning fs-5"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Modifier le Document</h4>
                <small class="text-muted font-monospace">{{ $document->reference }}</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">

            {{-- Erreurs --}}
            @if($errors->any())
            <div class="alert border-0 rounded-3 mb-4"
                 style="background:rgba(220,38,38,0.1);border-left:4px solid #ef4444 !important;color:#991b1b;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Veuillez corriger les erreurs :</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted" style="letter-spacing:.08em;font-size:.75rem;">
                        <i class="bi bi-info-circle me-2 text-primary"></i>Informations du document
                    </h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('documents.update', $document) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Titre --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Titre <span class="text-danger">*</span></label>
                            <input type="text" name="title"
                                value="{{ old('title', $document->title) }}"
                                class="form-control rounded-3 @error('title') is-invalid @enderror"
                                placeholder="Titre du document">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Description</label>
                            <textarea name="description" rows="3"
                                class="form-control rounded-3 @error('description') is-invalid @enderror"
                                placeholder="Description du document...">{{ old('description', $document->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Catégorie --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Catégorie</label>
                            <select name="category_id" class="form-select rounded-3">
                                <option value="">-- Choisir une catégorie --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $document->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- La priorité conserve sa valeur actuelle en base (champ retiré du formulaire) --}}
                        <input type="hidden" name="priority" value="{{ $document->priority }}">

                        {{-- Divider --}}
                        <hr class="my-4 opacity-25">

                        {{-- Nouveau fichier --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">
                                Nouveau fichier
                                <span class="text-muted fw-normal">(laisser vide pour garder l'ancien)</span>
                            </label>

                            {{-- Fichier actuel --}}
                            @if($document->versions->isNotEmpty())
                            @php $current = $document->versions->where('is_current', true)->first() ?? $document->versions->first(); @endphp
                            <div class="d-flex align-items-center gap-2 p-2 rounded-3 mb-2"
                                 style="background:rgba(59,130,246,0.07);border:1px solid rgba(59,130,246,0.2);">
                                <i class="bi bi-paperclip text-primary"></i>
                                <span class="small fw-medium">{{ $current->file_name }}</span>
                                <span class="badge bg-primary ms-auto" style="font-size:.65rem;">Actuel</span>
                            </div>
                            @endif

                            <input type="file" name="file"
                                class="form-control rounded-3 @error('file') is-invalid @enderror"
                                accept=".pdf,.docx,.xlsx,.doc,.xls,.pptx,.ppt,.png,.jpg,.jpeg,.txt,.csv,.zip">
                            <div class="form-text">Formats acceptés : PDF, DOCX, XLSX, Images (PNG/JPG)... — max 10 MB</div>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Notes de modification --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Notes de modification</label>
                            <textarea name="change_notes" rows="2"
                                class="form-control rounded-3"
                                placeholder="Décrivez les modifications apportées...">{{ old('change_notes') }}</textarea>
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold">
                                <i class="bi bi-floppy me-2"></i>Enregistrer
                            </button>
                            <a href="{{ route('documents.show', $document) }}"
                               class="btn btn-outline-secondary rounded-3 px-4">
                                Annuler
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>

        {{-- Sidebar info --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted" style="letter-spacing:.08em;font-size:.75rem;">
                        <i class="bi bi-clock-history me-2 text-primary"></i>Informations actuelles
                    </h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Référence</p>
                        <p class="fw-semibold font-monospace mb-0">{{ $document->reference }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Statut actuel</p>
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
                        <span class="badge {{ $sc['class'] }} rounded-pill px-3">{{ $sc['label'] }}</span>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Créé le</p>
                        <p class="fw-semibold mb-0">{{ $document->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Dernière modification</p>
                        <p class="fw-semibold mb-0">{{ $document->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection