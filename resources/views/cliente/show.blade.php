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
                    'COTIZANDO' => 'bg-blue-600 text-white',
                    'APROBADO' => 'bg-indigo-600 text-white',
                    'EN_PROCESO' => 'bg-yellow-500 text-slate-900',
                    'FINALIZADO' => 'bg-emerald-600 text-white',
                    default => 'bg-slate-700 text-white',
                };
                $statusIcon = match ($servicio->estado) {
                    'PENDIENTE' => 'fa-clock',
                    'COTIZANDO' => 'fa-file-invoice-dollar',
                    'APROBADO' => 'fa-check',
                    'EN_PROCESO' => 'fa-tools',
                    'FINALIZADO' => 'fa-check-circle',
                    default => 'fa-circle',
                };
            @endphp
            <div class="{{ $statusColor }} px-5 py-2 rounded-lg flex items-center gap-2 shadow-lg">
                <i class="fas {{ $statusIcon }}"></i>
                <span class="font-bold tracking-wide">{{ $servicio->estado }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-8">

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                    <i class="fas fa-align-left text-slate-400"></i>
                    <h3 class="font-bold text-slate-800">Requerimiento del Cliente</h3>
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
                    <div class="p-6 pl-8 flex flex-col sm:flex-row items-center sm:items-start gap-6">
                        <div
                            class="w-20 h-20 rounded-xl bg-slate-200 flex items-center justify-center text-slate-400 text-3xl font-bold border-2 border-white shadow-md">
                            {{ substr($servicio->tecnico->nombres, 0, 1) }}
                        </div>

                        <div class="text-center sm:text-left flex-1">
                            <h4 class="text-xl font-bold text-slate-800">
                                {{ $servicio->tecnico->nombres }} {{ $servicio->tecnico->apellido_paterno }}
                            </h4>
                            <p class="text-indigo-600 font-medium mb-3">{{ $servicio->tecnico->especialidad }}</p>

                            <div class="flex flex-wrap justify-center sm:justify-start gap-3">
                                <span
                                    class="px-3 py-1 bg-slate-100 text-slate-600 text-xs rounded-full border border-slate-200">
                                    <i class="fas fa-id-card mr-1"></i> DNI: {{ $servicio->tecnico->dni }}
                                </span>
                                <span
                                    class="px-3 py-1 bg-slate-100 text-slate-600 text-xs rounded-full border border-slate-200">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i> Personal Certificado
                                </span>
                            </div>
                        </div>

                        <div class="hidden sm:block border-l border-slate-100 pl-6 py-2">
                            <div class="text-center">
                                <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Estado</p>
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Activo
                                </span>
                            </div>
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
                            Nuestro equipo de operaciones está seleccionando al especialista ideal para tu caso. Recibirás
                            una notificación pronto.
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
                                    <th class="px-6 py-3 font-medium">Material</th>
                                    <th class="px-6 py-3 font-medium text-center">Cantidad</th>
                                    <th class="px-6 py-3 font-medium text-right">Precio Unit.</th>
                                    <th class="px-6 py-3 font-medium text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($servicio->materiales as $mat)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-3 font-medium text-slate-700">{{ $mat->nombre }}</td>
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
        </div>

        <div class="lg:col-span-1 space-y-8">

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-900 px-6 py-4 flex justify-between items-center">
                    <h3 class="font-bold text-white">Presupuesto</h3>
                    <i class="fas fa-wallet text-yellow-400"></i>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Mano de Obra / Servicio</span>
                        <span class="font-medium text-slate-700">S/ {{ number_format($servicio->mano_obra, 2) }}</span>
                    </div>

                    @php
                        $costoMateriales = $servicio->materiales->sum(function ($m) {
                            return $m->pivot->cantidad * $m->pivot->precio_unitario;
                        });
                    @endphp
                    <div class="flex justify-between items-center text-sm pb-4 border-b border-slate-100">
                        <span class="text-slate-500">Insumos y Materiales</span>
                        <span class="font-medium text-slate-700">S/ {{ number_format($costoMateriales, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="font-bold text-slate-800">TOTAL ESTIMADO</span>
                        <span class="font-bold text-xl text-indigo-600">
                            S/ {{ number_format($servicio->mano_obra + $costoMateriales, 2) }}
                        </span>
                    </div>

                    @if ($servicio->estado == 'FINALIZADO')
                        <button
                            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 rounded-lg shadow-md transition mt-2 flex justify-center items-center gap-2">
                            <i class="fas fa-check-circle"></i> Servicio Pagado
                        </button>
                    @else
                        <div
                            class="bg-blue-50 text-blue-700 text-xs p-3 rounded-lg text-center mt-2 border border-blue-100">
                            <i class="fas fa-info-circle mr-1"></i> El pago se gestiona al finalizar.
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800">Seguimiento</h3>
                </div>
                <div class="p-6">
                    <div class="relative pl-4 border-l-2 border-slate-200 space-y-8">

                        @foreach ($servicio->historial->sortByDesc('fecha_cambio') as $index => $h)
                            <div class="relative">
                                <div
                                    class="absolute -left-[21px] top-0 bg-white border-2 {{ $index === 0 ? 'border-indigo-500 text-indigo-500' : 'border-slate-300 text-slate-300' }} w-8 h-8 rounded-full flex items-center justify-center text-xs shadow-sm">
                                    <i class="fas {{ $index === 0 ? 'fa-check' : 'fa-history' }}"></i>
                                </div>

                                <div class="pl-4">
                                    <span
                                        class="text-[10px] font-bold uppercase tracking-wider {{ $index === 0 ? 'text-indigo-500' : 'text-slate-400' }}">
                                        {{ optional($h->fecha_cambio)->format('d M Y') ?? 'Fecha Pend.' }}
                                    </span>
                                    <h4 class="font-bold text-slate-800 text-sm mt-0.5">{{ $h->estado_nuevo }}</h4>

                                    <div
                                        class="text-xs text-slate-500 mt-2 bg-slate-50 p-2.5 rounded border border-slate-100 italic">
                                        "{{ $h->comentario }}"
                                    </div>
                                    <p class="text-[10px] text-slate-400 text-right mt-1">
                                        {{ optional($h->fecha_cambio)->format('h:i A') ?? '--:--' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach

                        <div class="relative">
                            <div
                                class="absolute -left-[21px] top-0 bg-slate-100 border-2 border-slate-200 w-8 h-8 rounded-full flex items-center justify-center text-slate-400 text-xs">
                                <i class="fas fa-flag"></i>
                            </div>
                            <div class="pl-4 opacity-70">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    {{ $servicio->created_at->format('d M Y') }}
                                </span>
                                <h4 class="font-bold text-slate-600 text-sm">SOLICITUD CREADA</h4>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
