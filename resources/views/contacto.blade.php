@extends('layout.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-3">Contacto</h1>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('contacto.send') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                   id="nombre" name="nombre" value="{{ old('nombre') }}" required>
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                   id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="mensaje" class="form-label">Mensaje</label>
            <textarea class="form-control @error('mensaje') is-invalid @enderror" 
                      id="mensaje" name="mensaje" rows="5" required>{{ old('mensaje') }}</textarea>
            @error('mensaje')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
    </form>
</div>
@endsection
