@extends('layouts.app')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">👥 Utilisateurs</h1>
        <a href="{{ route('users.create') }}"
           class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-600">
            + Nouvel utilisateur
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="p-4 text-left">Nom</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">Département</th>
                    <th class="p-4 text-left">Rôle</th>
                    <th class="p-4 text-left">Statut</th>
                    <th class="p-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-medium">{{ $user->name }}</td>
                    <td class="p-4 text-gray-500">{{ $user->email }}</td>
                    <td class="p-4 text-gray-500">{{ $user->department ?? '—' }}</td>
                    <td class="p-4">
                        @foreach($user->roles as $role)
                            <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </td>
                    <td class="p-4">
                        @if($user->is_active)
                            <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Actif</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">Désactivé</span>
                        @endif
                    </td>
                    <td class="p-4 flex gap-2">
                        <a href="{{ route('users.show', $user) }}"
                           class="text-blue-600 hover:underline text-xs">Voir</a>

                        {{-- Modifier rôle — diag 8 --}}
                        <form action="{{ route('users.role', $user) }}" method="POST" class="flex gap-1">
                            @csrf
                            @method('PATCH')
                            <select name="role" class="border rounded px-1 py-0.5 text-xs">
                                <option value="utilisateur" {{ $user->hasRole('utilisateur') ? 'selected' : '' }}>Utilisateur</option>
                                <option value="responsable" {{ $user->hasRole('responsable') ? 'selected' : '' }}>Responsable</option>
                                <option value="administrateur" {{ $user->hasRole('administrateur') ? 'selected' : '' }}>Admin</option>
                            </select>
                            <button type="submit" class="text-green-600 hover:underline text-xs">Changer</button>
                        </form>
{{-- Désactiver — diag 7 --}}
                        @if($user->is_active && $user->id !== auth()->id())
                        <form action="{{ route('users.disable', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="text-red-600 hover:underline text-xs"
                                onclick="return confirm('Désactiver cet utilisateur ?')">
                                Désactiver
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-400">
                        Aucun utilisateur trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

</div>
@endsection