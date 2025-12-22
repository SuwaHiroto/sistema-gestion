@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Editar Servicio #{{ $servicio->id_servicio }}</h2>
            <p class="text-slate-500 mt-1">Actualizar estado, asignación y costos finales.</p>
        </div>
        <a href="{{ route('servicios.index') }}"
            class="group flex items-center gap-2 text-slate-500 hover:text-slate-800 transition font-medium">
            <div
                class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-slate-400 transition">
                <i class="fas fa-arrow-left text-xs"></i>
            </div>
            <span>Volver al listado</span>
        </a>
    </div>

    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">

            <div class="bg-slate-900 px-8 py-5 border-b border-slate-800 flex justify-between items-center">
                <h3 class="font-bold text-white flex items-center gap-3 text-lg">
                    <span class="bg-yellow-400 text-slate-900 w-8 h-8 rounded flex items-center justify-center">
                        <i class="fas fa-edit"></i>
                    </span>
                    Modificar Solicitud
                </h3>

                @php
                    $statusColor = match ($servicio->estado) {
                        'PENDIENTE' => 'bg-slate-700 text-slate-200',
                        'APROBADO' => 'bg-indigo-600 text-white',
                        'EN_PROCESO' => 'bg-yellow-500 text-slate-900',
                        'FINALIZADO' => 'bg-emerald-600 text-white',
                        default => 'bg-slate-700 text-white',
                    };
                @endphp
                <span
                    class="{{ $statusColor }} px-3 py-1 rounded text-xs font-bold uppercase tracking-wider border border-white/10">
                    {{ $servicio->estado }}
                </span>
            </div>

            <form action="{{ route('servicios.update', $servicio->id_servicio) }}" method="POST" class="p-8"
                id="formServicio">
                @csrf
                @method('PUT')
                <input type="hidden" name="modo_edicion" value="general">

                <div class="mb-8">
                    <h4
                        class="text-slate-800 font-bold uppercase tracking-wider text-xs mb-4 border-b border-slate-100 pb-2">
                        1. Detalles Generales
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div class="col-span-1">
                            <label class="block text-slate-500 text-sm font-bold mb-2">Cliente</label>
                            <div
                                class="w-full bg-slate-100 border border-slate-200 text-slate-600 rounded-xl px-4 py-3 text-sm font-medium flex items-center gap-2">
                                <i class="fas fa-user text-slate-400"></i>
                                {{ $servicio->cliente->nombres ?? 'Desconocido' }}
                            </div>
                        </div>

                        <div class="col-span-1">
                            <label class="block text-slate-700 text-sm font-bold mb-2">Técnico Asignado</label>
                            <div class="relative">
                                <select name="id_tecnico"
                                    class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-3 pr-8 py-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition appearance-none">
                                    <option value="">-- Sin Asignar --</option>
                                    @foreach ($tecnicos as $tecnico)
                                        <option value="{{ $tecnico->id_tecnico }}"
                                            {{ $servicio->id_tecnico == $tecnico->id_tecnico ? 'selected' : '' }}>
                                            {{ $tecnico->nombres }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                    <i class="fas fa-chevron-down text-xs"></i></div>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <label class="block text-slate-700 text-sm font-bold mb-2">Cambiar Estado</label>
                            <div class="relative">
                                <select name="estado"
                                    class="w-full bg-white border-2 border-slate-200 rounded-xl pl-3 pr-8 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none transition appearance-none">
                                    <option value="PENDIENTE" {{ $servicio->estado == 'PENDIENTE' ? 'selected' : '' }}>
                                        PENDIENTE</option>
                                    <option value="COTIZANDO" {{ $servicio->estado == 'COTIZANDO' ? 'selected' : '' }}>
                                        COTIZANDO</option>
                                    <option value="APROBADO" {{ $servicio->estado == 'APROBADO' ? 'selected' : '' }}>
                                        APROBADO</option>
                                    <option value="EN_PROCESO" {{ $servicio->estado == 'EN_PROCESO' ? 'selected' : '' }}>EN
                                        PROCESO</option>
                                    <option value="FINALIZADO" {{ $servicio->estado == 'FINALIZADO' ? 'selected' : '' }}>
                                        FINALIZADO</option>
                                    <option value="CANCELADO" {{ $servicio->estado == 'CANCELADO' ? 'selected' : '' }}>
                                        CANCELADO</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                    <i class="fas fa-chevron-down text-xs"></i></div>
                            </div>
                        </div>

                        <div class="col-span-1 md:col-span-3">
                            <label class="block text-slate-700 text-sm font-bold mb-2">Descripción</label>
                            <textarea name="descripcion_solicitud" rows="2"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition resize-none">{{ old('descripcion_solicitud', $servicio->descripcion_solicitud) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-2">
                        <h4 class="text-slate-800 font-bold uppercase tracking-wider text-xs">2. Materiales y Costos</h4>
                        <button type="button" onclick="agregarFilaMaterial()"
                            class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-3 rounded-lg transition flex items-center gap-2 border border-slate-200">
                            <div
                                class="w-5 h-5 rounded-full bg-yellow-400 flex items-center justify-center text-slate-900 text-[10px]">
                                <i class="fas fa-plus"></i></div>
                            Agregar Item
                        </button>
                    </div>

                    <div class="bg-slate-50 rounded-xl border border-slate-200 overflow-hidden mb-6">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 uppercase bg-slate-100 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 font-bold w-5/12">Material</th>
                                    <th class="px-4 py-3 font-bold w-2/12 text-center">Cant.</th>
                                    <th class="px-4 py-3 font-bold w-2/12 text-right">Precio Unit.</th>
                                    <th class="px-4 py-3 font-bold w-2/12 text-right">Subtotal</th>
                                    <th class="px-4 py-3 font-bold w-1/12 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white" id="bodyMateriales">
                            </tbody>
                        </table>
                        <div id="mensajeVacio" class="hidden px-4 py-8 text-center text-slate-400 italic">
                            <i class="fas fa-box-open text-2xl mb-2 opacity-50 block"></i>
                            No hay materiales asignados actualmente.
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <div
                            class="w-full md:w-1/3 bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-4 shadow-sm">

                            <div class="flex justify-between items-center text-slate-500 text-sm">
                                <span>Materiales:</span>
                                <span class="font-medium" id="resumenMateriales">S/ 0.00</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <label class="text-slate-700 font-bold text-sm">Mano de Obra (S/):</label>
                                <input type="number" step="0.01" min="0" name="mano_de_obra" id="manoDeObra"
                                    class="w-32 bg-white border border-slate-300 rounded-lg p-2 text-right text-sm font-bold text-slate-700 focus:ring-2 focus:ring-yellow-400 outline-none transition"
                                    value="{{ number_format($servicio->mano_obra, 2, '.', '') }}"
                                    oninput="calcularTotalGeneral()">
                            </div>

                            <div class="border-t border-slate-200 pt-4 flex justify-between items-end">
                                <span class="font-bold text-slate-800 text-sm uppercase tracking-wide">Total Final</span>
                                <div class="text-right">
                                    <span class="block text-2xl font-black text-slate-900" id="totalGeneralDisplay">S/
                                        0.00</span>
                                </div>
                                <input type="hidden" name="monto_cotizado" id="inputTotalGeneral"
                                    value="{{ $servicio->monto_cotizado }}">
                                <input type="hidden" name="costo_final_real" id="inputCostoFinal"
                                    value="{{ $servicio->costo_final_real }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 border-t border-slate-100 pt-6 mt-4">
                    <button type="submit"
                        class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-slate-900/20 transform hover:-translate-y-0.5 transition duration-200 flex items-center gap-2">
                        <span>Guardar Cambios</span>
                        <i class="fas fa-save text-yellow-400"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        const catalogoMateriales = @json($materiales ?? []);
        const materialesActuales = @json($servicio->materiales ?? []);
        let contadorFilas = 0;

        document.addEventListener('DOMContentLoaded', function() {
            if (materialesActuales.length > 0) {
                materialesActuales.forEach(mat => {
                    agregarFilaMaterial(mat);
                });
            } else {
                mostrarMensajeVacio(true);
            }
            calcularTotalGeneral();
        });

        function mostrarMensajeVacio(mostrar) {
            const msg = document.getElementById('mensajeVacio');
            if (mostrar) msg.classList.remove('hidden');
            else msg.classList.add('hidden');
        }

        function agregarFilaMaterial(datos = null) {
            mostrarMensajeVacio(false);
            const tbody = document.getElementById('bodyMateriales');
            contadorFilas++;

            let opciones = '<option value="">-- Seleccionar --</option>';
            catalogoMateriales.forEach(mat => {
                const selected = (datos && datos.id_material == mat.id_material) ? 'selected' : '';
                opciones +=
                    `<option value="${mat.id_material}" data-precio="${mat.precio_referencial}" ${selected}>${mat.nombre} (${mat.unidad})</option>`;
            });

            const cantidad = datos ? datos.pivot.cantidad : 1;
            const precio = datos ? datos.pivot.precio_unitario : 0.00;
            const inputClass =
                "w-full bg-slate-50 border border-slate-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition";

            const row = document.createElement('tr');
            row.id = `fila-${contadorFilas}`;
            row.innerHTML = `
                <td class="px-4 py-2">
                    <select name="materiales[${contadorFilas}][id]" class="${inputClass}" onchange="actualizarPrecioReferencial(this, ${contadorFilas})" required>
                        ${opciones}
                    </select>
                </td>
                <td class="px-4 py-2">
                    <input type="number" name="materiales[${contadorFilas}][cantidad]" step="1" min="1" value="${cantidad}" class="${inputClass} text-center" oninput="calcularSubtotal(${contadorFilas})" required>
                </td>
                <td class="px-4 py-2">
                    <input type="number" name="materiales[${contadorFilas}][precio]" step="0.01" min="0" value="${precio}" class="${inputClass} text-right" oninput="calcularSubtotal(${contadorFilas})" required>
                </td>
                <td class="px-4 py-2 text-right font-bold text-slate-700 align-middle">
                    S/ <span id="subtotal-${contadorFilas}">0.00</span>
                    <input type="hidden" class="subtotal-input" value="0">
                </td>
                <td class="px-4 py-2 text-center align-middle">
                    <button type="button" onclick="eliminarFila(${contadorFilas})" class="w-8 h-8 rounded-full bg-white border border-red-200 text-red-500 hover:bg-red-50 hover:text-red-700 transition flex items-center justify-center">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
            calcularSubtotal(contadorFilas);
        }

        function actualizarPrecioReferencial(select, id) {
            const precio = select.options[select.selectedIndex].getAttribute('data-precio');
            if (precio) {
                const input = document.querySelector(`#fila-${id} input[name="materiales[${id}][precio]"]`);
                input.value = precio;
                calcularSubtotal(id);
            }
        }

        function calcularSubtotal(id) {
            const fila = document.getElementById(`fila-${id}`);
            const cant = parseFloat(fila.querySelector(`input[name="materiales[${id}][cantidad]"]`).value) || 0;
            const precio = parseFloat(fila.querySelector(`input[name="materiales[${id}][precio]"]`).value) || 0;
            const subtotal = cant * precio;

            fila.querySelector(`#subtotal-${id}`).innerText = subtotal.toFixed(2);
            fila.querySelector(`.subtotal-input`).value = subtotal;
            calcularTotalGeneral();
        }

        function eliminarFila(id) {
            document.getElementById(`fila-${id}`).remove();
            calcularTotalGeneral();
            const tbody = document.getElementById('bodyMateriales');
            if (tbody.children.length === 0) mostrarMensajeVacio(true);
        }

        function calcularTotalGeneral() {
            let totalMat = 0;
            document.querySelectorAll('.subtotal-input').forEach(i => totalMat += parseFloat(i.value) || 0);

            const manoObra = parseFloat(document.getElementById('manoDeObra').value) || 0;
            const total = totalMat + manoObra;

            document.getElementById('resumenMateriales').innerText = 'S/ ' + totalMat.toFixed(2);
            document.getElementById('totalGeneralDisplay').innerText = 'S/ ' + total.toFixed(2);
            document.getElementById('inputTotalGeneral').value = total.toFixed(2);

            // Si el estado es FINALIZADO, actualizamos costo_final_real también
            const estado = document.querySelector('select[name="estado"]').value;
            if (estado === 'FINALIZADO') {
                document.getElementById('inputCostoFinal').value = total.toFixed(2);
            }
        }
    </script>
@endsection
