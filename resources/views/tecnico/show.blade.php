@extends('layouts.tecnico')

@section('content')
    <!-- Botón Volver -->
    <a href="{{ route('tecnico.index') }}" class="inline-flex items-center text-gray-500 mb-4 hover:text-primary">
        <i class="fas fa-arrow-left mr-2"></i> Volver a la lista
    </a>

    <!-- Encabezado del Trabajo -->
    <div class="bg-white rounded-xl shadow-sm p-5 mb-4 border border-gray-200">
        <div class="flex justify-between items-start">
            <h2 class="text-xl font-bold text-gray-800 mb-2">Servicio #{{ $servicio->id_servicio }}</h2>
            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded">{{ $servicio->estado }}</span>
        </div>
        <p class="text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100 text-sm mb-4">
            {{ $servicio->descripcion_solicitud }}
        </p>

        <!-- Datos Cliente -->
        <div class="flex items-center gap-3 border-t border-gray-100 pt-3">
            <div class="bg-secondary w-10 h-10 rounded-full flex items-center justify-center text-white font-bold">
                {{ substr($servicio->cliente->nombres, 0, 1) }}
            </div>
            <div>
                <p class="font-bold text-sm text-gray-800">{{ $servicio->cliente->nombres }}</p>
                <div class="flex gap-4 text-xs text-gray-500">
                    <a href="tel:{{ $servicio->cliente->telefono }}"
                        class="flex items-center gap-1 text-blue-600 font-bold hover:underline">
                        <i class="fas fa-phone"></i> Llamar
                    </a>
                    <span class="flex items-center gap-1">
                        <i class="fas fa-map-pin"></i> {{ Str::limit($servicio->cliente->direccion, 20) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ACCIONES PRINCIPALES (Botones Grandes) -->
    @if ($servicio->estado == 'APROBADO' || $servicio->estado == 'COTIZANDO')
        <form action="{{ route('tecnico.update', $servicio->id_servicio) }}" method="POST" class="mb-6">
            @csrf @method('PUT')
            <input type="hidden" name="estado" value="EN_PROCESO">
            <button type="submit"
                class="w-full bg-green-600 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-green-700 active:scale-95 transition flex flex-col items-center gap-1">
                <i class="fas fa-play text-2xl"></i>
                <span>INICIAR TRABAJO</span>
            </button>
        </form>
    @elseif($servicio->estado == 'EN_PROCESO')
        <form action="{{ route('tecnico.update', $servicio->id_servicio) }}" method="POST" class="mb-6">
            @csrf @method('PUT')
            <input type="hidden" name="estado" value="FINALIZADO">
            <button type="submit"
                class="w-full bg-red-600 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-red-700 active:scale-95 transition flex flex-col items-center gap-1"
                onclick="return confirm('¿Seguro que terminaste el trabajo?')">
                <i class="fas fa-flag-checkered text-2xl"></i>
                <span>FINALIZAR TRABAJO</span>
            </button>
        </form>
    @elseif($servicio->estado == 'FINALIZADO')
        <div class="bg-green-100 text-green-800 p-4 rounded-xl text-center font-bold mb-6 border border-green-200">
            <i class="fas fa-check-circle text-2xl mb-1 block"></i>
            Trabajo Completado
        </div>
    @endif

    <!-- GESTIÓN DE MATERIALES (Solo si está en proceso o finalizado) -->
    @if ($servicio->estado == 'EN_PROCESO' || $servicio->estado == 'FINALIZADO')
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200">
            <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                <i class="fas fa-tools text-gray-400"></i> Materiales Utilizados
            </h3>
            <!-- Lista Actual -->
            @if ($servicio->materiales->count() > 0)
                <ul class="space-y-2 mb-4">
                    @foreach ($servicio->materiales as $mat)
                        <li class="flex justify-between text-sm border-b border-gray-100 pb-1">
                            <div>
                                <span class="text-gray-700 block">{{ $mat->nombre }}</span>
                                <span class="text-xs text-gray-400">Precio unitario: S/
                                    {{ number_format($mat->pivot->precio_unitario, 2) }}</span>
                            </div>
                            <span class="font-bold">x{{ $mat->pivot->cantidad }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-400 italic mb-4">No has registrado materiales.</p>
            @endif

            <!-- Formulario Agregar (Solo si NO ha finalizado) -->
            @if ($servicio->estado != 'FINALIZADO')
                <form action="{{ route('tecnico.update', $servicio->id_servicio) }}" method="POST"
                    class="bg-gray-50 p-3 rounded-lg border border-gray-200 mt-4">
                    @csrf @method('PUT')
                    <input type="hidden" name="agregar_material" value="1">

                    <div class="grid grid-cols-4 gap-2 mb-2">
                        <!-- Columna Material (Ocupa 2 espacios) -->
                        <div class="col-span-2">
                            <select name="id_material" id="selectMaterial"
                                class="w-full text-xs border-gray-300 rounded focus:ring-primary h-10" required
                                onchange="actualizarPrecio()">
                                <option value="" data-precio="0">+ Material</option>
                                @foreach ($materialesDisponibles as $m)
                                    <option value="{{ $m->id_material }}" data-precio="{{ $m->precio_referencial }}">
                                        {{ $m->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Columna Cantidad -->
                        <input type="number" name="cantidad" placeholder="Cant."
                            class="w-full text-xs border-gray-300 rounded text-center h-10" min="0.1" step="0.1"
                            required>

                        <!-- Columna Precio (NUEVO) -->
                        <input type="number" name="precio" id="inputPrecio" placeholder="S/ Unit."
                            class="w-full text-xs border-gray-300 rounded text-center h-10" min="0" step="0.1"
                            required>
                    </div>

                    <button
                        class="w-full bg-primary text-white text-xs font-bold py-2 rounded hover:bg-gray-800 transition shadow-sm">
                        AGREGAR ÍTEM
                    </button>
                </form>

                <!-- Script para autocompletar precio -->
                <script>
                    function actualizarPrecio() {
                        const select = document.getElementById('selectMaterial');
                        const precio = select.options[select.selectedIndex].getAttribute('data-precio');
                        document.getElementById('inputPrecio').value = precio;
                    }
                </script>
            @endif
        </div>
    @endif
    <!-- SECCIÓN DE PAGOS / COBROS -->
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200 mt-6">
        <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
            <i class="fas fa-wallet text-green-500"></i> Pagos y Cobros
        </h3>

        <!-- Resumen Pagado -->
        @php
            $pagado = $servicio->pagos->sum('monto');
            $costoTotal = $servicio->costo_final_real > 0 ? $servicio->costo_final_real : $servicio->monto_cotizado;
            $saldoPendiente = max(0, $costoTotal - $pagado);
        @endphp

        <div class="mb-4 grid grid-cols-2 gap-2 text-sm">
            <div class="bg-green-50 p-3 rounded-lg border border-green-100">
                <span class="text-green-800 block text-xs uppercase">Pagado</span>
                <span class="font-bold text-green-700 text-lg">S/ {{ number_format($pagado, 2) }}</span>
            </div>
            <div class="bg-red-50 p-3 rounded-lg border border-red-100">
                <span class="text-red-800 block text-xs uppercase">Pendiente</span>
                <span class="font-bold text-red-700 text-lg" id="saldoDisplay">S/
                    {{ number_format($saldoPendiente, 2) }}</span>
            </div>
        </div>

        <!-- Lista de Pagos -->
        @if ($servicio->pagos->count() > 0)
            <ul class="space-y-2 mb-4 max-h-40 overflow-y-auto">
                @foreach ($servicio->pagos as $pago)
                    <li class="flex justify-between text-sm border-b border-gray-100 pb-1 items-center">
                        <div>
                            <span class="text-gray-700 font-medium block">S/ {{ number_format($pago->monto, 2) }}</span>
                            <span class="text-[10px] text-gray-400">{{ $pago->tipo }} -
                                {{ $pago->created_at->format('d/m H:i') }}</span>
                        </div>
                        @if ($pago->validado)
                            <span class="text-green-500 text-xs"><i class="fas fa-check-circle"></i> OK</span>
                        @else
                            <span class="text-yellow-500 text-xs"><i class="fas fa-clock"></i> Pendiente</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        <!-- Formulario Nuevo Cobro -->
        @if ($servicio->estado != 'FINALIZADO' && $saldoPendiente > 0)
            <form action="{{ route('tecnico.pago.store', $servicio->id_servicio) }}" method="POST"
                class="bg-gray-50 p-3 rounded-lg border border-gray-200" onsubmit="return validarPago()">
                @csrf
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase">Registrar Nuevo Cobro</label>

                <div class="grid grid-cols-2 gap-2 mb-2">
                    <input type="number" step="0.01" name="monto" id="inputMontoPago" placeholder="Monto S/"
                        class="w-full text-sm border-gray-300 rounded focus:ring-green-500 focus:border-green-500"
                        max="{{ $saldoPendiente }}" required>

                    <select name="tipo"
                        class="w-full text-sm border-gray-300 rounded focus:ring-green-500 focus:border-green-500">
                        <option value="Efectivo">Efectivo</option>
                        <option value="Transferencia">Transferencia</option>
                        <option value="Yape/Plin">Yape/Plin</option>
                    </select>
                </div>

                <p id="errorMonto" class="text-red-500 text-xs mb-2 hidden">El monto excede el saldo pendiente.</p>

                <button
                    class="w-full bg-green-600 text-white text-xs font-bold py-3 rounded-lg hover:bg-green-700 shadow-sm flex items-center justify-center gap-2">
                    <i class="fas fa-money-bill-wave"></i> REGISTRAR COBRO
                </button>
            </form>
        @elseif($saldoPendiente <= 0)
            <div class="bg-green-100 text-green-800 p-3 rounded text-center text-sm font-bold border border-green-200">
                <i class="fas fa-check-double"></i> ¡Servicio Pagado en su Totalidad!
            </div>
        @endif
    </div>

    <script>
        function validarPago() {
            // Fix: Wrap Blade variable in quotes to ensure it's a valid JS string, then parse float.
            // Using number_format in PHP to ensure a clean number format (no commas for thousands) is safer for JS parsing.
            const saldo = parseFloat("{{ number_format($saldoPendiente, 2, '.', '') }}");
            const montoInput = document.getElementById('inputMontoPago');
            const monto = parseFloat(montoInput.value);
            const errorMsg = document.getElementById('errorMonto');

            if (isNaN(monto)) {
                return false; // Prevent submission if amount is invalid
            }

            if (monto > saldo) {
                errorMsg.classList.remove('hidden');
                // Optional: Add visual feedback to input
                montoInput.classList.add('border-red-500');
                return false; // Detiene el envío
            }

            errorMsg.classList.add('hidden');
            montoInput.classList.remove('border-red-500');
            return true; // Permite el envío
        }
    </script>
@endsection
