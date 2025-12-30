@extends('layouts.cliente')

@section('content')
    <div class="bg-slate-900 rounded-2xl p-6 md:p-10 mb-10 shadow-2xl relative overflow-hidden text-white">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 opacity-10 pointer-events-none">
            <i class="fas fa-bolt text-9xl text-yellow-400"></i>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">
                    Hola, <span class="text-yellow-400">{{ Auth::user()->name ?? 'Cliente' }}</span>
                </h1>
                <p class="text-slate-300 mt-2 text-sm md:text-base max-w-xl">
                    Bienvenido a tu panel de gestión eléctrica. Aquí puedes monitorear el progreso de tus instalaciones y
                    mantenimientos en tiempo real.
                </p>

                <div class="flex gap-6 mt-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-slate-700/50 rounded-lg">
                            <i class="fas fa-clipboard-list text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold leading-none">{{ $servicios->count() }}</p>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Total</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-slate-700/50 rounded-lg">
                            <i class="fas fa-hard-hat text-yellow-400"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold leading-none">
                                {{ $servicios->whereIn('estado', ['EN_PROCESO', 'APROBADO'])->count() }}
                            </p>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">En Curso</p>
                        </div>
                    </div>
                </div>
            </div>

            <button onclick="document.getElementById('modalSolicitud').classList.remove('hidden')"
                class="group bg-yellow-500 hover:bg-yellow-400 text-slate-900 font-bold py-3 px-6 rounded-xl shadow-[0_0_20px_rgba(234,179,8,0.3)] transition-all transform hover:-translate-y-1 flex items-center gap-3">
                <span class="bg-white/20 p-1.5 rounded-full">
                    <i class="fas fa-plus text-sm"></i>
                </span>
                <span>Nueva Solicitud</span>
            </button>
        </div>
    </div>

    @if (session('success'))
        <div
            class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 mb-8 rounded-r-lg shadow-sm flex items-center animate-fade-in-down">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            <div>
                <p class="font-bold">¡Solicitud Enviada!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($servicios as $servicio)
            @php
                // Lógica de estilos según estado
                $config = match ($servicio->estado) {
                    'PENDIENTE' => [
                        'border' => 'border-l-gray-400',
                        'text' => 'text-gray-600',
                        'icon' => 'fa-clock',
                        'label' => 'En Revisión',
                    ],
                    'APROBADO' => [
                        'border' => 'border-l-indigo-500',
                        'text' => 'text-indigo-600',
                        'icon' => 'fa-thumbs-up',
                        'label' => 'Aprobado',
                    ],
                    'EN_PROCESO' => [
                        'border' => 'border-l-yellow-500',
                        'text' => 'text-yellow-700',
                        'icon' => 'fa-tools',
                        'label' => 'En Ejecución',
                    ],
                    'FINALIZADO' => [
                        'border' => 'border-l-emerald-500',
                        'text' => 'text-emerald-700',
                        'icon' => 'fa-check-circle',
                        'label' => 'Finalizado',
                    ],
                    default => [
                        'border' => 'border-l-gray-300',
                        'text' => 'text-gray-500',
                        'icon' => 'fa-circle',
                        'label' => $servicio->estado,
                    ],
                };

                // Barra de progreso visual
                $progress = match ($servicio->estado) {
                    'PENDIENTE' => '10%',
                    'APROBADO' => '40%',
                    'EN_PROCESO' => '70%',
                    'FINALIZADO' => '100%',
                    default => '0%',
                };
            @endphp

            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-xl transition-all duration-300 flex flex-col h-full group relative overflow-hidden">

                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $config['border'] }}"></div>

                <div class="p-6 flex-1">
                    <div class="flex justify-between items-start mb-4 pl-2">
                        <div>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Ticket #{{ str_pad($servicio->id_servicio, 5, '0', STR_PAD_LEFT) }}
                            </span>
                            <div class="mt-1 flex items-center gap-1.5 {{ $config['text'] }}">
                                <i class="fas {{ $config['icon'] }} text-xs"></i>
                                <span class="text-xs font-bold uppercase">{{ $config['label'] }}</span>
                            </div>
                        </div>
                        <div
                            class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-slate-900 group-hover:text-yellow-400 transition-colors">
                            <i class="fas fa-bolt text-sm"></i>
                        </div>
                    </div>

                    <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 min-h-[3rem]">
                        {{ $servicio->descripcion_solicitud }}
                    </h3>

                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-5">
                        <i class="far fa-calendar-alt"></i>
                        <span>Solicitado: {{ $servicio->created_at->format('d/m/Y') }}</span>
                    </div>

                    <div class="space-y-1">
                        <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase">
                            <span>Progreso</span>
                            <span>{{ $progress }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full transition-all duration-1000 {{ str_replace('text-', 'bg-', $config['text']) }}"
                                style="width: {{ $progress }}"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 border-t border-gray-100 flex justify-between items-center pl-6">
                    <div>
                        @if ($servicio->mano_obra > 0)
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Costo Servicio Base</p>
                            <p class="text-sm font-black text-slate-800">S/ {{ number_format($servicio->mano_obra, 2) }}
                            </p>
                        @else
                            <p class="text-xs text-gray-400 italic">Por cotizar</p>
                        @endif
                    </div>

                    <a href="{{ route('cliente.servicios.show', $servicio->id_servicio) }}"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-400 hover:text-blue-600 hover:border-blue-500 shadow-sm transition-all">
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3">
                <div
                    class="flex flex-col items-center justify-center py-16 px-4 bg-white rounded-2xl border-2 border-dashed border-gray-300 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-clipboard text-4xl text-gray-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Todo limpio por aquí</h3>
                    <p class="text-gray-500 max-w-md mt-2 mb-8">
                        No tienes servicios registrados. ¿Necesitas ayuda con una instalación o reparación?
                    </p>
                    <button onclick="document.getElementById('modalSolicitud').classList.remove('hidden')"
                        class="text-blue-600 font-bold hover:text-blue-800 hover:underline flex items-center gap-2">
                        <span>Crear solicitud ahora</span> <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <div id="modalSolicitud" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                    <div class="bg-slate-900 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fas fa-bolt text-yellow-400"></i> Nuevo Servicio
                        </h3>
                        <button onclick="document.getElementById('modalSolicitud').classList.add('hidden')"
                            class="text-slate-400 hover:text-white transition">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    <form action="{{ route('cliente.servicios.store') }}" method="POST">
                        @csrf
                        <div class="p-6">
                            <div class="mb-2">
                                <label class="block text-slate-700 text-sm font-bold mb-2">¿En qué podemos ayudarte?</label>
                                <textarea name="descripcion" rows="5"
                                    class="w-full bg-slate-50 border border-slate-300 rounded-xl p-4 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none transition placeholder-slate-400 resize-none"
                                    placeholder="Ej: Hola, necesito instalar 5 luminarias LED en mi oficina y revisar un tomacorriente..." required></textarea>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-xs text-slate-400"><i class="fas fa-info-circle mr-1"></i> Mínimo 5
                                    caracteres.</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3">
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto transition">
                                Enviar Solicitud
                            </button>
                            <button type="button"
                                onclick="document.getElementById('modalSolicitud').classList.add('hidden')"
                                class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
