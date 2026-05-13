<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion — DSI DocManager</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-blue-900 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-xl shadow-lg w-96">
        <h1 class="text-2xl font-bold text-center text-blue-800 mb-6">
            DSI DocManager
        </h1>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                Email ou mot de passe incorrect
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Email</label>
                <input type="email" name="email"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                    placeholder="vous@dsi.local">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 mb-1">Mot de passe</label>
                <input type="password" name="password"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>
            <button type="submit"
                class="w-full bg-blue-800 text-white py-2 rounded hover:bg-blue-700">
                Se connecter
            </button>
        </form>
    </div>

</body>
</html>