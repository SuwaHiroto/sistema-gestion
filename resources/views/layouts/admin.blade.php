<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - ElectriGonza</title>
    <link rel="icon" href="{{ asset('/images/favicon.png') }}" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
                            900: '#0f172a', // Fondo Sidebar
                            950: '#020617', // Fondo Logo
                        },
                        yellow: {
                            400: '#facc15', // Acento Brillante
                            500: '#eab308', // Acento Botones
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Scrollbar personalizada para el sidebar */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .sidebar-link.active {
            background-color: #facc15;
            /* yellow-400 */
            color: #0f172a;
            /* slate-900 */
            font-weight: 700;
            box-shadow: 0 0 15px rgba(250, 204, 21, 0.3);
        }
    </style>
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased selection:bg-yellow-400 selection:text-slate-900">

    <div class="flex h-screen overflow-hidden">

        <aside
            class="w-72 bg-slate-900 text-slate-300 flex-col hidden md:flex transition-all duration-300 shadow-2xl z-20 relative">

            <div class="h-20 flex items-center px-6 border-b border-slate-800 bg-slate-950/50">
                <div class="flex items-center gap-3">
                    <div class="bg-yellow-400/10 p-2 rounded-lg border border-yellow-400/20">
                        <img src="{{ asset('/images/logo.png') }}" alt="Logo"
                            class="w-6 h-6 object-contain brightness-0 invert">
                    </div>
                    <div>
                        <span class="block text-white font-bold text-lg leading-none tracking-tight">
                            Electri<span class="text-yellow-400">Gonza</span>
                        </span>
                        <span
                            class="text-[10px] text-slate-500 font-medium uppercase tracking-widest">Administración</span>
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1 scrollbar-hide">

                <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 mt-2">Principal</p>

                <a href="{{ url('/dashboard') }}"
                    class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('dashboard') ? 'active' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i
                        class="fas fa-chart-pie w-6 text-center text-lg {{ request()->is('dashboard') ? 'text-slate-900' : 'text-slate-500 group-hover:text-yellow-400' }}"></i>
                    <span class="ml-3">Dashboard</span>
                </a>

                <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 mt-6">Operaciones</p>

                <a href="{{ url('/servicios') }}"
                    class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('servicios*') ? 'active' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i
                        class="fas fa-bolt w-6 text-center text-lg {{ request()->is('servicios*') ? 'text-slate-900' : 'text-slate-500 group-hover:text-yellow-400' }}"></i>
                    <span class="ml-3">Servicios</span>
                </a>

                <a href="{{ url('/pagos') }}"
                    class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('pagos*') ? 'active' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i
                        class="fas fa-file-invoice-dollar w-6 text-center text-lg {{ request()->is('pagos*') ? 'text-slate-900' : 'text-slate-500 group-hover:text-yellow-400' }}"></i>
                    <span class="ml-3">Pagos y Cobros</span>
                </a>

                <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 mt-6">Recursos</p>

                <a href="{{ url('/tecnicos') }}"
                    class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('tecnicos*') ? 'active' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i
                        class="fas fa-hard-hat w-6 text-center text-lg {{ request()->is('tecnicos*') ? 'text-slate-900' : 'text-slate-500 group-hover:text-yellow-400' }}"></i>
                    <span class="ml-3">Personal Técnico</span>
                </a>

                <a href="{{ url('/clientes') }}"
                    class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('clientes*') ? 'active' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i
                        class="fas fa-users w-6 text-center text-lg {{ request()->is('clientes*') ? 'text-slate-900' : 'text-slate-500 group-hover:text-yellow-400' }}"></i>
                    <span class="ml-3">Cartera Clientes</span>
                </a>

                <a href="{{ url('/materiales') }}"
                    class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('materiales*') ? 'active' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i
                        class="fas fa-boxes w-6 text-center text-lg {{ request()->is('materiales*') ? 'text-slate-900' : 'text-slate-500 group-hover:text-yellow-400' }}"></i>
                    <span class="ml-3">Materiales</span>
                </a>

                <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 mt-6">Análisis</p>

                <a href="{{ url('/reportes') }}"
                    class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('reportes*') ? 'active' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i
                        class="fas fa-chart-line w-6 text-center text-lg {{ request()->is('reportes*') ? 'text-slate-900' : 'text-slate-500 group-hover:text-yellow-400' }}"></i>
                    <span class="ml-3">Métricas</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800 bg-slate-950/30">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-yellow-400 flex items-center justify-center text-slate-900 font-bold shadow-lg shadow-yellow-400/20">
                        {{ substr(Auth::user()->email ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="text-xs text-slate-400 hover:text-red-400 transition flex items-center gap-1.5 mt-0.5">
                                <i class="fas fa-power-off"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50 relative">

            <header
                class="md:hidden h-16 bg-slate-900 text-white shadow-md flex items-center justify-between px-4 z-30">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('/images/logo.png') }}" alt="Logo"
                        class="w-6 h-6 object-contain brightness-0 invert">
                    <span class="font-bold">ElectriGonza</span>
                </div>
                <button class="text-slate-300 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-8 scroll-smooth">
                @yield('content')
            </main>

        </div>
    </div>

</body>

</html>
