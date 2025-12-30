@extends('layouts.admin')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Tesorería y Cobros</h2>
            <p class="text-slate-500 mt-1">Gestión de ingresos, validación de pagos y flujo de caja.</p>
        </div>

        <div
            class="bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-lg shadow-emerald-500/20 flex items-center gap-4 hover:transform hover:-translate-y-1 transition-all">
            <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                <i class="fas fa-wallet text-2xl"></i>
            </div>
            <div>
                <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">Total Recaudado (Validado)</p>
                <p class="text-2xl font-bold leading-none">
                    S/ {{ number_format($totalRecaudado ?? 0, 2) }}
                </p>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div
            class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-8 rounded-r-xl shadow-sm flex items-center gap-3 animate-fade-in-down">
            <div class="bg-emerald-100 p-2 rounded-full"><i class="fas fa-check"></i></div>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-r-xl shadow-sm">
            <p class="font-bold flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> Atención:</p>
            <ul class="list-disc ml-8 text-sm mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-1">
            <div
                class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden sticky top-6">
                <div class="bg-slate-900 px-6 py-5 border-b border-slate-800">
                    <h3 class="font-bold text-white flex items-center gap-2 text-lg">
                        <i class="fas fa-cash-register text-emerald-400"></i> Registrar Cobro
                    </h3>
                </div>

                <form action="{{ route('pagos.store') }}" method="POST" class="p-6 space-y-5">
                    @csrf

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2">Servicio a Cobrar</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <select name="id_servicio"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-10 p-3 text-sm focus:ring-2 focus:ring-emerald-400 focus:border-transparent outline-none transition appearance-none"
                                required>
                                <option value="">-- Seleccionar Ticket --</option>
                                @foreach ($serviciosPendientes as $servicio)
                                    <option value="{{ $servicio->id_servicio }}">
                                        #{{ str_pad($servicio->id_servicio, 5, '0', STR_PAD_LEFT) }} -
                                        {{ Str::limit($servicio->cliente->nombres, 20) }}
                                    </option>
                                @endforeach
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-1 ml-1">Solo muestra servicios activos.</p>
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2">Monto a Ingresar</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 font-bold">S/</span>
                            <input type="number" name="monto" step="0.01" min="0"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-10 p-3 text-lg font-bold text-slate-800 focus:ring-2 focus:ring-emerald-400 focus:border-transparent outline-none transition placeholder-slate-300"
                                placeholder="0.00" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2">Método de Pago</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="tipo" value="Efectivo" class="peer sr-only" checked>
                                <div
                                    class="p-3 rounded-xl border border-slate-200 bg-white peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition text-center text-sm font-medium flex flex-col items-center gap-1 hover:bg-slate-50">
                                    <i class="fas fa-money-bill-wave text-lg"></i> Efectivo
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="tipo" value="Yape/Plin" class="peer sr-only">
                                <div
                                    class="p-3 rounded-xl border border-slate-200 bg-white peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition text-center text-sm font-medium flex flex-col items-center gap-1 hover:bg-slate-50">
                                    <i class="fas fa-mobile-alt text-lg"></i> Yape / Plin
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="tipo" value="Transferencia" class="peer sr-only">
                                <div
                                    class="p-3 rounded-xl border border-slate-200 bg-white peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition text-center text-sm font-medium flex flex-col items-center gap-1 hover:bg-slate-50">
                                    <i class="fas fa-university text-lg"></i> Banco
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="tipo" value="Otro" class="peer sr-only">
                                <div
                                    class="p-3 rounded-xl border border-slate-200 bg-white peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition text-center text-sm font-medium flex flex-col items-center gap-1 hover:bg-slate-50">
                                    <i class="fas fa-ellipsis-h text-lg"></i> Otro
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-emerald-600/30 transition-all transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
                        <span>Registrar Ingreso</span>
                        <i class="fas fa-check-circle"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-700">Historial de Transacciones</h3>
                    <span
                        class="text-xs font-bold text-slate-500 bg-white border border-slate-200 px-3 py-1 rounded-full uppercase tracking-wide">
                        Últimos Registros
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-50/50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 font-bold text-slate-600">Fecha</th>
                                <th class="px-6 py-4 font-bold text-slate-600">Ticket</th>
                                <th class="px-6 py-4 font-bold text-slate-600">Método</th>
                                <th class="px-6 py-4 font-bold text-slate-600 text-right">Monto</th>
                                <th class="px-6 py-4 font-bold text-slate-600 text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pagos as $pago)
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-700">{{ $pago->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-slate-400">{{ $pago->created_at->format('h:i A') }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <a href="{{ route('servicios.show', $pago->id_servicio) }}"
                                            class="inline-flex items-center gap-1 font-mono font-bold text-indigo-600 hover:text-indigo-800 hover:underline bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                                            #{{ str_pad($pago->id_servicio, 5, '0', STR_PAD_LEFT) }}
                                        </a>
                                        <div class="text-xs text-slate-400 mt-1">
                                            Por: {{ Str::limit($pago->registradoPor->email ?? 'Sistema', 15) }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span
                                            class="text-slate-600 text-xs font-bold bg-white border border-slate-200 px-2 py-1 rounded shadow-sm">
                                            {{ $pago->tipo }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <span class="font-bold text-emerald-600 text-base">
                                            S/ {{ number_format($pago->monto, 2) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if ($pago->validado)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                <i class="fas fa-check-circle"></i> Validado
                                            </span>
                                        @else
                                            <form action="{{ route('pagos.validar', $pago->id_pago) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <button type="submit"
                                                    class="group/btn relative inline-flex items-center justify-center gap-2 px-4 py-1.5 text-xs font-bold text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-full hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 transition-all w-full"
                                                    title="Clic para confirmar recepción del dinero"
                                                    onclick="return confirm('¿Confirmar que el dinero ha ingresado a caja física?')">

                                                    <span class="group-hover/btn:hidden flex items-center gap-1">
                                                        <i class="fas fa-clock"></i> Pendiente
                                                    </span>
                                                    <span class="hidden group-hover/btn:flex items-center gap-1">
                                                        <i class="fas fa-check"></i> Validar
                                                    </span>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        <div
                                            class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-receipt text-2xl opacity-50"></i>
                                        </div>
                                        <p>No hay transacciones registradas.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $pagos->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
