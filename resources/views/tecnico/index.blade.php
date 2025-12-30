@extends('layouts.tecnico')

@section('content')
    <div class="flex justify-between items-end mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Mis Asignaciones</h2>
            <p class="text-slate-500 text-sm">
                <i class="far fa-calendar-alt mr-1"></i> {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}
            </p>
        </div>
        <div class="bg-slate-900 text-yellow-400 font-bold px-3 py-1 rounded-lg text-xs shadow-sm">
            {{ $servicios->count() }} Tareas
        </div>
    </div>

    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 text-sm shadow-sm animate-pulse">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
        </div>
    @endif

    <div class="space-y-5">
        @forelse($servicios as $servicio)
            @php
                $isWorking = $servicio->estado == 'EN_PROCESO';
                // Estilos dinámicos según estado
                $cardClasses = $isWorking
                    ? 'border-yellow-400 ring-1 ring-yellow-400/50 shadow-yellow-100'
                    : 'border-slate-200 hover:border-slate-300';
                $bgClass = $isWorking ? 'bg-yellow-50/30' : 'bg-white';
            @endphp

            <a href="{{ route('tecnico.show', $servicio->id_servicio) }}"
                class="block relative rounded-2xl shadow-sm border-l-4 {{ $cardClasses }} {{ $bgClass }} p-5 transition-all active:scale-[0.98]">

                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center gap-2">
                        <span
                            class="font-mono text-xs font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                            #{{ str_pad($servicio->id_servicio, 4, '0', STR_PAD_LEFT) }}
                        </span>
                        @if ($isWorking)
                            <span class="flex h-2 w-2 relative">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                            </span>
                        @endif
                    </div>

                    @if ($isWorking)
                        <span
                            class="bg-yellow-100 text-yellow-700 text-[10px] font-black px-2.5 py-1 rounded uppercase tracking-wide border border-yellow-200 flex items-center gap-1">
                            <i class="fas fa-tools animate-spin-slow"></i> En Ejecución
                        </span>
                    @else
                        @php
                            $badgeColor = match ($servicio->estado) {
                                'PENDIENTE' => 'bg-slate-100 text-slate-600 border-slate-200',
                                'APROBADO' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                'FINALIZADO' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span
                            class="{{ $badgeColor }} text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wide border">
                            {{ $servicio->estado }}
                        </span>
                    @endif
                </div>

                <h3 class="font-bold text-slate-800 text-lg leading-tight mb-2">
                    {{ Str::limit($servicio->descripcion_solicitud, 60) }}
                </h3>

                <div class="flex items-start gap-3 mb-4 p-3 bg-white/60 rounded-lg border border-slate-100/50">
                    <div class="mt-0.5 text-red-500 bg-red-50 p-1.5 rounded-full">
                        <i class="fas fa-map-marker-alt text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase">Ubicación</p>
                        <p class="text-sm text-slate-700 font-medium leading-snug">
                            {{ Str::limit($servicio->cliente->direccion ?? 'Sin dirección', 45) }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-3 border-t border-slate-100/50">
                    <div class="text-xs text-slate-500 font-medium flex items-center gap-1.5">
                        <i class="far fa-clock text-slate-400"></i>
                        @if ($servicio->fecha_inicio)
                            {{ $servicio->fecha_inicio->format('h:i A') }}
                        @else
                            <span class="italic">--:--</span>
                        @endif
                    </div>

                    <div
                        class="flex items-center gap-1 text-sm font-bold {{ $isWorking ? 'text-yellow-600' : 'text-blue-600' }}">
                        <span>Gestionar</span>
                        <i class="fas fa-chevron-right text-xs"></i>
                    </div>
                </div>
            </a>
        @empty
            <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                <div
                    class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4 text-slate-300 shadow-inner">
                    <i class="fas fa-clipboard-check text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700">¡Todo al día!</h3>
                <p class="text-slate-500 text-sm max-w-xs mx-auto mt-1">
                    No tienes servicios asignados pendientes en este momento.
                </p>
                <button onclick="window.location.reload()"
                    class="mt-6 text-sm font-bold text-slate-600 hover:text-slate-900 flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i> Actualizar lista
                </button>
            </div>
        @endforelse
    </div>
@endsection
