@extends('layouts.admin')

@section('content')

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestión de Pagos </h2>
            <p class="text-sm text-gray-500">Registro de cobros y validación de pagos.</p>
        </div>
        <!-- TARJETA DE TOTAL RECAUDADO -->
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-bold shadow-sm">
            Total Ingresos: S/ {{ number_format($pagos->where('validado', true)->sum('monto'), 2) }}
        </div>
    </div>

    <!-- MENSAJES DE ALERTA -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            <p><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <p class="font-bold">Error al registrar el pago:</p>
            <ul class="list-disc ml-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- COLUMNA 1: FORMULARIO DE REGISTRO DE PAGO (1/3) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 sticky top-6">
                <div class="bg-green-50 px-6 py-4 border-b border-green-100">
                    <h3 class="font-bold text-green-800 flex items-center gap-2">
                        <i class="fas fa-cash-register"></i> Registrar Cobro
                    </h3>
                </div>

                <form action="{{ route('pagos.store') }}" method="POST" class="p-6">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Servicio Asociado</label>
                        <select name="id_servicio"
                            class="w-full bg-white border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-green-500 focus:border-green-500"
                            required>
                            <option value="">-- Seleccionar Servicio --</option>
                            <!-- Listamos servicios pendientes de pago o activos -->
                            @foreach ($serviciosPendientes as $servicio)
                                <option value="{{ $servicio->id_servicio }}">
                                    #{{ $servicio->id_servicio }} - {{ $servicio->cliente->nombres }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Solo muestra servicios activos.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Monto (S/)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">S/</span>
                            </div>
                            <input type="number" name="monto" step="0.01" min="0"
                                class="pl-8 w-full bg-white border border-gray-300 rounded-lg p-2.5 text-sm font-bold text-gray-800 focus:ring-green-500 focus:border-green-500"
                                placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Método de Pago</label>
                        <select name="tipo" class="w-full bg-white border border-gray-300 rounded-lg p-2.5 text-sm">
                            <option value="Efectivo">Efectivo</option>
                            <option value="Yape/Plin">Yape/Plin</option>
                            <option value="Materiales">Compra de Materiales</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg shadow transition flex justify-center items-center gap-2">
                        <i class="fas fa-save"></i> Registrar Ingreso
                    </button>
                </form>
            </div>
        </div>

        <!-- COLUMNA 2: HISTORIAL DE PAGOS (2/3) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700">Historial de Transacciones</h3>
                    <span class="text-xs bg-white border px-2 py-1 rounded text-gray-500">Últimos 20 registros</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3">Fecha</th>
                                <th class="px-6 py-3">Servicio</th>
                                <th class="px-6 py-3">Registrado Por</th>
                                <th class="px-6 py-3">Tipo</th>
                                <th class="px-6 py-3 text-right">Monto</th>
                                <th class="px-6 py-3 text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pagos as $pago)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-xs">
                                        {{ $pago->created_at->format('d/m/Y') }}
                                        <span class="block text-gray-400">{{ $pago->created_at->format('H:i') }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-800">
                                        <a href="{{ route('servicios.show', $pago->id_servicio) }}"
                                            class="hover:text-blue-600 hover:underline">
                                            #{{ $pago->id_servicio }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        {{ $pago->registradoPor->email ?? 'Sistema' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs border">
                                            {{ $pago->tipo }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-green-600">
                                        S/ {{ number_format($pago->monto, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($pago->validado)
                                            <span
                                                class="text-green-500 bg-green-50 px-2 py-1 rounded-full text-xs font-bold border border-green-100 inline-flex items-center gap-1">
                                                <i class="fas fa-check"></i> Validado
                                            </span>
                                        @else
                                            <!-- Botón para validar -->
                                            <form action="{{ route('pagos.validar', $pago->id_pago) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="bg-yellow-100 hover:bg-green-100 text-yellow-700 hover:text-green-700 border border-yellow-200 hover:border-green-200 px-3 py-1 rounded-full text-xs font-bold transition flex items-center gap-1 mx-auto"
                                                    title="Clic para validar este pago"
                                                    onclick="return confirm('¿Confirmar que el dinero ha sido recibido en caja?')">
                                                    <i class="fas fa-check-circle"></i> Validar
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <p>No hay pagos registrados.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100">
                    {{ $pagos->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
