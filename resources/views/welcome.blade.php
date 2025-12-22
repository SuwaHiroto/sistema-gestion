<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenido - ElectriGonza</title>
    <link rel="icon" href="{{ asset('/images/favicon.png') }}" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#0f172a', // Slate 900
                            primary: '#eab308', // Yellow 500
                            light: '#f8fafc', // Slate 50
                        }
                    }
                }
            }
        }
    </script>
</head>

<body
    class="bg-slate-100 min-h-screen flex items-center justify-center p-4 selection:bg-brand-primary selection:text-brand-dark">

    <div
        class="bg-white rounded-3xl shadow-2xl overflow-hidden max-w-5xl w-full flex flex-col md:flex-row min-h-[600px] border border-slate-200">

        <div
            class="md:w-1/2 bg-brand-dark relative flex flex-col justify-between p-12 text-white overflow-hidden group">

            <div
                class="absolute -right-10 -bottom-20 text-brand-primary opacity-5 transform rotate-12 scale-150 transition-transform duration-1000 group-hover:scale-125">
                <i class="fas fa-bolt text-[400px]"></i>
            </div>
            <div
                class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/5 to-transparent pointer-events-none">
            </div>

            <div class="relative z-10 flex items-center gap-3">
                <div class="bg-white/10 p-2 rounded-xl backdrop-blur-sm border border-white/10">
                    <img src="{{ asset('/images/logo.png') }}" alt="Logo"
                        class="w-8 h-8 object-contain brightness-0 invert">
                </div>
                <span class="font-bold text-xl tracking-wide">Electri<span
                        class="text-brand-primary">Gonza</span></span>
            </div>

            <div class="relative z-10 my-10">
                <h1 class="text-4xl md:text-5xl font-black leading-tight mb-6">
                    Energía que <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-yellow-200">mueve
                        tu mundo.</span>
                </h1>
                <p class="text-slate-400 text-lg leading-relaxed max-w-md">
                    Gestiona instalaciones, mantenimientos y reparaciones eléctricas con los mejores expertos
                    certificados del país.
                </p>

                <ul class="mt-8 space-y-4">
                    <li class="flex items-center gap-3 text-slate-300">
                        <i class="fas fa-check-circle text-brand-primary"></i> <span>Atención 24/7</span>
                    </li>
                    <li class="flex items-center gap-3 text-slate-300">
                        <i class="fas fa-check-circle text-brand-primary"></i> <span>Personal Certificado</span>
                    </li>
                    <li class="flex items-center gap-3 text-slate-300">
                        <i class="fas fa-check-circle text-brand-primary"></i> <span>Garantía Asegurada</span>
                    </li>
                </ul>
            </div>

            <div class="relative z-10 text-sm text-slate-500 font-medium">
                &copy; {{ date('Y') }} Servicios Generales S.A.C.
            </div>
        </div>

        <div class="md:w-1/2 bg-white flex flex-col justify-center p-12 relative">

            <div class="max-w-sm mx-auto w-full text-center md:text-left">
                <h2 class="text-3xl font-bold text-slate-900 mb-2">Bienvenido</h2>
                <p class="text-slate-500 mb-8">Ingresa a tu cuenta para gestionar tus servicios.</p>

                @if (Route::has('login'))
                    @auth
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center mb-6">
                            <div
                                class="w-16 h-16 bg-brand-primary/20 text-brand-primary rounded-full flex items-center justify-center mx-auto mb-3 text-2xl font-bold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <h3 class="font-bold text-slate-800">¡Hola de nuevo, {{ Auth::user()->name }}!</h3>
                            <p class="text-sm text-slate-500 mb-4">Ya has iniciado sesión correctamente.</p>

                            @php
                                $dashboardRoute = match (Auth::user()->rol) {
                                    'admin' => route('dashboard'), // Ajusta si tu ruta admin es diferente
                                    'tecnico' => route('tecnico.panel'),
                                    default => route('cliente.index'),
                                };
                            @endphp

                            <a href="{{ $dashboardRoute }}"
                                class="block w-full py-3.5 px-6 bg-brand-dark hover:bg-slate-800 text-white font-bold rounded-xl shadow-lg shadow-brand-dark/30 transition-all transform hover:-translate-y-1">
                                Ir a mi Panel <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            <a href="{{ route('login') }}"
                                class="group flex items-center justify-center w-full py-4 px-6 bg-brand-primary hover:bg-yellow-400 text-slate-900 font-bold text-lg rounded-xl shadow-lg shadow-yellow-500/20 transition-all transform hover:-translate-y-1">
                                <i class="fas fa-sign-in-alt mr-3 group-hover:scale-110 transition-transform"></i>
                                Iniciar Sesión
                            </a>

                            @if (Route::has('register'))
                                <div class="relative py-2">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-slate-200"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="bg-white px-2 text-slate-400">¿Eres nuevo aquí?</span>
                                    </div>
                                </div>

                                <a href="{{ route('register') }}"
                                    class="flex items-center justify-center w-full py-4 px-6 bg-white border-2 border-slate-200 hover:border-brand-dark text-slate-600 hover:text-brand-dark font-bold text-lg rounded-xl transition-all">
                                    Crear Cuenta
                                </a>
                            @endif
                        </div>
                    @endauth
                @endif
            </div>
        </div>

    </div>

</body>

</html>
