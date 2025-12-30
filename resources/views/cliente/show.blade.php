@extends('layouts.cliente')

@section('content')
    <div class="mb-6">
        <a href="{{ route('cliente.index') }}"
            class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 font-medium transition-colors group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span>Volver al Dashboard</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
        <div
            class="bg-slate-900 px-6 py-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-800">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span
                        class="bg-yellow-400 text-slate-900 text-xs font-bold px-2 py-0.5 rounded uppercase tracking-wider">
                        Ticket #{{ str_pad($servicio->id_servicio, 5, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="text-slate-400 text-sm">|</span>
                    <span class="text-slate-300 text-sm flex items-center gap-2">
                        <i class="far fa-calendar-alt"></i> {{ $servicio->created_at->format('d M Y, h:i A') }}
                    </span>
                </div>
                <h1 class="text-white text-xl md:text-2xl font-bold">Detalle del Servicio</h1>
            </div>

            @php
                $statusColor = match ($servicio->estado) {
                    'PENDIENTE' => 'bg-gray-600 text-white',
                    'APROBADO' => 'bg-indigo-600 text-white',
                    'EN_PROCESO' => 'bg-yellow-500 text-slate-900',
                    'FINALIZADO' => 'bg-emerald-600 text-white',
                    default => 'bg-slate-700 text-white',
                };
            @endphp
            <div class="{{ $statusColor }} px-5 py-2 rounded-lg flex items-center gap-2 shadow-lg">
                <i class="fas fa-circle text-[10px]"></i>
                <span class="font-bold tracking-wide">{{ $servicio->estado }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-8">

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                    <i class="fas fa-align-left text-slate-400"></i>
                    <h3 class="font-bold text-slate-800">Tu Requerimiento</h3>
                </div>
                <div class="p-6">
                    <p class="text-slate-600 leading-relaxed text-lg">
                        {{ $servicio->descripcion_solicitud }}
                    </p>
                </div>
            </div>

            @if ($servicio->tecnico)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative">
                    <div class="absolute top-0 left-0 bottom-0 w-1.5 bg-indigo-500"></div>
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center gap-2 pl-8">
                        <i class="fas fa-user-hard-hat text-indigo-500"></i>
                        <h3 class="font-bold text-slate-800">Técnico Responsable</h3>
                    </div>
                    <div class="p-6 pl-8 flex items-center gap-6">
                        <div
                            class="w-16 h-16 rounded-xl bg-slate-200 flex items-center justify-center text-slate-400 text-2xl font-bold border-2 border-white shadow-md">
                            {{ substr($servicio->tecnico->nombres, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-800">
                                {{ $servicio->tecnico->nombres }} {{ $servicio->tecnico->apellido_paterno }}
                            </h4>
                            <p class="text-indigo-600 font-medium text-sm">{{ $servicio->tecnico->especialidad }}</p>
                            <p class="text-slate-400 text-xs mt-1">Personal Certificado</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-6 flex items-start gap-4">
                    <div class="p-3 bg-yellow-100 rounded-full text-yellow-600">
                        <i class="fas fa-hard-hat text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-yellow-800">Asignación Pendiente</h3>
                        <p class="text-sm text-yellow-700 mt-1">
                            Estamos asignando al mejor especialista para tu caso. Pronto verás sus datos aquí.
                        </p>
                    </div>
                </div>
            @endif

            @if ($servicio->materiales->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                        <i class="fas fa-box-open text-slate-400"></i>
                        <h3 class="font-bold text-slate-800">Materiales Utilizados</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-white text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-3 font-medium">Ítem</th>
                                    <th class="px-6 py-3 font-medium text-center">Cant.</th>
                                    <th class="px-6 py-3 font-medium text-right">P. Unit.</th>
                                    <th class="px-6 py-3 font-medium text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($servicio->materiales as $mat)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-3 text-slate-700">{{ $mat->nombre }}</td>
                                        <td class="px-6 py-3 text-center text-slate-500">{{ $mat->pivot->cantidad }}</td>
                                        <td class="px-6 py-3 text-right text-slate-500">S/
                                            {{ number_format($mat->pivot->precio_unitario, 2) }}</td>
                                        <td class="px-6 py-3 text-right font-bold text-slate-700">
                                            S/ {{ number_format($mat->pivot->cantidad * $mat->pivot->precio_unitario, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-900 px-6 py-4 border-b border-slate-800 flex justify-between items-center">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <i class="fas fa-receipt text-yellow-400"></i> Tus Pagos
                    </h3>
                </div>
                <div class="p-0">
                    @if ($servicio->pagos->isEmpty())
                        <div class="p-8 text-center text-slate-500">
                            <p>No has realizado pagos para este servicio aún.</p>
                        </div>
                    @else
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-3">Fecha</th>
                                    <th class="px-6 py-3">Método</th>
                                    <th class="px-6 py-3 text-center">Estado</th>
                                    <th class="px-6 py-3 text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($servicio->pagos as $pago)
                                    <tr>
                                        <td class="px-6 py-4 text-slate-700">{{ $pago->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 text-slate-600">{{ ucfirst($pago->tipo) }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($pago->validado)
                                                <span
                                                    class="bg-green-100 text-green-700 py-1 px-3 rounded-full text-xs font-bold border border-green-200">Validado</span>
                                            @else
                                                <span
                                                    class="bg-yellow-100 text-yellow-700 py-1 px-3 rounded-full text-xs font-bold border border-yellow-200">Pendiente</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-bold text-slate-800 text-right">S/
                                            {{ number_format($pago->monto, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-900 px-6 py-4 flex justify-between items-center">
                    <h3 class="font-bold text-white">Estado de Cuenta</h3>
                    <i class="fas fa-wallet text-yellow-400"></i>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Mano de Obra</span>
                        <span class="font-medium text-slate-700">S/
                            {{ number_format($servicio->mano_obra ?? 0, 2) }}</span>
                    </div>

                    @php
                        $costoMateriales = $servicio->materiales->sum(function ($m) {
                            return $m->pivot->cantidad * $m->pivot->precio_unitario;
                        });
                    @endphp
                    <div class="flex justify-between items-center text-sm pb-4 border-b border-slate-100">
                        <span class="text-slate-500">Materiales</span>
                        <span class="font-medium text-slate-700">S/ {{ number_format($costoMateriales, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="font-bold text-slate-800">TOTAL</span>
                        <span class="font-bold text-xl text-indigo-600">
                            S/ {{ number_format(($servicio->mano_obra ?? 0) + $costoMateriales, 2) }}
                        </span>
                    </div>

                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-bold">Pagado:</span>
                            <span class="font-bold text-green-600">S/
                                {{ number_format($servicio->pagos->where('validado', 1)->sum('monto'), 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm mt-2">
                            <span class="text-slate-500 font-bold">Pendiente:</span>
                            @php
                                $total = ($servicio->mano_obra ?? 0) + $costoMateriales;
                                $pagado = $servicio->pagos->where('validado', 1)->sum('monto');
                                $restante = $total - $pagado;
                            @endphp
                            <span class="font-bold text-red-500">S/
                                {{ number_format($restante > 0 ? $restante : 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800">Seguimiento</h3>
                </div>
                <div class="p-6">
                    <div class="relative pl-4 border-l-2 border-slate-200 space-y-8">
                        @foreach ($servicio->historial->sortByDesc('fecha_cambio') as $h)
                            <div class="relative">
                                <div
                                    class="absolute -left-[21px] top-0 bg-white border-2 border-slate-300 w-8 h-8 rounded-full flex items-center justify-center text-xs shadow-sm">
                                    <i class="fas fa-history text-slate-400"></i>
                                </div>
                                <div class="pl-4">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                        {{ optional($h->fecha_cambio)->format('d M Y h:i A') }}
                                    </span>
                                    <h4 class="font-bold text-slate-800 text-sm mt-0.5">{{ $h->estado_nuevo }}</h4>
                                    @if ($h->comentario)
                                        <div class="text-xs text-slate-500 mt-2 bg-slate-50 p-2 rounded italic">
                                            "{{ $h->comentario }}"</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
