<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - ElectroManager</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0f172a', // Azul Oscuro
                        secondary: '#f59e0b', // Ámbar
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 font-sans text-gray-800">

    <!-- Navbar Superior -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <img src="{{ asset('/images/logo.png') }}" alt="Electrigonza" class="w-14 h-14 mr-2 object-contain">
                    <span class="font-bold text-xl text-primary tracking-tight">Electrigonza</span>
                </div>

                <!-- Menú Usuario -->
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex flex-col items-end">
                        <span
                            class="text-sm font-bold text-gray-700">{{ Auth::user()->cliente->nombres ?? Auth::user()->name }}</span>
                        <span class="text-xs text-gray-500">{{ Auth::user()->email }}</span>
                    </div>

                    <div class="h-8 w-px bg-gray-200 mx-2"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm text-red-500 hover:text-red-700 font-medium border border-red-100 bg-red-50 px-3 py-2 rounded-lg transition hover:bg-red-100 flex items-center gap-2">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="hidden sm:inline">Salir</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Footer Simple -->
    <footer class="bg-white border-t border-gray-200 mt-auto py-6">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-400 text-sm">
            &copy; {{ date('Y') }} ElectroManager - Servicios Eléctricos Profesionales
        </div>
    </footer>

</body>

</html>
