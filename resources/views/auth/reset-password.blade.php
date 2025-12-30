<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña - ElectriGonza</title>
    <link rel="icon" href="{{ asset('/images/favicon.png') }}" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    },
                    colors: {
                        slate: {
                            850: '#1e293b',
                            900: '#0f172a'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div
        class="bg-white rounded-3xl shadow-2xl overflow-hidden max-w-4xl w-full flex flex-col md:flex-row border border-slate-200">

        <div
            class="hidden md:flex md:w-5/12 bg-slate-900 relative flex-col justify-between p-12 text-white overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/5 to-transparent z-10"></div>
            <div class="absolute -bottom-10 -left-10 text-yellow-500/10 z-0">
                <i class="fas fa-shield-alt text-[250px]"></i>
            </div>

            <div class="relative z-20 flex items-center gap-3">
                <div class="bg-white/10 p-2 rounded-xl backdrop-blur-sm border border-white/10">
                    <img src="{{ asset('/images/logo.png') }}" alt="Logo"
                        class="w-6 h-6 object-contain brightness-0 invert">
                </div>
                <span class="font-bold text-lg tracking-wide">
                    Electri<span class="text-yellow-400">Gonza</span>
                </span>
            </div>

            <div class="relative z-20 mb-8">
                <h2 class="text-3xl font-bold leading-tight mb-4">
                    Nueva <br> Seguridad.
                </h2>
                <p class="text-slate-400 text-sm">
                    Crea una contraseña segura para proteger tu cuenta y tus servicios.
                </p>
            </div>

            <div class="relative z-20 text-xs text-slate-500">
                &copy; {{ date('Y') }} Servicios Generales S.A.C.
            </div>
        </div>

        <div class="w-full md:w-7/12 p-8 md:p-12 bg-white relative flex flex-col justify-center">

            <div class="max-w-md mx-auto w-full">
                <h3 class="text-2xl font-bold text-slate-800 mb-2">Restablecer Contraseña</h3>
                <p class="text-slate-500 mb-8">Ingresa tus nuevas credenciales.</p>

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 text-red-600 p-4 rounded-xl text-sm border border-red-100">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Correo
                            Electrónico</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="far fa-envelope text-slate-400"></i>
                            </div>
                            <input id="email" type="email" name="email" :value="old('email', $request->email)"
                                required autofocus
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition placeholder-slate-400"
                                value="{{ $request->email }}" readonly>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Nueva
                            Contraseña</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-slate-400"></i>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition placeholder-slate-400"
                                placeholder="••••••••">

                            <button type="button" onclick="toggleVisibility('password', 'icon-pass')"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                <i id="icon-pass" class="far fa-eye text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Confirmar
                            Contraseña</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-slate-400"></i>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                autocomplete="new-password"
                                class="w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition placeholder-slate-400"
                                placeholder="••••••••">

                            <button type="button" onclick="toggleVisibility('password_confirmation', 'icon-confirm')"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                <i id="icon-confirm" class="far fa-eye text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-bold py-4 px-6 rounded-xl shadow-lg shadow-yellow-400/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2 mt-4">
                        <span>Actualizar Contraseña</span>
                        <i class="fas fa-check-circle"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

</body>

</html>
