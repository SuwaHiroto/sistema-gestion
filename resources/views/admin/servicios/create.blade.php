@extends('layouts.admin')

@section('content')
    <div class="max-w-5xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center text-sm text-gray-500">
            <a href="{{ url('/servicios') }}" class="hover:text-primary transition">
                <i class="fas fa-arrow-left mr-1"></i> Volver a Servicios
            </a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 font-bold">Nuevo Servicio / Cotización</span>
        </nav>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">

            <!-- Encabezado -->
            <div class="bg-gray-50 px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-file-invoice-dollar text-secondary"></i> Registrar Solicitud y Cotización
                </h2>
                <p class="text-gray-500 text-sm mt-1">Completa los datos del servicio, asignación y materiales requeridos.
                </p>
            </div>

            <form action="{{ route('servicios.store') }}" method="POST" class="p-8" id="formServicio">
                @csrf

                <!-- SECCIÓN 1: DATOS GENERALES -->
                <h3 class="text-gray-800 font-bold mb-4 border-b pb-2">1. Información General</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                    <!-- Cliente -->
                    <div class="col-span-1">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Cliente <span
                                class="text-red-500">*</span></label>
                        <select name="id_cliente"
                            class="w-full bg-white border border-gray-300 rounded-lg p-2.5 focus:ring-secondary focus:border-secondary"
                            required>
                            <option value="">-- Seleccionar --</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id_cliente }}"
                                    {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                                    {{ $cliente->nombres }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Técnico -->
                    <div class="col-span-1">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Técnico (Opcional)</label>
                        <select name="id_tecnico"
                            class="w-full bg-white border border-gray-300 rounded-lg p-2.5 focus:ring-secondary focus:border-secondary">
                            <option value="">-- Pendiente --</option>
                            @foreach ($tecnicos as $tecnico)
                                <option value="{{ $tecnico->id_tecnico }}"
                                    {{ old('id_tecnico') == $tecnico->id_tecnico ? 'selected' : '' }}>
                                    {{ $tecnico->nombres }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fecha Programada -->
                    <div class="col-span-1">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Fecha Programada</label>
                        <input type="datetime-local" name="fecha_inicio"
                            class="w-full bg-white border border-gray-300 rounded-lg p-2.5 focus:ring-secondary focus:border-secondary"
                            value="{{ old('fecha_inicio', now()->format('Y-m-d\TH:i')) }}">
                    </div>

                    <!-- Descripción -->
                    <div class="col-span-3">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Descripción de la Solicitud <span
                                class="text-red-500">*</span></label>
                        <textarea name="descripcion_solicitud" rows="2"
                            class="w-full bg-white border border-gray-300 rounded-lg p-3 focus:ring-secondary focus:border-secondary"
                            placeholder="Detalle del problema..." required>{{ old('descripcion_solicitud') }}</textarea>
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
                            <!-- Las filas se agregarán aquí con JS -->
                            <tr id="fila-vacia">
                                <td colspan="5" class="px-4 py-6 text-center text-gray-400 italic">
                                    No hay materiales agregados a la cotización.
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                            <input type="number" step="0.01" min="0" name="mano_de_obra" id="manoDeObra"
                                class="w-24 text-right border border-gray-300 rounded p-1 focus:ring-secondary focus:border-secondary"
                                value="0.00" oninput="calcularTotalGeneral()">
                        </div>

                        <div class="border-t border-gray-300 pt-3 flex justify-between items-center text-lg">
                            <span class="font-bold text-gray-800">TOTAL ESTIMADO:</span>
                            <span class="font-bold text-primary text-xl" id="totalGeneralDisplay">S/ 0.00</span>
                            <!-- Input oculto para enviar el total al backend -->
                            <input type="hidden" name="monto_cotizado" id="inputTotalGeneral" value="0">
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-6 mt-6">
                    <a href="{{ url('/servicios') }}"
                        class="text-gray-500 hover:text-gray-800 font-medium px-4 py-2">Cancelar</a>
                    <button type="submit"
                        class="bg-secondary hover:bg-yellow-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200">
                        <i class="fas fa-save mr-2"></i> Guardar Cotización
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPTS PARA LÓGICA DE CÁLCULO -->
    <script>
        // Pasamos los materiales de PHP a JS para usarlos en el select dinámico
        const catalogoMateriales = @json($materiales ?? []);
        let contadorFilas = 0;

        function agregarFilaMaterial() {
            const tbody = document.getElementById('bodyMateriales');
            const filaVacia = document.getElementById('fila-vacia');
            if (filaVacia) filaVacia.remove();

            contadorFilas++;

            // Creamos las opciones del select
            let opciones = '<option value="">-- Seleccionar --</option>';
            catalogoMateriales.forEach(mat => {
                opciones +=
                    `<option value="${mat.id_material}" data-precio="${mat.precio_referencial}">${mat.nombre} (${mat.unidad})</option>`;
            });

            const row = document.createElement('tr');
            row.id = `fila-${contadorFilas}`;
            row.innerHTML = `
            <td class="px-4 py-2">
                <select name="materiales[${contadorFilas}][id]" class="w-full border border-gray-300 rounded p-1 text-sm" onchange="actualizarPrecioReferencial(this, ${contadorFilas})" required>
                    ${opciones}
                </select>
            </td>
            <td class="px-4 py-2">
                <input type="number" name="materiales[${contadorFilas}][cantidad]" step="1" min="1" value="1" class="w-full border border-gray-300 rounded p-1 text-center text-sm" oninput="calcularSubtotal(${contadorFilas})" required>
            </td>
            <td class="px-4 py-2">
                <input type="number" name="materiales[${contadorFilas}][precio]" step="0.01" min="0" value="0.00" class="w-full border border-gray-300 rounded p-1 text-right text-sm" oninput="calcularSubtotal(${contadorFilas})" required>
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
        }

        function actualizarPrecioReferencial(select, id) {
            // Al seleccionar un material, ponemos su precio referencial automáticamente
            const precio = select.options[select.selectedIndex].getAttribute('data-precio');
            if (precio) {
                const inputPrecio = document.querySelector(`#fila-${id} input[name="materiales[${id}][precio]"]`);
                inputPrecio.value = precio;
                calcularSubtotal(id);
            }
        }

        function calcularSubtotal(id) {
            const fila = document.getElementById(`fila-${id}`);
            const cant = parseFloat(fila.querySelector(`input[name="materiales[${id}][cantidad]"]`).value) || 0;
            const precio = parseFloat(fila.querySelector(`input[name="materiales[${id}][precio]"]`).value) || 0;

            const subtotal = cant * precio;

            // Actualizar visualización
            fila.querySelector(`#subtotal-${id}`).innerText = subtotal.toFixed(2);
            // Actualizar valor oculto para sumar fácil
            fila.querySelector(`.subtotal-input`).value = subtotal;

            calcularTotalGeneral();
        }

        function eliminarFila(id) {
            document.getElementById(`fila-${id}`).remove();
            calcularTotalGeneral();

            // Si no quedan filas, volver a poner el mensaje vacío
            const tbody = document.getElementById('bodyMateriales');
            if (tbody.children.length === 0) {
                tbody.innerHTML = `
                <tr id="fila-vacia">
                    <td colspan="5" class="px-4 py-6 text-center text-gray-400 italic">
                        No hay materiales agregados a la cotización.
                    </td>
                </tr>`;
            }
        }

        function calcularTotalGeneral() {
            // Sumar Materiales
            let totalMateriales = 0;
            document.querySelectorAll('.subtotal-input').forEach(input => {
                totalMateriales += parseFloat(input.value) || 0;
            });

            // Sumar Mano de Obra
            const manoObra = parseFloat(document.getElementById('manoDeObra').value) || 0;

            // Total
            const total = totalMateriales + manoObra;

            // Actualizar UI
            document.getElementById('resumenMateriales').innerText = 'S/ ' + totalMateriales.toFixed(2);
            document.getElementById('totalGeneralDisplay').innerText = 'S/ ' + total.toFixed(2);
            document.getElementById('inputTotalGeneral').value = total.toFixed(2);
        }
    </script>
@endsection
