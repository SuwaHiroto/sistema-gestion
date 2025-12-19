<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Técnico - ElectroManager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e293b',
                        secondary: '#f59e0b',
                        action: '#3b82f6'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 font-sans text-gray-800 pb-20"> <!-- Padding bottom para menú móvil -->

    <!-- Navbar Móvil Superior -->
    <header class="bg-primary text-white p-4 shadow-md sticky top-0 z-50 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <img src="{{ asset('/images/logo.png') }}" alt="Electrigonza" class="w-14 h-14 mr-2 object-contain">
            <h1 class="font-bold text-lg">Mi Agenda</h1>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs bg-gray-700 px-2 py-1 rounded-full">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-gray-400 hover:text-white"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </header>

    <!-- Contenido -->
    <main class="p-4 max-w-3xl mx-auto">
        @yield('content')
    </main>

    <!-- Barra de Navegación Inferior (Estilo App) -->
    <nav
        class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 shadow-lg flex justify-around py-3 z-50 md:hidden">
        <a href="{{ route('tecnico.index') }}"
            class="flex flex-col items-center text-primary {{ request()->routeIs('tecnico.index') ? 'text-secondary' : 'text-gray-400' }}">
            <i class="fas fa-list-ul text-xl mb-1"></i>
            <span class="text-[10px] font-bold">Trabajos</span>
        </a>
        <a href="#" class="flex flex-col items-center text-gray-400 hover:text-secondary">
            <i class="fas fa-history text-xl mb-1"></i>
            <span class="text-[10px] font-bold">Historial</span>
        </a>
        <a href="#" class="flex flex-col items-center text-gray-400 hover:text-secondary">
            <i class="fas fa-user text-xl mb-1"></i>
            <span class="text-[10px] font-bold">Perfil</span>
        </a>
    </nav>

</body>

</html>
