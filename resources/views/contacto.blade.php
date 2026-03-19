@extends('layout.app')

@section('title', 'Contacto - ' . config('app.name'))

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="mb-3">Contacto</h1>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <p class="text-muted">¿Tienes dudas sobre un tour o necesitas asistencia con una reserva? Completa el formulario y te responderemos en menos de 24 horas.</p>

                            <form action="{{ route('contacto.send') ?? url('/contacto/enviar') }}" method="POST" novalidate>
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre</label>
                                    <input name="name" id="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo electrónico</label>
                                    <input name="email" id="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Teléfono (opcional)</label>
                                    <input name="phone" id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Mensaje</label>
                                    <textarea name="message" id="message" rows="6" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="d-grid">
                                    <button class="btn btn-primary">Enviar mensaje</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="mt-4 small text-muted">
                        <p class="mb-1">También puedes escribirnos: <a href="mailto:{{ config('mail.from.address') ?? config('app.admin_email') }}">{{ config('mail.from.address') ?? config('app.admin_email') }}</a></p>
                        <p class="mb-0">Teléfono: <a href="tel:{{ config('app.phone') ?? '+51 000 000 000' }}">{{ config('app.phone') ?? '+51 000 000 000' }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
