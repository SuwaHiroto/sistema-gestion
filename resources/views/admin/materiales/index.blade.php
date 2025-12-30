@extends('layouts.admin')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Catálogo de Materiales</h2>
            <p class="text-slate-500 mt-1">Administra el inventario de productos y precios referenciales.</p>
        </div>
        <button onclick="limpiarFormulario()" id="btnCancelar"
            class="hidden bg-white border border-red-200 text-red-600 hover:bg-red-50 font-bold py-2.5 px-5 rounded-xl shadow-sm transition flex items-center gap-2">
            <i class="fas fa-times"></i> Cancelar Edición
        </button>
    </div>

    @if (session('success'))
        <div
            class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-8 rounded-r-xl shadow-sm flex items-center gap-3 animate-fade-in-down">
            <div class="bg-emerald-100 p-2 rounded-full"><i class="fas fa-check"></i></div>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-r-xl shadow-sm">
            <p class="font-bold flex items-center gap-2"><i class="fas fa-exclamation-triangle"></i> Revisa los siguientes
                errores:</p>
            <ul class="list-disc ml-8 text-sm mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-1">
            <div
                class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden sticky top-6">

                <div class="bg-slate-900 px-6 py-5 border-b border-slate-800 transition-colors duration-300"
                    id="headerForm">
                    <h3 class="font-bold text-white flex items-center gap-2 text-lg" id="tituloForm">
                        <i class="fas fa-box-open text-yellow-400"></i> Nuevo Material
                    </h3>
                </div>

                <form action="{{ route('materiales.store') }}" method="POST" class="p-6 space-y-5" id="formMaterial">
                    @csrf
                    <div id="methodField"></div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2">Nombre del Material</label>
                        <div class="relative">
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition placeholder-slate-400"
                                placeholder="Ej: Cable #14 THW" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-700 text-sm font-bold mb-2">Unidad</label>
                            <div class="relative">
                                <select name="unidad" id="unidad"
                                    class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition appearance-none">
                                    <option value="unidad">Unidad</option>
                                    <option value="metro">Metro</option>
                                    <option value="caja">Caja</option>
                                    <option value="rollo">Rollo</option>
                                    <option value="paquete">Paquete</option>
                                    <option value="juego">Juego</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-slate-700 text-sm font-bold mb-2">Precio Ref. (S/)</label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold text-sm">S/</span>
                                <input type="number" step="0.01" min="0" name="precio_referencial"
                                    id="precio_referencial" value="{{ old('precio_referencial') }}"
                                    class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-8 p-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition text-right"
                                    placeholder="0.00" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmit"
                        class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-slate-900/20 hover:shadow-xl transition-all transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
                        <span>Guardar Material</span>
                        <i class="fas fa-save"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

                <div
                    class="p-5 border-b border-slate-100 bg-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <form action="{{ route('materiales.index') }}" method="GET" class="relative w-full max-w-md">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-slate-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent block w-full pl-10 p-2.5 outline-none transition shadow-sm"
                            placeholder="Buscar material...">
                    </form>
                    <span
                        class="text-xs font-bold text-slate-500 uppercase tracking-wide bg-white px-3 py-1 rounded border border-slate-200">
                        Total Items: {{ $materiales->total() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-50/50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 font-bold text-slate-600">Descripción del Material</th>
                                <th class="px-6 py-4 font-bold text-slate-600 text-center">Unidad</th>
                                <th class="px-6 py-4 font-bold text-slate-600 text-right">Precio Ref.</th>
                                <th class="px-6 py-4 font-bold text-slate-600 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($materiales as $mat)
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="px-6 py-4 font-medium text-slate-800">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-yellow-100 group-hover:text-yellow-600 transition-colors">
                                                <i class="fas fa-cube text-xs"></i>
                                            </div>
                                            {{ $mat->nombre }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="bg-white text-slate-600 px-2.5 py-1 rounded text-xs font-bold border border-slate-200 uppercase tracking-wider">
                                            {{ $mat->unidad }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-mono font-bold text-slate-700 bg-slate-50 px-2 py-1 rounded">
                                            S/ {{ number_format($mat->precio_referencial, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick='editarMaterial(@json($mat))'
                                            class="w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-500 hover:text-yellow-600 hover:border-yellow-400 flex items-center justify-center transition shadow-sm mx-auto"
                                            title="Editar precio o nombre">
                                            <i class="fas fa-pen text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <div
                                                class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                                <i class="fas fa-box-open text-3xl opacity-50"></i>
                                            </div>
                                            <p class="font-medium">No se encontraron materiales.</p>
                                            <p class="text-xs mt-1">Registra uno nuevo en el formulario.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $materiales->links() }}
                </div>
            </div>
        </div>

    </div>

    <script>
        function editarMaterial(material) {
            // 1. Cambios Visuales
            const header = document.getElementById('headerForm');
            const btn = document.getElementById('btnSubmit');
            const title = document.getElementById('tituloForm');

            header.classList.remove('bg-slate-900', 'border-slate-800');
            header.classList.add('bg-yellow-500', 'border-yellow-600');

            title.classList.remove('text-white');
            title.classList.add('text-slate-900');
            title.innerHTML = '<i class="fas fa-edit"></i> Editar Material';

            btn.innerHTML = '<span>Actualizar Precio</span> <i class="fas fa-sync"></i>';
            btn.classList.remove('bg-slate-900', 'hover:bg-slate-800', 'text-white');
            btn.classList.add('bg-yellow-500', 'hover:bg-yellow-600', 'text-slate-900', 'shadow-yellow-500/30');

            document.getElementById('btnCancelar').classList.remove('hidden');

            // 2. Rellenar Datos
            document.getElementById('nombre').value = material.nombre;
            document.getElementById('unidad').value = material.unidad;
            document.getElementById('precio_referencial').value = material.precio_referencial;

            // 3. Configurar Acción UPDATE (Segura para Hosting)
            // Usamos la ruta base de Laravel en lugar de hardcodear /materiales/
            const form = document.getElementById('formMaterial');
            const baseUrl = "{{ route('materiales.index') }}";
            form.action = `${baseUrl}/${material.id_material}`;

            // 4. Inyectar PUT
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // 5. Scroll
            header.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        function limpiarFormulario() {
            // 1. Restaurar Visuales
            const header = document.getElementById('headerForm');
            const btn = document.getElementById('btnSubmit');
            const title = document.getElementById('tituloForm');

            header.classList.add('bg-slate-900', 'border-slate-800');
            header.classList.remove('bg-yellow-500', 'border-yellow-600');

            title.classList.add('text-white');
            title.classList.remove('text-slate-900');
            title.innerHTML = '<i class="fas fa-box-open text-yellow-400"></i> Nuevo Material';

            btn.innerHTML = '<span>Guardar Material</span> <i class="fas fa-save"></i>';
            btn.classList.add('bg-slate-900', 'hover:bg-slate-800', 'text-white');
            btn.classList.remove('bg-yellow-500', 'hover:bg-yellow-600', 'text-slate-900', 'shadow-yellow-500/30');

            document.getElementById('btnCancelar').classList.add('hidden');

            // 2. Limpiar
            document.getElementById('formMaterial').reset();
            // Restaurar ruta original STORE
            document.getElementById('formMaterial').action = "{{ route('materiales.store') }}";
            document.getElementById('methodField').innerHTML = '';
        }
    </script>

@endsection
