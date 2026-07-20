@extends('layouts.app')

@section('content')

<style>
    :root {
        --accent: #4f8ef7;
        --accent-100: #eaf1ff;
        --navy-950: #0d2b6b;
        --slate-500: #6b7686;
        --slate-300: #dfe3ea;
        --slate-100: #f4f6f9;
    }

    .page-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--accent), #0d2b6b);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        box-shadow: 0 4px 10px rgba(79,142,247,0.35);
        flex-shrink: 0;
    }

    .page-title {
        font-weight: 800;
        color: var(--navy-950);
        margin-bottom: 2px;
        font-size: 21px;
    }

    .page-subtitle {
        font-size: 13px;
        color: var(--slate-500);
    }

    .btn-accent {
        background: linear-gradient(135deg, var(--accent), #3f74e0);
        border: none;
        color: #fff;
        border-radius: 10px;
        padding: 9px 18px;
        font-size: 13.5px;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(79,142,247,0.3);
        transition: filter .15s ease, transform .15s ease;
    }
    .btn-accent:hover { filter: brightness(1.06); color: #fff; transform: translateY(-1px); }

    .filters-card {
        border: 1px solid #ecf0f5;
        border-radius: 14px;
    }

    .search-input-group {
        background: var(--slate-100);
        border: 1px solid #e9ecf2;
        border-radius: 10px;
        overflow: hidden;
        transition: border-color .15s ease, background .15s ease, box-shadow .15s ease;
    }
    .search-input-group:focus-within {
        border-color: var(--accent);
        background: #fff;
        box-shadow: 0 0 0 4px var(--accent-100);
    }
    .search-input-group .input-group-text,
    .search-input-group .form-control {
        background: transparent;
        border: none;
    }
    .search-input-group .form-control:focus { box-shadow: none; }

    .form-select-soft {
        border-radius: 10px;
        border: 1px solid var(--slate-300);
        font-size: 13.5px;
        background-color: var(--slate-100);
    }
    .form-select-soft:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 4px var(--accent-100);
    }

    .btn-filter {
        border-radius: 10px;
        border: 1px solid var(--slate-300);
        color: var(--navy-950);
        font-size: 13.5px;
        font-weight: 600;
        background: #fff;
        transition: background .15s ease, border-color .15s ease;
    }
    .btn-filter:hover { background: var(--accent-100); border-color: var(--accent); color: var(--navy-950); }

    .docs-card {
        border: 1px solid #ecf0f5;
        border-radius: 14px;
        overflow: hidden;
    }

    table.docs-table thead th {
        background: var(--slate-100);
        color: var(--slate-500);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 13px 14px;
        border: none;
    }
    table.docs-table tbody tr {
        border-bottom: 1px solid #f0f2f6;
        transition: background .12s ease;
    }
    table.docs-table tbody tr:hover { background: #fafbfd; }
    table.docs-table tbody td { padding: 12px 14px; vertical-align: middle; }

    .ref-badge {
        font-family: 'Courier New', monospace;
        font-size: 11.5px;
        background: var(--slate-100);
        color: var(--slate-500);
        border: 1px solid var(--slate-300);
        border-radius: 6px;
        padding: 3px 8px;
    }

    .soft-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 11px;
        border-radius: 999px;
        white-space: nowrap;
    }

    /* Statuts */
    .badge-status-draft        { background:#eef0f4; color:#5a6473; }
    .badge-status-submitted    { background:var(--accent-100); color:#2258c9; }
    .badge-status-under_review { background:#fff3e0; color:#b3670c; }
    .badge-status-approved     { background:#e6f7ee; color:#178a4c; }
    .badge-status-published    { background:#e5f4fb; color:#0e7fa8; }
    .badge-status-disabled     { background:#fde9e9; color:#c62828; }

    /* Priorités */
    .badge-priority-low     { background:#e6f7ee; color:#178a4c; }
    .badge-priority-normal  { background:#eef0f4; color:#5a6473; }
    .badge-priority-high    { background:#fff3e0; color:#b3670c; }
    .badge-priority-urgent  { background:#fde9e9; color:#c62828; }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--slate-300);
        background: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background .15s ease, border-color .15s ease;
    }
    .action-btn:hover { background: var(--slate-100); }
    .action-btn.view  i { color: var(--accent); }
    .action-btn.pdf   i { color: #178a4c; }
    .action-btn.edit  i { color: var(--slate-500); }
    .action-btn.view:hover  { border-color: var(--accent); }
    .action-btn.pdf:hover   { border-color: #178a4c; }
    .action-btn.edit:hover  { border-color: var(--slate-500); }
</style>

{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="page-icon">
            <i class="bi bi-file-earmark-text"></i>
        </div>
        <div>
            <div class="page-title">Documents</div>
            <div class="page-subtitle">Gestion des documents DSI</div>
        </div>
    </div>
    <a href="{{ route('documents.create') }}" class="btn btn-accent">
        <i class="bi bi-plus-lg me-1"></i> Nouveau document
    </a>
</div>

{{-- Filtres --}}
<div class="card filters-card shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('documents.index') }}" class="d-flex gap-3 align-items-center">
            <div class="flex-grow-1">
                <div class="input-group search-input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Rechercher un document...">
                </div>
            </div>
            <select name="status" class="form-select form-select-soft" style="width:190px">
                <option value="">Tous les statuts</option>
                <option value="draft"        {{ request('status') == 'draft'        ? 'selected' : '' }}>Brouillon</option>
                <option value="submitted"    {{ request('status') == 'submitted'    ? 'selected' : '' }}>Soumis</option>
                <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>En révision</option>
                <option value="approved"     {{ request('status') == 'approved'     ? 'selected' : '' }}>Approuvé</option>
                <option value="published"    {{ request('status') == 'published'    ? 'selected' : '' }}>Publié</option>
                <option value="disabled"     {{ request('status') == 'disabled'     ? 'selected' : '' }}>Désactivé</option>
            </select>
            <button type="submit" class="btn btn-filter">
                <i class="bi bi-funnel me-1"></i>Filtrer
            </button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Table --}}
<div class="card docs-card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover docs-table mb-0" style="font-size:13px">
            <thead>
                <tr>
                    <th class="ps-3">Référence</th>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Statut</th>
                    <th>Priorité</th>
                    <th>Créé par</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $document)
                <tr>
                    <td class="ps-3">
                        <span class="ref-badge">{{ $document->reference }}</span>
                    </td>
                    <td>
                        <a href="{{ route('documents.show', $document) }}"
                           class="text-decoration-none fw-medium text-dark">
                            {{ Str::limit($document->title, 35) }}
                        </a>
                    </td>
                    <td class="text-muted">{{ $document->category->name ?? '—' }}</td>
                    <td>
                        @php
                            $labels = [
                                'draft'        => 'Brouillon',
                                'submitted'    => 'Soumis',
                                'under_review' => 'En révision',
                                'approved'     => 'Approuvé',
                                'published'    => 'Publié',
                                'disabled'     => 'Désactivé',
                            ];
                            $label = $labels[$document->status] ?? $document->status;
                        @endphp
                        <span class="soft-badge badge-status-{{ $document->status }}">{{ $label }}</span>
                    </td>
                    <td>
                        <span class="soft-badge badge-priority-{{ $document->priority }}">{{ ucfirst($document->priority) }}</span>
                    </td>
                    <td class="text-muted">{{ $document->creator->name ?? '—' }}</td>
                    <td class="text-muted">{{ $document->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('documents.show', $document) }}"
                               class="action-btn view" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('documents.voir', $document) }}"
                               target="_blank"
                               class="action-btn pdf" title="Ouvrir le document">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>

                            <a href="{{ route('documents.edit', $document) }}"
                               class="action-btn edit" title="Modifier">
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
    <div class="card-footer bg-white border-0 d-flex justify-content-end py-3">
        {{ $documents->links() }}
    </div>
    @endif
</div>

@endsection