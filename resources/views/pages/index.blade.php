@extends('layout.app')

@section('title', config('app.name'))

@push('head')
    <meta name="description" content="Cusco Travel Shop - tours, experiencias y reservas en Cusco. Encuentra tu próxima aventura.">
@endpush

@section('content')
    <section class="hero">
        <div class="hero-inner">
            <h1>{{ config('app.name') }}</h1>
            <p>Descubre los mejores tours por Cusco con una experiencia simple y segura.</p>
            <a href="{{ route('tours.index') }}" class="btn-primary">Ver tours</a>
        </div>
    </section>

    <section class="section">
        <h2>Tours destacados</h2>
        <div class="grid">
            @forelse(($featured ?? collect()) as $tour)
                <article class="card">
                    <h3>{{ $tour->titulo }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit($tour->descripcion, 120) }}</p>
                    <p class="price">US$ {{ number_format($tour->precio, 2) }}</p>
                    <a href="{{ route('tours.show', $tour->id) }}" class="btn-link">Ver detalle</a>
                </article>
            @empty
                <p>No hay tours destacados por el momento.</p>
            @endforelse
        </div>
    </section>

    <section class="section muted">
        <h2>Estadísticas rápidas</h2>
        <div class="stats">
            <div>
                <span class="label">Usuarios</span>
                <span class="value">{{ $stats['usuarios'] ?? 0 }}</span>
            </div>
            <div>
                <span class="label">Tours</span>
                <span class="value">{{ $stats['tours'] ?? 0 }}</span>
            </div>
            <div>
                <span class="label">Reservas</span>
                <span class="value">{{ $stats['reservas'] ?? 0 }}</span>
            </div>
        </div>
    </section>

    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="h4">¿Listo para tu próxima aventura?</h2>
            <p class="mb-3">Contáctanos para planificar un tour personalizado o reservar en grupo.</p>
            <a href="{{ route('contacto.index') }}" class="btn btn-outline-light">Contáctanos</a>
        </div>
    </section>

    @includeIf('components.modals')

@endsection
