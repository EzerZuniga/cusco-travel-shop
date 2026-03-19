@extends('layout.app')

@section('title', config('app.name') . ' - Viajes y Tours en Cusco')

@push('head')
	<meta name="description" content="Reserva tours y excursiones en Cusco: Machu Picchu, Valle Sagrado, city tours y experiencias culturales.">
@endpush

@section('content')
	{{-- Hero / Buscador rápido --}}
	<section class="hero bg-dark text-white position-relative overflow-hidden" style="background-image: url('{{ asset('assets/img/hero/cusco-hero.jpg') }}'); background-size: cover; background-position: center;">
		<div class="overlay" style="background: rgba(0,0,0,0.45); position: absolute; inset:0;"></div>
		<div class="container py-6 position-relative" style="z-index:2;">
			<div class="row align-items-center">
				<div class="col-lg-7">
					<h1 class="display-5 fw-bold">Explora Cusco y sus alrededores</h1>
					<p class="lead text-white-50">Encuentra tours, excursiones y experiencias locales. Reserva con facilidad y viaja seguro.</p>

					<form action="{{ route('tours.index') }}" method="GET" class="row g-2 mt-3">
						<div class="col-md-6">
							<input name="q" value="{{ request('q') }}" type="search" class="form-control" placeholder="Buscar tours, destinos..." aria-label="Buscar tours">
						</div>
						<div class="col-md-4">
							<select name="duration" class="form-select">
								<option value="">Duración</option>
								<option value="1">1 día</option>
								<option value="2">2 días</option>
								<option value="3">3+ días</option>
							</select>
						</div>
						<div class="col-md-2 d-grid">
							<button class="btn btn-warning">Buscar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

	{{-- Sección: Tours destacados --}}
	<section class="py-5">
		<div class="container">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<h2 class="h4 mb-0">Tours destacados</h2>
				<a href="{{ route('tours.index') }}" class="small">Ver todos</a>
			</div>

			<div class="row g-4">
				@forelse($featuredTours ?? [] as $tour)
					<div class="col-md-4">
						<div class="card h-100 shadow-sm">
							<a href="{{ route('tours.show', $tour->slug ?? $tour->id) }}">
								<img src="{{ $tour->image_url ?? asset('assets/img/tours/thumb-default.jpg') }}" class="card-img-top" alt="{{ $tour->title }}" style="height:200px; object-fit:cover;">
							</a>
							<div class="card-body d-flex flex-column">
								<h3 class="h6"><a href="{{ route('tours.show', $tour->slug ?? $tour->id) }}" class="text-dark text-decoration-none">{{ $tour->title }}</a></h3>
								<p class="text-muted mb-2 small">{{ Str::limit($tour->excerpt ?? $tour->summary ?? $tour->description, 100) }}</p>
								<div class="mt-auto d-flex justify-content-between align-items-center">
									<div class="text-muted small">Desde <strong>{{ $tour->price_formatted ?? ('$'.number_format($tour->price ?? 0,2)) }}</strong></div>
									<a href="{{ route('tours.show', $tour->slug ?? $tour->id) }}" class="btn btn-sm btn-outline-primary">Ver</a>
								</div>
							</div>
						</div>
					</div>
				@empty
					<div class="col-12">
						<div class="alert alert-info">No hay tours destacados disponibles.</div>
					</div>
				@endforelse
			</div>
		</div>
	</section>

	{{-- Sección: Estadísticas rápidas --}}
	<section class="py-4 bg-light">
		<div class="container">
			<div class="row text-center">
				<div class="col-6 col-md-3">
					<h3 class="h4 mb-0">{{ $stats['tours'] ?? 0 }}</h3>
					<small class="text-muted">Tours</small>
				</div>
				<div class="col-6 col-md-3">
					<h3 class="h4 mb-0">{{ $stats['reservas'] ?? 0 }}</h3>
					<small class="text-muted">Reservas</small>
				</div>
				<div class="col-6 col-md-3">
					<h3 class="h4 mb-0">{{ $stats['usuarios'] ?? 0 }}</h3>
					<small class="text-muted">Usuarios</small>
				</div>
				<div class="col-6 col-md-3">
					<h3 class="h4 mb-0">{{ $stats['ingresos_last_30'] ?? '$0.00' }}</h3>
					<small class="text-muted">Ingresos (30d)</small>
				</div>
			</div>
		</div>
	</section>

	{{-- Sección: Últimos posts del blog --}}
	<section class="py-5">
		<div class="container">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<h2 class="h5 mb-0">Desde nuestro blog</h2>
				<a href="{{ route('blog.index') }}" class="small">Ver todos</a>
			</div>
			<div class="row g-4">
				@forelse($latestPosts ?? [] as $post)
					<div class="col-md-4">
						<article class="card h-100">
							<a href="{{ route('blog.show', $post->slug ?? $post->id) }}">
								<img src="{{ $post->image_url ?? asset('assets/img/blog/thumb-1.jpg') }}" class="card-img-top" alt="{{ $post->title }}" style="height:160px; object-fit:cover;">
							</a>
							<div class="card-body">
								<h3 class="h6"><a href="{{ route('blog.show', $post->slug ?? $post->id) }}" class="text-dark text-decoration-none">{{ Str::limit($post->title, 70) }}</a></h3>
								<p class="text-muted small">{{ Str::limit($post->excerpt ?? $post->summary ?? $post->body, 120) }}</p>
							</div>
						</article>
					</div>
				@empty
					<div class="col-12"><div class="alert alert-info">No hay entradas del blog por ahora.</div></div>
				@endforelse
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