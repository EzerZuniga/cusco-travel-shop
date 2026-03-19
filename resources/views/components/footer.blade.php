<!-- Componente: Footer profesional y accesible -->
<footer class="py-5 bg-dark text-white mt-auto" role="contentinfo">
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-4 d-flex flex-column">
                <a href="{{ url('/') }}" class="mb-2 d-inline-block">
                    <img src="{{ asset('assets/img/logo/logo.png') }}" alt="{{ config('app.name', 'Cusco Travel') }}" height="48">
                </a>
                <p class="mb-1 small text-muted">Agencia de viajes en Cusco. Tours, excursiones y paquetes turísticos con reservas seguras.</p>
                <p class="small text-muted mt-auto">Horario: Lun-Dom 08:00 - 20:00</p>
            </div>

            <div class="col-md-3">
                <h6 class="text-white">Contacto</h6>
                <ul class="list-unstyled small text-muted">
                    <li><strong>Tel:</strong> <a href="tel:{{ config('app.contact_phone', '+51984123456') }}" class="text-muted">{{ config('app.contact_phone', '+51 984 123 456') }}</a></li>
                    <li><strong>Email:</strong> <a href="mailto:{{ config('app.contact_email', 'info@turismocusco.com') }}" class="text-muted">{{ config('app.contact_email', 'info@turismocusco.com') }}</a></li>
                    <li><strong>Dirección:</strong> <span class="text-muted">Cusco, Perú</span></li>
                </ul>
            </div>

            <div class="col-md-3">
                <h6 class="text-white">Enlaces rápidos</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('home') }}" class="text-muted">Inicio</a></li>
                    <li><a href="{{ route('tours.index') }}" class="text-muted">Tours</a></li>
                    <li><a href="{{ route('contacto') }}" class="text-muted">Contacto</a></li>
                    <li><a href="{{ route('web.terminos') ?? '#' }}" class="text-muted">Términos</a></li>
                </ul>
            </div>

            <div class="col-md-2">
                <h6 class="text-white">Síguenos</h6>
                <div class="d-flex gap-2">
                    <a href="{{ config('app.social_facebook', '#') }}" class="text-muted" aria-label="Facebook" target="_blank" rel="noopener noreferrer"><i class="bi bi-facebook"></i></a>
                    <a href="{{ config('app.social_instagram', '#') }}" class="text-muted" aria-label="Instagram" target="_blank" rel="noopener noreferrer"><i class="bi bi-instagram"></i></a>
                    <a href="{{ config('app.social_x', '#') }}" class="text-muted" aria-label="X (Twitter)" target="_blank" rel="noopener noreferrer"><i class="bi bi-x"></i></a>
                </div>
            </div>
        </div>

        <hr class="border-secondary mt-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small text-muted">
            <div>&copy; <span id="currentYear"></span> {{ config('app.name', 'Cusco Travel') }}. Todos los derechos reservados.</div>
            <div>
                <a href="{{ route('admin.login') }}" class="text-muted me-3">Panel</a>
                <a href="{{ route('privacy') ?? '#' }}" class="text-muted">Privacidad</a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Inserta año actual (evita depender de Blade en includes estáticos)
        (function(){
            var el = document.getElementById('currentYear');
            if(el) el.textContent = new Date().getFullYear();
        })();
    </script>
    @endpush
</footer>

{{-- Footer mejorado: accesible, responsivo y configurable mediante `config('app.*')` --}}
