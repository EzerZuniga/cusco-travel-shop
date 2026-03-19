<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cusco Travel Shop')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <style>
        body {margin:0; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#0f172a; color:#e5e7eb;}
        a {color:inherit; text-decoration:none;}
        .container {max-width:1080px; margin:0 auto; padding:1.5rem;}
        header {border-bottom:1px solid #1f2937; padding:1rem 0;}
        nav {display:flex; justify-content:space-between; align-items:center;}
        nav .links a {margin-left:1rem; font-size:0.95rem; color:#cbd5f5;}
        nav .links a:hover {color:#f97316;}
        .hero {padding:3rem 1.5rem; background:radial-gradient(circle at top, #1d4ed8, #020617);} 
        .hero-inner {max-width:720px; margin:0 auto;}
        .hero h1 {font-size:2.5rem; margin-bottom:0.5rem;}
        .hero p {color:#cbd5f5; max-width:34rem;}
        .btn-primary {display:inline-block; margin-top:1.25rem; padding:0.75rem 1.25rem; background:#f97316; color:#111827; border-radius:999px; font-weight:600;}
        .btn-primary:hover {background:#fb923c;}
        .btn-link {color:#f97316; font-weight:500;}
        .section {padding:2.5rem 1.5rem;}
        .section.muted {background:#020617; border-top:1px solid #1f2937; border-bottom:1px solid #1f2937;}
        h1, h2, h3 {margin-top:0;}
        .grid {display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1.5rem; margin-top:1.5rem;}
        .card {background:#020617; border-radius:1rem; padding:1.5rem; border:1px solid #1f2937;}
        .price {margin-top:0.75rem; font-weight:600; color:#f97316;}
        .stats {display:grid; grid-template-columns:repeat(auto-fit, minmax(120px, 1fr)); gap:1.5rem; margin-top:1.5rem;}
        .stats .label {display:block; font-size:0.8rem; color:#9ca3af;}
        .stats .value {font-size:1.4rem; font-weight:700;}
        .filters {display:flex; flex-wrap:wrap; gap:0.75rem; margin-top:1rem;}
        .filters input {padding:0.45rem 0.75rem; border-radius:999px; border:1px solid #374151; background:#020617; color:#e5e7eb;}
        .filters input::placeholder {color:#6b7280;}
        .back-link {display:inline-block; margin-bottom:1rem; font-size:0.9rem; color:#9ca3af;}
        footer {border-top:1px solid #1f2937; padding:1.5rem; text-align:center; font-size:0.85rem; color:#9ca3af;}
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <a href="{{ url('/') }}"><strong>Cusco Travel Shop</strong></a>
                <div class="links">
                    <a href="{{ route('tours.index') }}">Tours</a>
                    <a href="{{ route('contacto.index') }}">Contacto</a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer>
        <div class="container">
            &copy; {{ date('Y') }} Cusco Travel Shop
        </div>
    </footer>
</body>
</html>
<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('title', config('app.name', 'Cusco Travel'))</title>
	<meta name="description" content="@yield('meta_description', config('app.description', 'Tours y paquetes turísticos en Cusco'))">

	<link rel="icon" href="{{ asset('assets/img/logo/favicon.png') }}" type="image/png">

	{{-- Styles principales (usar asset() para resolver correctamente la ruta) --}}
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">

	{{-- Permitir que páginas inyecten estilos adicionales --}}
	@stack('styles')

	{{-- Head adicional específico de la página --}}
	@stack('head')
</head>
<body class="d-flex flex-column min-vh-100">
	{{-- Header (navbar + top) --}}
	@includeIf('components.header')

	{{-- Modales compartidos (login, register, confirm, alerts) --}}
	@includeIf('components.modals')

	<main class="flex-grow-1">
		<div class="container py-4">
			{{-- Mensajes flash (éxito / error) mostrados de forma accesible --}}
			@if(session('success'))
				<div class="alert alert-success" role="alert">{{ session('success') }}</div>
			@endif
			@if(session('error'))
				<div class="alert alert-danger" role="alert">{{ session('error') }}</div>
			@endif

			@yield('content')
		</div>
	</main>

	{{-- Footer --}}
	@includeIf('components.footer')

	{{-- Scripts principales --}}
	<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" defer></script>
	<script src="{{ asset('assets/js/main.js') }}" defer></script>

	{{-- Permitir que páginas inyecten scripts adicionales --}}
	@stack('scripts')

	{{-- Pequeño helper global en window (ej: csrfToken, appName) --}}
	<script>
		window.app = window.app || {};
		window.app.csrfToken = '{{ csrf_token() }}';
		window.app.name = '{{ e(config("app.name", "Cusco Travel")) }}';
	</script>
</body>
</html>
