<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mot de passe oublié — Doc Flow</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo-icosnet.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(180deg,
                #0a1628 0%,
                #0d2b6b 35%,
                #1a4fa0 65%,
                #00a8e8 100%
            );
        }

        .card-box {
            width: 100%;
            max-width: 420px;
            padding: 42px 38px;
            border-radius: 24px;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 16px;
        }

        h2 {
            color: white;
            text-align: center;
            font-weight: 700;
        }

        .subtitle {
            color: rgba(255,255,255,0.65);
            text-align: center;
            font-size: 13px;
            margin-bottom: 30px;
        }

        label {
            color: rgba(255,255,255,0.85);
            font-size: 13px;
            margin-bottom: 7px;
        }

        .form-control {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.25);
            color: white;
            border-radius: 12px;
            padding: 12px 15px;
        }

        .form-control:focus {
            background: rgba(255,255,255,0.15);
            color: white;
            border-color: rgba(255,255,255,0.5);
            box-shadow: none;
        }

        .form-control::placeholder {
            color: rgba(255,255,255,0.4);
        }

        .btn-main {
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 13px;
            background: linear-gradient(135deg, #fff, #e8f0fe);
            color: #0d2b6b;
            font-weight: 600;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 22px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 13px;
        }

        .back-link:hover {
            color: white;
        }

        .alert {
            background: rgba(220,38,38,0.2);
            border: 1px solid rgba(220,38,38,0.4);
            color: #fca5a5;
            border-radius: 10px;
            font-size: 13px;
        }
    </style>
</head>

<body>

<div class="card-box">

    <div class="logo">
        <img src="{{ asset('images/logo-icosnet.png') }}" alt="icosnet">
    </div>

    <h2>Mot de passe oublié ?</h2>

    <p class="subtitle">
        Entrez votre adresse email pour recevoir un code de vérification.
    </p>

    @if($errors->any())
        <div class="alert py-2 mb-3">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email">Adresse email</label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control"
                placeholder="vous@icosnet.dz"
                required
                autofocus
            >
        </div>

        <button type="submit" class="btn-main">
            <i class="bi bi-envelope me-2"></i>
            Envoyer le code
        </button>
    </form>

    <a href="{{ route('login') }}" class="back-link">
        <i class="bi bi-arrow-left me-1"></i>
        Retour à la connexion
    </a>

</div>

</body>
</html>