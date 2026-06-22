 @extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">✏️ Modifier le Document</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('documents.update', $document) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Titre --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Titre *</label>
                <input type="text" name="title" value="{{ old('title', $document->title) }}"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Description</label>
                <textarea name="description" rows="3"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">{{ old('description', $document->description) }}</textarea>
            </div>

            {{-- Catégorie --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Catégorie *</label>
                <select name="category_id"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                    <option value="">-- Choisir une catégorie --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $document->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Priorité --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Priorité</label>
                <select name="priority"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                    <option value="low" {{ old('priority', $document->priority) == 'low' ? 'selected' : '' }}>Basse</option>
                    <option value="normal" {{ old('priority', $document->priority) == 'normal' ? 'selected' : '' }}>Normale</option>
                    <option value="high" {{ old('priority', $document->priority) == 'high' ? 'selected' : '' }}>Haute</option>
                    <option value="urgent" {{ old('priority', $document->priority) == 'urgent' ? 'selected' : '' }}>Urgente</option>
                </select>
            </div>

            {{-- Nouveau fichier --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">
                    Nouveau fichier
                    <span class="text-gray-400 font-normal text-xs">(laisser vide pour garder l'ancien)</span>
                </label>
                <input type="file" name="file"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            {{-- Notes de modification --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-1">Notes de modification</label>
                <textarea name="change_notes" rows="2"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                    placeholder="Décrivez les modifications...">{{ old('change_notes') }}</textarea>
            </div>
{{-- Boutons --}}
            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-600">
                    💾 Enregistrer
                </button>
                <a href="{{ route('documents.show', $document) }}"
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                    Annuler
                </a>
            </div>

        </form>
    </div>

</div>
@endsection