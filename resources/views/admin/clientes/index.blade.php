@extends('layouts.admin')

@section('content')

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestión de Clientes</h2>
            <p class="text-sm text-gray-500">Registra y administra la base de datos de usuarios.</p>
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

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 sticky top-6">
                <div class="bg-blue-600 px-6 py-4 border-b border-blue-700" id="headerForm">
                    <h3 class="font-bold text-white flex items-center gap-2" id="tituloForm">
                        <i class="fas fa-user-plus"></i> Nuevo Cliente
                    </h3>
                </div>

                <form action="{{ route('clientes.store') }}" method="POST" class="p-6" id="formCliente">
                    @csrf
                    <div id="methodField"></div>

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

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nombre Completo / Razón Social</label>
                        <input type="text" name="nombres" id="nombres" value="{{ old('nombres') }}"
                            class="w-full  bg-gray-50 border border-gray-300 rounded-lg p-2 text-sm" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Teléfono / Celular</label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}"
                            class="w-full  bg-gray-50 border border-gray-300 rounded-lg p-2 text-sm" maxlength="9" pattern="\d{9}"
                            title="Debe contener 9 dígitos numéricos"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9);">
                        <p class="text-xs text-gray-400 mt-1">Máximo 9 dígitos.</p>
                    </div>

                    <div class="mb-6">
                        <label class="block  text-gray-700 text-sm font-bold mb-2">Dirección Principal</label>
                        <textarea name="direccion" id="direccion" rows="2" class="w-full  bg-gray-50 border border-gray-300 rounded-lg p-2 text-sm">{{ old('direccion') }}</textarea>
                    </div>

                    <button type="submit" id="btnSubmit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow transition">
                        Registrar Cliente
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">

                <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <form action="{{ route('clientes.index') }}" method="GET" class="relative w-full max-w-md">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                            placeholder="Buscar cliente...">
                    </form>
                    <span class="text-sm text-gray-500">Total: <strong>{{ $clientes->total() }}</strong></span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4">Cliente</th>
                                <th class="px-6 py-4">Contacto</th>
                                <th class="px-6 py-4">Dirección</th>
                                <th class="px-6 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($clientes as $cli)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold border border-blue-200">
                                                {{ substr($cli->nombres, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-800">{{ $cli->nombres }}</div>
                                                <div class="text-xs text-blue-500 truncate w-32"
                                                    title="{{ $cli->usuario->email ?? '' }}">
                                                    {{ $cli->usuario->email ?? 'Sin usuario' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-gray-600"><i class="fas fa-phone mr-1 text-gray-400"></i>
                                            {{ $cli->telefono ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-500 text-xs" title="{{ $cli->direccion }}">
                                            {{ Str::limit($cli->direccion, 20) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button
                                            onclick='editarCliente(@json($cli), @json($cli->usuario))'
                                            class="text-blue-500 hover:text-blue-700 p-2 rounded-full hover:bg-blue-50 transition"
                                            title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <p>No hay clientes registrados.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="p-4 border-t border-gray-100">
                    {{ $clientes->links() }}
                </div>
            </div>
        </div>

    </div>

    <!-- SCRIPT PARA EDITAR -->
    <script>
        function editarCliente(cliente, usuario) {
            // 1. Cambiar visualmente el formulario
            document.getElementById('headerForm').classList.replace('bg-blue-600', 'bg-yellow-500');
            document.getElementById('headerForm').classList.replace('border-blue-700', 'border-yellow-600');
            document.getElementById('tituloForm').innerHTML = '<i class="fas fa-edit"></i> Editar Cliente';
            document.getElementById('btnSubmit').innerText = 'Actualizar Datos';
            document.getElementById('btnSubmit').classList.replace('bg-blue-600', 'bg-yellow-600');
            document.getElementById('btnSubmit').classList.replace('hover:bg-blue-700', 'hover:bg-yellow-700');

            // 2. Mostrar botón cancelar y hint de contraseña
            document.getElementById('btnCancelar').classList.remove('hidden');
            document.getElementById('passwordHint').classList.remove('hidden');
            document.getElementById('password').removeAttribute('required'); // Ya no es obligatoria al editar

            // 3. Rellenar campos
            document.getElementById('nombres').value = cliente.nombres;
            document.getElementById('telefono').value = cliente.telefono;
            document.getElementById('direccion').value = cliente.direccion;

            if (usuario) {
                document.getElementById('email').value = usuario.email;
            }

            // 4. Cambiar acción del formulario a UPDATE
            const form = document.getElementById('formCliente');
            // IMPORTANTE: Ajustamos la ruta al ID del cliente
            form.action = `/clientes/${cliente.id_cliente}`;

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
            document.getElementById('tituloForm').innerHTML = '<i class="fas fa-user-plus"></i> Nuevo Cliente';
            document.getElementById('btnSubmit').innerText = 'Registrar Cliente';
            document.getElementById('btnSubmit').classList.replace('bg-yellow-600', 'bg-blue-600');
            document.getElementById('btnSubmit').classList.replace('hover:bg-yellow-700', 'hover:bg-blue-700');

            // Ocultar cancelar y hint
            document.getElementById('btnCancelar').classList.add('hidden');
            document.getElementById('passwordHint').classList.add('hidden');
            document.getElementById('password').setAttribute('required', 'required');

            // Limpiar inputs
            document.getElementById('formCliente').reset();

            // Restaurar acción STORE
            document.getElementById('formCliente').action = "{{ route('clientes.store') }}";

            // Quitar método PUT
            document.getElementById('methodField').innerHTML = '';
        }
    </script>

@endsection
