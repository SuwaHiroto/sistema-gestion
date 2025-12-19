@extends('layouts.admin')

@section('content')

    <!-- Navegación Superior -->
    <div class="flex items-center justify-between mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="{{ url('/servicios') }}"
                        class="text-gray-500 hover:text-primary transition font-medium">Servicios</a></li>
                <li><span class="text-gray-300">/</span></li>
                <li class="text-gray-800 font-bold">Servicio #{{ str_pad($servicio->id_servicio, 4, '0', STR_PAD_LEFT) }}
                </li>
            </ol>
        </nav>

        <!-- Botón Editar (Solo datos básicos) -->
        <a href="{{ url('/servicios/' . $servicio->id_servicio . '/edit') }}"
            class="text-gray-500 hover:text-blue-600 transition text-sm font-medium">
            <i class="fas fa-edit mr-1"></i> Editar Datos
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- COLUMNA IZQUIERDA (2/3): Información Principal -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Tarjeta de Detalle del Problema -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Solicitud del Cliente</h3>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mt-1">Registrado el
                            {{ $servicio->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <!-- Badge Estado Grande -->
                    @php
                        $claseEstado = match ($servicio->estado) {
                            'FINALIZADO' => 'bg-green-100 text-green-700 border-green-200',
                            'PENDIENTE' => 'bg-red-100 text-red-700 border-red-200',
                            default => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        };
                    @endphp
                    <span class="{{ $claseEstado }} px-4 py-2 rounded-lg text-sm font-bold border">
                        {{ $servicio->estado }}
                    </span>
                </div>

                <!-- Descripción -->
                <div class="bg-gray-50 p-5 rounded-lg border border-gray-100 text-gray-700 leading-relaxed mb-6 relative">
                    <i class="fas fa-quote-left text-gray-300 absolute top-2 left-2 text-xl"></i>
                    <p class="ml-4">{{ $servicio->descripcion_solicitud }}</p>
                </div>

                <!-- Datos del Cliente -->
                <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                    <div
                        class="w-12 h-12 rounded-full bg-gradient-to-br from-secondary to-yellow-400 flex items-center justify-center text-white font-bold text-xl shadow-sm">
                        {{ substr($servicio->cliente->nombres ?? '?', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Información de Contacto</p>
                        <p class="text-lg font-bold text-gray-800 leading-none">
                            {{ $servicio->cliente->nombres ?? 'Cliente desconocido' }}</p>
                        <div class="flex items-center gap-3 mt-1">
                            <a href="tel:{{ $servicio->cliente->telefono }}" class="text-sm text-blue-600 hover:underline">
                                <i class="fas fa-phone-alt mr-1"></i> {{ $servicio->cliente->telefono ?? 'N/A' }}
                            </a>
                            <span class="text-gray-300">|</span>
                            <span class="text-sm text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $servicio->cliente->direccion ?? 'Sin dirección' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Línea de Tiempo (Historial) -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-history text-gray-400 mr-2"></i> Historial de Actividad
                </h3>

                <div class="relative pl-4 border-l-2 border-gray-200 space-y-8">
                    @forelse($servicio->historial as $h)
                        <div class="relative">
                            <!-- Punto del timeline -->
                            <span
                                class="absolute -left-[21px] top-1 flex items-center justify-center w-8 h-8 bg-white rounded-full border-2 border-blue-100">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            </span>

                            <div class="ml-4">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-1">
                                    <h4 class="text-sm font-bold text-gray-800">
                                        Cambio a estado: <span class="text-primary">{{ $h->estado_nuevo }}</span>
                                    </h4>
                                    <time
                                        class="text-xs text-gray-400">{{ $h->fecha_cambio ? $h->fecha_cambio->format('d M Y - h:i A') : 'Fecha nula' }}</time>
                                </div>
                                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded border border-gray-100 mt-1">
                                    {{ $h->comentario }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Modificado por: <span
                                        class="font-medium text-gray-500">{{ $h->responsable->email ?? 'Sistema' }}</span>
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">No hay historial registrado.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA (1/3): Acciones y Resumen -->
        <div class="space-y-6">

            <!-- Panel de Asignación (Lo más importante para el admin) -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-purple-500"></div>
                <h3 class="text-sm uppercase font-bold text-gray-500 mb-4">Asignación de Técnico</h3>

                <form action="{{ route('servicios.update', $servicio->id_servicio) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        @if ($servicio->estado == 'FINALIZADO')
                            <div class="p-3 bg-green-50 border border-green-100 rounded-lg flex items-center gap-3">
                                <div
                                    class="bg-green-200 text-green-700 w-8 h-8 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-green-600 font-bold uppercase">Servicio Finalizado</p>
                                    <p class="text-sm font-bold text-gray-800">
                                        {{ $servicio->tecnico->nombres ?? 'Técnico' }}</p>
                                </div>
                            </div>
                        @else
                            <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Técnico
                                Disponible</label>
                            <div class="relative">
                                <select name="id_tecnico"
                                    class="w-full bg-white border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 shadow-sm">
                                    <option value="">-- Sin Asignar --</option>
                                    @foreach ($tecnicos as $tec)
                                        <option value="{{ $tec->id_tecnico }}"
                                            {{ $servicio->id_tecnico == $tec->id_tecnico ? 'selected' : '' }}>
                                            {{ $tec->nombres }} — {{ $tec->especialidad }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit"
                                class="mt-4 w-full bg-primary hover:bg-gray-800 text-white font-bold py-2.5 px-4 rounded-lg shadow transition flex justify-center items-center gap-2">
                                <i class="fas fa-save"></i> Guardar Asignación
                            </button>
                        @endif
                    </div>
                </form>

                <!-- Acciones Rápidas de Estado (Solo si ya tiene técnico) -->
                @if ($servicio->id_tecnico && $servicio->estado != 'FINALIZADO')
                    <div class="border-t pt-4 mt-4">
                        <p class="text-xs font-bold text-gray-400 mb-2 uppercase">Cambio rápido de estado</p>
                        <div class="grid grid-cols-2 gap-2">
                            <form action="{{ route('servicios.update', $servicio->id_servicio) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="estado" value="APROBADO">
                                <button
                                    class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold py-2 rounded border border-blue-200">
                                    Aprobar
                                </button>
                            </form>
                            <form action="{{ route('servicios.update', $servicio->id_servicio) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="estado" value="FINALIZADO">
                                <button
                                    class="w-full bg-green-50 hover:bg-green-100 text-green-700 text-xs font-bold py-2 rounded border border-green-200">
                                    Finalizar
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Resumen de Materiales -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
                    <h3 class="text-sm uppercase font-bold text-gray-500">Materiales Usados</h3>
                    <span
                        class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded-full">{{ $servicio->materiales->count() }}</span>
                </div>

                @if ($servicio->materiales->count() > 0)
                    <ul class="space-y-3">
                        @foreach ($servicio->materiales as $mat)
                            <li class="flex justify-between text-sm group">
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-box text-gray-300 mt-1"></i>
                                    <div>
                                        <p class="font-medium text-gray-700">{{ $mat->nombre }}</p>
                                        <p class="text-xs text-gray-400">x{{ $mat->pivot->cantidad }} {{ $mat->unidad }}
                                        </p>
                                    </div>
                                </div>
                                <span class="font-bold text-gray-600">S/
                                    {{ number_format($mat->pivot->precio_unitario * $mat->pivot->cantidad, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-500">Total Materiales</span>
                        <span class="text-lg font-bold text-primary">S/
                            {{ number_format($servicio->materiales->sum(fn($m) => $m->pivot->precio_unitario * $m->pivot->cantidad), 2) }}</span>
                    </div>
                @else
                    <div class="text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                        <i class="fas fa-box-open text-gray-300 text-2xl mb-2"></i>
                        <p class="text-xs text-gray-500">No se han registrado materiales aún.</p>
                        <p class="text-[10px] text-gray-400 mt-1">El técnico debe agregarlos desde su App.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
