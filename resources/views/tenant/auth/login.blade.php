@extends('tenant.layouts.app')

@section('title', 'Login')

@section('content')
<div class="auth-bg position-relative">

    <!-- BOTÃO VOLTAR -->
    <a href="{{ url()->previous() }}" class="btn-back">
        <i data-lucide="arrow-left"></i>
        Voltar
    </a>

    <div class="container-fluid">
        <div class="row min-vh-100 d-flex align-items-center justify-content-center">

            <!-- COLUNA CENTRAL -->
            <div class="col-12 d-flex justify-content-center">

                <!-- CARD LOGIN -->
                <div class="login-animate">
                    <div class="glass-card text-white">

                        <h3 class="text-center fw-bold mb-4">Login</h3>

                        <form action="{{ url('login') }}" method="POST">
                            @csrf

                            <!-- EMAIL -->
                            <div class="form-group">
                                <p class=" mb-4">E-mail</p>
                                <input type="email" name="email"
                                    class="form-control glass-input"
                                    placeholder="E-mail"
                                    required>
                            </div>

                            <!-- SENHA -->
                            <div class="form-group">
                                <p class=" mb-4">Senha</p>
                                <input type="password" name="password"
                                    class="form-control glass-input"
                                    placeholder="Senha"
                                    required>
                            </div>

                            <button type="submit" class="btn btn-gradient w-100 mt-3">
                                Entrar
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ESTILOS -->
<style>
    /* Botão voltar */
    .btn-back {
        position: absolute;
        top: 20px;
        left: 20px;
        display: flex;
        align-items: center;
        gap: 6px;
        color: #ffffff;
        text-decoration: none;
        font-weight: 500;
        opacity: 0.85;
        transition: 0.3s;
        z-index: 10;
    }

    .btn-back:hover {
        opacity: 1;
        transform: translateX(-3px);
    }

    /* Animação de entrada */
    .login-animate {
        animation: slideFade 0.8s ease forwards;
    }

    @keyframes slideFade {
        from {
            opacity: 0;
            transform: translateY(25px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Espaçamento dos inputs */
    .form-group {
        margin-bottom: 0.7rem;
    }
</style>

@endsection
