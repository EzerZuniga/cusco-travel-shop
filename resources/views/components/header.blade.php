<!-- Componente: Header profesional (top bar + navbar) -->
<header class="mb-4">
    <div class="header-top bg-light py-2 border-bottom">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center small text-muted">
                    <i class="bi bi-telephone me-1" aria-hidden="true"></i>
                    <a href="tel:{{ config('app.contact_phone', '+51984123456') }}" class="text-muted text-decoration-none">{{ config('app.contact_phone_formatted', '+51 984 123 456') }}</a>
                    <span class="mx-2">|</span>
                    <i class="bi bi-envelope me-1" aria-hidden="true"></i>
                    <a href="mailto:{{ config('app.contact_email', 'info@turismocusco.com') }}" class="text-muted text-decoration-none">{{ config('app.contact_email', 'info@turismocusco.com') }}</a>
                </div>

                <div>
                    <a href="#" class="text-decoration-none text-muted me-3">{{ strtoupper(config('app.currency', 'PEN')) }}</a>
                    @guest
                        <a href="{{ route('login') ?? '#' }}" class="text-decoration-none text-muted me-2"><i class="bi bi-box-arrow-in-right"></i> Ingresar</a>
                        <a href="{{ route('register') ?? '#' }}" class="text-decoration-none text-muted"><i class="bi bi-person-plus"></i> Registrarse</a>
                    @else
                        <a href="{{ route('dashboard') ?? route('admin.dashboard') }}" class="text-decoration-none text-muted me-2"><i class="bi bi-person-circle"></i> {{ auth()->user()->nombre ?? auth()->user()->name }}</a>
                        <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="text-decoration-none text-muted"><i class="bi bi-box-arrow-right"></i> Salir</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('assets/img/logo/logo.png') }}" alt="{{ config('app.name', 'Cusco Travel') }}" height="42" class="me-2">
                <span class="fw-bold">{{ config('app.name', 'Cusco Travel') }}</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tours.*') ? 'active' : '' }}" href="{{ route('tours.index') }}">Tours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}" href="{{ route('blog.index') ?? '#' }}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contacto') ? 'active' : '' }}" href="{{ route('contacto') }}">Contacto</a>
                    </li>
                </ul>

                <form class="d-flex me-3" role="search" method="GET" action="{{ route('tours.index') }}">
                    <input class="form-control me-2" type="search" name="q" placeholder="Buscar tours" aria-label="Buscar tours" value="{{ request('q') }}">
                    <button class="btn btn-outline-primary" type="submit">Buscar</button>
                </form>

                <div class="d-flex">
                    <a href="{{ route('carrito') ?? '#' }}" class="btn btn-outline-secondary me-2 d-flex align-items-center">
                        <i class="bi bi-cart me-2"></i>
                        <span class="d-none d-md-inline">Carrito</span>
                        <span class="badge bg-danger ms-2">{{ session('cart.items_count', 0) }}</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>

{{-- Header profesional: top bar con contacto y navbar completo, soporta auth y búsqueda. --}}
