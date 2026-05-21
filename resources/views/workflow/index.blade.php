 @extends('layouts.app')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#0d2b6b">
            <i class="bi bi-arrow-repeat me-2"></i>Workflow
        </h4>
        <small class="text-muted">Documents en attente de validation</small>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0" style="font-size:13px">
            <thead style="background:#f8faff">
                <tr>
                    <th class="border-0 ps-3 py-3" style="color:#0d2b6b">Document</th>
                    <th class="border-0 py-3" style="color:#0d2b6b">Étape</th>
                    <th class="border-0 py-3" style="color:#0d2b6b">Assigné à</th>
                    <th class="border-0 py-3" style="color:#0d2b6b">Statut</th>
                    <th class="border-0 py-3" style="color:#0d2b6b">Deadline</th>
                    <th class="border-0 py-3" style="color:#0d2b6b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($steps as $step)
                <tr>
                    <td class="ps-3 py-3">
                        <a href="{{ route('documents.show', $step->document) }}"
                           class="text-decoration-none fw-medium text-dark">
                            {{ Str::limit($step->document->title ?? '—', 35) }}
                        </a>
                    </td>
                    <td class="py-3">{{ $step->step_name }}</td>
                    <td class="py-3 text-muted">{{ $step->assignedUser->name ?? '—' }}</td>
                    <td class="py-3">
                        @php
                            $sc = ['pending'=>'secondary','in_progress'=>'warning','approved'=>'success','rejected'=>'danger'];
                            $sl = ['pending'=>'En attente','in_progress'=>'En cours','approved'=>'Approuvé','rejected'=>'Rejeté'];
                        @endphp
                        <span class="badge bg-{{ $sc[$step->status] ?? 'secondary' }}" style="font-size:11px">
                            {{ $sl[$step->status] ?? $step->status }}
                        </span>
                    </td>
                    <td class="py-3 text-muted">
                        {{ $step->deadline ? $step->deadline->format('d/m/Y') : '—' }}
                    </td>
                    <td class="py-3">
                        @if($step->status === 'in_progress' && auth()->id() === $step->assigned_to)
                        <div class="d-flex gap-1">
                            <form action="{{ route('workflow.approve', $step) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-check"></i> Approuver
                                </button>
                            </form>
                            <form action="{{ route('workflow.reject', $step) }}" method="POST" class="d-flex gap-1">
                                @csrf
                                <input type="text" name="comment" placeholder="Raison..."
                                    class="form-control form-control-sm" style="width:120px">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-x"></i> Rejeter
                                </button>
                            </form>
                        </div>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size:32px"></i>
                        <p class="mt-2">Aucun workflow en cours</p>
                    </td>
                </tr>
                @endforelse
</tbody>
        </table>
    </div>
</div>

@endsection