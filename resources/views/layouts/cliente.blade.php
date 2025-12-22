<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - ElectriGonza</title>
    <link rel="icon" href="{{ asset('/images/favicon.png') }}" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        slate: {
                            850: '#1e293b', // Un tono más oscuro para contrastes
                            900: '#0f172a', // Azul Oscuro Profundo (Primary)
                        },
                        yellow: {
                            400: '#facc15', // Amarillo Eléctrico Brillante
                            500: '#eab308', // Amarillo Ámbar (Secondary)
                        }
                    },
                    animation: {
                        'fade-in-down': 'fadeInDown 0.5s ease-out',
                    },
                    keyframes: {
                        fadeInDown: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(-10px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <nav class="bg-slate-900 shadow-lg border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                <div class="flex items-center gap-3">
                    <div class="bg-white/10 p-1.5 rounded-lg backdrop-blur-sm">
                        <img src="{{ asset('/images/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                    </div>
                    <span class="font-bold text-xl tracking-tight text-white">
                        Electri<span class="text-yellow-400">Gonza</span>
                    </span>
                </div>

                <div class="flex items-center gap-6">

                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex flex-col items-end">
                            <span class="text-sm font-bold text-white leading-tight">
                                {{ Auth::user()->cliente->nombres ?? Auth::user()->name }}
                            </span>
                            <span class="text-xs text-slate-400 font-medium">Cliente Verificado</span>
                        </div>

                        <div
                            class="h-9 w-9 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-slate-900 font-bold shadow-md ring-2 ring-slate-800">
                            {{ substr(Auth::user()->cliente->nombres ?? Auth::user()->name, 0, 1) }}
                        </div>
                    </div>

                    <div class="h-6 w-px bg-slate-700 hidden sm:block"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="group flex items-center gap-2 text-sm font-medium text-slate-400 hover:text-white transition-colors"
                            title="Cerrar Sesión">
                            <i class="fas fa-power-off group-hover:text-red-400 transition-colors"></i>
                            <span
                                class="hidden sm:inline group-hover:underline decoration-red-400 decoration-2 underline-offset-4">Salir</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-8 mt-auto">
        <div
            class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500">
            <div>
                &copy; {{ date('Y') }} <span class="font-bold text-slate-700">ElectriGonza</span>. Todos los
                derechos reservados.
            </div>
            <div class="flex gap-6">
                <a href="#" class="hover:text-blue-600 transition">Soporte</a>
                <a href="#" class="hover:text-blue-600 transition">Privacidad</a>
            </div>
        </div>
    </footer>

</body>

</html>
