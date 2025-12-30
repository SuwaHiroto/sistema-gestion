@extends('layouts.admin')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        @media print {

            /* Ocultar interfaz general */
            body * {
                visibility: hidden;
            }

            .no-print {
                display: none !important;
            }

            /* Mostrar solo el reporte */
            #reporte-imprimible,
            #reporte-imprimible * {
                visibility: visible;
            }

            /* Posicionamiento absoluto para ocupar toda la hoja */
            #reporte-imprimible {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
                background: white;
            }

            /* Mostrar encabezado oficial */
            .print-only {
                display: block !important;
            }

            /* Asegurar colores de fondo en impresión (Chrome/Edge) */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Evitar cortes en gráficos y tablas */
            tr,
            img,
            canvas,
            .break-inside-avoid {
                page-break-inside: avoid;
            }

            /* Tipografía legible para papel */
            body {
                font-size: 12pt;
                color: black;
            }
        }

        /* En pantalla normal, ocultar lo exclusivo de impresión */
        .print-only {
            display: none;
        }
    </style>

    <div class="flex justify-between items-end mb-6 no-print">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Panel de Métricas</h2>
            <p class="text-slate-500 mt-1">Reporte ejecutivo del rendimiento del sistema.</p>
        </div>
        <button onclick="window.print()"
            class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg flex items-center gap-2 transition transform hover:-translate-y-0.5">
            <i class="fas fa-print"></i> Imprimir Reporte
        </button>
    </div>

    <div id="reporte-imprimible" class="w-full">

        <div class="print-only mb-8 text-center border-b-2 border-slate-800 pb-4">
            <h1 class="text-3xl font-bold uppercase tracking-widest text-slate-900">Reporte Mensual de Servicios</h1>
            <p class="text-slate-600 font-medium text-lg mt-1">ElectroManager S.A.C.</p>
            <div class="flex justify-between text-xs text-slate-500 mt-4 px-10">
                <span>Generado por: {{ Auth::user()->email ?? 'Administrador' }}</span>
                <span>Fecha de Emisión: {{ now()->format('d/m/Y H:i A') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 break-inside-avoid">
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-emerald-500 relative overflow-hidden">
                <div class="flex justify-between items-center relative z-10">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ingresos (Validado)</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">S/ {{ number_format($totalIngresos, 2) }}</h3>
                    </div>
                    <div class="bg-emerald-100 p-3 rounded-full text-emerald-600">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-blue-500 relative overflow-hidden">
                <div class="flex justify-between items-center relative z-10">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Servicios Finalizados</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $serviciosFinalizados }}</h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full text-blue-600">
                        <i class="fas fa-clipboard-check text-xl"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-yellow-500 relative overflow-hidden">
                <div class="flex justify-between items-center relative z-10">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">En Proceso / Pendientes</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $serviciosPendientes }}</h3>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full text-yellow-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8 break-inside-avoid">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 lg:col-span-2">
                <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2 border-b border-slate-100 pb-3">
                    <i class="fas fa-chart-bar text-indigo-500"></i> Facturación Mensual
                </h3>
                <div class="relative w-full" style="height: 300px;">
                    <canvas id="ingresosChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                    <i class="fas fa-trophy text-yellow-500"></i> Top Rendimiento
                </h3>
                <ul class="space-y-3">
                    @forelse ($topTecnicos as $index => $tec)
                        <li class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-white flex items-center justify-center font-bold text-slate-400 text-xs shadow-sm border border-slate-200">
                                    #{{ $index + 1 }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-700 text-sm">
                                        {{ $tec->nombres }} {{ substr($tec->apellido_paterno, 0, 1) }}.
                                    </div>
                                    <div class="text-[10px] text-slate-400 uppercase font-bold">{{ $tec->especialidad }}
                                    </div>
                                </div>
                            </div>
                            <span
                                class="bg-white text-indigo-700 px-2.5 py-1 rounded-lg text-xs font-bold border border-indigo-100 shadow-sm">
                                {{ $tec->servicios_count }} <span class="text-[9px] text-slate-400 font-normal">svcs</span>
                            </span>
                        </li>
                    @empty
                        <li class="text-center text-slate-400 text-sm py-4 italic">No hay datos suficientes.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden break-inside-avoid">
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-sm uppercase flex items-center gap-2">
                    <i class="fas fa-table text-slate-400"></i> Detalle de Ingresos
                </h3>
            </div>
            <table class="w-full text-sm text-left">
                <thead class="bg-white text-slate-500 uppercase text-xs border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 font-bold">Mes</th>
                        <th class="px-6 py-3 text-center font-bold">N° Transacciones</th>
                        <th class="px-6 py-3 text-right font-bold">Total Recaudado</th>
                        <th class="px-6 py-3 text-center font-bold">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($ingresosPorMes as $ingreso)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-medium text-slate-800 capitalize">
                                {{-- Truco para obtener nombre del mes desde número --}}
                                {{ \Carbon\Carbon::create(null, (int) $ingreso->mes, 1)->locale('es')->isoFormat('MMMM') }}
                            </td>
                            <td class="px-6 py-4 text-center text-slate-600">
                                {{ $ingreso->cantidad }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-800">
                                S/ {{ number_format($ingreso->total, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="text-[10px] uppercase font-bold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full border border-emerald-200">
                                    Validado
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-400 italic">
                                No hay registros de ingresos este año.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="print-only mt-8 text-center text-[10px] text-slate-400 border-t border-slate-200 pt-4">
            <p>Este documento es un reporte generado automáticamente por el sistema ElectroManager. Validez interna.</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('ingresosChart').getContext('2d');

            // Degradado bonito para las barras
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(79, 70, 229, 0.8)'); // Indigo fuerte
            gradient.addColorStop(1, 'rgba(79, 70, 229, 0.1)'); // Indigo suave

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labelsGrafico), // Vienen del Controller
                    datasets: [{
                        label: 'Ingresos (S/)',
                        data: @json($dataGrafico), // Vienen del Controller
                        backgroundColor: gradient,
                        borderColor: '#4338ca',
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'S/ ' + context.raw.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [4, 4],
                                color: '#e2e8f0'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'S/ ' + value;
                                },
                                font: {
                                    size: 10
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
