@extends('layouts.app')

@section('content')

<style>
/* ===== KPI CARDS ===== */
.kpi-card {
    border: none;
    border-radius: 18px;
    transition: all 0.3s cubic-bezier(.4,0,.2,1);
    cursor: pointer;
    text-decoration: none;
    display: block;
    color: #fff !important;
    overflow: hidden;
    position: relative;
}
.kpi-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(13,43,107,0.25) !important;
    color: #fff !important;
}
.kpi-card::before {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 120px; height: 120px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
    pointer-events: none;
}
.kpi-card::after {
    content: '';
    position: absolute;
    bottom: -20px; left: -20px;
    width: 80px; height: 80px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    pointer-events: none;
}

/* Total — bleu nuit */
.kpi-1 { background: linear-gradient(135deg, #466bb9 0%, #1a4fa0 100%); box-shadow: 0 8px 24px rgba(13,43,107,0.35); }
/* Validés — vert */
.kpi-2 { background: linear-gradient(135deg, #059669 0%, #34d399 100%); box-shadow: 0 8px 24px rgba(5,150,105,0.35); }
/* En Relecture — rose */
.kpi-3 { background: linear-gradient(135deg, #db2777 0%, #f472b6 100%); box-shadow: 0 8px 24px rgba(219,39,119,0.35); }
/* Publiés — bleu ciel */
.kpi-4 { background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%); box-shadow: 0 8px 24px rgba(14,165,233,0.30); }
/* Rejetés — rouge */
.kpi-5 { background: linear-gradient(135deg, #dc2626 0%, #f87171 100%); box-shadow: 0 8px 24px rgba(220,38,38,0.35); }

.kpi-icon {
    width: 50px; height: 50px;
    border-radius: 14px;
    background: rgba(255,255,255,0.18);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    color: #fff;
    flex-shrink: 0;
    backdrop-filter: blur(4px);
}
.kpi-label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-weight: 700;
    color: rgba(255,255,255,0.7);
    margin-bottom: 4px;
}
.kpi-number {
    font-size: 36px;
    font-weight: 900;
    line-height: 1;
    color: #fff;
    margin-bottom: 2px;
}
.kpi-sub {
    font-size: 11px;
    color: rgba(255,255,255,0.65);
}

/* ===== SECTION CARDS ===== */
.section-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(13,43,107,0.08);
    overflow: hidden;
}
.section-header {
    background: #fff;
    border-bottom: 1px solid #f0f5ff;
    padding: 16px 20px;
    font-size: 13px;
    font-weight: 700;
    color: #0d2b6b;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top-left-radius: 16px;
    border-top-right-radius: 16px;
}
.section-header i { color: #1a4fa0; }

/* ===== DOC ROWS ===== */
.doc-row {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    border-bottom: 1px solid #f8faff;
    transition: background 0.15s;
    text-decoration: none;
    color: inherit;
}
.doc-row:hover { background: #f0f5ff; color: inherit; }
.doc-row:last-child { border-bottom: none; }

/* ===== STATUS PILLS ===== */
.status-pill {
    font-size: 11px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
    white-space: nowrap;
    letter-spacing: 0.3px;
}
.pill-draft        { background: #e8f0fe; color: #1a4fa0; }
.pill-submitted    { background: #dbeafe; color: #1d4ed8; }
.pill-under_review { background: #fce7f3; color: #db2777; }
.pill-approved     { background: #d1fae5; color: #059669; }
.pill-published    { background: #e0f2fe; color: #0284c7; }
.pill-rejected     { background: #fee2e2; color: #dc2626; }
.pill-disabled     { background: #f1f5f9; color: #64748b; }

/* ===== WORKFLOW ===== */
.workflow-row {
    padding: 13px 20px;
    border-bottom: 1px solid #f8faff;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: background 0.15s;
}
.workflow-row:hover { background: #f0f5ff; }
.workflow-row:last-child { border-bottom: none; }
.workflow-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* ===== LEGEND ===== */
.legend-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
}

/* ===== BTN VOIR TOUT ===== */
.btn-voir {
    font-size: 11px;
    background: #e8f0fe;
    color: #1a4fa0;
    border: none;
    border-radius: 20px;
    padding: 5px 14px;
    font-weight: 600;
    transition: all 0.2s;
    text-decoration: none;
}
.btn-voir:hover { background: #1a4fa0; color: #fff; }

/* ===== DEPARTMENT CHART CARD ===== */
.tickets-card {
    background: linear-gradient(135deg, #eaf2ff 0%, #dbe9ff 100%);
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(13,43,107,0.08);
    overflow: hidden;
    height: 100%;
}
.tickets-header {
    padding: 16px 20px 0 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.tickets-title {
    font-size: 13px;
    font-weight: 700;
    color: #0d2b6b;
}
.tickets-legend {
    display: flex;
    gap: 16px;
    font-size: 11px;
    color: #475569;
}
.tickets-legend span { display: flex; align-items: center; gap: 6px; }
.tickets-legend .dot { width: 10px; height: 3px; border-radius: 2px; display: inline-block; }
</style>

@php
    $departmentLabelsArr = $departmentLabels ?? ['DSI', 'RH', 'Finance', 'Marketing'];
    $departmentCountsArr = $departmentCounts ?? [10, 6, 4, 2];
@endphp

{{-- KPI Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('documents.index') }}" class="kpi-card kpi-1 card">
            <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                <div class="kpi-icon"><i class="bi bi-files"></i></div>
                <div>
                    <p class="kpi-label mb-1">Total</p>
                    <div class="kpi-number">{{ $totalDocuments }}</div>
                    <span class="kpi-sub">Documents</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('documents.index') }}?status=approved" class="kpi-card kpi-2 card">
            <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                <div class="kpi-icon"><i class="bi bi-check-circle-fill"></i></div>
                <div>
                    <p class="kpi-label mb-1">Validés</p>
                    <div class="kpi-number">{{ $approvedDocuments }}</div>
                    <span class="kpi-sub">Approuvés</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('documents.index') }}?status=under_review" class="kpi-card kpi-3 card">
            <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                <div class="kpi-icon"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <p class="kpi-label mb-1">En Relecture</p>
                    <div class="kpi-number">{{ $pendingDocuments }}</div>
                    <span class="kpi-sub">En attente</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('documents.index') }}?status=published" class="kpi-card kpi-4 card">
            <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                <div class="kpi-icon"><i class="bi bi-globe2"></i></div>
                <div>
                    <p class="kpi-label mb-1">Publiés</p>
                    <div class="kpi-number">{{ $publishedDocuments }}</div>
                    <span class="kpi-sub">Documents publiés</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('documents.index') }}?status=rejected" class="kpi-card kpi-5 card">
            <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                <div class="kpi-icon"><i class="bi bi-x-circle-fill"></i></div>
                <div>
                    <p class="kpi-label mb-1">Rejetés</p>
                    <div class="kpi-number">{{ $rejectedDocuments }}</div>
                    <span class="kpi-sub">Documents rejetés</span>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Donut + Top créateur + Tickets Chart --}}
<div class="row g-3 mb-3">
    <div class="col-lg-4">
        <div class="section-card card">
            <div class="section-header" style="background:linear-gradient(135deg,#0d2b6b,#1a4fa0);color:#fff;">
                <span><i class="bi bi-pie-chart me-2" style="color:#fff"></i>Répartition</span>
            </div>
            <div class="card-body d-flex align-items-center justify-content-start flex-column gap-3 py-4">
                <div style="position:relative;width:165px;height:165px">
                    <canvas id="donutChart"></canvas>
                    <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none">
                        <span style="font-size:30px;font-weight:900;color:#0d2b6b;line-height:1">{{ $totalDocuments }}</span>
                        <span style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:1.2px">total</span>
                    </div>
                </div>
                <div class="w-100 px-2">
                    @php
                        $legend = [
                            ['label'=>'Validés',     'color'=>'#059669', 'count'=>$approvedDocuments],
                            ['label'=>'En relecture','color'=>'#db2777', 'count'=>$pendingDocuments],
                            ['label'=>'Publiés',     'color'=>'#0ea5e9', 'count'=>$publishedDocuments],
                            ['label'=>'Rejetés',     'color'=>'#dc2626', 'count'=>$rejectedDocuments],
                        ];
                    @endphp
                    @foreach($legend as $item)
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="d-flex align-items-center gap-2" style="font-size:12px;color:#475569">
                            <span class="legend-dot" style="background:{{ $item['color'] }}"></span>
                            {{ $item['label'] }}
                        </span>
                        <span style="font-size:12px">
                            <strong style="color:{{ $item['color'] }}">{{ $item['count'] }}</strong>
                            <span class="text-muted ms-1">{{ $totalDocuments > 0 ? round($item['count'] / $totalDocuments * 100) : 0 }}%</span>
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="section-card card mt-3">
            <div class="section-header" style="background:linear-gradient(135deg,#0d2b6b,#1a4fa0);color:#fff;">
                <span><i class="bi bi-trophy me-2" style="color:#fff"></i>Top créateur</span>
            </div>
            <div class="card-body py-4">
                @if($topUser)
                <div class="d-flex align-items-center gap-3">
                    <div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#1a4fa0,#0d2b6b);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:18px;flex-shrink:0;">
                        {{ strtoupper(substr($topUser->name, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <p class="fw-semibold mb-0" style="font-size:14px;color:#0f172a">{{ $topUser->name }}</p>
                        <small style="color:#94a3b8;font-size:11.5px">{{ $topUser->department ?? '—' }}</small>
                    </div>
                    <div class="text-end">
                        <div style="font-size:22px;font-weight:900;color:#0d2b6b;line-height:1">{{ $topUser->documents_count }}</div>
                        <small style="color:#94a3b8;font-size:10.5px">documents</small>
                    </div>
                </div>
                @else
                <div class="text-center text-muted py-2">
                    <i class="bi bi-inbox" style="font-size:24px;color:#bfdbfe"></i>
                    <p class="mt-2 mb-0" style="font-size:12.5px">Aucune donnée disponible</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="tickets-card">
            <div class="tickets-header">
                <span class="tickets-title">Documents par département</span>
            </div>
            <div style="padding:10px 16px 16px 16px;height:280px">
                <canvas id="departmentChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Workflow --}}
<div class="section-card card">
    <div class="section-header" style="background:linear-gradient(135deg,#0d2b6b,#1a4fa0);color:#fff;">
        <span><i class="bi bi-arrow-repeat me-2" style="color:#fff"></i>Workflow en cours</span>
        <a href="{{ route('workflow.index') }}" class="btn-voir" style="background:rgba(255,255,255,0.15);color:#fff;">Voir tout →</a>
    </div>
    <div class="card-body p-0">
        @forelse($workflowSteps as $step)
        @php
            $wc = [
                'pending'     => ['#94a3b8', 'En attente'],
                'in_progress' => ['#db2777', 'En cours'],
                'approved'    => ['#059669', 'Approuvé'],
                'rejected'    => ['#dc2626', 'Rejeté'],
            ];
            $w = $wc[$step->status] ?? ['#94a3b8', $step->status];
        @endphp
        <div class="workflow-row">
            <div class="workflow-dot" style="background:{{ $w[0] }};box-shadow:0 0 0 3px {{ $w[0] }}33"></div>
            <div class="flex-grow-1 overflow-hidden">
                <a href="{{ route('documents.show', $step->document) }}" class="text-decoration-none fw-semibold" style="font-size:13px;color:#0d2b6b">
                    {{ Str::limit($step->document->title ?? '—', 40) }}
                </a>
                <div class="d-flex gap-3 mt-1">
                    <small style="color:#94a3b8;font-size:11px"><i class="bi bi-layers me-1"></i>{{ $step->step_name }}</small>
                    <small style="color:#94a3b8;font-size:11px"><i class="bi bi-person me-1"></i>{{ $step->assignedUser->name ?? '—' }}</small>
                    @if($step->deadline)
                    <small style="color:#94a3b8;font-size:11px"><i class="bi bi-calendar me-1"></i>{{ $step->deadline->format('d/m/Y') }}</small>
                    @endif
                </div>
            </div>
            <span class="status-pill flex-shrink-0" style="background:{{ $w[0] }}18;color:{{ $w[0] }}">{{ $w[1] }}</span>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox" style="font-size:28px;color:#bfdbfe"></i>
            <p class="mt-2 mb-0" style="font-size:13px">Aucun workflow en cours</p>
        </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [{{ $approvedDocuments }}, {{ $pendingDocuments }}, {{ $publishedDocuments }}, {{ $rejectedDocuments }}],
                backgroundColor: ['#059669', '#db2777', '#0ea5e9', '#dc2626'],
                borderWidth: 4,
                borderColor: '#fff',
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '74%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            const labels = ['Validés', 'En relecture', 'Publiés', 'Rejetés'];
                            return ' ' + labels[ctx.dataIndex] + ': ' + ctx.raw;
                        }
                    }
                }
            }
        }
    });

    // ===== Documents par département =====
    const departmentLabels = {!! json_encode($departmentLabelsArr) !!};
    const departmentCounts = {!! json_encode($departmentCountsArr) !!};
    const departmentColors = ['#1a4fa0', '#0ea5e9', '#3b82f6', '#38bdf8', '#2563eb', '#0284c7', '#60a5fa'];

    new Chart(document.getElementById('departmentChart'), {
        type: 'bar',
        data: {
            labels: departmentLabels,
            datasets: [{
                label: 'Documents',
                data: departmentCounts,
                backgroundColor: departmentLabels.map((_, i) => departmentColors[i % departmentColors.length]),
                borderRadius: 8,
                maxBarThickness: 46,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0d2b6b',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#1a4fa0',
                    borderWidth: 1,
                    callbacks: {
                        label: function(ctx) { return ' ' + ctx.raw + ' documents'; }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#334155', font: { size: 11, weight: '600' } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(13,43,107,0.08)' },
                    ticks: { color: '#334155', font: { size: 11 }, precision: 0 }
                }
            }
        }
    });
</script>
@endpush

@endsection