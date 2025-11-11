@extends('layout.app')

@push('head')
    <meta name="description" content="Descubre consejos, guías y tips para tu viaje a Cusco. Lee nuestros artículos sobre turismo, gastronomía, historia y cultura cusqueña.">
    <meta name="keywords" content="blog turismo cusco, consejos viaje cusco, guías turismo, tips machu picchu">
    <meta name="author" content="TurismoCusco">
    <meta property="og:title" content="Blog de Turismo - TurismoCusco | Consejos y Guías de Viaje">
    <meta property="og:description" content="Descubre consejos, guías y tips para tu viaje a Cusco. Lee nuestros artículos sobre turismo, gastronomía, historia y cultura cusqueña.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/blog') }}">
    <link rel="icon" href="/assets/img/logo/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endpush

@section('content')
    @includeIf('components.navbar')

    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5 mt-5">
        <div class="container">
            <h1 class="display-4" data-aos="fade-up">Blog</h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="100">Consejos, noticias y curiosidades sobre Cusco y el turismo en la región</p>
        </div>
    </section>

    <!-- Blog Search & Filter -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row g-3">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="input-group">
                        <input type="text" class="form-control" id="blog-search" placeholder="Buscar artículos...">
                        <button class="btn btn-primary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-lg-3" data-aos="fade-up">
                    <select class="form-control" id="category-filter">
                        <option value="">Todas las categorías</option>
                        <option value="consejos">Consejos de Viaje</option>
                        <option value="historia">Historia y Cultura</option>
                        <option value="gastronomia">Gastronomía</option>
                        <option value="eventos">Eventos y Festividades</option>
                        <option value="aventura">Aventura</option>
                    </select>
                </div>
                <div class="col-lg-3" data-aos="fade-left">
                    <select class="form-control" id="date-filter">
                        <option value="">Todas las fechas</option>
                        <option value="recent">Últimos 30 días</option>
                        <option value="month">Último mes</option>
                        <option value="year">Último año</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Featured Article -->
                    <div class="featured-article bg-gradient-primary text-white rounded overflow-hidden shadow-lg mb-5" data-aos="zoom-in">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <img src="/assets/img/blog/featured-machu-picchu.jpg" alt="Artículo Destacado" class="img-fluid h-100 object-cover">
                            </div>
                            <div class="col-lg-6 d-flex align-items-center">
                                <div class="p-5">
                                    <span class="badge bg-warning text-dark mb-2">ARTÍCULO DESTACADO</span>
                                    <h2 class="h3 mb-3">Guía Completa para Visitar Machu Picchu en 2024</h2>
                                    <p class="mb-4">Todo lo que necesitas saber para planificar tu visita a la ciudadela inca más famosa del mundo, incluyendo nuevas regulaciones y consejos prácticos.</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="/assets/img/authors/author-1.jpg" alt="Autor" class="rounded-circle me-3" width="40" height="40">
                                        <div>
                                            <small>Por <strong>María García</strong></small><br>
                                            <small class="text-white-50">15 de Septiembre, 2024</small>
                                        </div>
                                    </div>
                                    <a href="#" class="btn btn-warning">Leer Artículo Completo</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Posts Grid -->
                    <div class="row g-4" id="blog-posts">
                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                            <article class="blog-card bg-white rounded shadow-sm overflow-hidden h-100">
                                <img src="/assets/img/blog/valle-sagrado.jpg" alt="Valle Sagrado" class="img-fluid">
                                <div class="p-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-success me-2">Historia</span>
                                        <small class="text-muted">10 min de lectura</small>
                                    </div>
                                    <h4 class="h5 mb-3">Los Secretos del Valle Sagrado de los Incas</h4>
                                    <p class="text-muted mb-3">Descubre la historia fascinante y los misterios ocultos del Valle Sagrado, uno de los lugares más importantes del Imperio Inca.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <img src="/assets/img/authors/author-2.jpg" alt="Autor" class="rounded-circle me-2" width="32" height="32">
                                            <small>Carlos Mendoza</small>
                                        </div>
                                        <small class="text-muted">12 Sep 2024</small>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                            <article class="blog-card bg-white rounded shadow-sm overflow-hidden h-100">
                                <img src="/assets/img/blog/gastronomia-cusco.jpg" alt="Gastronomía Cusco" class="img-fluid">
                                <div class="p-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-warning text-dark me-2">Gastronomía</span>
                                        <small class="text-muted">8 min de lectura</small>
                                    </div>
                                    <h4 class="h5 mb-3">10 Platos Típicos que Debes Probar en Cusco</h4>
                                    <p class="text-muted mb-3">Una guía gastronómica completa de los sabores tradicionales cusqueños que no puedes perderte durante tu visita.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <img src="/assets/img/authors/author-3.jpg" alt="Autor" class="rounded-circle me-2" width="32" height="32">
                                            <small>Ana Quispe</small>
                                        </div>
                                        <small class="text-muted">8 Sep 2024</small>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                    
                    <!-- Load More -->
                    <div class="text-center mt-5">
                        <button class="btn btn-outline-primary btn-lg" id="load-more-posts" data-aos="fade-up">
                            <i class="bi bi-plus-circle me-2"></i>Cargar Más Artículos
                        </button>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Newsletter Subscription -->
                    <div class="card mb-4 bg-primary text-white" data-aos="fade-left">
                        <div class="card-body text-center">
                            <i class="bi bi-envelope-heart display-4 mb-3"></i>
                            <h5>¡Suscríbete a Nuestro Blog!</h5>
                            <p class="mb-3">Recibe los mejores consejos de viaje directamente en tu email</p>
                            <form id="newsletter-form">
                                <div class="mb-3">
                                    <input type="email" class="form-control" placeholder="Tu email" required>
                                </div>
                                <button type="submit" class="btn btn-warning w-100">Suscribirme</button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Categories -->
                    <div class="card mb-4" data-aos="fade-left" data-aos-delay="100">
                        <div class="card-header">
                            <h5 class="mb-0">Categorías</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <a href="#" class="blog-category d-flex justify-content-between" data-category="consejos">
                                        <span><i class="bi bi-lightbulb me-2"></i>Consejos de Viaje</span>
                                        <span class="badge bg-primary">12</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="blog-category d-flex justify-content-between" data-category="historia">
                                        <span><i class="bi bi-book me-2"></i>Historia y Cultura</span>
                                        <span class="badge bg-primary">8</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="blog-category d-flex justify-content-between" data-category="gastronomia">
                                        <span><i class="bi bi-cup-hot me-2"></i>Gastronomía</span>
                                        <span class="badge bg-primary">6</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Popular Posts -->
                    <div class="card mb-4" data-aos="fade-left" data-aos-delay="200">
                        <div class="card-header">
                            <h5 class="mb-0">Artículos Populares</h5>
                        </div>
                        <div class="card-body">
                            <div class="popular-post d-flex mb-3">
                                <img src="/assets/img/blog/thumb-1.jpg" alt="Popular 1" class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                <div>
                                    <h6 class="h6 mb-1">
                                        <a href="#" class="text-decoration-none">Mejor Época para Visitar Machu Picchu</a>
                                    </h6>
                                    <small class="text-muted">24 Sep 2024</small>
                                </div>
                            </div>
                            <div class="popular-post d-flex mb-3">
                                <img src="/assets/img/blog/thumb-2.jpg" alt="Popular 2" class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                <div>
                                    <h6 class="h6 mb-1">
                                        <a href="#" class="text-decoration-none">Qué Llevar en tu Mochila a Cusco</a>
                                    </h6>
                                    <small class="text-muted">20 Sep 2024</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @includeIf('components.modals')

    @push('scripts')
        <script src="/assets/js/bootstrap.bundle.min.js"></script>
        <script src="/vendor/aos/aos.js"></script>
        <script src="/utils/storage.js"></script>
        <script src="/utils/helpers.js"></script>
        <script src="/assets/js/main.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.AOS) AOS.init();

                // Funcionalidad de filtros del blog
                const searchInput = document.getElementById('blog-search');
                const categoryFilter = document.getElementById('category-filter');
                const dateFilter = document.getElementById('date-filter');
                const blogPosts = document.querySelectorAll('.blog-card');

                function filterBlogPosts() {
                    const searchTerm = searchInput.value.toLowerCase();
                    const categoryValue = categoryFilter.value;
                    const dateValue = dateFilter.value;

                    blogPosts.forEach(post => {
                        const title = post.querySelector('h4').textContent.toLowerCase();
                        const excerpt = post.querySelector('p').textContent.toLowerCase();
                        const category = post.querySelector('.badge').textContent.toLowerCase();
                        const date = post.querySelector('small.text-muted').textContent;

                        const matchesSearch = title.includes(searchTerm) || excerpt.includes(searchTerm);
                        const matchesCategory = !categoryValue || category.includes(categoryValue);
                        const matchesDate = !dateValue || filterByDate(date, dateValue);

                        if (matchesSearch && matchesCategory && matchesDate) {
                            post.style.display = 'block';
                        } else {
                            post.style.display = 'none';
                        }
                    });
                }

                function filterByDate(postDate, filterType) {
                    // Implementar lógica de filtrado por fecha
                    return true;
                }

                searchInput.addEventListener('input', filterBlogPosts);
                categoryFilter.addEventListener('change', filterBlogPosts);
                dateFilter.addEventListener('change', filterBlogPosts);
            });
        </script>
    @endpush
@endsection
