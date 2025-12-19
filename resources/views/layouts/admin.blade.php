<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - ElectroManager</title>

    <!-- Tailwind CSS (Vía CDN para desarrollo rápido) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome (Iconos) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Configuración de Colores Corporativos -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0f172a', // Azul Oscuro Profundo
                        secondary: '#f59e0b', // Ambar / Naranja Electricidad
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR (Menú Lateral) -->
        <aside class="w-64 bg-primary text-gray-300 flex-col hidden md:flex transition-all duration-300 shadow-xl z-10">
            <!-- Logo -->
                <div class="h-16 flex items-center justify-center border-b border-gray-700 bg-gray-900 shadow-inner">
                    <!-- Logo image: place your file at public/images/logo.png -->
                    <img src="{{ asset('/images/logo.png') }}" alt="Electrigonza" class="w-14 h-14 mr-2 object-contain">
                    <span class="text-white font-bold text-lg tracking-wider">Electrigonza</span>
                </div>

            <!-- Menú de Navegación -->
            <nav class="flex-1 overflow-y-auto py-6">
                <ul class="space-y-2 px-3">
                    <!-- Opción: Dashboard -->
                    <li>
                        <a href="{{ url('/dashboard') }}"
                            class="flex items-center p-3 rounded-lg transition-colors duration-200 
                           {{ request()->is('dashboard') ? 'bg-secondary text-white shadow-lg' : 'hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-tachometer-alt w-6 text-center"></i>
                            <span class="ml-3 font-medium">Dashboard</span>
                        </a>
                    </li>

                    <!-- Opción: Servicios -->
                    <li>
                        <a href="{{ url('/servicios') }}"
                            class="flex items-center p-3 rounded-lg transition-colors duration-200 
                           {{ request()->is('servicios*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-tools w-6 text-center"></i>
                            <span class="ml-3 font-medium">Servicios</span>
                        </a>
                    </li>

                    <!-- Opción: Técnicos -->
                    <li>
                        <a href="{{ url('/tecnicos') }}"
                            class="flex items-center p-3 rounded-lg transition-colors duration-200 
                           {{ request()->is('tecnicos*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-hard-hat w-6 text-center"></i>
                            <span class="ml-3 font-medium">Técnicos</span>
                        </a>
                    </li>
                    <!-- Opción: Clientes -->
                    <li>
                        <a href="{{ url('/clientes') }}"
                            class="flex items-center p-3 rounded-lg transition-colors duration-200 
                           {{ request()->is('clientes*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-users w-6 text-center"></i>
                            <span class="ml-3 font-medium">Clientes</span>
                        </a>
                    </li>

                    <!-- Opción: Pagos -->
                    <li>
                        <a href="{{ url('/pagos') }}"
                            class="flex items-center p-3 rounded-lg transition-colors duration-200 
                           {{ request()->is('pagos*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-file-invoice-dollar w-6 text-center"></i>
                            <span class="ml-3 font-medium">Pagos</span>
                        </a>
                    </li>
                    <!-- Opción: Materiales -->
                    <li>
                        <a href="{{ url('/materiales') }}"
                            class="flex items-center p-3 rounded-lg transition-colors duration-200 
                           {{ request()->is('materiales*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-boxes w-6 text-center"></i>
                            <span class="ml-3 font-medium">Materiales</span>
                        </a>
                    </li>

                    <!-- Opción: Reportes -->
                    <li>
                        <a href="{{ url('/reportes') }}"
                            class="flex items-center p-3 rounded-lg transition-colors duration-200 
                           {{ request()->is('reportes*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-chart-line w-6 text-center"></i>
                            <span class="ml-3 font-medium">Reportes</span>
                        </a>
                </ul>
            </nav>

            <!-- Footer del Sidebar (Usuario) -->
            <div class="p-4 border-t border-gray-700 bg-gray-900">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-tr from-secondary to-yellow-300 flex items-center justify-center text-primary font-bold shadow-md">
                        {{ substr(Auth::user()->email ?? 'A', 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold text-white truncate">Administrador</p>
                        <!-- Formulario de Logout compatible con Laravel -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="text-xs text-gray-400 hover:text-red-400 transition flex items-center gap-1">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-100">

            <!-- Topbar Móvil (Solo visible en pantallas pequeñas) -->
            <header class="md:hidden h-16 bg-white shadow-sm flex items-center justify-between px-4">
                <span class="font-bold text-primary">ElectroManager</span>
                <button class="text-gray-600 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </header>

            <!-- Área donde se inyectarán las vistas hijas -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>
