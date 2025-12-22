@extends('layouts.admin')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Gestión de Clientes</h2>
            <p class="text-slate-500 mt-1">Administra la base de datos de usuarios y sus accesos.</p>
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
            <p class="font-bold flex items-center gap-2"><i class="fas fa-exclamation-triangle"></i> Corrija los siguientes
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
                        <i class="fas fa-user-plus text-yellow-400"></i> Nuevo Cliente
                    </h3>
                </div>

                <form action="{{ route('clientes.store') }}" method="POST" class="p-6 space-y-5" id="formCliente">
                    @csrf
                    <div id="methodField"></div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2">Correo Electrónico</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i
                                    class="far fa-envelope"></i></span>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-10 p-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition"
                                required placeholder="ejemplo@correo.com">
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2">Contraseña</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i
                                    class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="password"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-10 p-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition"
                                placeholder="Mínimo 8 caracteres">
                        </div>
                        <p class="text-xs text-orange-600 mt-2 hidden font-medium flex items-center gap-1"
                            id="passwordHint">
                            <i class="fas fa-info-circle"></i> Déjalo vacío para mantener la contraseña actual.
                        </p>
                    </div>

                    <div class="relative py-2">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <div class="relative flex justify-center"><span
                                class="bg-white px-2 text-xs text-slate-400 uppercase tracking-widest">Datos
                                Personales</span></div>
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2">Nombre / Razón Social</label>
                        <input type="text" name="nombres" id="nombres" value="{{ old('nombres') }}"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-yellow-400 outline-none transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-yellow-400 outline-none transition"
                            maxlength="9" pattern="\d{9}" placeholder="999000111"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9);">
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2">Dirección</label>
                        <textarea name="direccion" id="direccion" rows="3"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-yellow-400 outline-none transition resize-none">{{ old('direccion') }}</textarea>
                    </div>

                    <button type="submit" id="btnSubmit"
                        class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-slate-900/20 hover:shadow-xl transition-all transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
                        <span>Registrar Cliente</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

                <div
                    class="p-5 border-b border-slate-100 bg-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">

                    <form action="{{ route('clientes.index') }}" method="GET" class="relative w-full max-w-md">
                        @if (request('ver_inactivos'))
                            <input type="hidden" name="ver_inactivos" value="1">
                        @endif
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-slate-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent block w-full pl-10 p-2.5 outline-none transition shadow-sm"
                            placeholder="Buscar cliente...">
                    </form>

                    <div class="flex items-center gap-3">
                        @if (request('ver_inactivos'))
                            <a href="{{ route('clientes.index') }}"
                                class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-medium py-2 px-4 rounded-lg text-xs shadow-sm transition flex items-center gap-2">
                                <i class="fas fa-eye-slash text-slate-400"></i> Ocultar Bajas
                            </a>
                        @else
                            <a href="{{ route('clientes.index', ['ver_inactivos' => 1]) }}"
                                class="bg-slate-200 hover:bg-slate-300 text-slate-600 font-medium py-2 px-4 rounded-lg text-xs transition flex items-center gap-2">
                                <i class="fas fa-history"></i> Ver Papelera
                            </a>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-50/50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 font-bold text-slate-600">Cliente</th>
                                <th class="px-6 py-4 font-bold text-slate-600">Contacto</th>
                                <th class="px-6 py-4 font-bold text-slate-600">Ubicación</th>
                                <th class="px-6 py-4 font-bold text-slate-600 text-center">Estado</th>
                                <th class="px-6 py-4 font-bold text-slate-600 text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($clientes as $cli)
                                <tr
                                    class="hover:bg-slate-50 transition duration-150 group {{ !$cli->estado ? 'bg-slate-50 opacity-60' : '' }}">

                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="relative">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold border-2 border-white shadow-sm group-hover:bg-yellow-400 group-hover:text-slate-900 transition-colors">
                                                    {{ substr($cli->nombres, 0, 1) }}
                                                </div>
                                                <span
                                                    class="absolute bottom-0 right-0 w-3 h-3 rounded-full border-2 border-white {{ $cli->estado ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                            </div>
                                            <div>
                                                <div
                                                    class="font-bold text-slate-800 {{ !$cli->estado ? 'line-through text-slate-500' : '' }}">
                                                    {{ $cli->nombres }}
                                                </div>
                                                <div
                                                    class="text-xs text-slate-400 group-hover:text-yellow-600 transition-colors font-medium">
                                                    {{ $cli->usuario->email ?? 'Sin usuario' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-700">
                                            <i
                                                class="fas fa-phone-alt text-slate-300 mr-2"></i>{{ $cli->telefono ?? '---' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span
                                            class="text-slate-500 text-xs bg-slate-100 px-2 py-1 rounded inline-block max-w-[150px] truncate"
                                            title="{{ $cli->direccion }}">
                                            {{ $cli->direccion ?? 'Sin dirección' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if ($cli->estado)
                                            <span
                                                class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200">
                                                ACTIVO
                                            </span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold border border-red-200">
                                                BAJA
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if ($cli->estado)
                                            <div
                                                class="flex justify-center items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button
                                                    onclick='editarCliente(@json($cli), @json($cli->usuario))'
                                                    class="w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-500 hover:text-yellow-600 hover:border-yellow-400 flex items-center justify-center transition shadow-sm"
                                                    title="Editar">
                                                    <i class="fas fa-pen text-xs"></i>
                                                </button>

                                                <form action="{{ route('clientes.destroy', $cli->id_cliente) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('¿Desactivar a {{ $cli->nombres }}?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-500 hover:text-red-600 hover:border-red-400 flex items-center justify-center transition shadow-sm"
                                                        title="Dar de baja">
                                                        <i class="fas fa-trash-alt text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 italic">--</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <div
                                                class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                                <i class="fas fa-users-slash text-2xl opacity-50"></i>
                                            </div>
                                            <p>No se encontraron clientes.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $clientes->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function editarCliente(cliente, usuario) {
            // 1. Cambios Visuales (Modo Edición: Amarillo Advertencia)
            const header = document.getElementById('headerForm');
            const btn = document.getElementById('btnSubmit');
            const title = document.getElementById('tituloForm');

            // Header: Cambia de Slate (Oscuro) a Yellow (Atención)
            header.classList.remove('bg-slate-900', 'border-slate-800');
            header.classList.add('bg-yellow-500', 'border-yellow-600');

            // Texto Header: Blanco -> Oscuro (para contraste)
            title.classList.remove('text-white');
            title.classList.add('text-slate-900');
            title.innerHTML = '<i class="fas fa-edit"></i> Editar Cliente';

            // Botón: Slate -> Yellow
            btn.innerHTML = 'Guardar Cambios';
            btn.classList.remove('bg-slate-900', 'hover:bg-slate-800', 'text-white');
            btn.classList.add('bg-yellow-500', 'hover:bg-yellow-600', 'text-slate-900', 'shadow-yellow-500/30');

            // 2. Mostrar ayudas
            document.getElementById('btnCancelar').classList.remove('hidden');
            document.getElementById('passwordHint').classList.remove('hidden');
            document.getElementById('password').removeAttribute('required');

            // 3. Rellenar Datos
            document.getElementById('nombres').value = cliente.nombres;
            document.getElementById('telefono').value = cliente.telefono;
            document.getElementById('direccion').value = cliente.direccion;
            if (usuario) document.getElementById('email').value = usuario.email;

            // 4. Configurar Ruta UPDATE (PUT)
            document.getElementById('formCliente').action =
            `/admin/clientes/${cliente.id_cliente}`; // Verifica que el prefijo sea /admin/ si usas esa ruta
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // 5. Scroll suave al formulario
            header.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        function limpiarFormulario() {
            // 1. Restaurar Visuales (Modo Crear: Slate Corporativo)
            const header = document.getElementById('headerForm');
            const btn = document.getElementById('btnSubmit');
            const title = document.getElementById('tituloForm');

            // Header: Yellow -> Slate
            header.classList.add('bg-slate-900', 'border-slate-800');
            header.classList.remove('bg-yellow-500', 'border-yellow-600');

            // Texto Header
            title.classList.add('text-white');
            title.classList.remove('text-slate-900');
            title.innerHTML = '<i class="fas fa-user-plus text-yellow-400"></i> Nuevo Cliente';

            // Botón
            btn.innerHTML = '<span>Registrar Cliente</span> <i class="fas fa-arrow-right"></i>';
            btn.classList.add('bg-slate-900', 'hover:bg-slate-800', 'text-white');
            btn.classList.remove('bg-yellow-500', 'hover:bg-yellow-600', 'text-slate-900', 'shadow-yellow-500/30');

            // 2. Ocultar extras
            document.getElementById('btnCancelar').classList.add('hidden');
            document.getElementById('passwordHint').classList.add('hidden');
            document.getElementById('password').setAttribute('required', 'required');

            // 3. Limpiar Campos
            document.getElementById('formCliente').reset();
            document.getElementById('formCliente').action = "{{ route('clientes.store') }}";
            document.getElementById('methodField').innerHTML = '';
        }
    </script>
@endsection
