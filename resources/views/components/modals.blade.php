{{-- Componentes: Modales reutilizables (login, register, confirmación, alert genérico) --}}

{{-- Login Modal --}}
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Iniciar sesión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ Route::has('login') ? route('login') : '#' }}" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="login_email" class="form-label">Correo electrónico</label>
                        <input id="login_email" name="email" type="email" class="form-control" required autofocus aria-required="true">
                    </div>

                    <div class="mb-3">
                        <label for="login_password" class="form-label">Contraseña</label>
                        <input id="login_password" name="password" type="password" class="form-control" required aria-required="true">
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">Recuérdame</label>
                        </div>
                        <a href="{{ Route::has('password.request') ? route('password.request') : '#' }}" class="small">¿Olvidaste tu contraseña?</a>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <small class="text-muted">¿No tienes cuenta? <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">Regístrate</a></small>
            </div>
        </div>
    </div>
</div>

{{-- Register Modal --}}
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Crear cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ Route::has('register') ? route('register') : '#' }}" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="reg_name" class="form-label">Nombre completo</label>
                        <input id="reg_name" name="name" type="text" class="form-control" required aria-required="true">
                    </div>
                    <div class="mb-3">
                        <label for="reg_email" class="form-label">Correo electrónico</label>
                        <input id="reg_email" name="email" type="email" class="form-control" required aria-required="true">
                    </div>
                    <div class="mb-3">
                        <label for="reg_password" class="form-label">Contraseña</label>
                        <input id="reg_password" name="password" type="password" class="form-control" required aria-required="true">
                    </div>
                    <div class="mb-3">
                        <label for="reg_password_confirmation" class="form-label">Confirmar contraseña</label>
                        <input id="reg_password_confirmation" name="password_confirmation" type="password" class="form-control" required aria-required="true">
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-success" type="submit">Crear cuenta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Confirm Delete Modal (reusable) --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">¿Estás seguro que deseas eliminar este elemento? Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="confirm-delete-form" method="POST" action="#">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Generic Alert Modal --}}
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">Aviso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p id="alertModalMessage" class="mb-0"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Función auxiliar para abrir el modal de confirmación y apuntar el formulario al action correcto
    function openConfirmDelete(actionUrl){
        var form = document.getElementById('confirm-delete-form');
        if(form){
            form.action = actionUrl;
            var modalEl = document.getElementById('confirmDeleteModal');
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    }

    // Mostrar alert genérico
    function showAlert(message){
        var el = document.getElementById('alertModalMessage');
        if(el) el.textContent = message;
        var modalEl = document.getElementById('alertModal');
        var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }

    // Cuando el servidor devuelva mensajes flash para mostrar (ej.: session('status'))
    document.addEventListener('DOMContentLoaded', function(){
        @if(session('status'))
            showAlert(@json(session('status')));
        @endif
    });
</script>
@endpush
