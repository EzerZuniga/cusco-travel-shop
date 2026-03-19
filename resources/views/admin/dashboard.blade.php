@extends('layout.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Panel de administración</h2>
        <small class="text-muted">Última actualización: {{ now()->format('d/m/Y H:i') }}</small>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title text-uppercase">Usuarios</h6>
                    <div class="display-6 fw-bold">{{ $usuarios_count ?? 0 }}</div>
                    <p class="text-muted mb-0">Registrados totales</p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-sm btn-outline-primary">Gestionar</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title text-uppercase">Tours</h6>
                    <div class="display-6 fw-bold">{{ $tours_count ?? 0 }}</div>
                    <p class="text-muted mb-0">Tours disponibles</p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.tours.index') }}" class="btn btn-sm btn-outline-primary">Gestionar</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title text-uppercase">Reservas</h6>
                    <div class="display-6 fw-bold">{{ $reservas_count ?? 0 }}</div>
                    <p class="text-muted mb-0">Reservas totales</p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.reservas.index') }}" class="btn btn-sm btn-outline-primary">Ver reservas</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title text-uppercase">Ingresos (30d)</h6>
                    <div class="display-6 fw-bold">{{ isset($ingresos_30d) ? number_format($ingresos_30d, 2) : '0.00' }}</div>
                    <p class="text-muted mb-0">{{ strtoupper($moneda ?? 'USD') }}</p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.reportes') }}" class="btn btn-sm btn-outline-primary">Ver reportes</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <strong>Reservas recientes</strong>
                </div>
                <div class="card-body p-0">
                    @if(!empty($reservas_recent) && $reservas_recent->count())
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Cliente</th>
                                        <th>Tour</th>
                                        <th>Asientos</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservas_recent as $reserva)
                                        <tr>
                                            <td>{{ $reserva->id }}</td>
                                            <td>{{ $reserva->usuario->nombre ?? '—' }}</td>
                                            <td>{{ $reserva->tour->titulo ?? '—' }}</td>
                                            <td>{{ $reserva->asientos ?? 1 }}</td>
                                            <td>{{ ucfirst($reserva->estado ?? 'pendiente') }}</td>
                                            <td>{{ optional($reserva->created_at)->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-muted">No hay reservas recientes.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <strong>Accesos rápidos</strong>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.tours.create') }}" class="btn btn-primary">Crear Tour</a>
                        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-outline-primary">Nuevo Usuario</a>
                        <a href="{{ route('admin.reservas.create') }}" class="btn btn-outline-secondary">Crear Reserva</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-muted small mt-3">Si ves datos desactualizados, ejecuta las tareas de mantenimiento: <code>php artisan cache:clear</code> y <code>php artisan view:clear</code>.</div>
</div>
@endsection

@push('styles')
<style>
    .display-6 { font-size: 1.75rem; }
</style>
@endpush

{{-- Dashboard admin mejorado: muestra estadísticas, reservas recientes y accesos rápidos --}}
