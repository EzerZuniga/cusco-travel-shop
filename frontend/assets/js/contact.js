// Gestor de Formularios de Contacto
class ContactManager {
    constructor() {
        this.form = null;
        this.init();
    }

    init() {
        this.form = document.getElementById('contact-form');
        if (this.form) {
            this.setupFormValidation();
            this.setupEventListeners();
        }
    }

    setupFormValidation() {
        // Validación en tiempo real
        const inputs = this.form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input));
        });

        // Validación personalizada para email
        const emailInput = this.form.querySelector('#email');
        if (emailInput) {
            emailInput.addEventListener('input', () => this.validateEmail(emailInput));
        }

        // Validación personalizada para teléfono
        const phoneInput = this.form.querySelector('#phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', () => this.formatPhone(phoneInput));
        }
    }

    setupEventListeners() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));

        // Intereses de tours
        const tourCheckboxes = this.form.querySelectorAll('input[name="tour-interest"]');
        tourCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => this.updateTourInterests());
        });

        // Cambio de tipo de consulta
        const subjectSelect = this.form.querySelector('#subject');
        if (subjectSelect) {
            subjectSelect.addEventListener('change', () => this.handleSubjectChange());
        }
    }

    validateField(field) {
        const value = field.value.trim();
        const isRequired = field.hasAttribute('required');
        
        if (isRequired && !value) {
            this.showFieldError(field, 'Este campo es obligatorio');
            return false;
        }

        // Validaciones específicas por tipo
        switch (field.type) {
            case 'email':
                return this.validateEmail(field);
            case 'tel':
                return this.validatePhone(field);
            default:
                this.clearFieldError(field);
                return true;
        }
    }

    validateEmail(field) {
        const value = field.value.trim();
        
        if (value && !Helpers.isValidEmail(value)) {
            this.showFieldError(field, 'Ingresa un email válido');
            return false;
        }
        
        this.clearFieldError(field);
        return true;
    }

    validatePhone(field) {
        const value = field.value.trim();
        
        if (value && !Helpers.isValidPhone(value)) {
            this.showFieldError(field, 'Ingresa un número de teléfono válido (9 dígitos)');
            return false;
        }
        
        this.clearFieldError(field);
        return true;
    }

    formatPhone(field) {
        let value = field.value.replace(/\D/g, '');
        
        if (value.length > 0) {
            value = value.match(/.{1,3}/g).join(' ');
        }
        
        field.value = value;
    }

    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.classList.add('is-invalid');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        
        field.parentNode.appendChild(errorDiv);
    }

    clearFieldError(field) {
        field.classList.remove('is-invalid');
        
        const errorFeedback = field.parentNode.querySelector('.invalid-feedback');
        if (errorFeedback) {
            errorFeedback.remove();
        }
    }

    updateTourInterests() {
        const selectedTours = Array.from(
            this.form.querySelectorAll('input[name="tour-interest"]:checked')
        ).map(checkbox => checkbox.value);
        
        // Puedes usar esta información para personalizar la experiencia
        console.log('Tours de interés:', selectedTours);
    }

    handleSubjectChange() {
        const subject = this.form.querySelector('#subject').value;
        const messageField = this.form.querySelector('#message');
        
        if (messageField) {
            switch (subject) {
                case 'informacion-tours':
                    messageField.placeholder = 'Por favor, cuéntanos qué tours te interesan, fechas tentativas, número de personas...';
                    break;
                case 'reserva':
                    messageField.placeholder = 'Especifica los tours que deseas reservar, fechas exactas, número de personas, necesidades especiales...';
                    break;
                case 'cotizacion':
                    messageField.placeholder = 'Detalla los servicios requeridos, número de personas, fechas, alojamiento, etc...';
                    break;
                default:
                    messageField.placeholder = 'Cuéntanos más sobre tu consulta...';
            }
        }
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        if (!this.validateForm()) {
            TurismoCuscoApp.showNotification('Por favor, corrige los errores en el formulario', 'warning');
            return;
        }

        const formData = this.getFormData();
        
        try {
            await this.submitForm(formData);
        } catch (error) {
            this.handleSubmitError(error);
        }
    }

    validateForm() {
        let isValid = true;
        const requiredFields = this.form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    getFormData() {
        const formData = new FormData(this.form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            if (key === 'tour-interest') {
                if (!data[key]) data[key] = [];
                data[key].push(value);
            } else {
                data[key] = value;
            }
        }

        // Información adicional
        data.timestamp = new Date().toISOString();
        data.page = window.location.href;
        data.userAgent = navigator.userAgent;

        return data;
    }

    async submitForm(formData) {
        // Mostrar estado de carga
        this.setFormState('loading');

        // Enviar a API
        const response = await apiService.sendContact(formData);

        if (response.success) {
            this.handleSubmitSuccess();
        } else {
            throw new Error(response.error || 'Error al enviar el formulario');
        }
    }

    setFormState(state) {
        const submitButton = this.form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        
        switch (state) {
            case 'loading':
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    Enviando...
                `;
                break;
            case 'success':
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
                break;
            case 'error':
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
                break;
        }
    }

    handleSubmitSuccess() {
        this.setFormState('success');
        
        // Mostrar mensaje de éxito
        TurismoCuscoApp.showNotification('¡Mensaje enviado correctamente! Te contactaremos pronto.', 'success');
        
        // Resetear formulario
        this.form.reset();
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    handleSubmitError(error) {
        this.setFormState('error');
        
        console.error('Contact form error:', error);
        TurismoCuscoApp.showNotification('Error al enviar el mensaje. Por favor, intenta nuevamente.', 'danger');
    }
}

// Inicializar manager de contacto
document.addEventListener('DOMContentLoaded', () => {
    new ContactManager();
});