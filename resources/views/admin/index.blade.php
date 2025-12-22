@extends('layouts.admin')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Panel de Control</h2>
            <p class="text-slate-500 mt-1">Visión general del rendimiento y operaciones.</p>
        </div>
        <div class="hidden sm:flex items-center gap-3 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
            <div class="bg-green-100 text-green-600 p-2 rounded-lg">
                <i class="fas fa-server"></i>
            </div>
            <div>
                <p class="text-[10px] uppercase font-bold text-slate-400">Estado del Sistema</p>
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-xs font-bold text-slate-700">Operativo</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-all">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-clipboard-list text-6xl text-slate-800"></i>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-folder-open text-xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total_servicios'] ?? 0 }}</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Total Servicios</p>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-all">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-clock text-6xl text-yellow-500"></i>
            </div>
            <div class="relative z-10">
                <div
                    class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-xl flex items-center justify-center mb-4 border border-yellow-100">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $stats['pendientes'] ?? 0 }}</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Requieren Atención</p>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-all">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-users text-6xl text-blue-500"></i>
            </div>
            <div class="relative z-10">
                <div
                    class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 border border-blue-100">
                    <i class="fas fa-hard-hat text-xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $stats['tecnicos'] ?? 0 }}</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Personal Activo</p>
            </div>
        </div>

        <div
            class="bg-slate-900 rounded-2xl p-6 shadow-lg shadow-slate-900/20 relative overflow-hidden group hover:-translate-y-1 transition-all">
            <div class="absolute right-0 top-0 p-4 opacity-10">
                <i class="fas fa-wallet text-6xl text-white"></i>
            </div>
            <div class="relative z-10 text-white">
                <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center mb-4 backdrop-blur-sm">
                    <i class="fas fa-dollar-sign text-xl text-yellow-400"></i>
                </div>
                <h3 class="text-3xl font-bold">S/ {{ number_format($stats['pagos_hoy'] ?? 0, 2) }}</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Ingresos de Hoy</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden h-fit">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-700">Actividad Reciente</h3>
                <a href="{{ url('/servicios') }}" class="text-xs font-bold text-blue-600 hover:underline">Ver Todo
                    &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-white border-b border-slate-100">
                        <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <th class="px-6 py-4">Cliente</th>
                            <th class="px-6 py-4">Asunto</th>
                            <th class="px-6 py-4 text-center">Estado</th>
                            <th class="px-6 py-4 text-right">Hace</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recent_servicios ?? [] as $servicio)
                            <tr class="hover:bg-slate-50 transition group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-700">{{ $servicio->cliente->nombres ?? 'Eliminado' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-500 truncate max-w-[150px]"
                                        title="{{ $servicio->descripcion_solicitud }}">
                                        {{ $servicio->descripcion_solicitud }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusClass = match ($servicio->estado) {
                                            'PENDIENTE' => 'bg-slate-100 text-slate-600',
                                            'APROBADO' => 'bg-blue-50 text-blue-600',
                                            'EN_PROCESO' => 'bg-yellow-50 text-yellow-700',
                                            'FINALIZADO' => 'bg-emerald-50 text-emerald-700',
                                            default => 'bg-gray-50 text-gray-500',
                                        };
                                    @endphp
                                    <span
                                        class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $statusClass }} border border-transparent group-hover:border-current">
                                        {{ $servicio->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-xs text-slate-400 font-mono">
                                    {{ $servicio->created_at->diffForHumans(null, true, true) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400 italic">No hay actividad
                                    reciente.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-fit">
            <h3 class="font-bold text-slate-700 mb-4">Acciones Rápidas</h3>
            <div class="space-y-3">

                <a href="{{ url('/servicios/create') }}"
                    class="flex items-center gap-4 p-3 rounded-xl border border-slate-200 hover:border-yellow-400 hover:bg-yellow-50 transition group">
                    <div
                        class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-yellow-400 group-hover:text-slate-900 transition">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-700 group-hover:text-slate-900">Nuevo Servicio</p>
                        <p class="text-xs text-slate-400">Registrar solicitud</p>
                    </div>
                </a>

                <a href="{{ url('/tecnicos') }}"
                    class="flex items-center gap-4 p-3 rounded-xl border border-slate-200 hover:border-blue-400 hover:bg-blue-50 transition group">
                    <div
                        class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-blue-500 group-hover:text-white transition">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-700 group-hover:text-blue-700">Registrar Técnico</p>
                        <p class="text-xs text-slate-400">Alta de personal</p>
                    </div>
                </a>

                <a href="{{ url('/pagos') }}"
                    class="flex items-center gap-4 p-3 rounded-xl border border-slate-200 hover:border-emerald-400 hover:bg-emerald-50 transition group">
                    <div
                        class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-emerald-500 group-hover:text-white transition">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-700 group-hover:text-emerald-700">Registrar Pago</p>
                        <p class="text-xs text-slate-400">Ingreso a caja</p>
                    </div>
                </a>

            </div>
        </div>
    </div>
@endsection
