@extends('layouts.tecnico')

@section('content')
    <div class="mb-5">
        <a href="{{ route('tecnico.index') }}"
            class="inline-flex items-center gap-2 text-slate-500 font-bold active:text-slate-800 transition">
            <div class="bg-white p-2 rounded-full shadow-sm border border-slate-200">
                <i class="fas fa-arrow-left"></i>
            </div>
            <span>Volver a la lista</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="bg-slate-900 p-5 flex justify-between items-start">
            <div>
                <span class="text-yellow-400 font-mono text-xs font-bold uppercase tracking-widest">Orden de Trabajo</span>
                <h1 class="text-2xl font-bold text-white leading-none mt-1">
                    #{{ str_pad($servicio->id_servicio, 4, '0', STR_PAD_LEFT) }}</h1>
            </div>
            @php
                $estadoClass = match ($servicio->estado) {
                    'EN_PROCESO' => 'bg-yellow-400 text-slate-900 animate-pulse',
                    'FINALIZADO' => 'bg-emerald-500 text-white',
                    default => 'bg-slate-700 text-slate-300',
                };
                $estadoIcon = match ($servicio->estado) {
                    'EN_PROCESO' => 'fa-cog fa-spin',
                    'FINALIZADO' => 'fa-check',
                    default => 'fa-clock',
                };
            @endphp
            <span
                class="{{ $estadoClass }} px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wide flex items-center gap-1">
                <i class="fas {{ $estadoIcon }}"></i> {{ str_replace('_', ' ', $servicio->estado) }}
            </span>
        </div>

        <div class="p-5 border-b border-slate-100">
            <p class="text-slate-600 text-sm leading-relaxed font-medium">
                {{ $servicio->descripcion_solicitud }}
            </p>
        </div>

        <div class="p-5 bg-slate-50">
            <div class="flex items-center gap-3 mb-4">
                <div
                    class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold border-2 border-white shadow-sm">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">{{ $servicio->cliente->nombres }}</p>
                    <p class="text-xs text-slate-500 truncate max-w-[200px]">{{ $servicio->cliente->direccion }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <a href="tel:{{ $servicio->cliente->telefono }}"
                    class="flex items-center justify-center gap-2 bg-white border border-slate-300 text-slate-700 font-bold py-3 rounded-xl shadow-sm active:bg-slate-100 active:scale-95 transition">
                    <i class="fas fa-phone-alt text-green-500"></i> Llamar
                </a>
                <a href="https://maps.google.com/?q={{ urlencode($servicio->cliente->direccion) }}" target="_blank"
                    class="flex items-center justify-center gap-2 bg-white border border-slate-300 text-slate-700 font-bold py-3 rounded-xl shadow-sm active:bg-slate-100 active:scale-95 transition">
                    <i class="fas fa-map-marker-alt text-red-500"></i> Mapa
                </a>
            </div>
        </div>
    </div>

    <div class="mb-8">
        @if ($servicio->estado == 'APROBADO' || $servicio->estado == 'COTIZANDO' || $servicio->estado == 'PENDIENTE')
            <form action="{{ route('tecnico.update', $servicio->id_servicio) }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="estado" value="EN_PROCESO">
                <button type="submit"
                    class="w-full bg-emerald-500 text-white font-black text-xl py-5 rounded-2xl shadow-lg shadow-emerald-500/30 active:scale-95 active:bg-emerald-600 transition flex items-center justify-center gap-3">
                    <i class="fas fa-play"></i> INICIAR TRABAJO
                </button>
            </form>
        @elseif($servicio->estado == 'EN_PROCESO')
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <form action="{{ route('tecnico.update', $servicio->id_servicio) }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="estado" value="FINALIZADO">

                    <div class="mb-4">
                        <label class="block text-slate-700 text-sm font-bold mb-2">Costo Mano de Obra (S/)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-slate-400 font-bold">S/</span>
                            <input type="number" step="0.01" name="mano_obra"
                                value="{{ $servicio->mano_obra > 0 ? $servicio->mano_obra : '' }}"
                                class="w-full pl-8 h-12 text-lg font-bold border-slate-300 rounded-lg focus:ring-yellow-400 outline-none"
                                placeholder="0.00" required>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Confirma el valor de tu servicio antes de cerrar.</p>
                    </div>

                    <button type="submit"
                        class="w-full bg-slate-900 text-white font-black text-xl py-4 rounded-xl shadow-lg shadow-slate-900/30 active:scale-95 active:bg-slate-800 transition flex items-center justify-center gap-3"
                        onclick="return confirm('¿Confirmas que has terminado el trabajo? Esto cerrará el ticket.')">
                        <i class="fas fa-flag-checkered text-yellow-400"></i> FINALIZAR TRABAJO
                    </button>
                </form>
            </div>
        @endif
    </div>

    @if ($servicio->estado == 'EN_PROCESO' || $servicio->estado == 'FINALIZADO')
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="bg-slate-50 px-5 py-3 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-700 flex items-center gap-2">
                    <i class="fas fa-tools text-slate-400"></i> Materiales
                </h3>
                <span
                    class="bg-slate-200 text-slate-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $servicio->materiales->count() }}</span>
            </div>

            <div class="p-5">
                @if ($servicio->materiales->count() > 0)
                    <ul class="space-y-3 mb-5">
                        @foreach ($servicio->materiales as $mat)
                            <li
                                class="flex justify-between items-center text-sm border-b border-slate-50 pb-2 last:border-0">
                                <div>
                                    <span class="font-bold text-slate-700 block">{{ $mat->nombre }}</span>
                                    <span class="text-xs text-slate-400">S/
                                        {{ number_format($mat->pivot->precio_unitario, 2) }} c/u</span>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="block font-black text-slate-800 text-lg">x{{ $mat->pivot->cantidad }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-4 text-slate-400 text-sm mb-4">
                        Sin materiales registrados.
                    </div>
                @endif

                @if ($servicio->estado != 'FINALIZADO')
                    <form action="{{ route('tecnico.update', $servicio->id_servicio) }}" method="POST"
                        class="bg-slate-100 p-3 rounded-xl">
                        @csrf @method('PUT')
                        <input type="hidden" name="agregar_material" value="1">

                        <div class="space-y-3">
                            <select name="id_material" id="selectMaterial"
                                class="w-full text-sm border-slate-300 rounded-lg h-12 outline-none" required
                                onchange="actualizarPrecio()">
                                <option value="" data-precio="0">Seleccionar Material...</option>
                                @foreach ($materialesDisponibles as $m)
                                    <option value="{{ $m->id_material }}" data-precio="{{ $m->precio_referencial }}">
                                        {{ $m->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="flex gap-2">
                                <input type="number" name="cantidad" placeholder="Cant."
                                    class="w-1/3 text-sm border-slate-300 rounded-lg h-12 text-center font-bold outline-none"
                                    min="0.1" step="0.1" required>
                                <input type="number" name="precio" id="inputPrecio" placeholder="Precio S/"
                                    class="w-2/3 text-sm border-slate-300 rounded-lg h-12 text-center outline-none"
                                    step="0.01" required>
                            </div>

                            <button
                                class="w-full bg-white border-2 border-slate-200 text-slate-700 font-bold py-3 rounded-lg active:bg-slate-50 active:scale-95 transition">
                                <i class="fas fa-plus text-yellow-500 mr-1"></i> Agregar Ítem
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
        <div class="bg-slate-50 px-5 py-3 border-b border-slate-100">
            <h3 class="font-bold text-slate-700 flex items-center gap-2">
                <i class="fas fa-wallet text-green-500"></i> Gestión de Cobros
            </h3>
        </div>

        <div class="p-5">
            @php
                // 1. Calcular Deuda Total (Mano de Obra + Materiales)
                if ($servicio->estado == 'FINALIZADO' && $servicio->costo_final_real > 0) {
                    $costoTotal = $servicio->costo_final_real;
                } else {
                    $costoMateriales = $servicio->materiales->sum(function ($m) {
                        return $m->pivot->cantidad * $m->pivot->precio_unitario;
                    });
                    $costoTotal = ($servicio->mano_obra ?? 0) + $costoMateriales;
                }

                $pagado = $servicio->pagos->sum('monto');
                $saldoPendiente = max(0, $costoTotal - $pagado);
            @endphp

            <div class="flex gap-3 mb-6">
                <div class="flex-1 bg-green-50 rounded-xl p-3 border border-green-100 text-center">
                    <p class="text-[10px] uppercase font-bold text-green-600">Pagado</p>
                    <p class="text-xl font-black text-green-700">S/ {{ number_format($pagado, 2) }}</p>
                </div>
                <div class="flex-1 bg-red-50 rounded-xl p-3 border border-red-100 text-center">
                    <p class="text-[10px] uppercase font-bold text-red-600">Por Cobrar</p>
                    <p class="text-xl font-black text-red-700">S/ {{ number_format($saldoPendiente, 2) }}</p>
                </div>
            </div>

            @if ($servicio->pagos->count() > 0)
                <div class="mb-5">
                    <p class="text-xs font-bold text-slate-400 uppercase mb-2">Historial</p>
                    <ul class="space-y-2">
                        @foreach ($servicio->pagos as $pago)
                            <li class="flex justify-between items-center text-sm bg-slate-50 p-2 rounded-lg">
                                <span class="font-bold text-slate-700">S/ {{ number_format($pago->monto, 2) }}</span>
                                <span class="text-xs text-slate-500">{{ $pago->tipo }}</span>
                                @if ($pago->validado)
                                    <i class="fas fa-check-circle text-green-500"></i>
                                @else
                                    <i class="fas fa-clock text-yellow-500"></i>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($saldoPendiente > 0)
                <form action="{{ route('tecnico.pago.store', $servicio->id_servicio) }}" method="POST"
                    onsubmit="return validarPago()">
                    @csrf
                    <p class="text-xs font-bold text-slate-700 mb-2 uppercase">Registrar Nuevo Cobro</p>

                    <div class="flex gap-2 mb-2">
                        <div class="relative w-2/3">
                            <span class="absolute left-3 top-3 text-slate-400 font-bold">S/</span>
                            <input type="number" step="0.01" name="monto" id="inputMontoPago"
                                class="w-full pl-8 h-12 text-lg font-bold border-slate-300 rounded-lg focus:ring-green-500 outline-none"
                                placeholder="0.00" required>
                        </div>
                        <select name="tipo"
                            class="w-1/3 h-12 text-sm border-slate-300 rounded-lg bg-white outline-none">
                            <option value="Efectivo">Efectivo</option>
                            <option value="Yape/Plin">Yape</option>
                            <option value="Transferencia">Banco</option>
                        </select>
                    </div>

                    <p id="errorMonto" class="text-red-500 text-xs font-bold mb-2 hidden">¡Monto excede deuda!</p>

                    <button
                        class="w-full bg-green-600 text-white font-bold py-3 rounded-xl shadow-lg active:scale-95 transition">
                        COBRAR AHORA
                    </button>
                </form>
            @elseif($saldoPendiente <= 0 && $costoTotal > 0)
                <div class="text-center p-3 bg-green-100 text-green-800 rounded-xl font-bold border border-green-200">
                    <i class="fas fa-star mr-1"></i> ¡Cuenta Saldada!
                </div>
            @endif
        </div>
    </div>

    <script>
        function actualizarPrecio() {
            const select = document.getElementById('selectMaterial');
            const precio = select.options[select.selectedIndex].getAttribute('data-precio');
            document.getElementById('inputPrecio').value = precio;
        }

        function validarPago() {
            // Pasamos el valor PHP a JS de forma segura
            const saldo = {{ number_format($saldoPendiente, 2, '.', '') }};
            const input = document.getElementById('inputMontoPago');
            const monto = parseFloat(input.value);
            const error = document.getElementById('errorMonto');

            // Opcional: Permitir pagar de más? Generalmente no.
            if (monto > saldo) {
                error.classList.remove('hidden');
                input.classList.add('border-red-500', 'text-red-600');
                return false;
            }
            return true;
        }
    </script>
@endsection
