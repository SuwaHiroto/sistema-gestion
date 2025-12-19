@extends('layouts.admin')

@section('content')
    <div class="max-w-5xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center text-sm text-gray-500">
            <a href="{{ route('servicios.index') }}" class="hover:text-primary transition">
                <i class="fas fa-arrow-left mr-1"></i> Volver a Servicios
            </a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 font-bold">Editar Servicio #{{ $servicio->id_servicio }}</span>
        </nav>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">

            <!-- Encabezado -->
            <div class="bg-gray-50 px-8 py-6 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-edit text-blue-600"></i> Editar Cotización y Estado
                    </h2>
                    <p class="text-gray-500 text-sm mt-1">Actualiza los detalles, asignación y costos del servicio.</p>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold uppercase tracking-wider">
                    {{ $servicio->estado }}
                </span>
            </div>

            <form action="{{ route('servicios.update', $servicio->id_servicio) }}" method="POST" class="p-8"
                id="formServicio">
                @csrf
                @method('PUT')

                <!-- Input oculto para identificar edición completa -->
                <input type="hidden" name="modo_edicion" value="general">

                <!-- SECCIÓN 1: DATOS GENERALES -->
                <h3 class="text-gray-800 font-bold mb-4 border-b pb-2">1. Información General</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                    <!-- Cliente (Solo Lectura) -->
                    <div class="col-span-1">
                        <label class="block text-gray-500 text-sm font-bold mb-2">Cliente</label>
                        <div class="w-full bg-gray-100 border border-gray-200 text-gray-600 rounded-lg p-2.5">
                            {{ $servicio->cliente->nombres ?? 'Desconocido' }}
                        </div>
                    </div>

                    <!-- Técnico -->
                    <div class="col-span-1">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Técnico Asignado</label>
                        <select name="id_tecnico"
                            class="w-full bg-white border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Sin Asignar --</option>
                            @foreach ($tecnicos as $tecnico)
                                <option value="{{ $tecnico->id_tecnico }}"
                                    {{ $servicio->id_tecnico == $tecnico->id_tecnico ? 'selected' : '' }}>
                                    {{ $tecnico->nombres }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Estado -->
                    <div class="col-span-1">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Estado del Servicio</label>
                        <select name="estado"
                            class="w-full bg-white border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 font-bold text-gray-700">
                            <option value="PENDIENTE" {{ $servicio->estado == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE
                            </option>
                            <option value="APROBADO" {{ $servicio->estado == 'APROBADO' ? 'selected' : '' }}>APROBADO
                            </option>
                            <option value="EN_PROCESO" {{ $servicio->estado == 'EN_PROCESO' ? 'selected' : '' }}>EN PROCESO
                            </option>
                            <option value="FINALIZADO" {{ $servicio->estado == 'FINALIZADO' ? 'selected' : '' }}>FINALIZADO
                            </option>
                            <option value="CANCELADO" {{ $servicio->estado == 'CANCELADO' ? 'selected' : '' }}>CANCELADO
                            </option>
                        </select>
                    </div>

                    <!-- Descripción -->
                    <div class="col-span-3">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Descripción de la Solicitud</label>
                        <textarea name="descripcion_solicitud" rows="2"
                            class="w-full bg-white border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500" required>{{ old('descripcion_solicitud', $servicio->descripcion_solicitud) }}</textarea>
                    </div>
                </div>

                <!-- SECCIÓN 2: MATERIALES Y COSTOS -->
                <h3 class="text-gray-800 font-bold mb-4 border-b pb-2 flex justify-between items-center">
                    2. Materiales y Costos
                    <button type="button" onclick="agregarFilaMaterial()"
                        class="text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1 rounded font-bold transition">
                        <i class="fas fa-plus mr-1"></i> Agregar Material
                    </button>
                </h3>

                <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden mb-6">
                    <table class="w-full text-sm text-left" id="tablaMateriales">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                            <tr>
                                <th class="px-4 py-3 w-5/12">Material</th>
                                <th class="px-4 py-3 w-2/12">Cantidad</th>
                                <th class="px-4 py-3 w-2/12">Precio Unit. (S/)</th>
                                <th class="px-4 py-3 w-2/12">Subtotal</th>
                                <th class="px-4 py-3 w-1/12 text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white" id="bodyMateriales">
                            <!-- Las filas se llenarán con JS -->
                        </tbody>
                    </table>
                    <div id="mensajeVacio" class="hidden px-4 py-6 text-center text-gray-400 italic">
                        No hay materiales agregados a la cotización.
                    </div>
                </div>

                <!-- TOTALES -->
                <div class="flex justify-end">
                    <div class="w-full md:w-1/3 bg-gray-50 p-6 rounded-lg border border-gray-200 space-y-3">
                        <div class="flex justify-between items-center text-gray-600">
                            <span>Total Materiales:</span>
                            <span class="font-bold" id="resumenMateriales">S/ 0.00</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <label class="text-gray-700 font-bold">Mano de Obra (S/):</label>
                            <!-- Calculamos mano de obra inicial como Total - Materiales -->
                            <input type="number" step="0.01" min="0" name="mano_de_obra" id="manoDeObra"
                                class="w-24 text-right border border-gray-300 rounded p-1 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ number_format($servicio->monto_cotizado - $servicio->materiales->sum(fn($m) => $m->pivot->precio_unitario * $m->pivot->cantidad), 2, '.', '') }}"
                                oninput="calcularTotalGeneral()">
                        </div>

                        <div class="border-t border-gray-300 pt-3 flex justify-between items-center text-lg">
                            <span class="font-bold text-gray-800">TOTAL:</span>
                            <span class="font-bold text-blue-600 text-xl" id="totalGeneralDisplay">S/ 0.00</span>
                            <!-- Input oculto para enviar el total al backend -->
                            <input type="hidden" name="monto_cotizado" id="inputTotalGeneral"
                                value="{{ $servicio->monto_cotizado }}">

                            <!-- Campo para costo final real (opcional aquí o en finalizar) -->
                            <input type="hidden" name="costo_final_real" id="inputCostoFinal"
                                value="{{ $servicio->costo_final_real }}">
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-6 mt-6">
                    <a href="{{ route('servicios.show', $servicio->id_servicio) }}"
                        class="text-gray-500 hover:text-gray-800 font-medium px-4 py-2">Cancelar</a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200">
                        <i class="fas fa-save mr-2"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPTS PARA LÓGICA DE CÁLCULO -->
    <script>
        // Datos pasados desde el backend
        const catalogoMateriales = @json($materiales ?? []);
        const materialesActuales = @json($servicio->materiales ?? []);
        let contadorFilas = 0;

        // Inicializar al cargar
        document.addEventListener('DOMContentLoaded', function() {
            const tbody = document.getElementById('bodyMateriales');

            // Si hay materiales guardados, los renderizamos
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

        function agregarFilaMaterial(datosPreexistentes = null) {
            mostrarMensajeVacio(false);
            const tbody = document.getElementById('bodyMateriales');
            contadorFilas++;

            let opciones = '<option value="">-- Seleccionar --</option>';
            catalogoMateriales.forEach(mat => {
                const selected = (datosPreexistentes && datosPreexistentes.id_material == mat.id_material) ?
                    'selected' : '';
                opciones +=
                    `<option value="${mat.id_material}" data-precio="${mat.precio_referencial}" ${selected}>${mat.nombre} (${mat.unidad})</option>`;
            });

            const cantidad = datosPreexistentes ? datosPreexistentes.pivot.cantidad : 1;
            const precio = datosPreexistentes ? datosPreexistentes.pivot.precio_unitario : 0.00;

            const row = document.createElement('tr');
            row.id = `fila-${contadorFilas}`;
            row.innerHTML = `
            <td class="px-4 py-2">
                <select name="materiales[${contadorFilas}][id]" class="w-full border border-gray-300 rounded p-1 text-sm" onchange="actualizarPrecioReferencial(this, ${contadorFilas})" required>
                    ${opciones}
                </select>
            </td>
            <td class="px-4 py-2">
                <input type="number" name="materiales[${contadorFilas}][cantidad]" step="1" min="1" value="${cantidad}" class="w-full border border-gray-300 rounded p-1 text-center text-sm" oninput="calcularSubtotal(${contadorFilas})" required>
            </td>
            <td class="px-4 py-2">
                <input type="number" name="materiales[${contadorFilas}][precio]" step="0.01" min="0" value="${precio}" class="w-full border border-gray-300 rounded p-1 text-right text-sm" oninput="calcularSubtotal(${contadorFilas})" required>
            </td>
            <td class="px-4 py-2 text-right font-bold text-gray-700">
                S/ <span id="subtotal-${contadorFilas}">0.00</span>
                <input type="hidden" class="subtotal-input" value="0">
            </td>
            <td class="px-4 py-2 text-center">
                <button type="button" onclick="eliminarFila(${contadorFilas})" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;
            tbody.appendChild(row);
            calcularSubtotal(contadorFilas); // Calcular inicial
        }

        function actualizarPrecioReferencial(select, id) {
            const precio = select.options[select.selectedIndex].getAttribute('data-precio');
            if (precio) {
                const inputPrecio = document.querySelector(`#fila-${id} input[name="materiales[${id}][precio]"]`);
                // Solo actualizamos si el precio es 0 o si el usuario quiere resetearlo (lógica simple: actualizar siempre al cambiar material)
                inputPrecio.value = precio;
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
            if (tbody.children.length === 0) {
                mostrarMensajeVacio(true);
            }
        }

        function calcularTotalGeneral() {
            let totalMateriales = 0;
            document.querySelectorAll('.subtotal-input').forEach(input => {
                totalMateriales += parseFloat(input.value) || 0;
            });

            const manoObra = parseFloat(document.getElementById('manoDeObra').value) || 0;
            const total = totalMateriales + manoObra;

            document.getElementById('resumenMateriales').innerText = 'S/ ' + totalMateriales.toFixed(2);
            document.getElementById('totalGeneralDisplay').innerText = 'S/ ' + total.toFixed(2);
            document.getElementById('inputTotalGeneral').value = total.toFixed(2);

            // También actualizamos costo final real si el estado es finalizado
            const estado = document.querySelector('select[name="estado"]').value;
            if (estado === 'FINALIZADO') {
                document.getElementById('inputCostoFinal').value = total.toFixed(2);
            }
        }
    </script>
@endsection
