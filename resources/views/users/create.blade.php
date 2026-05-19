@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">👤 Nouvel Utilisateur</h1>

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
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            {{-- Nom --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Nom *</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                    placeholder="Nom complet">
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                    placeholder="email@dsi.local">
            </div>

            {{-- Mot de passe --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Mot de passe *</label>
                <input type="password" name="password"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                    placeholder="Minimum 8 caractères">
            </div>

            {{-- Département --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Département</label>
                <input type="text" name="department" value="{{ old('department') }}"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                    placeholder="DSI, RH, Finance...">
            </div>

            {{-- Rôle — diag 6 --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-1">Rôle *</label>
                <select name="role"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                    <option value="">-- Choisir un rôle --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}"
                            {{ old('role') == $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Boutons --}}
            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-600">
                    ✅ Créer l'utilisateur
                </button>
                <a href="{{ route('users.index') }}"
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                    Annuler
                </a>
            </div>

        </form>
    </div>

</div>
@endsection