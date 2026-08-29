@extends('layouts.app')

@section('content')

<style>
    :root{
        --accent:#178a4c;
        --accent-light:#22b06b;
        --accent-100:#e6f7ee;
        --navy:#0d2b6b;
        --slate-500:#64748b;
        --slate-300:#dfe3ea;
        --slate-100:#f4f6f9;
    }

    .page-icon{
        width:42px;height:42px;border-radius:12px;
        background:linear-gradient(135deg, var(--accent-light), var(--accent));
        display:flex;align-items:center;justify-content:center;
        color:#fff;font-size:18px;
        box-shadow:0 4px 10px rgba(23,138,76,0.32);
        flex-shrink:0;
    }
    .page-title{font-weight:800;color:var(--navy);margin-bottom:2px;font-size:21px}
    .page-subtitle{font-size:13px;color:var(--slate-500)}

    .wf-card{border:1px solid #ecf0f5;border-radius:14px;overflow:hidden;position:relative}
    .wf-card::before{
        content:"";position:absolute;top:0;left:0;right:0;height:4px;z-index:1;
        background:linear-gradient(90deg,#22b06b,#178a4c);
    }

    table.wf-table thead th{
        background:linear-gradient(180deg,#f8faff,var(--slate-100));color:var(--slate-500);
        font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;
        padding:13px 14px;border:none;
    }
    table.wf-table tbody tr{border-bottom:1px solid #f0f2f6;transition:background .12s ease;border-left:3px solid #178a4c}
    table.wf-table tbody tr:hover{background:#fafbfd}
    table.wf-table tbody td{padding:12px 14px;vertical-align:middle}

    .soft-badge{
        display:inline-block;font-size:11px;font-weight:700;
        padding:4px 11px;border-radius:999px;white-space:nowrap;
        background:var(--accent-100);color:var(--accent);
    }

    .btn-wf-voir{
        display:inline-flex;align-items:center;justify-content:center;
        width:32px;height:32px;border-radius:8px;
        border:1.5px solid var(--slate-300);background:#fff;color:#1d4ed8;
        transition:background .15s ease, border-color .15s ease;
        flex-shrink:0;
    }
    .btn-wf-voir:hover{background:#eff6ff;border-color:#3b6ff0;color:#1d4ed8}

    .btn-wf-validate-resp{
        display:inline-flex;align-items:center;gap:5px;
        padding:6px 13px;border-radius:8px;border:none;
        background:linear-gradient(135deg,#22b06b,#178a4c);
        color:#fff;font-size:12px;font-weight:700;
        box-shadow:0 4px 10px -3px rgba(23,138,76,0.4);
        transition:filter .15s ease, transform .15s ease;
    }
    .btn-wf-validate-resp:hover{filter:brightness(1.06);transform:translateY(-1px);color:#fff}

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
        font-size:12px;padding:5px 10px;width:130px;
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
            <i class="bi bi-shield-check"></i>
        </div>
        <div>
            <div class="page-title">Validation</div>
            <div class="page-subtitle">Documents validés par le département, en attente de votre validation finale</div>
        </div>
    </div>
</div>

<div class="card wf-card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover wf-table mb-0" style="font-size:13px">
            <thead>
                <tr>
                    <th class="ps-3">Document</th>
                    <th>Créé par</th>
                    <th>Département</th>
                    <th>Statut</th>
                    <th>Deadline</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($steps as $step)
                @php $doc = $step->document; @endphp
                <tr>
                    <td class="ps-3">
                        <a href="{{ route('documents.show', $doc) }}"
                           class="text-decoration-none fw-medium text-dark">
                            {{ Str::limit($doc->title ?? '—', 35) }}
                        </a>
                    </td>
                    <td class="text-muted">{{ $doc->creator->name ?? '—' }}</td>
                    <td class="text-muted">{{ $doc->department ?? '—' }}</td>
                    <td>
                        <span class="soft-badge">En attente de validation</span>
                    </td>
                    <td class="text-muted">
                        {{ $step->deadline ? $step->deadline->format('d/m/Y') : '—' }}
                    </td>
                    <td>
                        <div class="d-flex gap-2 align-items-center">
                            <a href="{{ route('documents.show', $doc) }}" class="btn-wf-voir" title="Voir le document" target="_blank">
                                <i class="bi bi-eye"></i>
                            </a>
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
                            <p class="title">Aucun document en attente</p>
                            <p class="subtitle">Les documents validés par le département apparaîtront ici</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($steps->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $steps->links() }}
    </div>
    @endif
</div>

@endsection