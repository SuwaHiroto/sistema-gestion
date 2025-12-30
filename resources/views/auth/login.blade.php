<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - ElectriGonza</title>
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
                        slate: {
                            850: '#1e293b',
                            900: '#0f172a', // Color Oscuro Corporativo
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div
        class="bg-white rounded-3xl shadow-2xl overflow-hidden max-w-5xl w-full flex flex-col md:flex-row border border-slate-200">

        <div
            class="hidden md:flex md:w-5/12 bg-slate-900 relative flex-col justify-between p-12 text-white overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/5 to-transparent z-10"></div>
            <div class="absolute -bottom-20 -left-20 text-yellow-500/10 z-0">
                <i class="fas fa-bolt text-[300px]"></i>
            </div>

            <div class="relative z-20 flex items-center gap-3">
                <div class="bg-white/10 p-2 rounded-xl backdrop-blur-sm border border-white/10">
                    <img src="{{ asset('/images/logo.png') }}" alt="Logo"
                        class="w-8 h-8 object-contain brightness-0 invert">
                </div>
                <span class="font-bold text-xl tracking-wide">
                    Electri<span class="text-yellow-400">Gonza</span>
                </span>
            </div>

            <div class="relative z-20 mb-8">
                <h2 class="text-4xl font-bold leading-tight mb-4">
                    Bienvenido <br> de nuevo.
                </h2>
                <p class="text-slate-400 text-lg">
                    Accede a tu panel para gestionar solicitudes, técnicos y mantenimientos.
                </p>
            </div>

            <div class="relative z-20 text-sm text-slate-500">
                &copy; {{ date('Y') }} Servicios Generales S.A.C.
            </div>
        </div>

        <div class="w-full md:w-7/12 p-8 md:p-16 bg-white relative">

            <div class="max-w-md mx-auto">
                <h3 class="text-3xl font-bold text-slate-800 mb-2">Iniciar Sesión</h3>
                <p class="text-slate-500 mb-8">Ingresa tus credenciales para continuar.</p>

                <x-validation-errors class="mb-4 bg-red-50 text-red-600 p-4 rounded-xl text-sm border border-red-100" />

                @if (session('status'))
                    <div
                        class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-xl border border-green-100">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Correo
                            Electrónico</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="far fa-envelope text-slate-400"></i>
                            </div>
                            <input id="email" type="email" name="email" :value="old('email')" required
                                autofocus
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition placeholder-slate-400"
                                placeholder="ejemplo@correo.com">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-sm font-bold text-slate-700">Contraseña</label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-slate-400"></i>
                            </div>

                            <input id="password" type="password" name="password" required
                                autocomplete="current-password"
                                class="w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition placeholder-slate-400"
                                placeholder="••••••••">

                            <button type="button" onclick="toggleVisibility('password', 'icon-login')"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer transition-colors">
                                <i id="icon-login" class="far fa-eye text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center cursor-pointer group">
                            <div class="relative flex items-center">
                                <input id="remember_me" type="checkbox" name="remember"
                                    class="peer h-5 w-5 rounded border-gray-300 text-yellow-500 focus:ring-yellow-400 cursor-pointer">
                            </div>
                            <span
                                class="ml-2 text-sm text-slate-600 group-hover:text-slate-800 transition">Recordarme</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm font-semibold text-slate-500 hover:text-yellow-600 transition">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                        class="w-full bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-bold py-4 px-6 rounded-xl shadow-lg shadow-yellow-400/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2 text-lg">
                        <span>Ingresar al Sistema</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="mt-10 text-center pt-6 border-t border-slate-100">
                    <p class="text-slate-500 text-sm">
                        ¿Aún no tienes cuenta?
                        <a href="{{ route('register') }}"
                            class="font-bold text-slate-800 hover:text-yellow-600 transition ml-1">Regístrate aquí</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="md:hidden fixed bottom-4 text-xs text-slate-400 text-center w-full">
        &copy; {{ date('Y') }} ElectriGonza
    </div>

    <script>
        function toggleVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash'); // Cambia a ojo tachado
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye'); // Vuelve a ojo normal
            }
        }
    </script>

</body>

</html>
