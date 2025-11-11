<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ config('app.name', 'Cusco Travel') }}</title>
	<link rel="stylesheet" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/css/styles.css">
    {{-- Additional head elements pushed from pages --}}
    @stack('head')
</head>
<body>
	@includeIf('components.header')

	<main class="py-4">
		@yield('content')
	</main>

	@includeIf('components.footer')

	<script src="/assets/js/bootstrap.bundle.min.js"></script>
	<script src="/assets/js/main.js"></script>
    {{-- Additional scripts pushed from pages --}}
    @stack('scripts')
</body>
</html>
