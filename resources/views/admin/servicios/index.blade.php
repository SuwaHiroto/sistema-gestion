@extends('layouts.admin')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Gestión de Servicios</h2>
            <p class="text-slate-500 mt-1">Control de solicitudes, asignación de técnicos y facturación.</p>
        </div>
        <a href="{{ url('/servicios/create') }}"
            class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-slate-900/20 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
            <div class="bg-yellow-400 text-slate-900 rounded-full w-5 h-5 flex items-center justify-center text-xs">
                <i class="fas fa-plus"></i>
            </div>
            <span>Nuevo Servicio</span>
        </a>
    </div>

    @if (session('success'))
        <div
            class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-8 rounded-r-xl shadow-sm flex items-center gap-3 animate-fade-in-down">
            <div class="bg-emerald-100 p-2 rounded-full"><i class="fas fa-check"></i></div>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="p-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <div class="relative w-full max-w-sm">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i
                        class="fas fa-search"></i></span>
                <input type="text" placeholder="Buscar por cliente o ID..."
                    class="w-full bg-white border border-slate-300 rounded-lg pl-10 p-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition">
            </div>
            <div class="text-xs font-bold text-slate-500 uppercase bg-white border border-slate-200 px-3 py-1 rounded">
                Total: {{ $servicios->total() }}
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-700 uppercase bg-slate-100/50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-bold w-24">Ticket</th>
                        <th class="px-6 py-4 font-bold">Cliente / Solicitud</th>
                        <th class="px-6 py-4 font-bold">Técnico Líder</th>
                        <th class="px-6 py-4 font-bold text-right">Materiales</th>
                        <th class="px-6 py-4 font-bold text-right">M. Obra</th>
                        <th class="px-6 py-4 font-bold text-right">Total</th>
                        <th class="px-6 py-4 font-bold text-center">Estado</th>
                        <th class="px-6 py-4 font-bold text-center">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($servicios as $servicio)
                        @php
                            // Lógica de Costos (Mantenida del original)
                            $costoMateriales = $servicio->materiales->sum(
                                fn($m) => $m->pivot->cantidad * $m->pivot->precio_unitario,
                            );
                            $total =
                                $servicio->costo_final_real > 0
                                    ? $servicio->costo_final_real
                                    : $servicio->monto_cotizado;
                            $manoObra = max(0, $total - $costoMateriales);

                            // Lógica de Estados (Diseño Industrial)
                            $statusConfig = match ($servicio->estado) {
                                'PENDIENTE' => [
                                    'bg' => 'bg-slate-100',
                                    'text' => 'text-slate-600',
                                    'border' => 'border-slate-200',
                                    'icon' => 'fa-clock',
                                ],
                                'COTIZANDO' => [
                                    'bg' => 'bg-blue-50',
                                    'text' => 'text-blue-600',
                                    'border' => 'border-blue-100',
                                    'icon' => 'fa-calculator',
                                ],
                                'APROBADO' => [
                                    'bg' => 'bg-indigo-50',
                                    'text' => 'text-indigo-600',
                                    'border' => 'border-indigo-100',
                                    'icon' => 'fa-thumbs-up',
                                ],
                                'EN_PROCESO' => [
                                    'bg' => 'bg-yellow-50',
                                    'text' => 'text-yellow-700',
                                    'border' => 'border-yellow-200',
                                    'icon' => 'fa-tools',
                                ],
                                'FINALIZADO' => [
                                    'bg' => 'bg-emerald-50',
                                    'text' => 'text-emerald-700',
                                    'border' => 'border-emerald-200',
                                    'icon' => 'fa-check-circle',
                                ],
                                'CANCELADO' => [
                                    'bg' => 'bg-red-50',
                                    'text' => 'text-red-600',
                                    'border' => 'border-red-100',
                                    'icon' => 'fa-ban',
                                ],
                                default => [
                                    'bg' => 'bg-gray-50',
                                    'text' => 'text-gray-500',
                                    'border' => 'border-gray-200',
                                    'icon' => 'fa-circle',
                                ],
                            };
                        @endphp

                        <tr class="bg-white hover:bg-slate-50 transition duration-150 group">

                            <td class="px-6 py-4">
                                <span
                                    class="font-mono font-bold text-slate-700 bg-slate-100 border border-slate-200 px-2 py-1 rounded text-xs">
                                    #{{ str_pad($servicio->id_servicio, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">
                                    {{ $servicio->cliente->nombres ?? 'Desconocido' }}
                                </div>
                                <div class="text-xs text-slate-500 truncate max-w-[200px]"
                                    title="{{ $servicio->descripcion_solicitud }}">
                                    {{ $servicio->descripcion_solicitud }}
                                </div>
                                <div class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                    <i class="far fa-calendar"></i> {{ $servicio->created_at->format('d M Y') }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @if ($servicio->tecnico)
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 text-xs font-bold border-2 border-white shadow-sm">
                                            {{ substr($servicio->tecnico->nombres, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span
                                                class="text-slate-700 font-bold text-xs">{{ explode(' ', $servicio->tecnico->nombres)[0] }}</span>
                                            <span class="text-[10px] text-slate-400">Asignado</span>
                                        </div>
                                    </div>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded bg-orange-50 text-orange-600 text-xs border border-orange-100 font-medium">
                                        <i class="fas fa-exclamation-circle"></i> Por Asignar
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right font-mono text-xs text-slate-500">
                                S/ {{ number_format($costoMateriales, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-xs text-slate-500">
                                S/ {{ number_format($manoObra, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-mono font-bold text-slate-800 text-sm">
                                    S/ {{ number_format($total, 2) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }}">
                                    <i class="fas {{ $statusConfig['icon'] }}"></i> {{ $servicio->estado }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('servicios.show', $servicio->id_servicio) }}"
                                    class="text-slate-400 hover:text-yellow-600 transition-colors p-2 rounded-full hover:bg-yellow-50"
                                    title="Ver Detalles">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div
                                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                        <i class="fas fa-clipboard-list text-3xl opacity-30"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-600">Sin Servicios</h3>
                                    <p class="text-sm text-slate-400 max-w-xs mx-auto mt-1">No hay solicitudes registradas
                                        en el sistema actualmente.</p>

                                    <a href="{{ url('/servicios/create') }}"
                                        class="mt-4 text-sm font-bold text-yellow-600 hover:underline">
                                        Crear la primera solicitud
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($servicios->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $servicios->links() }}
            </div>
        @endif
    </div>
@endsection
