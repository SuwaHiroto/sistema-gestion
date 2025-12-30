@extends('layouts.admin')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Equipo Técnico</h2>
            <p class="text-slate-500 mt-1">Gestión de personal operativo, credenciales y especialidades.</p>
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
            <p class="font-bold flex items-center gap-2"><i class="fas fa-exclamation-triangle"></i> Corrija los errores:
            </p>
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
                        <i class="fas fa-user-hard-hat text-yellow-400"></i> Nuevo Técnico
                    </h3>
                </div>

                <form action="{{ route('tecnicos.store') }}" method="POST" class="p-6 space-y-4" id="formTecnico">
                    @csrf
                    <div id="methodField"></div>

                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Acceso al Sistema</p>
                        <div>
                            <label class="block text-slate-700 text-xs font-bold mb-1">Correo Electrónico</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="w-full bg-white border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition"
                                required placeholder="usuario@empresa.com">
                        </div>
                        <div>
                            <label class="block text-slate-700 text-xs font-bold mb-1">Contraseña</label>
                            <input type="password" name="password" id="password"
                                class="w-full bg-white border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition"
                                placeholder="******">
                            <p class="text-[10px] text-orange-600 mt-1 hidden font-medium flex items-center gap-1"
                                id="passwordHint">
                                <i class="fas fa-info-circle"></i> Dejar vacío para mantener la actual.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-700 text-xs font-bold mb-1">Nombres</label>
                                <input type="text" name="nombres" id="nombres" value="{{ old('nombres') }}"
                                    class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-400 outline-none transition"
                                    required>
                            </div>
                            <div>
                                <label class="block text-slate-700 text-xs font-bold mb-1">Ap. Paterno</label>
                                <input type="text" name="apellido_paterno" id="apellido_paterno"
                                    value="{{ old('apellido_paterno') }}"
                                    class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-400 outline-none transition"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-slate-700 text-xs font-bold mb-1">Ap. Materno</label>
                            <input type="text" name="apellido_materno" id="apellido_materno"
                                value="{{ old('apellido_materno') }}"
                                class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-400 outline-none transition">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-700 text-xs font-bold mb-1">DNI</label>
                                <input type="text" name="dni" id="dni" value="{{ old('dni') }}"
                                    maxlength="8"
                                    class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-400 outline-none transition"
                                    required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8);">
                            </div>
                            <div>
                                <label class="block text-slate-700 text-xs font-bold mb-1">Teléfono</label>
                                <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}"
                                    maxlength="9"
                                    class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-400 outline-none transition"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9);">
                            </div>
                        </div>

                        <div>
                            <label class="block text-slate-700 text-xs font-bold mb-1">Especialidad</label>
                            <div class="relative">
                                <select name="especialidad" id="especialidad"
                                    class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-400 outline-none transition appearance-none"
                                    required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Electricidad General">Electricidad General</option>
                                    <option value="Instalaciones Industriales">Instalaciones Industriales</option>
                                    <option value="Mantenimiento">Mantenimiento</option>
                                    <option value="Domótica">Domótica</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmit"
                        class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-slate-900/20 transition-all transform hover:-translate-y-0.5 flex justify-center items-center gap-2 mt-4">
                        <span>Registrar Técnico</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

                <div
                    class="p-5 border-b border-slate-100 bg-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-6 bg-yellow-400 rounded-full"></span>
                        <h3 class="font-bold text-slate-700">Listado de Personal</h3>
                        @if (request('ver_inactivos'))
                            <span
                                class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-xs font-bold border border-red-200">Papelera</span>
                        @endif
                    </div>

                    @if (request('ver_inactivos'))
                        <a href="{{ route('tecnicos.index') }}"
                            class="bg-white border border-slate-300 text-slate-600 hover:bg-slate-50 font-medium py-2 px-4 rounded-lg text-xs shadow-sm transition flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i> Volver a Activos
                        </a>
                    @else
                        <a href="{{ route('tecnicos.index', ['ver_inactivos' => 1]) }}"
                            class="bg-slate-200 hover:bg-slate-300 text-slate-600 font-medium py-2 px-4 rounded-lg text-xs transition flex items-center gap-2">
                            <i class="fas fa-archive"></i> Ver Bajas
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-50/50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 font-bold">Técnico</th>
                                <th class="px-6 py-4 font-bold">Contacto</th>
                                <th class="px-6 py-4 font-bold text-center">Especialidad</th>
                                <th class="px-6 py-4 font-bold text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($tecnicos as $tec)
                                <tr
                                    class="hover:bg-slate-50 transition group {{ !$tec->estado ? 'bg-slate-50 opacity-60' : '' }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="relative">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold border-2 border-white shadow-sm group-hover:bg-yellow-400 group-hover:text-slate-900 transition-colors">
                                                    {{ substr($tec->nombres, 0, 1) }}
                                                </div>
                                                <span
                                                    class="absolute bottom-0 right-0 w-3 h-3 rounded-full border-2 border-white {{ $tec->estado ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                            </div>
                                            <div>
                                                <div
                                                    class="font-bold text-slate-800 {{ !$tec->estado ? 'line-through text-slate-400' : '' }}">
                                                    {{ $tec->nombres }} {{ $tec->apellido_paterno }}
                                                </div>
                                                <div class="text-xs text-slate-400 font-mono">
                                                    {{ $tec->usuario->email ?? 'Sin acceso web' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-700">DNI: {{ $tec->dni }}</div>
                                        <div class="text-xs text-slate-500"><i class="fas fa-phone mr-1"></i>
                                            {{ $tec->telefono }}</div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="bg-white text-slate-600 py-1 px-3 rounded-full text-xs font-bold border border-slate-200 shadow-sm">
                                            {{ $tec->especialidad }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if ($tec->estado)
                                            <div
                                                class="flex justify-center items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button
                                                    onclick='editarTecnico(@json($tec), @json($tec->usuario))'
                                                    class="w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-500 hover:text-yellow-600 hover:border-yellow-400 flex items-center justify-center transition shadow-sm"
                                                    title="Editar">
                                                    <i class="fas fa-pen text-xs"></i>
                                                </button>

                                                <form action="{{ route('tecnicos.destroy', $tec->id_tecnico) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('¿Desactivar a {{ $tec->nombres }}? El usuario no podrá iniciar sesión.');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-500 hover:text-red-600 hover:border-red-400 flex items-center justify-center transition shadow-sm"
                                                        title="Dar de baja">
                                                        <i class="fas fa-user-slash text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span
                                                class="text-xs font-bold text-red-400 uppercase bg-red-50 px-2 py-1 rounded border border-red-100">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                                <i class="fas fa-hard-hat text-2xl opacity-50"></i>
                                            </div>
                                            <p>No hay técnicos registrados.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100 bg-slate-50">{{ $tecnicos->links() }}</div>
            </div>
        </div>
    </div>

    <script>
        function editarTecnico(tecnico, usuario) {
            // 1. Cambios Visuales (Modo Edición: Amarillo)
            const header = document.getElementById('headerForm');
            const btn = document.getElementById('btnSubmit');
            const title = document.getElementById('tituloForm');

            // Header: Slate -> Yellow
            header.classList.remove('bg-slate-900', 'border-slate-800');
            header.classList.add('bg-yellow-500', 'border-yellow-600');

            // Titulo
            title.classList.remove('text-white');
            title.classList.add('text-slate-900');
            title.innerHTML = '<i class="fas fa-edit"></i> Editar Técnico';

            // Botón
            btn.innerHTML = '<span>Guardar Cambios</span> <i class="fas fa-save"></i>';
            btn.classList.remove('bg-slate-900', 'hover:bg-slate-800', 'text-white');
            btn.classList.add('bg-yellow-500', 'hover:bg-yellow-600', 'text-slate-900', 'shadow-yellow-500/30');

            // Mostrar ayudas
            document.getElementById('btnCancelar').classList.remove('hidden');
            document.getElementById('passwordHint').classList.remove('hidden');
            document.getElementById('password').removeAttribute('required'); // Pass opcional

            // 2. Llenar Campos
            document.getElementById('nombres').value = tecnico.nombres;
            document.getElementById('apellido_paterno').value = tecnico.apellido_paterno;
            document.getElementById('apellido_materno').value = tecnico.apellido_materno;
            document.getElementById('dni').value = tecnico.dni;
            document.getElementById('telefono').value = tecnico.telefono;
            document.getElementById('especialidad').value = tecnico.especialidad;

            if (usuario) document.getElementById('email').value = usuario.email;

            // 3. Configurar Acción PUT (Ruta Segura)
            const form = document.getElementById('formTecnico');
            const baseUrl = "{{ route('tecnicos.index') }}";
            form.action = `${baseUrl}/${tecnico.id_tecnico}`;

            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // 4. Scroll
            header.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        function limpiarFormulario() {
            // 1. Restaurar Visuales (Modo Crear: Slate)
            const header = document.getElementById('headerForm');
            const btn = document.getElementById('btnSubmit');
            const title = document.getElementById('tituloForm');

            header.classList.add('bg-slate-900', 'border-slate-800');
            header.classList.remove('bg-yellow-500', 'border-yellow-600');

            title.classList.add('text-white');
            title.classList.remove('text-slate-900');
            title.innerHTML = '<i class="fas fa-user-hard-hat text-yellow-400"></i> Nuevo Técnico';

            btn.innerHTML = '<span>Registrar Técnico</span> <i class="fas fa-arrow-right"></i>';
            btn.classList.add('bg-slate-900', 'hover:bg-slate-800', 'text-white');
            btn.classList.remove('bg-yellow-500', 'hover:bg-yellow-600', 'text-slate-900', 'shadow-yellow-500/30');

            document.getElementById('btnCancelar').classList.add('hidden');
            document.getElementById('passwordHint').classList.add('hidden');
            document.getElementById('password').setAttribute('required', 'required'); // Pass obligatorio

            // 2. Limpiar
            document.getElementById('formTecnico').reset();
            document.getElementById('formTecnico').action = "{{ route('tecnicos.store') }}";
            document.getElementById('methodField').innerHTML = '';
        }
    </script>

@endsection
