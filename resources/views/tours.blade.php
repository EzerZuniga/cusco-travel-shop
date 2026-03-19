@extends('layout.app')

@section('title', 'Tours - ' . config('app.name'))

@push('head')
	<meta name="description" content="Explora y reserva tours en Cusco: Machu Picchu, Valle Sagrado, city tours y más experiencias locales.">
@endpush

@section('content')
	<nav aria-label="breadcrumb" class="bg-light py-2">
		<div class="container">
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
				<li class="breadcrumb-item active" aria-current="page">Tours</li>
			</ol>
		</div>
	</nav>

	<section class="py-4">
		<div class="container">
			<div class="row g-4">
				<main class="col-lg-8">
					<header class="d-flex justify-content-between align-items-center mb-3">
						<h1 class="h4 mb-0">Tours</h1>
						@if(isset($tours))
							<small class="text-muted">Mostrando {{ $tours->total() }} resultados</small>
						@endif
					</header>

					<form method="GET" action="{{ route('tours.index') }}" class="mb-3" aria-label="Filtros de tours">
						<div class="row g-2">
							<div class="col-md-6">
								<label for="q" class="visually-hidden">Buscar</label>
								<input id="q" type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar tours o destinos">
							</div>
							<div class="col-md-3">
								<select name="duration" class="form-select" aria-label="Duración">
									<option value="">Duración</option>
									<option value="1" {{ request('duration')=='1' ? 'selected' : '' }}>1 día</option>
									<option value="2" {{ request('duration')=='2' ? 'selected' : '' }}>2 días</option>
									<option value="3" {{ request('duration')=='3' ? 'selected' : '' }}>3+ días</option>
								</select>
							</div>
							<div class="col-md-2">
								<select name="order" class="form-select" aria-label="Ordenar">
									<option value="latest" {{ request('order')=='latest' ? 'selected' : '' }}>Más recientes</option>
									<option value="price_asc" {{ request('order')=='price_asc' ? 'selected' : '' }}>Precio ↑</option>
									<option value="price_desc" {{ request('order')=='price_desc' ? 'selected' : '' }}>Precio ↓</option>
								</select>
							</div>
							<div class="col-md-1 d-grid">
								<button class="btn btn-primary" type="submit">OK</button>
							</div>
						</div>
					</form>

					@if(isset($tours) && $tours->count())
						<div class="row g-3">
							@foreach($tours as $tour)
								<div class="col-md-6">
									<article class="card h-100 shadow-sm">
										<a href="{{ route('tours.show', $tour->slug ?? $tour->id) }}">
											<img src="{{ $tour->image_url ?? asset('assets/img/tours/thumb-default.jpg') }}" alt="{{ $tour->title }}" class="card-img-top" style="height:180px; object-fit:cover;">
										</a>
										<div class="card-body d-flex flex-column">
											<h3 class="h6"><a href="{{ route('tours.show', $tour->slug ?? $tour->id) }}" class="text-dark text-decoration-none">{{ $tour->title }}</a></h3>
											<p class="text-muted small mb-2">{{ Str::limit($tour->excerpt ?? $tour->summary ?? $tour->description, 100) }}</p>
											<div class="mt-auto d-flex justify-content-between align-items-center">
												<div class="small text-muted">Duración: {{ $tour->duration ?? '-' }} días</div>
												<div>
													<span class="text-primary fw-bold me-2">{{ $tour->price_formatted ?? ('$'.number_format($tour->price ?? 0,2)) }}</span>
													<a href="{{ route('tours.show', $tour->slug ?? $tour->id) }}" class="btn btn-sm btn-outline-primary">Ver</a>
												</div>
											</div>
										</div>
									</article>
								</div>
							@endforeach
						</div>

						<div class="mt-4 d-flex justify-content-center" role="navigation" aria-label="Paginación de tours">
							{{ $tours->withQueryString()->links() }}
						</div>
					@else
						<div class="alert alert-info">No se encontraron tours. Intenta modificar tus filtros o <a href="{{ route('tours.index') }}">limpiar filtros</a>.</div>
					@endif
				</main>

				<aside class="col-lg-4">
					<div class="card mb-3">
						<div class="card-body">
							<h6 class="mb-3">Filtrar por precio</h6>
							<form method="GET" action="{{ route('tours.index') }}" aria-label="Filtro por precio">
								<input type="hidden" name="q" value="{{ request('q') }}">
								<div class="row g-2">
									<div class="col-6">
										<label for="min_price" class="visually-hidden">Precio mínimo</label>
										<input id="min_price" type="number" name="min_price" value="{{ request('min_price') }}" class="form-control" placeholder="Min">
									</div>
									<div class="col-6">
										<label for="max_price" class="visually-hidden">Precio máximo</label>
										<input id="max_price" type="number" name="max_price" value="{{ request('max_price') }}" class="form-control" placeholder="Max">
									</div>
								</div>
								<div class="d-grid mt-2">
									<button class="btn btn-outline-primary">Aplicar</button>
								</div>
							</form>
						</div>
					</div>

					<div class="card mb-3">
						<div class="card-header">Tours Populares</div>
						<ul class="list-group list-group-flush">
							@forelse($popularTours ?? [] as $p)
								<li class="list-group-item d-flex align-items-center">
									<img src="{{ $p->image_url ?? asset('assets/img/tours/thumb-default.jpg') }}" alt="{{ $p->title }}" width="64" height="44" class="rounded me-2" style="object-fit:cover;">
									<div>
										<a href="{{ route('tours.show', $p->slug ?? $p->id) }}" class="text-decoration-none">{{ Str::limit($p->title, 60) }}</a>
										<div><small class="text-muted">Desde {{ $p->price_formatted ?? ('$'.number_format($p->price ?? 0,2)) }}</small></div>
									</div>
								</li>
							@empty
								<li class="list-group-item">Sin tours populares</li>
							@endforelse
						</ul>
					</div>

					<div class="card">
						<div class="card-body text-center">
							<h6>¿Necesitas ayuda?</h6>
							<p class="small text-muted">Contáctanos para recomendaciones personalizadas.</p>
							<a href="{{ route('contacto') }}" class="btn btn-primary btn-sm">Contáctanos</a>
						</div>
					</div>
				</aside>
			</div>
		</div>
	</section>

	@includeIf('components.modals')

@endsection

@push('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', function(){ if(window.AOS) AOS.init(); });
	</script>
@endpush