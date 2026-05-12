<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DSI DocManager</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    {{-- Navbar --}}
    <nav class="bg-blue-800 text-white px-6 py-4 flex justify-between">
        <span class="font-bold text-lg">DSI DocManager</span>
        <div>
            <span class="mr-4">{{ auth()->user()->name }}</span>
            <a href="/logout" class="bg-red-500 px-3 py-1 rounded">Déconnexion</a>
        </div>
    </nav>

    <div class="flex">
        {{-- Sidebar --}}
        <aside class="w-64 bg-white h-screen shadow p-4">
            <ul class="space-y-2">
                <li><a href="/dashboard" class="block p-2 rounded hover:bg-blue-100">🏠 Dashboard</a></li>
                <li><a href="/documents" class="block p-2 rounded hover:bg-blue-100">📄 Documents</a></li>
                @if(auth()->user()->isAdmin())
                <li><a href="/admin/users" class="block p-2 rounded hover:bg-blue-100">👥 Utilisateurs</a></li>
                <li><a href="/admin/audit" class="block p-2 rounded hover:bg-blue-100">🔍 Audit</a></li>
                @endif
            </ul>
        </aside>

        {{-- Contenu --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>