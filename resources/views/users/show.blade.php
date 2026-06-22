@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">← Retour</a>
        <h4 class="mb-0 fw-bold">👤 Profil de {{ $user->name }}</h4>
    </div>

    <div class="row g-4">
        <!-- Infos utilisateur -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:80px;height:80px;font-size:32px;color:white;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                    <p class="text-muted small mb-3">{{ $user->department ?? '—' }}</p>

                    @foreach($user->roles as $role)
                        <span class="badge bg-primary rounded-pill px-3">{{ $role->name }}</span>
                    @endforeach

                    <div class="mt-3">
                        @if($user->is_active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-danger">Désactivé</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents de l'utilisateur -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 pt-3">
                    <h6 class="fw-bold mb-0">📄 Documents ({{ $user->documents->count() }})</h6>
                </div>
                <div class="card-body p-0">
                    @if($user->documents->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <div style="font-size:40px">📄</div>
                            Aucun document créé
                        </div>
                    @else
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Titre</th>
                                    <th>Statut</th>
                                    <th>Priorité</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->documents as $doc)
                                <tr>
                                    <td class="fw-medium">{{ $doc->title }}</td>
                                    <td>
                                        <span class="badge rounded-pill
                                            @if($doc->status == 'published') bg-success
                                            @elseif($doc->status == 'draft') bg-secondary
                                            @elseif($doc->status == 'submitted') bg-info
                                            @elseif($doc->status == 'under_review') bg-warning
                                            @elseif($doc->status == 'approved') bg-primary
                                            @else bg-danger
                                            @endif">
                                            {{ $doc->status }}
                                        </span>
                                    </td>
                                    <td>{{ $doc->priority }}</td>
                                    <td class="text-muted small">{{ $doc->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection