<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - ElectriGonza</title>
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
        class="bg-white rounded-[2rem] shadow-2xl overflow-hidden max-w-5xl w-full flex flex-col md:flex-row border border-slate-200">

        <div
            class="hidden md:flex md:w-5/12 bg-slate-900 relative flex-col justify-between p-12 text-white overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/5 to-transparent z-10"></div>
            <div class="absolute -top-10 -right-10 text-yellow-500/10 z-0">
                <i class="fas fa-plug text-[300px] rotate-45"></i>
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

            <div class="relative z-20 my-auto">
                <h2 class="text-4xl font-bold leading-tight mb-4">
                    Únete a los <br> expertos.
                </h2>
                <p class="text-slate-400 text-lg leading-relaxed">
                    Crea tu cuenta hoy y gestiona tus servicios eléctricos con la rapidez y seguridad que mereces.
                </p>

                <ul class="mt-8 space-y-3 text-sm text-slate-300">
                    <li class="flex items-center gap-3">
                        <span
                            class="w-6 h-6 rounded-full bg-yellow-500/20 text-yellow-400 flex items-center justify-center text-xs"><i
                                class="fas fa-check"></i></span>
                        Seguimiento en tiempo real
                    </li>
                    <li class="flex items-center gap-3">
                        <span
                            class="w-6 h-6 rounded-full bg-yellow-500/20 text-yellow-400 flex items-center justify-center text-xs"><i
                                class="fas fa-check"></i></span>
                        Historial de servicios
                    </li>
                </ul>
            </div>

            <div class="relative z-20 text-xs text-slate-500 mt-auto">
                &copy; {{ date('Y') }} Servicios Generales S.A.C.
            </div>
        </div>

        <div class="w-full md:w-7/12 p-8 md:p-12 bg-white relative">

            <div class="max-w-md mx-auto">
                <h3 class="text-3xl font-bold text-slate-800 mb-2">Crear Cuenta</h3>
                <p class="text-slate-500 mb-6">Completa el formulario para registrarte.</p>

                <x-validation-errors class="mb-4 bg-red-50 text-red-600 p-4 rounded-xl text-sm border border-red-100" />

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-1">Correo
                            Electrónico</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="far fa-envelope text-slate-400"></i>
                            </div>
                            <input id="email" type="email" name="email" :value="old('email')" required
                                autocomplete="username"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition placeholder-slate-400"
                                placeholder="ejemplo@correo.com">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-1">Contraseña</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-slate-400"></i>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition placeholder-slate-400"
                                placeholder="Mínimo 8 caracteres">
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-1">Confirmar
                            Contraseña</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-check-double text-slate-400"></i>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                autocomplete="new-password"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition placeholder-slate-400"
                                placeholder="Repite tu contraseña">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-yellow-400/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2 mt-4">
                        <span>Registrarme</span>
                        <i class="fas fa-user-plus"></i>
                    </button>
                </form>

                <div class="mt-8 text-center pt-6 border-t border-slate-100">
                    <p class="text-slate-500 text-sm">
                        ¿Ya tienes una cuenta?
                        <a href="{{ route('login') }}"
                            class="font-bold text-slate-800 hover:text-yellow-600 transition ml-1">Inicia Sesión</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="md:hidden fixed bottom-4 text-xs text-slate-400 text-center w-full">
        &copy; {{ date('Y') }} ElectriGonza
    </div>

</body>

</html>
