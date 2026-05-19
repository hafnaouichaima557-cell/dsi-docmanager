@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#0d2b6b">
            <i class="bi bi-file-earmark-text me-2"></i>Documents
        </h4>
        <small class="text-muted">Gestion des documents DSI</small>
    </div>
    <a href="{{ route('documents.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nouveau document
    </a>
</div>

{{-- Filtres --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('documents.index') }}" class="d-flex gap-3 align-items-center">
            <div class="flex-grow-1">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control border-start-0"
                        placeholder="Rechercher un document...">
                </div>
            </div>
            <select name="status" class="form-select" style="width:180px">
                <option value="">Tous les statuts</option>
                <option value="draft"        {{ request('status') == 'draft'        ? 'selected' : '' }}>Brouillon</option>
                <option value="submitted"    {{ request('status') == 'submitted'    ? 'selected' : '' }}>Soumis</option>
                <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>En révision</option>
                <option value="approved"     {{ request('status') == 'approved'     ? 'selected' : '' }}>Approuvé</option>
                <option value="published"    {{ request('status') == 'published'    ? 'selected' : '' }}>Publié</option>
                <option value="disabled"     {{ request('status') == 'disabled'     ? 'selected' : '' }}>Désactivé</option>
            </select>
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-funnel me-1"></i>Filtrer
            </button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0" style="font-size:13px">
            <thead style="background:#f8faff">
                <tr>
                    <th class="border-0 ps-3 py-3" style="color:#0d2b6b">Référence</th>
                    <th class="border-0 py-3" style="color:#0d2b6b">Titre</th>
                    <th class="border-0 py-3" style="color:#0d2b6b">Catégorie</th>
                    <th class="border-0 py-3" style="color:#0d2b6b">Statut</th>
                    <th class="border-0 py-3" style="color:#0d2b6b">Priorité</th>
                    <th class="border-0 py-3" style="color:#0d2b6b">Créé par</th>
                    <th class="border-0 py-3" style="color:#0d2b6b">Date</th>
                    <th class="border-0 py-3" style="color:#0d2b6b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $document)
                <tr>
                    <td class="ps-3 py-3">
                        <span class="badge bg-light text-dark border font-monospace">
                            {{ $document->reference }}
                        </span>
                    </td>
                    <td class="py-3">
                        <a href="{{ route('documents.show', $document) }}"
                           class="text-decoration-none fw-medium text-dark">
                            {{ Str::limit($document->title, 35) }}
                        </a>
                    </td>
                    <td class="py-3 text-muted">{{ $document->category->name ?? '—' }}</td>
                    <td class="py-3">
                        @php
                            $badges = [
                                'draft'        => ['secondary', 'Brouillon'],
                                'submitted'    => ['primary',   'Soumis'],
                                'under_review' => ['warning',   'En révision'],
                                'approved'     => ['success',   'Approuvé'],
                                'published'    => ['info',      'Publié'],
                                'disabled'     => ['danger',    'Désactivé'],
                            ];
                            $b = $badges[$document->status] ?? ['secondary', $document->status];
                        @endphp
                        <span class="badge bg-{{ $b[0] }}" style="font-size:11px">{{ $b[1] }}</span>
                    </td>
                    <td class="py-3">
                        @php
                            $pColors = ['low'=>'success','normal'=>'secondary','high'=>'warning','urgent'=>'danger'];
                            $pColor  = $pColors[$document->priority] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $pColor }}" style="font-size:11px">{{ ucfirst($document->priority) }}</span>
                    </td>
                    <td class="py-3 text-muted">{{ $document->creator->name ?? '—' }}</td>
                    <td class="py-3 text-muted">{{ $document->created_at->format('d/m/Y') }}</td>
                    <td class="py-3">
                        <div class="d-flex gap-1">
                            <a href="{{ route('documents.show', $document) }}"
                               class="btn btn-sm btn-outline-primary" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('documents.edit', $document) }}"
                               class="btn btn-sm btn-outline-secondary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size:32px"></i>
<p class="mt-2 mb-0">Aucun document trouvé</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($documents->hasPages())
    <div class="card-footer bg-white border-0 d-flex justify-content-end">
        {{ $documents->links() }}
    </div>
    @endif
</div>

@endsection