@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">📄 Documents</h1>
        <a href="{{ route('documents.create') }}"
           class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-600">
            + Nouveau document
        </a>
    </div>

    {{-- Message succès --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="p-4 text-left">Référence</th>
                    <th class="p-4 text-left">Titre</th>
                    <th class="p-4 text-left">Catégorie</th>
                    <th class="p-4 text-left">Statut</th>
                    <th class="p-4 text-left">Priorité</th>
                    <th class="p-4 text-left">Créé par</th>
                    <th class="p-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($documents as $document)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-mono text-xs">{{ $document->reference }}</td>
                    <td class="p-4 font-medium">{{ $document->title }}</td>
                    <td class="p-4 text-gray-500">{{ $document->category->name ?? '—' }}</td>
                    <td class="p-4">
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
                    </td>
                    <td class="p-4 text-gray-500">{{ $document->priority }}</td>
                    <td class="p-4 text-gray-500">{{ $document->creator->name ?? '—' }}</td>
                    <td class="p-4">
                        <a href="{{ route('documents.show', $document) }}"
                           class="text-blue-600 hover:underline mr-2">Voir</a>
                        <a href="{{ route('documents.edit', $document) }}"
                           class="text-yellow-600 hover:underline">Modifier</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-400">
                        Aucun document trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $documents->links() }}
    </div>

</div>
@endsection