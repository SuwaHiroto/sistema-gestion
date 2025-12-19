@push('styles')
    {{-- Asegúrate de que este archivo exista o usa tus estilos inline --}}
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
@endpush

<x-app-layout>
    <div class="container py-5"> {{-- Agregué container py-5 para margen --}}

        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h2 class="fw-bold text-primary mb-1" style="font-size: 2rem;">Panel de Control</h2>
                <p class="text-muted mb-0">Resumen rápido de actividad.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <small class="text-muted">Hola, <span class="fw-semibold">{{ Auth::user()->email }}</span></small>
            </div>
        </div>

        <div class="row g-4">
            {{-- Tarjeta Clientes --}}
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3 bg-light rounded-circle p-3">
                                <i class="bi bi-people fs-2 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Clientes</h6>
                                <h4 class="fw-bold mt-1 mb-0">{{ $conteo['clientes'] }}</h4>
                                <small class="text-muted" style="font-size: 0.8rem;">Total registrados</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tarjeta Servicios Mes --}}
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3 bg-light rounded-circle p-3">
                                <i class="bi bi-tools fs-2 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Servicios (Mes)</h6>
                                <h4 class="fw-bold mt-1 mb-0">{{ $conteo['servicios'] }}</h4>
                                <small class="text-muted" style="font-size: 0.8rem;">Realizados este mes</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tarjeta Cobros Hoy --}}
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3 bg-light rounded-circle p-3">
                                <i class="bi bi-currency-dollar fs-2 text-success"></i> {{-- Cambié a verde --}}
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Ingresos (Hoy)</h6>
                                <h4 class="fw-bold mt-1 mb-0">S/ {{ number_format($conteo['pagos_hoy'], 2) }}</h4>
                                <small class="text-muted" style="font-size: 0.8rem;">Caja del día</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tarjeta Pendientes --}}
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3 bg-light rounded-circle p-3">
                                <i class="bi bi-exclamation-triangle fs-2 text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Pendientes</h6>
                                <h4 class="fw-bold mt-1 mb-0">{{ $conteo['pendientes'] }}</h4>
                                <small class="text-muted" style="font-size: 0.8rem;">Por finalizar</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Accesos Rápidos (Opcional) --}}
        <h5 class="mt-5 mb-3 fw-bold text-secondary">Accesos Rápidos</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('admin.servicios.create_interno') }}" class="btn btn-outline-primary w-100 py-3">
                    <i class="bi bi-plus-circle d-block fs-3 mb-1"></i>
                    Nuevo Servicio
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.clientes.index') }}" class="btn btn-outline-dark w-100 py-3">
                    <i class="bi bi-person-plus d-block fs-3 mb-1"></i>
                    Nuevo Cliente
                </a>
            </div>
        </div>

    </div>
</x-app-layout>
