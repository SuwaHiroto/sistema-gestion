<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Perfil - ElectriGonza</title>
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
                            900: '#0f172a', // Fondo Corporativo
                        },
                        yellow: {
                            400: '#facc15', // Acento Brillante
                            500: '#eab308', // Acento Hover
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-100 font-sans text-slate-800 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden border border-slate-200">

        <div class="bg-slate-900 px-8 py-8 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/5 to-transparent z-0"></div>
            <div class="absolute -top-6 -right-6 text-yellow-500/10 z-0">
                <i class="fas fa-bolt text-9xl"></i>
            </div>

            <div class="relative z-10">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-yellow-400 mb-4 text-slate-900 shadow-lg shadow-yellow-400/20 transform rotate-3">
                    <i class="fas fa-user-edit text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-white tracking-tight">¡Casi listos!</h2>
                <p class="text-slate-400 text-sm mt-2 max-w-xs mx-auto">
                    Para brindarte un servicio eléctrico eficiente, necesitamos unos datos finales.
                </p>
            </div>
        </div>

        <form action="{{ route('cliente.complete.store') }}" method="POST" class="p-8 space-y-5">
            @csrf

            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2">Nombre Completo</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-user text-slate-400"></i>
                    </div>
                    <input type="text" name="nombres"
                        class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition placeholder-slate-400"
                        placeholder="Ej: Juan Pérez" required autofocus>
                </div>
            </div>

            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2">Celular / Teléfono</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-slate-400"></i>
                    </div>
                    <input type="tel" name="telefono"
                        class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition placeholder-slate-400"
                        maxlength="9" pattern="\d{9}" placeholder="999 000 111"
                        title="Debe contener 9 dígitos numéricos" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9);">
                </div>
            </div>

            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2">Dirección Principal</label>
                <div class="relative">
                    <div class="absolute top-4 left-4 pointer-events-none">
                        <i class="fas fa-map-marker-alt text-slate-400"></i>
                    </div>
                    <textarea name="direccion" rows="3"
                        class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition placeholder-slate-400 resize-none"
                        placeholder="Ej: Av. La Cultura 123, Cusco" required></textarea>
                </div>
                <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                    <i class="fas fa-info-circle"></i> Usaremos esta dirección para tus visitas técnicas.
                </p>
            </div>

            <button type="submit"
                class="w-full bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-bold py-4 rounded-xl shadow-lg shadow-yellow-400/30 transform hover:-translate-y-1 transition duration-200 flex items-center justify-center gap-2 text-lg mt-6">
                <span>Finalizar Registro</span>
                <i class="fas fa-arrow-right"></i>
            </button>

            <div class="text-center pt-4 border-t border-slate-100">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="text-sm text-slate-400 hover:text-red-500 font-medium transition flex items-center justify-center gap-2 mx-auto group">
                        <i class="fas fa-sign-out-alt group-hover:scale-110 transition-transform"></i>
                        Cancelar y Cerrar Sesión
                    </button>
                </form>
            </div>
        </form>
    </div>

</body>

</html>
