@extends('layouts.tecnico')

@section('content')
    <div class="mb-4">
        <h2 class="text-xl font-bold text-gray-800">Trabajos Asignados</h2>
        <p class="text-sm text-gray-500">{{ now()->format('d M, Y') }}</p>
    </div>

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">{{ session('error') }}</div>
    @endif

    <div class="space-y-4">
        @forelse($servicios as $servicio)
            <!-- Tarjeta de Trabajo -->
            <a href="{{ route('tecnico.show', $servicio->id_servicio) }}"
                class="block bg-white rounded-xl shadow-sm border-l-4 {{ $servicio->estado == 'EN_PROCESO' ? 'border-green-500 ring-2 ring-green-100' : 'border-blue-500' }} p-4 hover:shadow-md transition active:scale-95">
                <div class="flex justify-between items-start mb-2">
                    <span
                        class="text-xs font-bold text-gray-400">#{{ str_pad($servicio->id_servicio, 4, '0', STR_PAD_LEFT) }}</span>
                    @if ($servicio->estado == 'EN_PROCESO')
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-bold animate-pulse">EN
                            CURSO</span>
                    @else
                        <span
                            class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-bold">{{ $servicio->estado }}</span>
                    @endif
                </div>

                <h3 class="font-bold text-gray-800 text-lg mb-1">{{ Str::limit($servicio->descripcion_solicitud, 40) }}</h3>

                <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                    <i class="fas fa-map-marker-alt text-red-500"></i>
                    <span>{{ Str::limit($servicio->cliente->direccion, 30) }}</span>
                </div>

                <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                    <div class="text-xs text-gray-500">
                        <i class="far fa-calendar text-gray-400 mr-1"></i>
                        {{ $servicio->fecha_inicio ? $servicio->fecha_inicio->format('H:i A') : 'Sin hora' }}
                    </div>
                    <div class="text-blue-600 font-bold text-sm flex items-center gap-1">
                        Ver Detalles <i class="fas fa-chevron-right text-xs"></i>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-10">
                <div class="bg-gray-200 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <i class="fas fa-check-double text-3xl"></i>
                </div>
                <h3 class="text-gray-500 font-medium">¡Todo listo!</h3>
                <p class="text-gray-400 text-sm">No tienes trabajos pendientes por ahora.</p>
            </div>
        @endforelse
    </div>
@endsection
