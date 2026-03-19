@extends('layout.app')

@section('content')
<div class="container py-5">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h2 class="mb-0">Usuarios</h2>
		<a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">Nuevo usuario</a>
	</div>

	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif
	@if(session('error'))
		<div class="alert alert-danger">{{ session('error') }}</div>
	@endif

	<div class="card mb-4">
		<div class="card-body">
			<form method="GET" action="{{ route('admin.usuarios.index') }}" class="row g-2">
				<div class="col-md-4">
					<input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por nombre o email">
				</div>
				<div class="col-md-2">
					<select name="role" class="form-select">
						<option value="">Todos los roles</option>
						<option value="admin" {{ request('role')=='admin' ? 'selected' : '' }}>Admin</option>
						<option value="user" {{ request('role')=='user' ? 'selected' : '' }}>Usuario</option>
					</select>
				</div>
				<div class="col-md-2">
					<button class="btn btn-outline-primary">Filtrar</button>
				</div>
				<div class="col-md-4 text-end text-muted">
					<small>Mostrando {{ $usuarios->total() ?? 0 }} resultados</small>
				</div>
			</form>
		</div>
	</div>

	<div class="card">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-hover mb-0">
					<thead class="table-light">
						<tr>
							<th>ID</th>
							<th>Nombre</th>
							<th>Email</th>
							<th>Rol</th>
							<th>Creado</th>
							<th class="text-end">Acciones</th>
						</tr>
					</thead>
					<tbody>
						@forelse($usuarios as $usuario)
						<tr>
							<td>{{ $usuario->id }}</td>
							<td>{{ $usuario->nombre ?? $usuario->name ?? '—' }}</td>
							<td>{{ $usuario->email }}</td>
							<td>{{ $usuario->rol ?? 'usuario' }}</td>
							<td>{{ optional($usuario->created_at)->format('d/m/Y') }}</td>
							<td class="text-end">
								<a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-sm btn-outline-secondary">Editar</a>

								<form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminar usuario #'+{{ $usuario->id }}+'?');">
									@csrf
									@method('DELETE')
									<button class="btn btn-sm btn-outline-danger">Eliminar</button>
								</form>
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="6" class="text-center text-muted py-4">No se encontraron usuarios.</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
		<div class="card-footer">
			<div class="d-flex justify-content-between align-items-center">
				<div>
					@if($usuarios->count()) Mostrando {{ $usuarios->firstItem() }} - {{ $usuarios->lastItem() }} @endif
				</div>
				<div>
					{{ $usuarios->withQueryString()->links() }}
				</div>
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script>
	// Confirmación mínima para eliminar (se puede mejorar con modales)
	document.querySelectorAll('form[method="POST"]').forEach(f => {
		f.addEventListener('submit', e => {
			// deja que el onsubmit inline maneje la confirmación
		});
	});
</script>
@endpush

{{-- Vista administrativa para gestionar usuarios: búsqueda, filtros, paginación y acciones CRUD --}}
