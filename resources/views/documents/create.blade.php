@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">📄 Nouveau Document</h1>

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
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Titre --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Titre *</label>
                <input type="text" name="title" value="{{ old('title') }}"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                    placeholder="Titre du document">
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Description</label>
                <textarea name="description" rows="3"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                    placeholder="Description du document">{{ old('description') }}</textarea>
            </div>

            {{-- Catégorie --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Catégorie *</label>
                <select name="category_id"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                    <option value="">-- Choisir une catégorie --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Basse</option>
                    <option value="normal" {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>Normale</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Haute</option>
                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                </select>
            </div>

            {{-- Fichier --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-1">Fichier *</label>
                <input type="file" name="file"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                <p class="text-xs text-gray-400 mt-1">PDF, DOCX, XLSX — max 10MB</p>
            </div>

            {{-- Boutons --}}
            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Créer le document
                </button>
                <a href="{{ route('documents.index') }}"
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                    Annuler
                </a>
            </div>

        </form>
    </div>

</div>
@endsection