// Utilidades de Interfaz de Usuario
class UIManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupSmoothScrolling();
        this.setupLazyLoading();
        this.setupAnimations();
        this.setupBackToTop();
        this.setupImageZoom();
    }

    setupSmoothScrolling() {
        // Scroll suave para enlaces internos
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href^="#"]');
            if (link) {
                e.preventDefault();
                const target = document.querySelector(link.getAttribute('href'));
                if (target) {
                    Helpers.smoothScrollTo(target);
                }
            }
        });
    }

    setupLazyLoading() {
        // Lazy loading para imágenes
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        } else {
            // Fallback para navegadores antiguos
            document.querySelectorAll('img[data-src]').forEach(img => {
                img.src = img.dataset.src;
            });
        }
    }

    setupAnimations() {
        // Animaciones al hacer scroll
        if ('IntersectionObserver' in window) {
            const animationObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.animate-on-scroll').forEach(element => {
                animationObserver.observe(element);
            });
        }
    }

    setupBackToTop() {
        // Botón "volver arriba"
        const backToTop = document.createElement('button');
        backToTop.innerHTML = '<i class="bi bi-chevron-up"></i>';
        backToTop.className = 'btn btn-primary back-to-top';
        backToTop.style.cssText = `
            position: fixed;
            bottom: 80px;
            right: 25px;
            z-index: 999;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
        `;

        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        document.body.appendChild(backToTop);

        // Mostrar/ocultar botón
        window.addEventListener('scroll', Helpers.throttle(() => {
            if (window.pageYOffset > 300) {
                backToTop.style.display = 'flex';
            } else {
                backToTop.style.display = 'none';
            }
        }, 100));
    }

    setupImageZoom() {
        // Zoom en imágenes de galería
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('zoomable-image')) {
                this.openImageZoom(e.target);
            }
        });
    }

    openImageZoom(imgElement) {
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: zoom-out;
        `;

        const zoomedImg = document.createElement('img');
        zoomedImg.src = imgElement.src;
        zoomedImg.alt = imgElement.alt;
        zoomedImg.style.cssText = `
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
            transform: scale(0.8);
            transition: transform 0.3s ease;
        `;

        overlay.appendChild(zoomedImg);
        document.body.appendChild(overlay);
        document.body.style.overflow = 'hidden';

        // Animación de entrada
        setTimeout(() => {
            zoomedImg.style.transform = 'scale(1)';
        }, 10);

        // Cerrar al hacer click
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay || e.target === zoomedImg) {
                zoomedImg.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    document.body.removeChild(overlay);
                    document.body.style.overflow = '';
                }, 300);
            }
        });

        // Cerrar con ESC
        const handleKeydown = (e) => {
            if (e.key === 'Escape') {
                zoomedImg.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    document.body.removeChild(overlay);
                    document.body.style.overflow = '';
                    document.removeEventListener('keydown', handleKeydown);
                }, 300);
            }
        };

        document.addEventListener('keydown', handleKeydown);
    }

    // Loading states
    showLoading(container) {
        const spinner = document.createElement('div');
        spinner.className = 'loading-spinner';
        spinner.style.cssText = `
            width: 40px;
            height: 40px;
            margin: 2rem auto;
        `;

        if (typeof container === 'string') {
            container = document.querySelector(container);
        }

        if (container) {
            container.innerHTML = '';
            container.appendChild(spinner);
        }
    }

    hideLoading(container) {
        if (typeof container === 'string') {
            container = document.querySelector(container);
        }

        const spinner = container?.querySelector('.loading-spinner');
        if (spinner) {
            spinner.remove();
        }
    }

    // Modal helpers
    showModal(modalId, options = {}) {
        const modalElement = document.getElementById(modalId);
        if (!modalElement) return null;

        const modal = new bootstrap.Modal(modalElement, options);
        modal.show();
        return modal;
    }

    hideModal(modalId) {
        const modalElement = document.getElementById(modalId);
        if (!modalElement) return;

        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        }
    }

    // Form helpers
    resetForm(formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.reset();
            
            // Limpiar errores de validación
            const invalidFields = form.querySelectorAll('.is-invalid');
            invalidFields.forEach(field => {
                field.classList.remove('is-invalid');
                const feedback = field.parentNode.querySelector('.invalid-feedback');
                if (feedback) feedback.remove();
            });
        }
    }

    // Tab switcher
    setupTabs(containerSelector) {
        const container = document.querySelector(containerSelector);
        if (!container) return;

        const tabButtons = container.querySelectorAll('[data-tab]');
        const tabPanes = container.querySelectorAll('.tab-pane');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');
                
                // Actualizar botones activos
                tabButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Actualizar paneles activos
                tabPanes.forEach(pane => pane.classList.remove('active', 'show'));
                const targetPane = document.getElementById(tabId);
                if (targetPane) {
                    targetPane.classList.add('active', 'show');
                }
            });
        });
    }

    // Accordion helpers
    setupAccordions(containerSelector) {
        const container = document.querySelector(containerSelector);
        if (!container) return;

        const accordionHeaders = container.querySelectorAll('.accordion-header');
        
        accordionHeaders.forEach(header => {
            header.addEventListener('click', () => {
                const button = header.querySelector('.accordion-button');
                const target = header.nextElementSibling;
                
                if (button.classList.contains('collapsed')) {
                    // Abrir
                    button.classList.remove('collapsed');
                    target.classList.add('show');
                } else {
                    // Cerrar
                    button.classList.add('collapsed');
                    target.classList.remove('show');
                }
            });
        });
    }

    // Copy to clipboard
    setupCopyButtons() {
        document.addEventListener('click', (e) => {
            const copyButton = e.target.closest('[data-copy]');
            if (copyButton) {
                const textToCopy = copyButton.getAttribute('data-copy');
                Helpers.copyToClipboard(textToCopy).then(success => {
                    if (success) {
                        const originalHTML = copyButton.innerHTML;
                        copyButton.innerHTML = '<i class="bi bi-check"></i> Copiado';
                        copyButton.disabled = true;
                        
                        setTimeout(() => {
                            copyButton.innerHTML = originalHTML;
                            copyButton.disabled = false;
                        }, 2000);
                    }
                });
            }
        });
    }

    // Price formatter
    formatPrice(amount, currency = 'PEN') {
        return Helpers.formatCurrency(amount, currency);
    }

    // Date formatter
    formatDate(date, options = {}) {
        return Helpers.formatDate(date, options);
    }

    // Responsive helpers
    isMobile() {
        return window.innerWidth < 768;
    }

    isTablet() {
        return window.innerWidth >= 768 && window.innerWidth < 992;
    }

    isDesktop() {
        return window.innerWidth >= 992;
    }

    // Session storage para estado UI
    setUIState(key, value) {
        sessionStorage.setItem(`ui_${key}`, JSON.stringify(value));
    }

    getUIState(key, defaultValue = null) {
        const item = sessionStorage.getItem(`ui_${key}`);
        return item ? JSON.parse(item) : defaultValue;
    }
}

// Inicializar UI Manager
document.addEventListener('DOMContentLoaded', () => {
    window.UIManager = new UIManager();
});