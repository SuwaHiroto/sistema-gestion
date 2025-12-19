@extends('layouts.admin')

@section('content')

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestión de Personal Técnico</h2>
            <p class="text-sm text-gray-500">Registra y administra a los técnicos de campo.</p>
        </div>
        <!-- Botón para limpiar/cancelar edición -->
        <button onclick="limpiarFormulario()" id="btnCancelar"
            class="hidden bg-red-100 text-gray-700 hover:bg-red-300 font-bold py-2 px-4 rounded shadow-sm transition">
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
                <div class="bg-blue-600 px-6 py-4 border-b border-blue-700" id="headerForm">
                    <h3 class="font-bold text-white flex items-center gap-2" id="tituloForm">
                        <i class="fas fa-user-plus"></i> Nuevo Técnico
                    </h3>
                </div>

                <form action="{{ route('tecnicos.store') }}" method="POST" class="p-6" id="formTecnico">
                    @csrf
                    <!-- Campo oculto para el método PUT (se activa con JS al editar) -->
                    <div id="methodField"></div>

                    <!-- Datos de Cuenta -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Correo Electrónico</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Contraseña</label>
                        <input type="password" name="password" id="password"
                            class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Mínimo 8 caracteres">
                        <p class="text-xs text-gray-400 mt-1 hidden" id="passwordHint">Dejar en blanco para mantener la
                            actual.</p>
                    </div>

                    <hr class="my-6 border-gray-100">

                    <!-- Datos Personales -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">Nombres</label>
                            <input type="text" name="nombres" id="nombres" value="{{ old('nombres') }}"
                                class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">Ap. Paterno</label>
                            <input type="text" name="apellido_paterno" id="apellido_paterno"
                                value="{{ old('apellido_paterno') }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Ap. Materno</label>
                        <input type="text" name="apellido_materno" id="apellido_materno"
                            value="{{ old('apellido_materno') }}"
                            class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">DNI</label>
                            <input type="text" name="dni" id="dni" value="{{ old('dni') }}" maxlength="8"
                                class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}"
                                maxlength="9" pattern="\d{9}" title="Debe contener 9 dígitos numéricos"
                                class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Especialidad</label>
                        <select name="especialidad" id="especialidad"
                            class="w-full bg-white border border-gray-300 rounded-lg p-2.5 text-sm">
                            <option value="Electricidad General">Electricidad General</option>
                            <option value="Instalaciones Industriales">Instalaciones Industriales</option>
                            <option value="Tableros Eléctricos">Tableros Eléctricos</option>
                            <option value="Domótica">Domótica</option>
                        </select>
                    </div>

                    <!-- Estado (Solo visible al editar o por defecto activo) -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Estado</label>
                        <select name="estado" id="estado"
                            class="w-full bg-white border border-gray-300 rounded-lg p-2.5 text-sm">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo (Baja)</option>
                        </select>
                    </div>

                    <button type="submit" id="btnSubmit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow transition">
                        Registrar Técnico
                    </button>
                </form>
            </div>
        </div>

        <!-- COLUMNA 2: LISTA (2/3) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4">Técnico</th>
                                <th class="px-6 py-4">Contacto</th>
                                <th class="px-6 py-4">Especialidad</th>
                                <th class="px-6 py-4 text-center">Estado</th>
                                <th class="px-6 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($tecnicos as $tec)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold border border-indigo-200">
                                                {{ substr($tec->nombres, 0, 1) }}{{ substr($tec->apellido_paterno, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-800">{{ $tec->nombres }}
                                                    {{ $tec->apellido_paterno }}</div>
                                                <div class="text-xs text-gray-400">DNI: {{ $tec->dni }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-gray-600"><i class="fas fa-phone mr-1 text-gray-400"></i>
                                            {{ $tec->telefono ?? '-' }}</div>
                                        <div class="text-xs text-blue-500 truncate w-32"
                                            title="{{ $tec->usuario->email ?? '' }}">
                                            {{ $tec->usuario->email ?? 'Sin usuario' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded border border-gray-200">
                                            {{ $tec->especialidad }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($tec->estado)
                                            <span
                                                class="text-green-700 bg-green-100 px-2 py-1 rounded-full text-xs font-bold border border-green-200">Activo</span>
                                        @else
                                            <span
                                                class="text-red-700 bg-red-100 px-2 py-1 rounded-full text-xs font-bold border border-red-200">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <!-- Botón Editar que llama a la función JS -->
                                        <button
                                            onclick='editarTecnico(@json($tec), @json($tec->usuario))'
                                            class="text-blue-500 hover:text-blue-700 p-2 rounded-full hover:bg-blue-50 transition"
                                            title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <p>No hay técnicos registrados.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- SCRIPT PARA EDITAR -->
    <script>
        function editarTecnico(tecnico, usuario) {
            // 1. Cambiar visualmente el formulario
            document.getElementById('headerForm').classList.replace('bg-blue-600', 'bg-yellow-500');
            document.getElementById('headerForm').classList.replace('border-blue-700', 'border-yellow-600');
            document.getElementById('tituloForm').innerHTML = '<i class="fas fa-edit"></i> Editar Técnico';
            document.getElementById('btnSubmit').innerText = 'Actualizar Datos';
            document.getElementById('btnSubmit').classList.replace('bg-blue-600', 'bg-yellow-600');
            document.getElementById('btnSubmit').classList.replace('hover:bg-blue-700', 'hover:bg-yellow-700');

            // 2. Mostrar botón cancelar y hint de contraseña
            document.getElementById('btnCancelar').classList.remove('hidden');
            document.getElementById('passwordHint').classList.remove('hidden');
            document.getElementById('password').removeAttribute('required'); // Ya no es obligatoria al editar

            // 3. Rellenar campos
            document.getElementById('nombres').value = tecnico.nombres;
            document.getElementById('apellido_paterno').value = tecnico.apellido_paterno;
            document.getElementById('apellido_materno').value = tecnico.apellido_materno;
            document.getElementById('dni').value = tecnico.dni;
            document.getElementById('telefono').value = tecnico.telefono;
            document.getElementById('especialidad').value = tecnico.especialidad;
            document.getElementById('estado').value = tecnico.estado ? '1' : '0';

            if (usuario) {
                document.getElementById('email').value = usuario.email;
            }

            // 4. Cambiar acción del formulario a UPDATE
            const form = document.getElementById('formTecnico');
            // IMPORTANTE: Asegúrate de que la ruta base sea correcta.
            // Si usas prefix 'admin', ajusta la URL. Aquí asumo /tecnicos/{id}
            form.action = `/tecnicos/${tecnico.id_tecnico}`;

            // 5. Agregar el campo oculto _method: PUT
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // 6. Scroll arriba para ver el formulario
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function limpiarFormulario() {
            // Resetear visualmente
            document.getElementById('headerForm').classList.replace('bg-yellow-500', 'bg-blue-600');
            document.getElementById('headerForm').classList.replace('border-yellow-600', 'border-blue-700');
            document.getElementById('tituloForm').innerHTML = '<i class="fas fa-user-plus"></i> Nuevo Técnico';
            document.getElementById('btnSubmit').innerText = 'Registrar Técnico';
            document.getElementById('btnSubmit').classList.replace('bg-yellow-600', 'bg-blue-600');
            document.getElementById('btnSubmit').classList.replace('hover:bg-yellow-700', 'hover:bg-blue-700');

            // Ocultar cancelar y hint
            document.getElementById('btnCancelar').classList.add('hidden');
            document.getElementById('passwordHint').classList.add('hidden');
            document.getElementById('password').setAttribute('required', 'required');

            // Limpiar inputs
            document.getElementById('formTecnico').reset();

            // Restaurar acción STORE
            document.getElementById('formTecnico').action = "{{ route('tecnicos.store') }}";

            // Quitar método PUT
            document.getElementById('methodField').innerHTML = '';
        }
    </script>

@endsection
