@extends('layouts.admin')

@section('content')
    <!-- Encabezado con Botón Crear -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestión de Servicios</h2>
            <p class="text-sm text-gray-500">Administra las solicitudes, asignaciones y costos.</p>
        </div>
        <a href="{{ url('/servicios/create') }}"
            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Servicio
        </a>
    </div>

    <!-- Alertas de Éxito -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r shadow-sm" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Tabla Principal -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 font-bold">ID</th>
                        <th class="px-6 py-4 font-bold">Cliente / Solicitud</th>
                        <th class="px-6 py-4 font-bold">Técnico</th>
                        <th class="px-6 py-4 font-bold text-right">Materiales</th>
                        <th class="px-6 py-4 font-bold text-right">M. Obra</th>
                        <th class="px-6 py-4 font-bold text-right">Total</th>
                        <th class="px-6 py-4 font-bold text-center">Estado</th>
                        <th class="px-6 py-4 font-bold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($servicios as $servicio)
                        @php
                            // Cálculo de Costos
                            // 1. Costo de Materiales (Suma de cantidad * precio unitario de la tabla pivote)
                            $costoMateriales = $servicio->materiales->sum(function ($material) {
                                return $material->pivot->cantidad * $material->pivot->precio_unitario;
                            });

                            // 2. Total (Usamos costo_final_real si está finalizado, sino el cotizado)
                            $total =
                                $servicio->costo_final_real > 0
                                    ? $servicio->costo_final_real
                                    : $servicio->monto_cotizado;

                            // 3. Mano de Obra (Diferencia)
                            $manoObra = max(0, $total - $costoMateriales);
                        @endphp

                        <tr class="bg-white hover:bg-gray-50 transition duration-150">
                            <!-- ID -->
                            <td class="px-6 py-4 font-bold text-primary whitespace-nowrap">
                                #{{ str_pad($servicio->id_servicio, 4, '0', STR_PAD_LEFT) }}
                            </td>

                            <!-- Cliente y Descripción -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">
                                    {{ $servicio->cliente->nombres ?? 'Cliente Eliminado' }}</div>
                                <div class="text-xs text-gray-500 truncate w-48"
                                    title="{{ $servicio->descripcion_solicitud }}">
                                    {{ Str::limit($servicio->descripcion_solicitud, 40) }}
                                </div>
                                <div class="text-[10px] text-gray-400 mt-1">{{ $servicio->created_at->format('d/m/Y') }}
                                </div>
                            </td>

                            <!-- Técnico -->
                            <td class="px-6 py-4">
                                @if ($servicio->tecnico)
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xs font-bold border border-blue-200">
                                            {{ substr($servicio->tecnico->nombres, 0, 1) }}
                                        </div>
                                        <span
                                            class="text-gray-700 font-medium text-xs">{{ explode(' ', $servicio->tecnico->nombres)[0] }}</span>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 text-red-400 text-xs italic">
                                        <i class="fas fa-user-slash"></i> Pendiente
                                    </span>
                                @endif
                            </td>

                            <!-- COSTOS (Nuevas Columnas) -->
                            <td class="px-6 py-4 text-right text-gray-500">
                                <span class="text-xs">S/</span> {{ number_format($costoMateriales, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right text-gray-500">
                                <span class="text-xs">S/</span> {{ number_format($manoObra, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-800">
                                <span class="text-xs text-gray-400">S/</span> {{ number_format($total, 2) }}
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4 text-center">
                                @php
                                    $estados = [
                                        'PENDIENTE' => 'bg-red-100 text-red-800 border-red-200',
                                        'APROBADO' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'EN_PROCESO' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                        'FINALIZADO' => 'bg-green-100 text-green-800 border-green-200',
                                        'CANCELADO' => 'bg-gray-100 text-gray-800 border-gray-200',
                                    ];
                                    $clase = $estados[$servicio->estado] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span
                                    class="{{ $clase }} border px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase">
                                    {{ $servicio->estado }}
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <a href="{{ route('servicios.show', $servicio->id_servicio) }}"
                                    class="group text-blue-600 hover:text-blue-900 font-medium text-xs border border-blue-200 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded transition inline-flex items-center">
                                    Gestionar <i
                                        class="fas fa-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-folder-open text-5xl mb-4 text-gray-200"></i>
                                    <p class="text-lg font-medium text-gray-500">No hay servicios registrados</p>
                                    <p class="text-sm">Registra una nueva solicitud para comenzar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if ($servicios->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $servicios->links() }}
            </div>
        @endif
    </div>
@endsection
