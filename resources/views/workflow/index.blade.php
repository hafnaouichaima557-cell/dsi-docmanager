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
    }

    .page-icon{
        width:42px;height:42px;border-radius:12px;
        background:linear-gradient(135deg, var(--accent-light), var(--navy));
        display:flex;align-items:center;justify-content:center;
        color:#fff;font-size:18px;
        box-shadow:0 4px 10px rgba(29,78,216,0.32);
        flex-shrink:0;
    }
    .page-title{font-weight:800;color:var(--navy);margin-bottom:2px;font-size:21px}
    .page-subtitle{font-size:13px;color:var(--slate-500)}

    .wf-card{border:1px solid #ecf0f5;border-radius:14px;overflow:hidden;position:relative}
    .wf-card::before{
        content:"";position:absolute;top:0;left:0;right:0;height:4px;z-index:1;
        background:linear-gradient(90deg,#5a6473,#d97706,#178a4c,#c62828);
    }

    .kpi-row{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:1.25rem}
    .kpi-card{
        border-radius:14px;padding:14px 16px;display:flex;align-items:center;gap:12px;
        border:1px solid transparent;
    }
    .kpi-icon{
        width:38px;height:38px;border-radius:10px;flex-shrink:0;
        display:flex;align-items:center;justify-content:center;font-size:16px;color:#fff;
    }
    .kpi-value{font-size:19px;font-weight:800;line-height:1.1}
    .kpi-label{font-size:11.5px;font-weight:600;opacity:.85}

    .kpi-pending    {background:#eef0f4;} .kpi-pending .kpi-icon{background:#5a6473} .kpi-pending .kpi-value{color:#3a4250}
    .kpi-in_progress{background:#fff3e0;} .kpi-in_progress .kpi-icon{background:#d97706} .kpi-in_progress .kpi-value{color:#8a4c07}
    .kpi-approved   {background:#e6f7ee;} .kpi-approved .kpi-icon{background:#178a4c} .kpi-approved .kpi-value{color:#0e6b3a}
    .kpi-rejected   {background:#fde9e9;} .kpi-rejected .kpi-icon{background:#c62828} .kpi-rejected .kpi-value{color:#961f1f}

    table.wf-table thead th{
        background:linear-gradient(180deg,#f8faff,var(--slate-100));color:var(--slate-500);
        font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;
        padding:13px 14px;border:none;
    }
    table.wf-table tbody tr{border-bottom:1px solid #f0f2f6;transition:background .12s ease;border-left:3px solid transparent}
    table.wf-table tbody tr:hover{background:#fafbfd}
    table.wf-table tbody td{padding:12px 14px;vertical-align:middle}
    table.wf-table tbody tr.row-pending{border-left-color:#c7ccd6}
    table.wf-table tbody tr.row-in_progress{border-left-color:#d97706}
    table.wf-table tbody tr.row-approved{border-left-color:#178a4c}
    table.wf-table tbody tr.row-rejected{border-left-color:#c62828}

    .soft-badge{
        display:inline-block;font-size:11px;font-weight:700;
        padding:4px 11px;border-radius:999px;white-space:nowrap;
    }
    .badge-wf-pending     {background:#eef0f4;color:#5a6473}
    .badge-wf-in_progress {background:#fff3e0;color:#b3670c}
    .badge-wf-approved    {background:#e6f7ee;color:#178a4c}
    .badge-wf-rejected    {background:#fde9e9;color:#c62828}

    .btn-wf-voir{
        display:inline-flex;align-items:center;justify-content:center;
        width:32px;height:32px;border-radius:8px;
        border:1.5px solid var(--slate-300);background:#fff;color:var(--accent);
        transition:background .15s ease, border-color .15s ease;
        flex-shrink:0;
    }
    .btn-wf-voir:hover{background:var(--accent-100);border-color:var(--accent-light);color:var(--accent)}

    .btn-wf-approve, .btn-wf-validate-dept, .btn-wf-validate-resp{
        display:inline-flex;align-items:center;gap:5px;
        padding:6px 13px;border-radius:8px;border:none;
        color:#fff;font-size:12px;font-weight:700;
        transition:filter .15s ease, transform .15s ease;
    }
    .btn-wf-approve, .btn-wf-validate-resp{
        background:linear-gradient(135deg,#22b06b,#178a4c);
        box-shadow:0 4px 10px -3px rgba(23,138,76,0.4);
    }
    .btn-wf-validate-dept{
        background:linear-gradient(135deg,#0ea5e9,#0284c7);
        box-shadow:0 4px 10px -3px rgba(2,132,199,0.4);
    }
    .btn-wf-approve:hover, .btn-wf-validate-dept:hover, .btn-wf-validate-resp:hover{
        filter:brightness(1.06);transform:translateY(-1px);color:#fff;
    }

    .btn-wf-reject{
        display:inline-flex;align-items:center;gap:5px;
        padding:6px 13px;border-radius:8px;border:none;
        background:#fff;color:#c62828;border:1.5px solid #f6c6c6;
        font-size:12px;font-weight:700;
        transition:background .15s ease;
    }
    .btn-wf-reject:hover{background:#fde9e9;color:#c62828}

    .reject-input{
        border:1.5px solid var(--slate-300);border-radius:8px;
        font-size:12px;padding:5px 10px;width:120px;
        background:var(--slate-100);outline:none;
        transition:border-color .15s ease, background .15s ease;
    }
    .reject-input:focus{border-color:var(--accent);background:#fff;box-shadow:0 0 0 3px var(--accent-100)}

    .wf-empty{padding:3.5rem 1rem;text-align:center}
    .wf-empty-icon{
        width:64px;height:64px;border-radius:50%;
        background:var(--accent-100);color:var(--accent-light);
        display:flex;align-items:center;justify-content:center;
        margin:0 auto 14px;font-size:26px;
    }
    .wf-empty p.title{font-size:14px;font-weight:700;color:var(--navy);margin:0 0 4px}
    .wf-empty p.subtitle{font-size:12.5px;color:var(--slate-500);margin:0}
</style>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="page-icon">
            <i class="bi bi-arrow-repeat"></i>
        </div>
        <div>
            <div class="page-title">Workflow</div>
            <div class="page-subtitle">Documents en attente de validation</div>
        </div>
    </div>
</div>

@php
    $wfCounts = [
        'pending'     => $steps->where('status','pending')->count(),
        'in_progress' => $steps->where('status','in_progress')->count(),
        'approved'    => $steps->where('status','approved')->count(),
        'rejected'    => $steps->where('status','rejected')->count(),
    ];
@endphp
<div class="kpi-row">
    <div class="kpi-card kpi-pending">
        <div class="kpi-icon"><i class="bi bi-hourglass-split"></i></div>
        <div><div class="kpi-value">{{ $wfCounts['pending'] }}</div><div class="kpi-label">En attente</div></div>
    </div>
    <div class="kpi-card kpi-in_progress">
        <div class="kpi-icon"><i class="bi bi-arrow-repeat"></i></div>
        <div><div class="kpi-value">{{ $wfCounts['in_progress'] }}</div><div class="kpi-label">En cours</div></div>
    </div>
    <div class="kpi-card kpi-approved">
        <div class="kpi-icon"><i class="bi bi-check-circle"></i></div>
        <div><div class="kpi-value">{{ $wfCounts['approved'] }}</div><div class="kpi-label">Approuvé</div></div>
    </div>
    <div class="kpi-card kpi-rejected">
        <div class="kpi-icon"><i class="bi bi-x-circle"></i></div>
        <div><div class="kpi-value">{{ $wfCounts['rejected'] }}</div><div class="kpi-label">Rejeté</div></div>
    </div>
</div>

<div class="card wf-card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover wf-table mb-0" style="font-size:13px">
            <thead>
                <tr>
                    <th class="ps-3">Document</th>
                    <th>Étape</th>
                    <th>Assigné à</th>
                    <th>Statut</th>
                    <th>Deadline</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($steps as $step)
                @php
                    $doc = $step->document;
                    $sameDept = $doc && auth()->user()->department && $doc->department
                        && strtolower(trim(auth()->user()->department)) === strtolower(trim($doc->department));

                    $canManualAct = $step->status === 'in_progress' && auth()->id() === $step->assigned_to;

                    $canValidateDept = $doc
                        && $step->step_order == 1
                        && $step->status === 'in_progress'
                        && is_null($step->assigned_to)
                        && $sameDept;

                    $canValidateResp = $doc
                        && $step->step_order == 2
                        && $step->status === 'in_progress'
                        && is_null($step->assigned_to)
                        && (auth()->user()->isAdmin() || (auth()->user()->hasRole('responsable') && $sameDept));
                @endphp
                <tr class="row-{{ $step->status }}">
                    <td class="ps-3">
                        <a href="{{ route('documents.show', $step->document) }}"
                           class="text-decoration-none fw-medium text-dark">
                            {{ Str::limit($step->document->title ?? '—', 35) }}
                        </a>
                    </td>
                    <td>{{ $step->step_name }}</td>
                    <td class="text-muted">{{ $step->assignedUser->name ?? '—' }}</td>
                    <td>
                        @php
                            $sl = ['pending'=>'En attente','in_progress'=>'En cours','approved'=>'Approuvé','rejected'=>'Rejeté'];
                        @endphp
                        <span class="soft-badge badge-wf-{{ $step->status }}">
                            {{ $sl[$step->status] ?? $step->status }}
                        </span>
                    </td>
                    <td class="text-muted">
                        {{ $step->deadline ? $step->deadline->format('d/m/Y') : '—' }}
                    </td>
                    <td>
                        <div class="d-flex gap-2 align-items-center">
                            @if($doc)
                            <a href="{{ route('documents.show', $doc) }}" class="btn-wf-voir" title="Voir le document" target="_blank">
                                <i class="bi bi-eye"></i>
                            </a>
                            @endif

                            @if($canManualAct)
                            <form action="{{ route('workflow.approve', $step) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-wf-approve">
                                    <i class="bi bi-check"></i> Approuver
                                </button>
                            </form>
                            <form action="{{ route('workflow.reject', $step) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <input type="text" name="comment" placeholder="Raison..." class="reject-input" required>
                                <button type="submit" class="btn-wf-reject">
                                    <i class="bi bi-x"></i> Rejeter
                                </button>
                            </form>
                            @elseif($canValidateDept)
                            <form action="{{ route('workflow.validate-department', $step) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-wf-validate-dept">
                                    <i class="bi bi-check"></i> Valider
                                </button>
                            </form>
                            <form action="{{ route('workflow.reject-department', $step) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <input type="text" name="comment" placeholder="Raison..." class="reject-input" required>
                                <button type="submit" class="btn-wf-reject">
                                    <i class="bi bi-x"></i> Rejeter
                                </button>
                            </form>
                            @elseif($canValidateResp)
                            <form action="{{ route('workflow.validate-responsable', $step) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-wf-validate-resp">
                                    <i class="bi bi-check2-all"></i> Validation
                                </button>
                            </form>
                            <form action="{{ route('workflow.reject-responsable', $step) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <input type="text" name="comment" placeholder="Raison..." class="reject-input" required>
                                <button type="submit" class="btn-wf-reject">
                                    <i class="bi bi-x"></i> Rejeter
                                </button>
                            </form>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="wf-empty">
                            <div class="wf-empty-icon">
                                <i class="bi bi-inbox"></i>
                            </div>
                            <p class="title">Aucun workflow en cours</p>
                            <p class="subtitle">Les documents en attente de validation apparaîtront ici</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection