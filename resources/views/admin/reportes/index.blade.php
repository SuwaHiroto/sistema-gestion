@extends('layouts.admin')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Reportes y Métricas</h2>
        <p class="text-sm text-gray-500">Análisis de rendimiento del mes actual.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Reporte de Ingresos por Mes (Tabla Simple) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-line text-green-500"></i> Ingresos Recientes
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="p-3">Mes</th>
                            <th class="p-3 text-right">Cant. Pagos</th>
                            <th class="p-3 text-right">Total (S/)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($ingresosPorMes as $ingreso)
                            <tr>
                                <td class="p-3 font-medium">
                                    {{ \Carbon\Carbon::createFromFormat('m', $ingreso->mes)->format('F') }}</td>
                                <td class="p-3 text-right">{{ $ingreso->cantidad }}</td>
                                <td class="p-3 text-right font-bold text-green-600">S/
                                    {{ number_format($ingreso->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reporte de Técnicos (Top 5) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-trophy text-yellow-500"></i> Top Técnicos (Servicios Finalizados)
            </h3>
            <ul class="space-y-4">
                @foreach ($topTecnicos as $tec)
                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                {{ substr($tec->nombres, 0, 1) }}
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ $tec->nombres }}
                                {{ $tec->apellido_paterno }}</span>
                        </div>
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-bold">
                            {{ $tec->servicios_count }} servicios
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>

    <!-- Botón de Exportar (Simulado) -->
    <div class="mt-8 text-right">
        <button onclick="window.print()"
            class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center gap-2">
            <i class="fas fa-print"></i> Imprimir Reporte
        </button>
    </div>
@endsection
