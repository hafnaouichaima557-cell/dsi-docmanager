<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Vérification du code - Doc Flow</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #0d2b6b, #1d4ed8);
            color: white;
        }

        .auth-card {
            width: 100%;
            max-width: 430px;
            padding: 40px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo h1 {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .logo p {
            font-size: 14px;
            opacity: 0.8;
            margin: 0;
        }

        .form-control {
            height: 50px;
            border-radius: 12px;
            border: none;
            padding: 0 15px;
            text-align: center;
            font-size: 22px;
            letter-spacing: 8px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.25);
        }

        .btn-primary {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 12px;
            background: white;
            color: #0d2b6b;
            font-weight: 700;
        }

        .btn-primary:hover {
            background: #f1f5f9;
            color: #0d2b6b;
        }

        .email-text {
            word-break: break-word;
            font-weight: 600;
        }

        .back-link {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 13px;
        }

        .back-link:hover {
            color: white;
            text-decoration: underline;
        }

        .alert {
            font-size: 13px;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<div class="auth-card">

    <div class="logo">
        <h1>Doc Flow</h1>
        <p>Vérification du code</p>
    </div>

    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="text-center mb-4">
        <p class="mb-2">
            Un code à 6 chiffres a été envoyé à :
        </p>

        <div class="email-text">
            {{ $email }}
        </div>
    </div>

    <form method="POST" action="{{ route('password.verify.store') }}">
        @csrf

        <div class="mb-4">
            <label for="code" class="form-label">
                Code de vérification
            </label>

            <input
                type="text"
                name="code"
                id="code"
                class="form-control"
                maxlength="6"
                minlength="6"
                pattern="[0-9]{6}"
                inputmode="numeric"
                autocomplete="one-time-code"
                placeholder="000000"
                required
                autofocus
            >
        </div>

        <button type="submit" class="btn btn-primary">
            Vérifier le code
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('password.request') }}" class="back-link">
            ← Demander un nouveau code
        </a>
    </div>

</div>

</body>
</html>