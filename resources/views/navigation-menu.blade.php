<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
    <div class="container-fluid">
        {{-- LOGO --}}
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
            {{-- Asegúrate de que la ruta de la imagen sea correcta --}}
            <img src="{{ asset('img/logo.png') }}" alt="EG" style="height: 30px; margin-right: 10px;"
                onerror="this.style.display='none'" />
            Electrigonza
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">

                {{-- =========================================================
                     MENÚ PARA ADMINISTRADORES
                     ========================================================= --}}
                @if (Auth::user()->rol == 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.servicios.*') ? 'active' : '' }}"
                            href="{{ route('admin.servicios.index') }}">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.clientes.*') ? 'active' : '' }}"
                            href="{{ route('admin.clientes.index') }}">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.materiales.*') ? 'active' : '' }}"
                            href="{{ route('admin.materiales.index') }}">Materiales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}"
                            href="{{ route('admin.usuarios.index') }}">Personal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}"
                            href="{{ route('admin.reportes.index') }}">Reportes</a>
                    </li>

                    {{-- =========================================================
                     MENÚ PARA TÉCNICOS
                     ========================================================= --}}
                @elseif(Auth::user()->rol == 'tecnico')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.servicios.*') ? 'active' : '' }}"
                            href="{{ route('admin.servicios.index') }}">Mis Asignaciones</a>
                    </li>

                    {{-- =========================================================
                     MENÚ PARA CLIENTES (PORTAL)
                     ========================================================= --}}
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cliente.servicios.index') ? 'active' : '' }}"
                            href="{{ route('cliente.servicios.index') }}">Mis Solicitudes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cliente.servicios.create') ? 'active' : '' }}"
                            href="{{ route('cliente.servicios.create') }}">Nueva Solicitud</a>
                    </li>
                @endif

                {{-- =========================================================
                     DROPDOWN PERFIL (COMÚN PARA TODOS)
                     ========================================================= --}}
                <li class="nav-item dropdown ms-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown">
                        <span class="me-2 d-none d-lg-inline">{{ Auth::user()->name ?: Auth::user()->email }}</span>
                        {{-- Icono de usuario si no hay foto --}}
                        <i class="bi bi-person-circle fs-5"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarDropdown">
                        <li>
                            <h6 class="dropdown-header">Cuenta</h6>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="bi bi-gear me-2"></i> Configuración
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>
