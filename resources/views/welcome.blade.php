<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Electrigonza - Servicios Generales</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, sans-serif;
        }

        .welcome-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 900px;
            width: 90%;
        }

        .left-panel {
            background: #0d6efd;
            /* Tu color primario */
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
        }

        .right-panel {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .btn-lg-custom {
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            width: 100%;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>

    <div class="card welcome-card">

        <div class="row g-0">
            <div class="col-md-6 left-panel">
                <img src="{{ asset('/images/logo.png') }}" alt="Electrigonza" 
                    class = "w-12 h-12 mb-4">
                <div class="mb-4">

                    <h1 class="fw-bold display-5">Electrigonza</h1>
                    <p class="lead opacity-75">Soluciones Eléctricas & Servicios Generales</p>
                </div>
            </div>

            <div class="col-md-6 right-panel text-center">
                <h3 class="fw-bold text-dark mb-2">Bienvenido!</h3>
                <p class="text-muted mb-4">Ingresa para gestionar tus solicitudes</p>

                @if (Route::has('login'))
                    <div class="w-100">
                        @auth
                            {{-- Si ya está logueado, mostrar botón de Dashboard --}}
                            <div class="alert alert-success mb-4">
                                ¡Hola de nuevo! Ya has iniciado sesión.
                            </div>
                            <!-- Usamos route() en vez de url() por buena práctica, pero url() funciona igual -->
                            <a href="{{ route('cliente.index') }}" class="btn btn-primary btn-lg btn-lg-custom">
                                Ir a mi Panel <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        @else
                            {{-- Botones para visitante --}}
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-lg-custom shadow">
                                Iniciar Sesión
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg btn-lg-custom">
                                    Crear Cuenta Nueva
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </div>

</body>

</html>
