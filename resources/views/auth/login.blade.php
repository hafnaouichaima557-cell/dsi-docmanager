<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Connexion - Doc Flow</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100%;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            overflow: hidden;
            position: relative;

            background:
                radial-gradient(circle at 50% 110%,
                    rgba(0, 194, 255, 0.75) 0%,
                    rgba(0, 139, 220, 0.45) 25%,
                    rgba(16, 58, 137, 0.75) 55%,
                    rgba(5, 20, 52, 1) 100%);
        }

        /* =========================
           ÉTOILES
        ========================= */

        .stars {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }

        .star {
            position: absolute;
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 8px rgba(255,255,255,0.6);
            animation: twinkle 3s infinite ease-in-out;
        }

        .star:nth-child(3n) {
            animation-delay: 0.6s;
        }

        .star:nth-child(3n+1) {
            animation-delay: 1.4s;
        }

        .star:nth-child(4n) {
            width: 2px;
            height: 2px;
        }

        .star:nth-child(5n) {
            width: 4px;
            height: 4px;
            animation-delay: 2.1s;
        }

        .star:nth-child(1) {
            top: 4%;
            left: 2%;
        }

        .star:nth-child(2) {
            top: 2%;
            left: 23%;
        }

        .star:nth-child(3) {
            top: 3%;
            left: 46%;
        }

        .star:nth-child(4) {
            top: 7%;
            left: 74%;
        }

        .star:nth-child(5) {
            top: 17%;
            left: 85%;
        }

        .star:nth-child(6) {
            top: 26%;
            left: 70%;
        }

        .star:nth-child(7) {
            top: 32%;
            left: 24%;
        }

        .star:nth-child(8) {
            top: 37%;
            left: 8%;
        }

        .star:nth-child(9) {
            top: 50%;
            left: 93%;
        }

        .star:nth-child(10) {
            top: 63%;
            left: 4%;
        }

        .star:nth-child(11) {
            top: 72%;
            left: 89%;
        }

        .star:nth-child(12) {
            top: 82%;
            left: 13%;
        }

        .star:nth-child(13) {
            top: 88%;
            left: 75%;
        }

        .star:nth-child(14) {
            top: 21%;
            left: 96%;
        }

        .star:nth-child(15) {
            top: 45%;
            left: 36%;
        }

        .star:nth-child(16) {
            top: 12%;
            left: 57%;
        }

        .star:nth-child(17) {
            top: 6%;
            left: 90%;
        }

        .star:nth-child(18) {
            top: 15%;
            left: 38%;
        }

        .star:nth-child(19) {
            top: 24%;
            left: 10%;
        }

        .star:nth-child(20) {
            top: 29%;
            left: 63%;
        }

        .star:nth-child(21) {
            top: 34%;
            left: 90%;
        }

        .star:nth-child(22) {
            top: 40%;
            left: 48%;
        }

        .star:nth-child(23) {
            top: 48%;
            left: 20%;
        }

        .star:nth-child(24) {
            top: 55%;
            left: 78%;
        }

        .star:nth-child(25) {
            top: 58%;
            left: 55%;
        }

        .star:nth-child(26) {
            top: 66%;
            left: 30%;
        }

        .star:nth-child(27) {
            top: 70%;
            left: 62%;
        }

        .star:nth-child(28) {
            top: 76%;
            left: 5%;
        }

        .star:nth-child(29) {
            top: 80%;
            left: 44%;
        }

        .star:nth-child(30) {
            top: 85%;
            left: 92%;
        }

        .star:nth-child(31) {
            top: 92%;
            left: 20%;
        }

        .star:nth-child(32) {
            top: 95%;
            left: 60%;
        }

        .star:nth-child(33) {
            top: 8%;
            left: 15%;
        }

        .star:nth-child(34) {
            top: 18%;
            left: 80%;
        }

        .star:nth-child(35) {
            top: 25%;
            left: 55%;
        }

        .star:nth-child(36) {
            top: 30%;
            left: 3%;
        }

        .star:nth-child(37) {
            top: 38%;
            left: 33%;
        }

        .star:nth-child(38) {
            top: 44%;
            left: 68%;
        }

        .star:nth-child(39) {
            top: 52%;
            left: 12%;
        }

        .star:nth-child(40) {
            top: 60%;
            left: 40%;
        }

        .star:nth-child(41) {
            top: 62%;
            left: 85%;
        }

        .star:nth-child(42) {
            top: 68%;
            left: 18%;
        }

        .star:nth-child(43) {
            top: 74%;
            left: 95%;
        }

        .star:nth-child(44) {
            top: 78%;
            left: 52%;
        }

        .star:nth-child(45) {
            top: 84%;
            left: 30%;
        }

        .star:nth-child(46) {
            top: 90%;
            left: 8%;
        }

        .star:nth-child(47) {
            top: 93%;
            left: 42%;
        }

        .star:nth-child(48) {
            top: 96%;
            left: 78%;
        }

        @keyframes twinkle {
            0%, 100% {
                opacity: 0.35;
                transform: scale(0.8);
            }

            50% {
                opacity: 1;
                transform: scale(1.3);
            }
        }

        /* =========================
           ICÔNES DOCUMENTAIRES
        ========================= */

        .floating-icon {
            position: fixed;
            z-index: 1;
            color: rgba(129, 192, 255, 0.15);
            font-size: 42px;
            pointer-events: none;
            animation: floating 6s ease-in-out infinite;
        }

        .icon-1 {
            top: 10%;
            left: 2%;
        }

        .icon-2 {
            top: 22%;
            right: 6%;
            font-size: 40px;
            animation-delay: 1s;
        }

        .icon-3 {
            top: 42%;
            left: 4%;
            font-size: 44px;
            animation-delay: 2s;
        }

        .icon-4 {
            bottom: 13%;
            left: 11%;
            font-size: 44px;
            animation-delay: 1.5s;
        }

        .icon-5 {
            bottom: 7%;
            right: 9%;
            font-size: 42px;
            animation-delay: 3s;
        }

        .icon-6 {
            bottom: 16%;
            right: 2%;
            font-size: 38px;
            animation-delay: 2.5s;
        }

        .icon-7 {
            bottom: 6%;
            left: 46%;
            font-size: 42px;
            animation-delay: 1s;
        }

        .icon-8 {
            top: 4%;
            left: 38%;
            font-size: 36px;
            animation-delay: 0.5s;
        }

        .icon-9 {
            top: 60%;
            left: 2%;
            font-size: 40px;
            animation-delay: 2.2s;
        }

        .icon-10 {
            top: 68%;
            right: 4%;
            font-size: 38px;
            animation-delay: 1.8s;
        }

        .icon-11 {
            bottom: 30%;
            left: 20%;
            font-size: 34px;
            animation-delay: 2.8s;
        }

        .icon-12 {
            bottom: 28%;
            right: 20%;
            font-size: 36px;
            animation-delay: 0.8s;
        }

        @keyframes floating {
            0%, 100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-12px);
            }
        }

        /* =========================
           CONTAINER
        ========================= */

        .login-wrapper {
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 5;
            padding: 20px;
        }

        /* =========================
           CARTE
        ========================= */

        .login-card {
            width: 420px;
            max-height: 92vh;
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;

            padding: 36px 36px 24px;

            border-radius: 24px;

            background:
                linear-gradient(
                    180deg,
                    rgba(29, 49, 82, 0.96) 0%,
                    rgba(39, 83, 151, 0.92) 48%,
                    rgba(12, 164, 221, 0.90) 100%
                );

            border: 1px solid rgba(255, 255, 255, 0.24);

            box-shadow:
                0 25px 70px rgba(0, 0, 0, 0.40),
                inset 0 1px 0 rgba(255, 255, 255, 0.12);

            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);

            position: relative;
        }

        .login-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: rgba(255,255,255,0.35);
        }

        .login-card::-webkit-scrollbar {
            display: none;
        }

        /* =========================
           LOGO
        ========================= */

        .logo-box {
            width: 72px;
            height: 68px;

            margin: 0 auto 16px;

            border-radius: 16px;

            background:
                linear-gradient(
                    135deg,
                    #1557ff,
                    #1765ee,
                    #2d62db
                );

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;
            font-size: 14px;
            font-weight: 600;

            box-shadow:
                0 12px 30px rgba(0, 57, 190, 0.35),
                inset 0 1px 1px rgba(255,255,255,0.2);
        }

        .logo-text {
            letter-spacing: -0.5px;
        }

        /* =========================
           TITRE
        ========================= */

        .login-title {
            color: white;
            text-align: center;
            font-size: 30px;
            font-weight: 700;
            margin: 0 0 6px;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            color: rgba(255,255,255,0.62);
            text-align: center;
            font-size: 14px;
            margin-bottom: 30px;
        }

        /* =========================
           LABEL
        ========================= */

        .form-label-custom {
            display: block;
            color: rgba(255,255,255,0.82);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 7px;
        }

        /* =========================
           INPUT
        ========================= */

        .input-group-custom {
            display: flex;
            width: 100%;
            height: 50px;
            margin-bottom: 18px;
        }

        .input-icon {
            width: 44px;
            min-width: 44px;

            display: flex;
            align-items: center;
            justify-content: center;

            color: rgba(255,255,255,0.72);

            background: rgba(255,255,255,0.08);

            border: 1px solid rgba(255,255,255,0.28);
            border-right: none;

            border-radius: 13px 0 0 13px;

            font-size: 18px;
        }

        .input-custom {
            flex: 1;

            height: 50px;

            border: 1px solid rgba(255,255,255,0.8);
            border-radius: 0 13px 13px 0;

            background: rgba(245, 248, 255, 0.94);

            color: #1d2738;

            font-size: 14px;

            padding: 0 16px;

            outline: none;

            transition: 0.25s ease;
        }

        .input-custom:focus {
            background: white;

            border-color: white;

            box-shadow:
                0 0 0 4px rgba(255,255,255,0.10);
        }

        .input-custom::placeholder {
            color: #8b96a8;
        }

        /* =========================
           REMEMBER + FORGOT
        ========================= */

        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-top: 2px;
            margin-bottom: 24px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;

            color: rgba(255,255,255,0.78);

            font-size: 13px;

            cursor: pointer;
        }

        .remember-checkbox {
            width: 17px;
            height: 17px;

            appearance: none;
            -webkit-appearance: none;

            border: 2px solid rgba(255,255,255,0.42);

            border-radius: 4px;

            background: rgba(255,255,255,0.10);

            cursor: pointer;

            position: relative;
        }

        .remember-checkbox:checked {
            background: white;
            border-color: white;
        }

        .remember-checkbox:checked::after {
            content: "✓";

            position: absolute;

            left: 2px;
            top: -3px;

            color: #1e55b8;

            font-size: 13px;
            font-weight: bold;
        }

        .forgot-link {
            color: rgba(255,255,255,0.88);

            text-decoration: none;

            font-size: 13px;
            font-weight: 500;

            transition: 0.2s;
        }

        .forgot-link:hover {
            color: white;
            text-decoration: underline;
        }

        /* =========================
           BOUTON
        ========================= */

        .login-button {
            width: 100%;
            height: 50px;

            border: none;
            border-radius: 13px;

            background: rgba(255,255,255,0.96);

            color: #18386f;

            font-size: 15px;
            font-weight: 600;

            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;

            cursor: pointer;

            box-shadow:
                0 8px 25px rgba(0,0,0,0.13);

            transition: all 0.25s ease;
        }

        .login-button i {
            font-size: 15px;
        }

        .login-button:hover {
            background: white;

            transform: translateY(-2px);

            box-shadow:
                0 12px 30px rgba(0,0,0,0.20);
        }

        .login-button:active {
            transform: translateY(0);
        }

        /* =========================
           MESSAGES
        ========================= */

        .alert-custom {
            border-radius: 10px;
            border: none;

            font-size: 13px;

            margin-bottom: 16px;
        }

        /* =========================
           FOOTER
        ========================= */

        .login-footer {
            text-align: center;

            color: rgba(255,255,255,0.42);

            font-size: 12px;

            margin-top: 22px;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 700px) {

            body {
                overflow-y: auto;
            }

            .login-wrapper {
                padding: 15px;
            }

            .login-card {
                width: 100%;
                max-height: none;

                padding: 32px 22px 24px;

                border-radius: 20px;
            }

            .logo-box {
                width: 62px;
                height: 58px;
                font-size: 12px;
            }

            .login-title {
                font-size: 26px;
            }

            .login-subtitle {
                font-size: 13px;
                margin-bottom: 24px;
            }

            .input-group-custom,
            .input-custom {
                height: 46px;
            }

            .input-icon {
                height: 46px;
                min-width: 42px;
                width: 42px;
            }

            .options-row {
                align-items: flex-start;
                gap: 10px;
            }

            .forgot-link {
                font-size: 12px;
            }

            .floating-icon {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- =========================
         ÉTOILES
    ========================= -->

    <div class="stars">
        @for ($i = 0; $i < 48; $i++)
            <span class="star"></span>
        @endfor
    </div>


    <!-- =========================
         ICÔNES FLOTTANTES
    ========================= -->

    <i class="bi bi-file-earmark-text floating-icon icon-1"></i>

    <i class="bi bi-file-earmark-pdf floating-icon icon-2"></i>

    <i class="bi bi-shield-check floating-icon icon-3"></i>

    <i class="bi bi-folder floating-icon icon-4"></i>

    <i class="bi bi-file-earmark-check floating-icon icon-5"></i>

    <i class="bi bi-file-earmark-word floating-icon icon-6"></i>

    <i class="bi bi-files floating-icon icon-7"></i>

    <i class="bi bi-file-earmark-lock floating-icon icon-8"></i>

    <i class="bi bi-cloud-check floating-icon icon-9"></i>

    <i class="bi bi-printer floating-icon icon-10"></i>

    <i class="bi bi-file-earmark-spreadsheet floating-icon icon-11"></i>

    <i class="bi bi-archive floating-icon icon-12"></i>


    <!-- =========================
         LOGIN
    ========================= -->

    <div class="login-wrapper">

        <div class="login-card">

            <!-- LOGO -->
            <div class="logo-box">
                <span class="logo-text">iCOSNET</span>
            </div>


            <!-- TITRE -->
            <h1 class="login-title">
                Doc Flow
            </h1>

            <div class="login-subtitle">
                Direction des Systèmes d'Information — icosnet
            </div>


            <!-- ERREURS -->
            @if ($errors->any())
                <div class="alert alert-danger alert-custom">
                    <i class="bi bi-exclamation-circle me-2"></i>

                    {{ $errors->first() }}
                </div>
            @endif


            <!-- MESSAGE SUCCESS -->
            @if (session('status'))
                <div class="alert alert-success alert-custom">
                    <i class="bi bi-check-circle me-2"></i>

                    {{ session('status') }}
                </div>
            @endif


            <!-- FORMULAIRE -->
            <form method="POST" action="{{ route('login') }}">

                @csrf


                <!-- EMAIL -->
                <label class="form-label-custom">
                    Adresse email
                </label>

                <div class="input-group-custom">

                    <div class="input-icon">
                        <i class="bi bi-envelope"></i>
                    </div>

                    <input
                        type="email"
                        name="email"
                        class="input-custom"
                        value="{{ old('email') }}"
                        placeholder="Adresse email"
                        required
                        autofocus
                        autocomplete="email"
                    >

                </div>


                <!-- PASSWORD -->
                <label class="form-label-custom">
                    Mot de passe
                </label>

                <div class="input-group-custom">

                    <div class="input-icon">
                        <i class="bi bi-lock"></i>
                    </div>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="input-custom"
                        placeholder="Mot de passe"
                        required
                        autocomplete="current-password"
                    >

                </div>


                <!-- OPTIONS -->
                <div class="options-row">

                    <label class="remember-label">

                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            class="remember-checkbox"
                            {{ old('remember') ? 'checked' : '' }}
                        >

                        <span>
                            Se souvenir de moi
                        </span>

                    </label>


                    <!-- MOT DE PASSE OUBLIÉ -->
                    <a
                        href="{{ route('password.request') }}"
                        class="forgot-link"
                    >
                        Mot de passe oublié ?
                    </a>

                </div>


                <!-- BOUTON -->
                <button
                    type="submit"
                    class="login-button"
                >

                    <i class="bi bi-box-arrow-in-right"></i>

                    <span>
                        Se connecter
                    </span>

                </button>

            </form>


            <!-- FOOTER -->
            <div class="login-footer">
                © {{ date('Y') }} icosnet — Tous droits réservés
            </div>

        </div>

    </div>


    <!-- =========================
         JS
    ========================= -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const password = document.getElementById('password');

            if (password) {

                password.addEventListener('keydown', function (event) {

                    if (event.key === 'Enter') {
                        this.form.submit();
                    }

                });

            }

        });
    </script>

</body>
</html>