@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Nueva Solicitud</h2>
            <p class="text-slate-500 mt-1">Registra un nuevo servicio y genera su presupuesto inicial.</p>
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
                        <i class="fas fa-file-signature"></i>
                    </span>
                    Ficha de Servicio
                </h3>
                <span class="text-slate-400 text-sm font-mono">Folio: AUTO-GENERADO</span>
            </div>

            <form action="{{ route('servicios.store') }}" method="POST" class="p-8" id="formServicio">
                @csrf

                <div class="mb-8">
                    <h4
                        class="text-slate-800 font-bold uppercase tracking-wider text-xs mb-4 border-b border-slate-100 pb-2">
                        1. Información del Proyecto
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="col-span-1">
                            <label class="block text-slate-700 text-sm font-bold mb-2">Cliente <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i
                                        class="far fa-user"></i></span>
                                <select name="id_cliente" required
                                    class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-10 p-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition appearance-none">
                                    <option value="">-- Seleccionar Cliente --</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id_cliente }}"
                                            {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                                            {{ $cliente->nombres }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <label class="block text-slate-700 text-sm font-bold mb-2">Técnico Líder (Opcional)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i
                                        class="fas fa-hard-hat"></i></span>
                                <select name="id_tecnico"
                                    class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-10 p-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition appearance-none">
                                    <option value="">-- Por Asignar --</option>
                                    @foreach ($tecnicos as $tecnico)
                                        <option value="{{ $tecnico->id_tecnico }}"
                                            {{ old('id_tecnico') == $tecnico->id_tecnico ? 'selected' : '' }}>
                                            {{ $tecnico->nombres }} ({{ $tecnico->especialidad }})
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <label class="block text-slate-700 text-sm font-bold mb-2">Fecha de Inicio</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i
                                        class="far fa-calendar-alt"></i></span>
                                <input type="datetime-local" name="fecha_inicio"
                                    class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-10 p-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition"
                                    value="{{ old('fecha_inicio', now()->format('Y-m-d\TH:i')) }}">
                            </div>
                        </div>

                        <div class="col-span-1 md:col-span-3">
                            <label class="block text-slate-700 text-sm font-bold mb-2">Descripción del Requerimiento <span
                                    class="text-red-500">*</span></label>
                            <textarea name="descripcion_solicitud" rows="3"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl p-4 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition resize-none placeholder-slate-400"
                                placeholder="Describa detalladamente el problema eléctrico o la instalación requerida..." required>{{ old('descripcion_solicitud') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-2">
                        <h4 class="text-slate-800 font-bold uppercase tracking-wider text-xs">
                            2. Presupuesto y Materiales
                        </h4>
                        <button type="button" onclick="agregarFilaMaterial()"
                            class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-3 rounded-lg transition flex items-center gap-2 border border-slate-200">
                            <div
                                class="w-5 h-5 rounded-full bg-yellow-400 flex items-center justify-center text-slate-900 text-[10px]">
                                <i class="fas fa-plus"></i>
                            </div>
                            Agregar Item
                        </button>
                    </div>

                    <div class="bg-slate-50 rounded-xl border border-slate-200 overflow-hidden mb-6">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 uppercase bg-slate-100 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 font-bold w-5/12">Material / Insumo</th>
                                    <th class="px-4 py-3 font-bold w-2/12 text-center">Cant.</th>
                                    <th class="px-4 py-3 font-bold w-2/12 text-right">Precio Unit.</th>
                                    <th class="px-4 py-3 font-bold w-2/12 text-right">Subtotal</th>
                                    <th class="px-4 py-3 font-bold w-1/12 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white" id="bodyMateriales">
                                <tr id="fila-vacia">
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-400 italic bg-white">
                                        <i class="fas fa-box-open text-2xl mb-2 opacity-50 block"></i>
                                        No se han agregado materiales.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end">
                        <div
                            class="w-full md:w-1/3 bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-4 shadow-sm">
                            <div class="flex justify-between items-center text-slate-500 text-sm">
                                <span>Costo Materiales:</span>
                                <span class="font-medium" id="resumenMateriales">S/ 0.00</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <label class="text-slate-700 font-bold text-sm">Mano de Obra (S/):</label>
                                <input type="number" step="0.01" min="0" name="mano_obra" id="manoDeObra"
                                    class="w-32 bg-white border border-slate-300 rounded-lg p-2 text-right text-sm font-bold text-slate-700 focus:ring-2 focus:ring-yellow-400 outline-none transition"
                                    value="0.00" oninput="calcularTotalGeneral()">
                            </div>

                            <div class="border-t border-slate-200 pt-4 flex justify-between items-end">
                                <span class="font-bold text-slate-800 text-sm uppercase tracking-wide">Total Estimado</span>
                                <div class="text-right">
                                    <span class="block text-2xl font-black text-slate-900" id="totalGeneralDisplay">S/
                                        0.00</span>
                                    <span class="text-[10px] text-slate-400 uppercase font-bold">Impuestos incluidos</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 border-t border-slate-100 pt-6 mt-4">
                    <button type="submit"
                        class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-slate-900/20 transform hover:-translate-y-0.5 transition duration-200 flex items-center gap-2">
                        <span>Generar Orden</span>
                        <i class="fas fa-check-circle text-yellow-400"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const catalogoMateriales = @json($materiales ?? []);
        let contadorFilas = 0;

        function agregarFilaMaterial() {
            const tbody = document.getElementById('bodyMateriales');
            const filaVacia = document.getElementById('fila-vacia');
            if (filaVacia) filaVacia.remove();

            contadorFilas++;

            let opciones = '<option value="">-- Elegir --</option>';
            catalogoMateriales.forEach(mat => {
                opciones +=
                    `<option value="${mat.id_material}" data-precio="${mat.precio_referencial}">${mat.nombre} (${mat.unidad})</option>`;
            });

            const row = document.createElement('tr');
            row.id = `fila-${contadorFilas}`;
            const inputClass =
                "w-full bg-slate-50 border border-slate-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition";

            row.innerHTML = `
                <td class="px-4 py-2">
                    <select name="materiales[${contadorFilas}][id]" class="${inputClass}" onchange="actualizarPrecioReferencial(this, ${contadorFilas})" required>
                        ${opciones}
                    </select>
                </td>
                <td class="px-4 py-2">
                    <input type="number" name="materiales[${contadorFilas}][cantidad]" step="1" min="1" value="1" class="${inputClass} text-center" oninput="calcularSubtotal(${contadorFilas})" required>
                </td>
                <td class="px-4 py-2">
                    <input type="number" name="materiales[${contadorFilas}][precio]" step="0.01" min="0" value="0.00" class="${inputClass} text-right" oninput="calcularSubtotal(${contadorFilas})" required>
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
        }

        function actualizarPrecioReferencial(select, id) {
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

            fila.querySelector(`#subtotal-${id}`).innerText = subtotal.toFixed(2);
            fila.querySelector(`.subtotal-input`).value = subtotal;
            calcularTotalGeneral();
        }

        function eliminarFila(id) {
            document.getElementById(`fila-${id}`).remove();
            calcularTotalGeneral();
            const tbody = document.getElementById('bodyMateriales');
            if (tbody.children.length === 0) {
                tbody.innerHTML =
                    `<tr id="fila-vacia"><td colspan="5" class="px-4 py-8 text-center text-slate-400 italic bg-white"><i class="fas fa-box-open text-2xl mb-2 opacity-50 block"></i>No se han agregado materiales.</td></tr>`;
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
        }
    </script>
@endsection
