@extends('layout.app')

@section('title', $tour->titulo ?? 'Tour')

@section('content')
    <section class="section">
        <a href="{{ route('tours.index') }}" class="back-link">← Volver a tours</a>

        <h1>{{ $tour->titulo }}</h1>
        @if($tour->descripcion)
            <p>{{ $tour->descripcion }}</p>
        @endif

        <p class="price">Precio: US$ {{ number_format($tour->precio, 2) }}</p>
        @if($tour->duracion)
            <p>Duración: {{ $tour->duracion }}</p>
        @endif
    </section>
@endsection
