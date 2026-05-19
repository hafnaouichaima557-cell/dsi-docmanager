<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — DSI DocManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            font-family: 'Segoe UI', sans-serif;
            background: #0a1628;
        }

        /* Animated background */
        .bg-scene {
            position: fixed;
            inset: 0;
            background: linear-gradient(180deg,
                #0a1628 0%,
                #0d2b6b 30%,
                #1a4fa0 60%,
                #00a8e8 100%
            );
            z-index: 0;
        }

        /* Stars */
        .stars {
            position: fixed;
            inset: 0;
            z-index: 1;
        }
        .star {
            position: absolute;
            background: #fff;
            border-radius: 50%;
            animation: twinkle 3s infinite alternate;
        }

        @keyframes twinkle {
            0% { opacity: 0.2; transform: scale(1); }
            100% { opacity: 1; transform: scale(1.3); }
        }

        /* Floating documents */
        .floating-docs {
            position: fixed;
            inset: 0;
            z-index: 2;
            pointer-events: none;
        }
        .doc-icon {
            position: absolute;
            color: rgba(255,255,255,0.08);
            font-size: 48px;
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        /* Glass card */
        .login-card {
            position: relative;
            z-index: 10;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
        }

        .login-title {
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            text-align: center;
            margin-bottom: 8px;
        }

        .login-subtitle {
            font-size: 13px;
            color: rgba(255,255,255,0.6);
            text-align: center;
            margin-bottom: 32px;
        }

        .form-label {
            color: rgba(255,255,255,0.8);
            font-size: 13px;
            margin-bottom: 6px;
        }

        .glass-input {
            background: rgba(255,255,255,0.1) !important;
            border: 1px solid rgba(255,255,255,0.25) !important;
            border-radius: 12px !important;
            color: #fff !important;
            padding: 12px 16px !important;
            font-size: 14px !important;
            transition: all 0.3s;
        }
        .glass-input::placeholder { color: rgba(255,255,255,0.4) !important; }
        .glass-input:focus {
            background: rgba(255,255,255,0.15) !important;
            border-color: rgba(255,255,255,0.5) !important;
            box-shadow: 0 0 0 3px rgba(255,255,255,0.1) !important;
            color: #fff !important;
        }
.input-icon {
            background: rgba(255,255,255,0.1) !important;
            border: 1px solid rgba(255,255,255,0.25) !important;
            border-right: none !important;
            border-radius: 12px 0 0 12px !important;
            color: rgba(255,255,255,0.7) !important;
        }
        .input-icon + .glass-input {
            border-left: none !important;
            border-radius: 0 12px 12px 0 !important;
        }

        .btn-login {
            background: linear-gradient(135deg, #fff 0%, #e8f0fe 100%);
            color: #0d2b6b;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-size: 15px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(255,255,255,0.2);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255,255,255,0.3);
            color: #0d2b6b;
        }

        .form-check-input {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.4);
        }
        .form-check-label { color: rgba(255,255,255,0.7); font-size: 13px; }

        .divider {
            border-color: rgba(255,255,255,0.15);
            margin: 24px 0;
        }

        .footer-text {
            color: rgba(255,255,255,0.4);
            font-size: 12px;
            text-align: center;
            margin-top: 24px;
        }
    </style>
</head>
<body>

{{-- Background --}}
<div class="bg-scene"></div>

{{-- Stars --}}
<div class="stars" id="stars"></div>

{{-- Floating document icons --}}
<div class="floating-docs">
    <i class="bi bi-file-earmark-text doc-icon" style="top:10%;left:5%;animation-delay:0s"></i>
    <i class="bi bi-file-earmark-pdf doc-icon" style="top:20%;right:8%;animation-delay:1s"></i>
    <i class="bi bi-folder doc-icon" style="top:60%;left:3%;animation-delay:2s"></i>
    <i class="bi bi-file-earmark-word doc-icon" style="top:70%;right:5%;animation-delay:0.5s"></i>
    <i class="bi bi-shield-check doc-icon" style="top:40%;left:8%;animation-delay:1.5s"></i>
    <i class="bi bi-file-earmark-check doc-icon" style="bottom:15%;right:10%;animation-delay:2.5s"></i>
    <i class="bi bi-archive doc-icon" style="bottom:10%;left:15%;animation-delay:3s;font-size:64px"></i>
    <i class="bi bi-file-earmark-text doc-icon" style="top:5%;left:40%;animation-delay:0.8s;font-size:36px"></i>
    <i class="bi bi-files doc-icon" style="top:80%;right:20%;animation-delay:1.8s;font-size:56px"></i>
</div>

{{-- Login Card --}}
<div class="login-card">

    {{-- Logo --}}
    <div class="text-center mb-4">
        <img src="{{ asset('images/logo-icosnet.png') }}"
             alt="icosnet" height="40"
             onerror="this.style.display='none'" class="mb-3">
        <h2 class="login-title">DSI DocManager</h2>
        <p class="login-subtitle">Direction des Systèmes d'Information — icosnet</p>
    </div>

    @if($errors->any())
    <div class="alert py-2 mb-3" style="background:rgba(220,38,38,0.2);border:1px solid rgba(220,38,38,0.4);border-radius:10px;color:#fca5a5;font-size:13px">
        <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Adresse email</label>
            <div class="input-group">
                <span class="input-group-text input-icon">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="form-control glass-input"
                    placeholder="vous@icosnet.dz" autofocus>
            </div>
        </div>
<div class="mb-4">
            <label class="form-label">Mot de passe</label>
            <div class="input-group">
                <span class="input-group-text input-icon">
                    <i class="bi bi-lock"></i>
                </span>
                <input type="password" name="password"
                    class="form-control glass-input"
                    placeholder="••••••••">
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Se souvenir de moi</label>
            </div>
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i>
            Se connecter
        </button>

    </form>

    <p class="footer-text">© {{ date('Y') }} icosnet — Tous droits réservés</p>

</div>

<script>
    // Generate stars
    const starsContainer = document.getElementById('stars');
    for (let i = 0; i < 150; i++) {
        const star = document.createElement('div');
        star.classList.add('star');
        const size = Math.random() * 3 + 1;
        star.style.cssText = `
            width: ${size}px;
            height: ${size}px;
            top: ${Math.random() * 60}%;
            left: ${Math.random() * 100}%;
            animation-delay: ${Math.random() * 3}s;
            animation-duration: ${2 + Math.random() * 3}s;
        `;
        starsContainer.appendChild(star);
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>