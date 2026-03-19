@extends('layout.app')

@section('title', 'Contacto - ' . config('app.name'))

@push('head')
	<meta name="description" content="Contáctanos para consultas sobre tours, reservas y soporte. Estamos para ayudarte en tu viaje a Cusco.">
@endpush

@section('content')
	<section class="py-5 bg-light">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-10">
					<div class="card shadow-sm">
						<div class="row g-0">
							<div class="col-md-6 border-end d-none d-md-block">
								<div class="p-4 h-100 d-flex flex-column justify-content-center" style="min-height:320px; background-image: url('{{ asset('assets/img/contact/contact-hero.jpg') }}'); background-size: cover; background-position: center;">
									<div class="bg-dark bg-opacity-50 text-white p-3 rounded">
										<h4>Estamos para ayudarte</h4>
										<p class="mb-1">¿Tienes preguntas sobre tours o reservas? Escríbenos y responderemos pronto.</p>
										<p class="mb-0 small">Horario de atención: Lun - Vie 09:00 - 18:00</p>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card-body p-4">
									<h3 class="h5">Contáctanos</h3>
									<p class="text-muted">Completa el formulario y nos pondremos en contacto contigo a la brevedad.</p>

									{{-- Mensajes flash --}}
									@if(session('success'))
										<div class="alert alert-success">{{ session('success') }}</div>
									@endif
									@if(session('error'))
										<div class="alert alert-danger">{{ session('error') }}</div>
									@endif

									{{-- Formulario de contacto --}}
									<form method="POST" action="{{ route('contacto.send') ?? url('/contacto/enviar') }}" id="contact-form" novalidate>
										@csrf
										<div class="mb-3">
											<label for="name" class="form-label">Nombre</label>
											<input name="name" id="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
											@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
										</div>

										<div class="mb-3">
											<label for="email" class="form-label">Email</label>
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
											<textarea name="message" id="message" rows="5" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
											@error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
										</div>

										<div class="d-grid">
											<button type="submit" class="btn btn-primary">Enviar mensaje</button>
										</div>
									</form>

									<hr class="my-4">
									<div class="small text-muted">
										<p class="mb-1">También puedes escribirnos a: <a href="mailto:{{ config('mail.from.address') ?? config('app.admin_email') }}">{{ config('mail.from.address') ?? config('app.admin_email') }}</a></p>
										<p class="mb-0">Tel: <a href="tel:{{ config('app.phone') ?? '+51-0-000-000-000' }}">{{ config('app.phone') ?? '+51 000 000 000' }}</a></p>
									</div>
								</div>
							</div>
						</div>
					</div>

					{{-- Mapa opcional (si está configurado) --}}
					@if(env('GOOGLE_MAPS_EMBED'))
						<div class="mt-4">
							<div class="ratio ratio-16x9 rounded shadow-sm overflow-hidden">
								{!! env('GOOGLE_MAPS_EMBED') !!}
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</section>

	@includeIf('components.modals')

@endsection

@push('scripts')
	<script>
		(function(){
			const form = document.getElementById('contact-form');
			if(!form) return;

			form.addEventListener('submit', function(e){
				// validación cliente mínima
				const name = form.querySelector('#name');
				const email = form.querySelector('#email');
				const message = form.querySelector('#message');
				let valid = true;

				[name, email, message].forEach(function(el){
					if(!el.value.trim()){
						el.classList.add('is-invalid');
						valid = false;
					} else {
						el.classList.remove('is-invalid');
					}
				});

				if(!valid){
					e.preventDefault();
					window.scrollTo({top: form.offsetTop - 80, behavior: 'smooth'});
				}
			});
		})();
	</script>
@endpush