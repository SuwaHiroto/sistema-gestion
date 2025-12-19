@extends('layouts.admin')

@section('content')

    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Panel de Control</h2>
            <p class="text-gray-500 mt-1">Resumen general del sistema</p>
        </div>
        <div class="text-right hidden sm:block">
            <p class="text-sm font-bold text-primary">{{ now()->format('d M, Y') }}</p>
            <div class="flex items-center justify-end gap-2 text-xs text-green-600">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                Sistema Operativo
            </div>
        </div>
    </div>

    <!-- TARJETAS DE ESTADÍSTICAS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Total Servicios -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-blue-500 hover:-translate-y-1 transition duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-100 text-blue-600 p-3 rounded-lg">
                    <i class="fas fa-clipboard-list fa-lg"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase">Total</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total_servicios'] ?? 0 }}</h3>
            <p class="text-sm text-gray-500">Servicios registrados</p>
        </div>

        <!-- Pendientes -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-secondary hover:-translate-y-1 transition duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-yellow-100 text-secondary p-3 rounded-lg">
                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase">Atención</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800">{{ $stats['pendientes'] ?? 0 }}</h3>
            <p class="text-sm text-gray-500">Pendientes de acción</p>
        </div>

        <!-- Técnicos -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-indigo-500 hover:-translate-y-1 transition duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-indigo-100 text-indigo-600 p-3 rounded-lg">
                    <i class="fas fa-hard-hat fa-lg"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase">Personal</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800">{{ $stats['tecnicos'] ?? 0 }}</h3>
            <p class="text-sm text-gray-500">Técnicos activos</p>
        </div>

        <!-- Ingresos Hoy -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-green-500 hover:-translate-y-1 transition duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-green-100 text-green-600 p-3 rounded-lg">
                    <i class="fas fa-wallet fa-lg"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase">Hoy</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800">S/ {{ number_format($stats['pagos_hoy'] ?? 0, 2) }}</h3>
            <p class="text-sm text-gray-500">Ingresos del día</p>
        </div>
    </div>

    <!-- SECCIÓN DIVIDIDA: Tabla y Accesos Rápidos -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Tabla de Actividad Reciente (Ocupa 2 columnas) -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-700">Últimas Solicitudes</h3>
                <a href="{{ url('/servicios') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Ver todas &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b bg-white">
                            <th class="px-6 py-4">Cliente</th>
                            <th class="px-6 py-4">Solicitud</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($recent_servicios ?? [] as $servicio)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $servicio->cliente->nombres ?? 'Usuario Eliminado' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ Str::limit($servicio->descripcion_solicitud, 30) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $estilos = [
                                        'PENDIENTE' => 'bg-red-100 text-red-700',
                                        'COTIZANDO' => 'bg-yellow-100 text-yellow-700',
                                        'APROBADO' => 'bg-blue-100 text-blue-700',
                                        'EN_PROCESO' => 'bg-indigo-100 text-indigo-700',
                                        'FINALIZADO' => 'bg-green-100 text-green-700'
                                    ];
                                    $clase = $estilos[$servicio->estado] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="px-2 py-1 text-xs font-bold rounded-full {{ $clase }}">
                                    {{ $servicio->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">
                                {{ $servicio->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <p>No hay solicitudes recientes</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Acciones Rápidas (Ocupa 1 columna) -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 h-fit">
            <h3 class="font-bold text-gray-700 mb-4">Acciones Rápidas</h3>
            <div class="space-y-3">
                <a href="{{ url('/servicios/create') }}" class="w-full flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-secondary hover:text-secondary hover:bg-yellow-50 transition group cursor-pointer">
                    <span class="font-medium">Nuevo Servicio</span>
                    <i class="fas fa-plus bg-gray-100 p-2 rounded-full group-hover:bg-white text-gray-500 group-hover:text-secondary transition"></i>
                </a>
                
                <a href="{{ url('/tecnicos') }}" class="w-full flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group">
                    <span class="font-medium">Registrar Técnico</span>
                    <i class="fas fa-user-plus bg-gray-100 p-2 rounded-full group-hover:bg-white text-gray-500 group-hover:text-blue-600 transition"></i>
                </a>

                <a href="{{ url('/pagos') }}" class="w-full flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-green-500 hover:text-green-600 hover:bg-green-50 transition group">
                    <span class="font-medium">Registrar Pago</span>
                    <i class="fas fa-dollar-sign bg-gray-100 p-2 rounded-full group-hover:bg-white text-gray-500 group-hover:text-green-600 transition"></i>
                </a>
            </div>
        </div>
    </div>
@endsection