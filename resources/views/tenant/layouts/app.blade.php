<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FaturaJá')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --color-primary: #123859;
            /* Azul escuro */
            --color-accent: #F9941F;
            /* Laranja */
            --color-surface: #F2F2F2;
            --color-border: #E5E5E5;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            overflow: hidden;
            background: #0e2a44;
        }

        /* FUNDO ANIMADO – FATURAJÁ (SÓBRIO) */
        .auth-bg {
            min-height: 100vh;
            background: linear-gradient(-45deg, #0e2a44, #123859, #0e2a44, #123859);
            background-size: 300% 300%;
            animation: gradientMove 26s ease infinite;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Glow abstrato muito discreto */
        .auth-bg::before,
        .auth-bg::after {
            content: '';
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            filter: blur(160px);
            opacity: 0.18;
        }

        .auth-bg::before {
            background: var(--color-accent);
            top: -160px;
            right: -160px;
        }

        .auth-bg::after {
            background: #ffffff;
            bottom: -180px;
            left: -180px;
            opacity: 0.08;
        }

        /* CARD GLASS – DISCRETO E PROFISSIONAL */
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 22px;
            padding: 2.2rem;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.28);
        }

        /* INPUTS – MAIS COMPACTOS */
        .glass-input {
            background: rgba(255, 255, 255, 0.22);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 26px;
            padding: 0.55rem 0.9rem;
            color: #ffffff;
        }

        .glass-input::placeholder {
            color: rgba(255, 255, 255, 0.65);
        }

        .glass-input:focus {
            background: rgba(255, 255, 255, 0.28);
            border-color: var(--color-accent);
            box-shadow: 0 0 0 0.15rem rgba(249, 148, 31, 0.35);
            color: #ffffff;
        }

        /* ESPAÇAMENTO ENTRE CAMPOS */
        .form-group {
            margin-bottom: 0.85rem;
        }

        .form-label {
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
        }

        /* BOTÃO – IDENTIDADE FATURAJÁ */
        .btn-gradient {
            margin-top: 0.9rem;
            background-color: var(--color-accent);
            border: none;
            border-radius: 30px;
            padding: 0.6rem;
            font-weight: 600;
            color: #123859;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(249, 148, 31, 0.45);
            filter: brightness(105%);
        }

        /* TEXTOS */
        .auth-bg h1,
        .auth-bg h3 {
            color: #ffffff;
        }

        .auth-bg p {
            color: rgba(255, 255, 255, 0.75);
        }
    </style>
</head>

<body>
    @yield('content')

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>
