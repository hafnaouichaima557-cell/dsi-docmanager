@extends('layouts.app')

@section('content')

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1" style="font-size:12px;text-transform:uppercase;letter-spacing:1px">Total Documents</p>
                        <h2 class="fw-bold mb-0" style="color:#0d2b6b">{{ $totalDocuments }}</h2>
                        <small class="text-muted">Tous statuts confondus</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;background:#e8f0fe">
                        <i class="bi bi-file-earmark-text" style="font-size:22px;color:#1a4fa0"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1" style="font-size:12px;text-transform:uppercase;letter-spacing:1px">Documents Validés</p>
                        <h2 class="fw-bold mb-0 text-success">{{ $approvedDocuments }}</h2>
                        <small class="text-muted">Approuvés</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;background:#d1fae5">
                        <i class="bi bi-check-circle" style="font-size:22px;color:#059669"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1" style="font-size:12px;text-transform:uppercase;letter-spacing:1px">En Relecture</p>
                        <h2 class="fw-bold mb-0 text-warning">{{ $pendingDocuments }}</h2>
                        <small class="text-muted">En attente d'approbation</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;background:#fef3c7">
                        <i class="bi bi-clock" style="font-size:22px;color:#d97706"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1" style="font-size:12px;text-transform:uppercase;letter-spacing:1px">Publiés</p>
                        <h2 class="fw-bold mb-0 text-primary">{{ $publishedDocuments }}</h2>
                        <small class="text-muted">Documents publiés</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;background:#ede9fe">
                        <i class="bi bi-globe" style="font-size:22px;color:#7c3aed"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts + Recent --}}
<div class="row g-3">
{{-- Donut Chart --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 fw-semibold" style="font-size:14px">
                Répartition des documents
            </div>
            <div class="card-body d-flex align-items-center justify-content-center flex-column gap-3">
                <div style="position:relative;width:160px;height:160px">
                    <canvas id="donutChart"></canvas>
                    <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center">
                        <span class="fw-bold" style="font-size:24px">{{ $totalDocuments }}</span>
                        <span class="text-muted" style="font-size:11px">total</span>
                    </div>
                </div>
                <div class="w-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="d-flex align-items-center gap-2" style="font-size:13px">
                            <span class="rounded-circle" style="width:10px;height:10px;background:#1d4ed8;display:inline-block"></span>
                            Validés
                        </span>
                        <strong>{{ $approvedDocuments }}</strong>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="d-flex align-items-center gap-2" style="font-size:13px">
                            <span class="rounded-circle" style="width:10px;height:10px;background:#d97706;display:inline-block"></span>
                            En relecture
                        </span>
                        <strong>{{ $pendingDocuments }}</strong>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="d-flex align-items-center gap-2" style="font-size:13px">
                            <span class="rounded-circle" style="width:10px;height:10px;background:#7c3aed;display:inline-block"></span>
                            Publiés
                        </span>
                        <strong>{{ $publishedDocuments }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- Recent Documents --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
                <span class="fw-semibold" style="font-size:14px">Derniers documents</span>
                <a href="{{ route('documents.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:12px">
                    Voir tout
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentDocuments->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox" style="font-size:32px"></i>
                        <p class="mt-2">Aucun document pour le moment</p>
                    </div>
                @else
                <table class="table table-hover mb-0" style="font-size:13px">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 ps-3">Titre</th>
                            <th class="border-0">Catégorie</th>
                            <th class="border-0">Statut</th>
                            <th class="border-0">Créé par</th>
                            <th class="border-0">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDocuments as $doc)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('documents.show', $doc) }}"
                                   class="text-decoration-none fw-medium text-dark">
                                    {{ Str::limit($doc->title, 30) }}
                                </a>
                            </td>
                            <td class="text-muted">{{ $doc->category->name ?? '—' }}</td>
                            <td>
                                @php
                                    $badges = [
                                        'draft'        => 'secondary',
                                        'submitted'    => 'primary',
                                        'under_review' => 'warning',
                                        'approved'     => 'success',
                                        'published'    => 'info',
                                        'disabled'     => 'danger',
                                    ];
                                    $badge = $badges[$doc->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badge }}" style="font-size:11px">
                                    {{ $doc->status }}
                                </span>
                            </td>
                            <td class="text-muted">{{ $doc->creator->name ?? '—' }}</td>
                            <td class="text-muted">{{ $doc->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [{{ $approvedDocuments }}, {{ $pendingDocuments }}, {{ $publishedDocuments }}],
                backgroundColor: ['#1d4ed8', '#d97706', '#7c3aed'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: { legend: { display: false }, tooltip: { enabled: false } }
        }
    });
</script>
@endpush

@endsection