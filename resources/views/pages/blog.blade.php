@extends('layout.app')

@section('title', isset($title) ? $title . ' - ' . config('app.name') : 'Blog - ' . config('app.name'))

@push('head')
    <meta name="description" content="Blog de viajes: noticias, guías y consejos para visitar Cusco y el sur del Perú.">
    <meta name="keywords" content="Cusco, blog, turismo, viaje, machu picchu, gastronomía">
    <link rel="icon" href="{{ asset('assets/img/logo/logo.png') }}" type="image/png">
@endpush

@section('content')
    {{-- Encabezado de la página (layout ya incluye header si aplica) --}}
    <section class="page-header bg-light py-5">
        <div class="container">
            <h1 class="h2 mb-1">Blog</h1>
            <p class="text-muted mb-0">Consejos, guías y noticias para planificar tu viaje a Cusco.</p>
        </div>
    </section>

    <section class="py-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    {{-- Barra de búsqueda y filtros (envían por GET para SEO y compatibilidad) --}}
                    <form method="GET" action="{{ route('blog.index') }}" class="mb-4">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input name="search" value="{{ request('search') }}" type="search" class="form-control" placeholder="Buscar artículos, lugares o consejos..." aria-label="Buscar artículos">
                            </div>
                            <div class="col-md-3">
                                <select name="category" class="form-select">
                                    <option value="">Todas las categorías</option>
                                    @if(!empty($categories))
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->slug ?? $cat->id }}" {{ request('category') == ($cat->slug ?? $cat->id) ? 'selected' : '' }}>{{ $cat->name ?? $cat }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-primary">Buscar</button>
                            </div>
                        </div>
                    </form>

                    {{-- Entrada destacada (si viene definida por el controlador) --}}
                    @if(!empty($featured))
                        <article class="card mb-4 shadow-sm overflow-hidden">
                            <div class="row g-0">
                                <div class="col-md-5">
                                    <img src="{{ $featured->image_url ?? asset('assets/img/blog/featured-machu-picchu.jpg') }}" class="img-fluid h-100 object-cover" alt="{{ $featured->title }}">
                                </div>
                                <div class="col-md-7">
                                    <div class="card-body">
                                        <small class="text-muted">{{ $featured->category->name ?? ($featured->category ?? 'Destacado') }}</small>
                                        <h2 class="h4 mt-1"><a href="{{ route('blog.show', $featured->slug ?? $featured->id) }}">{{ $featured->title }}</a></h2>
                                        <p class="text-muted">{{ Str::limit($featured->excerpt ?? $featured->summary ?? $featured->body, 160) }}</p>
                                        <div class="d-flex align-items-center mt-3">
                                            <img src="{{ $featured->author->avatar ?? asset('assets/img/authors/author-1.jpg') }}" alt="{{ $featured->author->name ?? 'Autor' }}" width="40" height="40" class="rounded-circle me-2">
                                            <div>
                                                <small class="d-block">Por <strong>{{ $featured->author->name ?? ($featured->author ?? 'Equipo') }}</strong></small>
                                                <small class="text-muted">{{ optional($featured->published_at)->format('d M, Y') ?? (optional($featured->created_at)->format('d M, Y') ?? '') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endif

                    {{-- Lista de posts (paginada) --}}
                    @if(isset($posts) && $posts->count())
                        <div class="row g-4">
                            @foreach($posts as $post)
                                <div class="col-md-6">
                                    <article class="card h-100 shadow-sm">
                                        <a href="{{ route('blog.show', $post->slug ?? $post->id) }}">
                                            <img src="{{ $post->image_url ?? asset('assets/img/blog/thumb-1.jpg') }}" class="card-img-top" alt="{{ $post->title }}" style="height:200px; object-fit:cover;">
                                        </a>
                                        <div class="card-body d-flex flex-column">
                                            <small class="text-muted">{{ $post->category->name ?? ($post->category ?? '') }} · {{ $post->reading_time ?? '' }}</small>
                                            <h3 class="h6 mt-2"><a href="{{ route('blog.show', $post->slug ?? $post->id) }}" class="text-dark text-decoration-none">{{ $post->title }}</a></h3>
                                            <p class="text-muted mb-3">{{ Str::limit($post->excerpt ?? $post->summary ?? $post->body, 120) }}</p>
                                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $post->author->avatar ?? asset('assets/img/authors/author-2.jpg') }}" alt="{{ $post->author->name ?? 'Autor' }}" width="32" height="32" class="rounded-circle me-2">
                                                    <small class="text-muted">{{ $post->author->name ?? ($post->author ?? 'Equipo') }}</small>
                                                </div>
                                                <small class="text-muted">{{ optional($post->published_at)->format('d M, Y') ?? (optional($post->created_at)->format('d M, Y') ?? '') }}</small>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 d-flex justify-content-center">
                            {{ $posts->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">No se encontraron artículos. Intenta otra búsqueda o vuelve más tarde.</div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <aside class="col-lg-4">
                    {{-- Newsletter --}}
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <h5>Suscríbete</h5>
                            <p class="text-muted">Recibe novedades y guías en tu correo.</p>
                            <form action="{{ route('newsletter.subscribe') ?? '#' }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input name="email" type="email" class="form-control" placeholder="Tu correo" required>
                                    <button class="btn btn-primary" type="submit">Suscribirme</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Categorías --}}
                    <div class="card mb-4">
                        <div class="card-header">Categorías</div>
                        <div class="list-group list-group-flush">
                            @if(!empty($categories))
                                @foreach($categories as $cat)
                                    <a href="{{ route('blog.index', array_merge(request()->except('page'), ['category' => $cat->slug ?? $cat->id])) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">{{ $cat->name ?? $cat }} <span class="badge bg-primary rounded-pill">{{ $cat->posts_count ?? '' }}</span></a>
                                @endforeach
                            @else
                                <div class="list-group-item">No hay categorías</div>
                            @endif
                        </div>
                    </div>

                    {{-- Posts recientes --}}
                    <div class="card mb-4">
                        <div class="card-header">Recientes</div>
                        <ul class="list-group list-group-flush">
                            @forelse($recentPosts ?? [] as $r)
                                <li class="list-group-item d-flex">
                                    <img src="{{ $r->image_url ?? asset('assets/img/blog/thumb-1.jpg') }}" alt="{{ $r->title }}" width="64" height="48" class="rounded me-3" style="object-fit:cover;">
                                    <div>
                                        <a href="{{ route('blog.show', $r->slug ?? $r->id) }}" class="text-decoration-none">{{ Str::limit($r->title, 60) }}</a>
                                        <div><small class="text-muted">{{ optional($r->published_at)->format('d M, Y') ?? '' }}</small></div>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item">No hay artículos recientes</li>
                            @endforelse
                        </ul>
                    </div>

                    {{-- Tags --}}
                    @if(!empty($tags))
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6>Etiquetas</h6>
                                <div class="mt-2">
                                    @foreach($tags as $tag)
                                        <a href="{{ route('blog.index', ['tag' => $tag->slug ?? $tag->name]) }}" class="badge bg-light text-dark me-1 mb-1">{{ $tag->name ?? $tag }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>

    @includeIf('components.modals')

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.AOS) AOS.init();
        });
    </script>
@endpush
