// Gestor de Autenticación
class AuthManager {
    constructor() {
        this.currentUser = null;
        this.init();
    }

    init() {
        this.checkExistingAuth();
        this.setupAuthModals();
        this.setupEventListeners();
    }

    checkExistingAuth() {
        this.currentUser = storage.get('user', null);
        this.updateAuthUI();
    }

    setupAuthModals() {
        // Modal de login
        const loginModal = document.getElementById('loginModal');
        if (loginModal) {
            loginModal.addEventListener('show.bs.modal', () => {
                this.clearAuthForms();
            });
        }

        // Modal de registro
        const registerModal = document.getElementById('registerModal');
        if (registerModal) {
            registerModal.addEventListener('show.bs.modal', () => {
                this.clearAuthForms();
            });
        }
    }

    setupEventListeners() {
        // Formulario de login
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => this.handleLogin(e));
        }

        // Formulario de registro
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', (e) => this.handleRegister(e));
        }

        // Logout
        const logoutButtons = document.querySelectorAll('[data-action="logout"]');
        logoutButtons.forEach(button => {
            button.addEventListener('click', () => this.handleLogout());
        });
    }

    async handleLogin(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const credentials = {
            email: formData.get('email'),
            password: formData.get('password')
        };

        if (!this.validateLoginForm(credentials)) {
            return;
        }

        await this.performLogin(credentials);
    }

    async handleRegister(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const userData = {
            name: formData.get('name'),
            email: formData.get('email'),
            password: formData.get('password'),
            confirmPassword: formData.get('confirmPassword'),
            phone: formData.get('phone'),
            acceptTerms: formData.get('acceptTerms') === 'on'
        };

        if (!this.validateRegisterForm(userData)) {
            return;
        }

        await this.performRegister(userData);
    }

    validateLoginForm(credentials) {
        let isValid = true;

        if (!credentials.email) {
            this.showFieldError('loginEmail', 'El email es obligatorio');
            isValid = false;
        } else if (!Helpers.isValidEmail(credentials.email)) {
            this.showFieldError('loginEmail', 'Ingresa un email válido');
            isValid = false;
        } else {
            this.clearFieldError('loginEmail');
        }

        if (!credentials.password) {
            this.showFieldError('loginPassword', 'La contraseña es obligatoria');
            isValid = false;
        } else {
            this.clearFieldError('loginPassword');
        }

        return isValid;
    }

    validateRegisterForm(userData) {
        let isValid = true;

        // Validar nombre
        if (!userData.name || userData.name.length < 2) {
            this.showFieldError('registerName', 'El nombre debe tener al menos 2 caracteres');
            isValid = false;
        } else {
            this.clearFieldError('registerName');
        }

        // Validar email
        if (!userData.email) {
            this.showFieldError('registerEmail', 'El email es obligatorio');
            isValid = false;
        } else if (!Helpers.isValidEmail(userData.email)) {
            this.showFieldError('registerEmail', 'Ingresa un email válido');
            isValid = false;
        } else {
            this.clearFieldError('registerEmail');
        }

        // Validar contraseña
        if (!userData.password) {
            this.showFieldError('registerPassword', 'La contraseña es obligatoria');
            isValid = false;
        } else if (userData.password.length < 6) {
            this.showFieldError('registerPassword', 'La contraseña debe tener al menos 6 caracteres');
            isValid = false;
        } else {
            this.clearFieldError('registerPassword');
        }

        // Validar confirmación de contraseña
        if (userData.password !== userData.confirmPassword) {
            this.showFieldError('registerConfirmPassword', 'Las contraseñas no coinciden');
            isValid = false;
        } else {
            this.clearFieldError('registerConfirmPassword');
        }

        // Validar teléfono
        if (userData.phone && !Helpers.isValidPhone(userData.phone)) {
            this.showFieldError('registerPhone', 'Ingresa un número de teléfono válido');
            isValid = false;
        } else {
            this.clearFieldError('registerPhone');
        }

        // Validar términos
        if (!userData.acceptTerms) {
            this.showFieldError('registerAcceptTerms', 'Debes aceptar los términos y condiciones');
            isValid = false;
        } else {
            this.clearFieldError('registerAcceptTerms');
        }

        return isValid;
    }

    async performLogin(credentials) {
        try {
            this.setAuthFormState('login', 'loading');

            // Simular login - reemplazar con API real
            await new Promise(resolve => setTimeout(resolve, 1000));

            const user = {
                id: Helpers.generateId(),
                name: credentials.email.split('@')[0],
                email: credentials.email,
                avatar: 'assets/img/users/default-avatar.jpg',
                joinedAt: new Date().toISOString()
            };

            this.currentUser = user;
            storage.set('user', user);

            this.setAuthFormState('login', 'success');
            this.closeAuthModals();
            this.updateAuthUI();
            
            TurismoCuscoApp.showNotification(`¡Bienvenido, ${user.name}!`, 'success');

        } catch (error) {
            this.setAuthFormState('login', 'error');
            this.showFieldError('loginPassword', 'Credenciales incorrectas');
        }
    }

    async performRegister(userData) {
        try {
            this.setAuthFormState('register', 'loading');

            // Simular registro - reemplazar con API real
            await new Promise(resolve => setTimeout(resolve, 1500));

            const user = {
                id: Helpers.generateId(),
                name: userData.name,
                email: userData.email,
                phone: userData.phone,
                avatar: 'assets/img/users/default-avatar.jpg',
                joinedAt: new Date().toISOString()
            };

            this.currentUser = user;
            storage.set('user', user);

            this.setAuthFormState('register', 'success');
            this.closeAuthModals();
            this.updateAuthUI();
            
            TurismoCuscoApp.showNotification(`¡Cuenta creada exitosamente, ${user.name}!`, 'success');

        } catch (error) {
            this.setAuthFormState('register', 'error');
            TurismoCuscoApp.showNotification('Error al crear la cuenta', 'danger');
        }
    }

    setAuthFormState(formType, state) {
        const form = document.getElementById(`${formType}Form`);
        const button = form?.querySelector('button[type="submit"]');
        
        if (!button) return;

        const originalText = button.innerHTML;

        switch (state) {
            case 'loading':
                button.disabled = true;
                button.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    ${formType === 'login' ? 'Iniciando sesión...' : 'Creando cuenta...'}
                `;
                break;
            case 'success':
            case 'error':
                button.disabled = false;
                button.innerHTML = originalText;
                break;
        }
    }

    handleLogout() {
        this.currentUser = null;
        storage.remove('user');
        this.updateAuthUI();
        TurismoCuscoApp.showNotification('Sesión cerrada correctamente', 'info');
    }

    updateAuthUI() {
        const authElements = document.querySelectorAll('[data-auth-state]');
        
        authElements.forEach(element => {
            const authState = element.getAttribute('data-auth-state');
            const shouldShow = (
                (authState === 'authenticated' && this.currentUser) ||
                (authState === 'unauthenticated' && !this.currentUser)
            );
            
            element.style.display = shouldShow ? 'block' : 'none';
        });

        // Actualizar información del usuario
        const userNameElements = document.querySelectorAll('[data-user-name]');
        userNameElements.forEach(element => {
            if (this.currentUser) {
                element.textContent = this.currentUser.name;
            }
        });

        const userAvatarElements = document.querySelectorAll('[data-user-avatar]');
        userAvatarElements.forEach(element => {
            if (this.currentUser) {
                element.src = this.currentUser.avatar;
                element.style.display = 'block';
            } else {
                element.style.display = 'none';
            }
        });
    }

    showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        this.clearFieldError(field);
        
        field.classList.add('is-invalid');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        
        field.parentNode.appendChild(errorDiv);
    }

    clearFieldError(field) {
        if (typeof field === 'string') {
            field = document.getElementById(field);
        }
        
        if (!field) return;
        
        field.classList.remove('is-invalid');
        
        const errorFeedback = field.parentNode.querySelector('.invalid-feedback');
        if (errorFeedback) {
            errorFeedback.remove();
        }
    }

    clearAuthForms() {
        // Limpiar errores
        const authForms = document.querySelectorAll('#loginForm, #registerForm');
        authForms.forEach(form => {
            const fields = form.querySelectorAll('.is-invalid');
            fields.forEach(field => this.clearFieldError(field));
        });

        // Resetear estados de botones
        const buttons = document.querySelectorAll('#loginForm button, #registerForm button');
        buttons.forEach(button => {
            button.disabled = false;
            const originalText = button.getAttribute('data-original-text');
            if (originalText) {
                button.innerHTML = originalText;
            }
        });
    }

    closeAuthModals() {
        const modals = ['loginModal', 'registerModal'];
        
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal) {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) {
                    bsModal.hide();
                }
            }
        });
    }

    // Métodos públicos
    isAuthenticated() {
        return this.currentUser !== null;
    }

    getCurrentUser() {
        return this.currentUser;
    }

    requireAuth() {
        if (!this.isAuthenticated()) {
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
            return false;
        }
        return true;
    }
}

// Inicializar auth manager
document.addEventListener('DOMContentLoaded', () => {
    window.AuthManager = new AuthManager();
});