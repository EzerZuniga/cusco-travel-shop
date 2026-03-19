@extends('layout.app')

@section('title', 'Tours')

@section('content')
    <section class="section">
        <h1>Todos los tours</h1>

        <form method="GET" class="filters">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar tour...">
            <input type="number" step="0.01" name="min_price" value="{{ request('min_price') }}" placeholder="Precio mínimo">
            <input type="number" step="0.01" name="max_price" value="{{ request('max_price') }}" placeholder="Precio máximo">
            <button type="submit" class="btn-primary">Filtrar</button>
        </form>

        <div class="grid">
            @forelse(($tours ?? []) as $tour)
                <article class="card">
                    <h2>{{ $tour->titulo }}</h2>
                    @if($tour->descripcion)
                        <p>{{ Str::limit($tour->descripcion, 150) }}</p>
                    @endif
                    <p class="price">US$ {{ number_format($tour->precio, 2) }}</p>
                    <a href="{{ route('tours.show', $tour->id) }}" class="btn-link">Ver detalle</a>
                </article>
            @empty
                <p>No hay tours para mostrar.</p>
            @endforelse
        </div>

        @if(method_exists($tours, 'links'))
            <div class="pagination">
                {{ $tours->links() }}
            </div>
        @endif
    </section>
@endsection
@extends('layout.app')

@section('title', 'Tours - ' . config('app.name'))

@push('head')
	<meta name="description" content="Explora y reserva tours en Cusco: Machu Picchu, Valle Sagrado, city tours y más experiencias locales.">
@endpush

@section('content')
	<section class="page-header py-5 bg-light">
		<div class="container">
			<h1 class="h3 mb-1">Tours</h1>
			<p class="text-muted mb-0">Filtra, compara y reserva tours en Cusco y alrededores.</p>
		</div>
	</section>

	<section class="py-4">
		<div class="container">
			<div class="row g-4">
				<div class="col-lg-8">
					{{-- Filtros principales (envían por GET) --}}
					<form method="GET" action="{{ route('tours.index') }}" class="mb-3">
						<div class="row g-2 align-items-center">
							<div class="col-md-6">
								<input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por título, destino o palabra clave">
							</div>
							<div class="col-md-3">
								<select name="duration" class="form-select">
									<option value="">Duración</option>
									<option value="1" {{ request('duration')=='1' ? 'selected' : '' }}>1 día</option>
									<option value="2" {{ request('duration')=='2' ? 'selected' : '' }}>2 días</option>
									<option value="3" {{ request('duration')=='3' ? 'selected' : '' }}>3+ días</option>
								</select>
							</div>
							<div class="col-md-2">
								<select name="order" class="form-select">
									<option value="latest" {{ request('order')=='latest' ? 'selected' : '' }}>Más recientes</option>
									<option value="price_asc" {{ request('order')=='price_asc' ? 'selected' : '' }}>Precio ↑</option>
									<option value="price_desc" {{ request('order')=='price_desc' ? 'selected' : '' }}>Precio ↓</option>
								</select>
							</div>
							<div class="col-md-1 d-grid">
								<button class="btn btn-primary">OK</button>
							</div>
						</div>
					</form>

					{{-- Lista de tours --}}
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

						<div class="mt-4 d-flex justify-content-center">
							{{ $tours->withQueryString()->links() }}
						</div>
					@else
						<div class="alert alert-info">No se encontraron tours. Intenta modificar tus filtros.</div>
					@endif
				</div>

				{{-- Sidebar: filtros avanzados y destacados --}}
				<aside class="col-lg-4">
					<div class="card mb-3">
						<div class="card-body">
							<h6 class="mb-3">Filtrar por precio</h6>
							<form method="GET" action="{{ route('tours.index') }}">
								<input type="hidden" name="q" value="{{ request('q') }}">
								<div class="row g-2">
									<div class="col-6">
										<input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control" placeholder="Min">
									</div>
									<div class="col-6">
										<input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control" placeholder="Max">
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