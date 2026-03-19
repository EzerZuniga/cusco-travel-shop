{{-- Navbar profesional: accesible, responsiva, con soporte para auth y búsqueda --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom" role="navigation" aria-label="Main navigation">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('assets/img/logo/logo.png') }}" alt="{{ config('app.name', 'Cusco Travel') }}" height="40" class="me-2">
            <span class="fw-bold d-none d-md-inline">{{ config('app.name', 'Cusco Travel') }}</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Abrir menú">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Inicio</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('tours.*') ? 'active' : '' }}" href="#" id="toursDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Tours</a>
                    <ul class="dropdown-menu" aria-labelledby="toursDropdown">
                        <li><a class="dropdown-item" href="{{ route('tours.index') }}">Todos los tours</a></li>
                        <li><a class="dropdown-item" href="{{ route('tours.index', ['featured' => 1]) }}">Destacados</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('tours.index', ['duration' => 'half']) }}">Medio día</a></li>
                        <li><a class="dropdown-item" href="{{ route('tours.index', ['duration' => 'full']) }}">Día completo</a></li>
                    </ul>
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

            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item me-2">
                    <a class="btn btn-outline-secondary d-flex align-items-center" href="{{ route('carrito') ?? '#' }}" aria-label="Ver carrito">
                        <i class="bi bi-cart me-2" aria-hidden="true"></i>
                        <span class="d-none d-md-inline">Carrito</span>
                        <span class="badge bg-danger ms-2">{{ session('cart.items_count', 0) }}</span>
                    </a>
                </li>

                @guest
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Ingresar</a>
                </li>
                @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->nombre ?? auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('dashboard') ?? route('admin.dashboard') }}">Panel</a></li>
                        <li><a class="dropdown-item" href="{{ route('perfil') ?? '#' }}">Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Cerrar sesión</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </li>
                    </ul>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

{{-- Navbar profesional: accesible, soporta búsqueda, carrito y auth; usa rutas nombradas y asset() para logo. --}}
