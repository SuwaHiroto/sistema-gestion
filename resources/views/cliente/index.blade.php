@extends('layouts.cliente')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Mis Servicios</h1>
            <p class="text-gray-500 mt-1">Consulta el estado de tus reparaciones e instalaciones.</p>
        </div>
        <button onclick="document.getElementById('modalSolicitud').classList.remove('hidden')"
            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition transform hover:-translate-y-1 flex items-center gap-2">
            <i class="fas fa-plus-circle text-lg"></i> Solicitar Nuevo Servicio
        </button>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded shadow-sm flex items-center">
            <i class="fas fa-check-circle mr-2 text-xl"></i>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($servicios as $servicio)
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <span
                            class="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded">#{{ str_pad($servicio->id_servicio, 4, '0', STR_PAD_LEFT) }}</span>
                        @php
                            $estilos = match ($servicio->estado) {
                                'PENDIENTE' => ['bg-red-100', 'text-red-700', '10%'],
                                'APROBADO' => ['bg-blue-100', 'text-blue-800', '50%'],
                                'EN_PROCESO' => ['bg-indigo-100', 'text-indigo-800', '75%'],
                                'FINALIZADO' => ['bg-green-100', 'text-green-800', '100%'],
                                default => ['bg-gray-100', 'text-gray-600', '0%'],
                            };
                        @endphp
                        <span
                            class="{{ $estilos[0] }} {{ $estilos[1] }} px-3 py-1 rounded-full text-xs font-bold">{{ $servicio->estado }}</span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 mb-2 h-14 overflow-hidden">
                        {{ Str::limit($servicio->descripcion_solicitud, 50) }}</h3>
                    <p class="text-xs text-gray-500 mb-4 flex items-center gap-1">
                        <i class="far fa-calendar-alt"></i> {{ $servicio->created_at->format('d M Y, h:i A') }}
                    </p>

                    <!-- Barra de Progreso -->
                    <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                        <div class="bg-secondary h-2 rounded-full transition-all duration-1000"
                            style="width: {{ $estilos[2] }}"></div>
                    </div>
                    <p class="text-xs text-right text-gray-400 font-medium">{{ $estilos[2] }} completado</p>
                </div>

                <!-- Footer de la tarjeta con costos si aplica -->
                @if ($servicio->monto_cotizado > 0)
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-xs text-gray-500 font-bold uppercase">Cotización</span>
                        <span class="text-sm font-bold text-gray-800">S/
                            {{ number_format($servicio->monto_cotizado, 2) }}</span>
                    </div>
                @endif
            </div>
        @empty
            <div
                class="col-span-1 md:col-span-3 text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4 text-gray-400">
                    <i class="fas fa-inbox text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Aún no tienes servicios</h3>
                <p class="text-gray-500 mt-1 mb-6">¿Tienes algún problema eléctrico? Solicita un técnico ahora.</p>
                <button onclick="document.getElementById('modalSolicitud').classList.remove('hidden')"
                    class="text-blue-500 font-bold hover:underline">
                    Crear primera solicitud
                </button>
            </div>
        @endforelse
    </div>

    <!-- MODAL SOLICITUD NUEVO SERVICIO -->
    <div id="modalSolicitud"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all scale-100">
            <div class="bg-primary px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">Nueva Solicitud de Servicio</h3>
                <button onclick="document.getElementById('modalSolicitud').classList.add('hidden')"
                    class="text-gray-300 hover:text-white transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('cliente.servicios.store') }}" method="POST" class="p-6">
                @csrf
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Describe tu problema o necesidad</label>
                    <textarea name="descripcion_solicitud" rows="4"
                        class="w-full border-gray-300 rounded-lg p-3 text-sm focus:ring-secondary focus:border-secondary shadow-sm"
                        placeholder="Ej: Necesito instalar 3 tomacorrientes en la sala y revisar una llave térmica que salta..." required></textarea>
                    <p class="text-xs text-gray-400 mt-2 text-right">Mínimo 5 caracteres</p>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalSolicitud').classList.add('hidden')"
                        class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition">Cancelar</button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-bold shadow-md transition flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
