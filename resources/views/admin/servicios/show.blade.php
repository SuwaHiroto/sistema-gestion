@extends('layouts.admin')

@section('content')

    <div class="mb-6">
        <a href="{{ route('servicios.index') }}"
            class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-800 font-medium transition group">
            <div
                class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-slate-400 transition">
                <i class="fas fa-arrow-left text-xs"></i>
            </div>
            <span>Volver al listado</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden mb-8">
        <div
            class="bg-slate-900 px-8 py-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 text-white opacity-5">
                <i class="fas fa-bolt text-9xl"></i>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-1">
                    <span
                        class="bg-yellow-400 text-slate-900 text-xs font-bold px-2 py-0.5 rounded uppercase tracking-wider">
                        Servicio #{{ str_pad($servicio->id_servicio, 4, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="text-slate-400 text-sm">|</span>
                    <span class="text-slate-300 text-sm flex items-center gap-2">
                        <i class="far fa-calendar-alt"></i> {{ $servicio->created_at->format('d M Y, h:i A') }}
                    </span>
                </div>
                <h1 class="text-white text-2xl md:text-3xl font-bold tracking-tight">Expediente de Servicio</h1>
            </div>

            @php
                $statusStyles = match ($servicio->estado) {
                    'PENDIENTE' => 'bg-slate-700 text-slate-200 border-slate-600',
                    'APROBADO' => 'bg-indigo-600 text-white border-indigo-500',
                    'EN_PROCESO' => 'bg-yellow-500 text-slate-900 border-yellow-400',
                    'FINALIZADO' => 'bg-emerald-600 text-white border-emerald-500',
                    'CANCELADO' => 'bg-red-600 text-white border-red-500',
                    default => 'bg-slate-700 text-white border-slate-600',
                };
            @endphp
            <div class="relative z-10 px-5 py-2 rounded-xl border {{ $statusStyles }} shadow-lg flex items-center gap-3">
                <div class="p-1.5 bg-white/20 rounded-full backdrop-blur-sm">
                    <i class="fas fa-info-circle text-sm"></i>
                </div>
                <div>
                    <p class="text-[10px] opacity-80 uppercase font-bold tracking-wider">Estado Actual</p>
                    <p class="text-lg font-bold leading-none">{{ $servicio->estado }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-700">Información del Cliente</h3>
                    <a href="{{ route('servicios.edit', $servicio->id_servicio) }}"
                        class="text-xs font-bold text-blue-600 hover:underline">
                        <i class="fas fa-pen mr-1"></i> Editar Datos
                    </a>
                </div>
                <div class="p-6">
                    <div class="flex items-start gap-5">
                        <div
                            class="w-14 h-14 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 text-2xl border-2 border-white shadow-sm">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-slate-800">
                                {{ $servicio->cliente->nombres ?? 'Cliente Eliminado' }}</h4>
                            <div class="flex flex-wrap gap-4 mt-2 text-sm text-slate-500">
                                <span class="flex items-center gap-1.5"><i class="fas fa-phone text-slate-300"></i>
                                    {{ $servicio->cliente->telefono ?? 'N/A' }}</span>
                                <span class="flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-slate-300"></i>
                                    {{ $servicio->cliente->direccion ?? 'Sin dirección' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 bg-yellow-50/50 rounded-xl p-5 border border-yellow-100 relative">
                        <i class="fas fa-quote-left text-yellow-200 absolute top-4 left-4 text-2xl"></i>
                        <div class="relative z-10 pl-6">
                            <p class="text-xs font-bold text-yellow-600 uppercase mb-1">Descripción del Problema</p>
                            <p class="text-slate-700 italic leading-relaxed">"{{ $servicio->descripcion_solicitud }}"</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-700">Bitácora de Actividad</h3>
                </div>
                <div class="p-6">
                    <div class="relative pl-4 border-l-2 border-slate-100 space-y-8 ml-2">
                        @forelse($servicio->historial->sortByDesc('fecha_cambio') as $index => $h)
                            <div class="relative group">
                                <span
                                    class="absolute -left-[23px] top-1 flex items-center justify-center w-4 h-4 bg-white rounded-full border-2 {{ $index === 0 ? 'border-yellow-400' : 'border-slate-300' }}">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full {{ $index === 0 ? 'bg-yellow-400' : 'bg-slate-300' }}"></span>
                                </span>
                                <div class="ml-4">
                                    <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between mb-1">
                                        <h4 class="text-sm font-bold text-slate-800">
                                            Estado: <span
                                                class="{{ $index === 0 ? 'text-yellow-600' : 'text-slate-500' }}">{{ $h->estado_nuevo }}</span>
                                        </h4>
                                        <time class="text-xs text-slate-400 font-mono">
                                            {{ $h->fecha_cambio ? $h->fecha_cambio->format('d/m/Y H:i') : '--' }}
                                        </time>
                                    </div>
                                    @if ($h->comentario)
                                        <div
                                            class="text-sm text-slate-600 bg-slate-50 p-3 rounded-lg border border-slate-100 mt-1 inline-block">
                                            {{ $h->comentario }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400 italic">No hay historial registrado.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
                <div class="h-1.5 w-full bg-slate-900"></div>
                <div class="p-6">
                    <h3 class="text-xs uppercase font-bold text-slate-400 mb-4 tracking-wider">Personal Técnico</h3>
                    @if ($servicio->tecnico)
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="w-12 h-12 rounded-full bg-slate-900 text-yellow-400 flex items-center justify-center font-bold border-2 border-white shadow-md">
                                {{ substr($servicio->tecnico->nombres, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $servicio->tecnico->nombres }}</p>
                                <p class="text-xs text-slate-500">{{ $servicio->tecnico->especialidad }}</p>
                                <span
                                    class="inline-block mt-1 px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded-full uppercase">Asignado</span>
                            </div>
                        </div>
                    @else
                        <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 text-center mb-4">
                            <div
                                class="w-10 h-10 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-user-slash"></i>
                            </div>
                            <p class="text-sm font-bold text-orange-800">Sin Técnico Asignado</p>
                        </div>
                    @endif

                    @if ($servicio->estado != 'FINALIZADO' && $servicio->estado != 'CANCELADO')
                        <form action="{{ route('servicios.update', $servicio->id_servicio) }}" method="POST"
                            class="mt-4">
                            @csrf @method('PUT')
                            <label class="text-xs font-bold text-slate-600 block mb-2">Cambiar / Asignar Técnico</label>
                            <div class="flex gap-2">
                                <select name="id_tecnico"
                                    class="w-full bg-slate-50 border border-slate-300 text-xs rounded-lg px-2 py-2 focus:ring-2 focus:ring-yellow-400 outline-none">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($tecnicos as $tec)
                                        <option value="{{ $tec->id_tecnico }}"
                                            {{ $servicio->id_tecnico == $tec->id_tecnico ? 'selected' : '' }}>
                                            {{ $tec->nombres }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                    class="bg-slate-900 text-white px-3 py-2 rounded-lg hover:bg-slate-700 transition">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-700 text-sm">Resumen de Costos</h3>
                    <i class="fas fa-wallet text-slate-300"></i>
                </div>
                <div class="p-6">
                    @php
                        // 1. Costo Materiales
                        $costoMateriales = $servicio->materiales->sum(
                            fn($m) => $m->pivot->precio_unitario * $m->pivot->cantidad,
                        );

                        // 2. Mano de Obra
                        $manoObra = $servicio->mano_obra ?? 0;

                        // 3. Total Real o Estimado
                        if ($servicio->estado === 'FINALIZADO' && $servicio->costo_final_real > 0) {
                            $total = $servicio->costo_final_real;
                        } else {
                            $total = $costoMateriales + $manoObra;
                        }
                    @endphp

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-slate-500">
                            <span>Materiales ({{ $servicio->materiales->count() }})</span>
                            <span>S/ {{ number_format($costoMateriales, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Mano de Obra</span>
                            <span>S/ {{ number_format($manoObra, 2) }}</span>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex justify-between items-end">
                            <span class="font-bold text-slate-800">Total General</span>
                            <span class="text-xl font-black text-slate-900">S/ {{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    @if ($servicio->estado != 'FINALIZADO')
                        <a href="{{ route('servicios.edit', $servicio->id_servicio) }}"
                            class="mt-4 w-full block text-center bg-white border-2 border-slate-100 hover:border-yellow-400 text-slate-600 hover:text-slate-900 font-bold py-2 rounded-lg transition text-xs uppercase tracking-wide">
                            Ajustar Costos
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
