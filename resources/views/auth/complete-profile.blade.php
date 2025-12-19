<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Perfil - Electrigonza</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0f172a',
                        secondary: '#f59e0b'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 font-sans text-gray-800 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">

        <!-- Encabezado -->
        <div class="bg-blue-600 px-8 py-6 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-secondary mb-3 text-white">
                <i class="fas fa-user-edit text-xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-white">¡Bienvenido a Electrigonza!</h2>
            <p class="text-blue-200 text-sm mt-1">Para brindarte un mejor servicio, necesitamos completar tu
                información.</p>
        </div>

        <!-- Formulario -->
        <form action="{{ route('cliente.complete.store') }}" method="POST" class="p-8">
            @csrf

            <!-- Nombre Completo -->
            <div class="mb-5">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nombre Completo</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" name="nombres"
                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-blue focus:ring-2 focus:ring-blue-200 outline-none transition"
                        placeholder="Ej: Juan Pérez" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <!-- Teléfono -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Celular / Teléfono</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                        <input type="tel" name="telefono"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-blue focus:ring-2 focus:ring-blue-200 outline-none transition"
                            maxlength="9" pattern="\d{9}" title="Debe contener 9 dígitos numéricos" required>
                    </div>
                </div>
            </div>

            <!-- Dirección -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Dirección Principal</label>
                <div class="relative">
                    <div class="absolute top-3 left-3 pointer-events-none">
                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                    </div>
                    <textarea name="direccion" rows="2"
                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-blue focus:ring-2 focus:ring-blue-200 outline-none transition"
                        placeholder="Av. La Cultura 123, Cusco" required></textarea>
                </div>
                <p class="text-xs text-gray-400 mt-1">Esta será la dirección predeterminada para tus servicios.</p>
            </div>

            <!-- Botón Guardar -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-lg transform active:scale-95 transition duration-200 flex items-center justify-center gap-2">
                <span>Guardar y Continuar</span>
                <i class="fas fa-arrow-right"></i>
            </button>

            <div class="text-center mt-6">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-gray-400 hover:text-red-500 underline">
                        Cancelar y Cerrar Sesión
                    </button>
                </form>
            </div>
        </form>
    </div>

</body>

</html>
