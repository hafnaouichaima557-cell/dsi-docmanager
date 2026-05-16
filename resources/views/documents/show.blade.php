[16/05/2026 10:54] Chocho🫀: @extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">📄 {{ $document->title }}</h1>
        <a href="{{ route('documents.index') }}"
           class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
            ← Retour
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-3 gap-6">

        {{-- Infos principales --}}
        <div class="col-span-2 space-y-6">

            {{-- Détails --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-bold text-gray-700 mb-4">Informations</h2>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500">Référence</dt>
                        <dd class="font-mono font-medium">{{ $document->reference }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Catégorie</dt>
                        <dd>{{ $document->category->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Créé par</dt>
                        <dd>{{ $document->creator->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Priorité</dt>
                        <dd>{{ ucfirst($document->priority) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Créé le</dt>
                        <dd>{{ $document->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Statut</dt>
                        <dd>
                            @php
                                $colors = [
                                    'draft'        => 'gray',
                                    'submitted'    => 'blue',
                                    'under_review' => 'yellow',
                                    'approved'     => 'green',
                                    'published'    => 'purple',
                                    'disabled'     => 'red',
                                ];
                                $color = $colors[$document->status] ?? 'gray';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs bg-{{ $color }}-100 text-{{ $color }}-700">
                                {{ $document->status }}
                            </span>
                        </dd>
                    </div>
                </dl>
                @if($document->description)
                    <div class="mt-4 pt-4 border-t">
                        <dt class="text-gray-500 text-sm mb-1">Description</dt>
                        <dd class="text-sm">{{ $document->description }}</dd>
                    </div>
                @endif
            </div>
[16/05/2026 10:54] Chocho🫀: {{-- Workflow Steps --}}
            @if($document->workflowSteps->count() > 0)
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-bold text-gray-700 mb-4">🔄 Workflow</h2>
                <div class="space-y-3">
                    @foreach($document->workflowSteps as $step)
                    <div class="flex items-center gap-4 p-3 rounded-lg border
                        {{ $step->status === 'approved' ? 'border-green-200 bg-green-50' :
                           ($step->status === 'rejected' ? 'border-red-200 bg-red-50' :
                           ($step->status === 'in_progress' ? 'border-blue-200 bg-blue-50' : 'border-gray-200')) }}">
                        <div class="text-2xl">
                            {{ $step->status === 'approved' ? '✅' :
                               ($step->status === 'rejected' ? '❌' :
                               ($step->status === 'in_progress' ? '🔄' : '⏳')) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-sm">{{ $step->step_name }}</p>
                            <p class="text-xs text-gray-500">{{ $step->assignedUser->name ?? '—' }}</p>
                            @if($step->comment)
                                <p class="text-xs text-gray-600 mt-1">{{ $step->comment }}</p>
                            @endif
                        </div>
                        {{-- Actions workflow --}}
                        @if($step->status === 'in_progress' && auth()->id() === $step->assigned_to)
                        <div class="flex gap-2">
                            <form action="{{ route('workflow.approve', $step) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600">
                                    Approuver
                                </button>
                            </form>
                            <form action="{{ route('workflow.reject', $step) }}" method="POST">
                                @csrf
                                <input type="text" name="comment" placeholder="Raison..."
                                    class="border rounded px-2 py-1 text-xs w-32">
                                <button type="submit"
                                    class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">
                                    Rejeter
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Actions sidebar --}}
        <div class="space-y-4">

            {{-- Actions --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-bold text-gray-700 mb-4">Actions</h2>
                <div class="space-y-2">

                    @can('update', $document)
                    <a href="{{ route('documents.edit', $document) }}"
                       class="block w-full text-center bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        ✏️ Modifier
                    </a>
                    @endcan

                    @if($document->canBeSubmitted())
                    <form action="{{ route('workflow.submit', $document) }}" method="POST">
                        @csrf
                        <input type="hidden" name="steps[0][name]" value="Validation">
                        <input type="hidden" name="steps[0][user_id]" value="{{ auth()->id() }}">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            📤 Soumettre
                        </button>
                    </form>
                    @endif
[16/05/2026 10:54] Chocho🫀: @can('publish', $document)
                    <form action="{{ route('documents.publish', $document) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="w-full bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                            🌐 Publier
                        </button>
                    </form>
                    @endcan

                    @can('disable', $document)
                    <form action="{{ route('documents.disable', $document) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                            onclick="return confirm('Désactiver ce document ?')">
                            🚫 Désactiver
                        </button>
                    </form>
                    @endcan

                </div>
            </div>

            {{-- Versions --}}
            @if($document->versions->count() > 0)
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-bold text-gray-700 mb-4">📎 Versions</h2>
                <div class="space-y-2">
                    @foreach($document->versions as $version)
                    <div class="flex justify-between items-center text-sm p-2 rounded border">
                        <span>v{{ $version->version_number }} — {{ $version->file_name }}</span>
                        <span class="text-gray-400 text-xs">{{ $version->file_size_formatted }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection