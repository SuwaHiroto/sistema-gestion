@extends('layouts.admin')

@section('content')

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Cátalogo de Materiales</h2>
            <p class="text-sm text-gray-500">Administra el catálogo de productos y precios referenciales.</p>
        </div>
        <!-- Botón para cancelar edición -->
        <button onclick="limpiarFormulario()" id="btnCancelar"
            class="hidden bg-gray-200 text-gray-700 hover:bg-gray-300 font-bold py-2 px-4 rounded shadow-sm transition">
            <i class="fas fa-times mr-2"></i> Cancelar Edición
        </button>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            <p><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <p class="font-bold">Error:</p>
            <ul class="list-disc ml-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- COLUMNA 1: FORMULARIO (Registro / Edición) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 sticky top-6">
                <div class="bg-indigo-600 px-6 py-4 border-b border-indigo-700" id="headerForm">
                    <h3 class="font-bold text-white flex items-center gap-2" id="tituloForm">
                        <i class="fas fa-box-open"></i> Nuevo Material
                    </h3>
                </div>

                <form action="{{ route('materiales.store') }}" method="POST" class="p-6" id="formMaterial">
                    @csrf
                    <!-- Campo oculto para método PUT -->
                    <div id="methodField"></div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nombre del Material</label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                            class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Ej: Cable #14 THW" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Unidad</label>
                            <select name="unidad" id="unidad"
                                class="w-full bg-white border border-gray-300 rounded-lg p-2.5 text-sm">
                                <option value="unidad">Unidad</option>
                                <option value="metro">Metro</option>
                                <option value="caja">Caja</option>
                                <option value="rollo">Rollo</option>
                                <option value="paquete">Paquete</option>
                                <option value="juego">Juego</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Precio Ref. (S/)</label>
                            <input type="number" step="0.01" min="0" name="precio_referencial"
                                id="precio_referencial" value="{{ old('precio_referencial') }}"
                                class="w-full bg-white border border-gray-300 rounded-lg p-2.5 text-sm font-bold text-right"
                                placeholder="0.00" required>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-lg shadow transition">
                        Guardar Material
                    </button>
                </form>
            </div>
        </div>

        <!-- COLUMNA 2: LISTA (2/3) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">

                <!-- Buscador rápido -->
                <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <form action="{{ route('materiales.index') }}" method="GET" class="relative w-full max-w-md">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2.5"
                            placeholder="Buscar material...">
                    </form>
                    <span class="text-sm text-gray-500">Items: <strong>{{ $materiales->total() }}</strong></span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4">Descripción</th>
                                <th class="px-6 py-4 text-center">Unidad</th>
                                <th class="px-6 py-4 text-right">Precio Ref.</th>
                                <th class="px-6 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($materiales as $mat)
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $mat->nombre }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs border border-gray-200 uppercase">
                                            {{ $mat->unidad }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-700">
                                        S/ {{ number_format($mat->precio_referencial, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick='editarMaterial(@json($mat))'
                                            class="text-indigo-500 hover:text-indigo-700 p-2 rounded-full hover:bg-indigo-50 transition"
                                            title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-box-open text-3xl mb-2 text-gray-300"></i>
                                        <p>No hay materiales registrados.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-100">
                    {{ $materiales->links() }}
                </div>
            </div>
        </div>

    </div>

    <!-- SCRIPT PARA EDITAR -->
    <script>
        function editarMaterial(material) {
            // 1. Cambiar visualmente el formulario
            document.getElementById('headerForm').classList.replace('bg-indigo-600', 'bg-yellow-500');
            document.getElementById('headerForm').classList.replace('border-indigo-700', 'border-yellow-600');
            document.getElementById('tituloForm').innerHTML = '<i class="fas fa-edit"></i> Editar Material';
            document.getElementById('btnSubmit').innerText = 'Actualizar Precio';
            document.getElementById('btnSubmit').classList.replace('bg-indigo-600', 'bg-yellow-600');
            document.getElementById('btnSubmit').classList.replace('hover:bg-indigo-700', 'hover:bg-yellow-700');

            document.getElementById('btnCancelar').classList.remove('hidden');

            // 2. Rellenar campos
            document.getElementById('nombre').value = material.nombre;
            document.getElementById('unidad').value = material.unidad;
            document.getElementById('precio_referencial').value = material.precio_referencial;

            // 3. Cambiar acción del formulario
            const form = document.getElementById('formMaterial');
            form.action = `/materiales/${material.id_material}`;

            // 4. Agregar método PUT
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function limpiarFormulario() {
            // Resetear visualmente
            document.getElementById('headerForm').classList.replace('bg-yellow-500', 'bg-indigo-600');
            document.getElementById('headerForm').classList.replace('border-yellow-600', 'border-indigo-700');
            document.getElementById('tituloForm').innerHTML = '<i class="fas fa-box-open"></i> Nuevo Material';
            document.getElementById('btnSubmit').innerText = 'Guardar Material';
            document.getElementById('btnSubmit').classList.replace('bg-yellow-600', 'bg-indigo-600');
            document.getElementById('btnSubmit').classList.replace('hover:bg-yellow-700', 'hover:bg-indigo-700');

            document.getElementById('btnCancelar').classList.add('hidden');

            // Limpiar inputs
            document.getElementById('formMaterial').reset();

            // Restaurar acción STORE
            document.getElementById('formMaterial').action = "{{ route('materiales.store') }}";

            // Quitar método PUT
            document.getElementById('methodField').innerHTML = '';
        }
    </script>

@endsection
