// Aplicación principal - Turismo Cusco
class TurismoCuscoApp {
    constructor() {
        this.cart = storage.get('cart', []);
        this.user = storage.get('user', null);
        this.favorites = storage.get('favorites', []);
        this.init();
    }

    init() {
        this.loadComponents();
        this.setupEventListeners();
        this.updateCartCount();
        this.checkAuthStatus();
        this.loadInitialData();
    }

    // Sistema de componentes
    async loadComponents() {
        const components = ['header', 'navbar', 'footer', 'modals'];
        
        for (const component of components) {
            try {
                const response = await fetch(`components/${component}.html`);
                if (!response.ok) continue;
                
                const html = await response.text();
                const elements = document.querySelectorAll(`[data-component="${component}"]`);
                
                elements.forEach(element => {
                    element.innerHTML = html;
                    
                    // Ejecutar scripts dentro del componente
                    const scripts = element.querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.head.appendChild(newScript).remove();
                    });
                });
            } catch (error) {
                console.warn(`Component ${component} not found`);
            }
        }
        
        // Inicializar componentes después de cargar
        this.initializeComponents();
    }

    initializeComponents() {
        // Inicializar tooltips
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));

        // Inicializar popovers
        const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
        popovers.forEach(popover => new bootstrap.Popover(popover));
    }

    setupEventListeners() {
        // Buscador global
        const searchInput = document.querySelector('.search-box input');
        if (searchInput) {
            searchInput.addEventListener('input', Helpers.debounce((e) => {
                this.handleSearch(e.target.value);
            }, 300));
        }

        // Filtros de URL
        this.handleUrlFilters();
    }

    handleUrlFilters() {
        const urlParams = new URLSearchParams(window.location.search);
        const destino = urlParams.get('destino');
        const categoria = urlParams.get('categoria');
        
        if (destino && window.ToursManager) {
            window.ToursManager.filterByDestination(destino);
        }
        
        if (categoria && window.ToursManager) {
            window.ToursManager.filterByCategory(categoria);
        }
    }

    handleSearch(query) {
        if (query.length < 2) return;
        
        // Buscar en tours
        if (window.ToursManager) {
            window.ToursManager.searchTours(query);
        }
        
        // Buscar en blog si estamos en esa página
        if (window.BlogManager) {
            window.BlogManager.searchPosts(query);
        }
    }

    // Carrito de compras
    updateCartCount() {
        const cartCountElements = document.querySelectorAll('#cart-count');
        const totalItems = this.cart.reduce((sum, item) => sum + item.quantity, 0);
        
        cartCountElements.forEach(element => {
            element.textContent = totalItems;
            element.style.display = totalItems > 0 ? 'block' : 'none';
        });
    }

    addToCart(tour, quantity = 1, date = null) {
        const existingItem = this.cart.find(item => item.id === tour.id && item.date === date);
        
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            this.cart.push({
                ...tour,
                quantity,
                date: date || new Date().toISOString().split('T')[0],
                addedAt: new Date().toISOString()
            });
        }
        
        storage.set('cart', this.cart);
        this.updateCartCount();
        
        // Mostrar notificación
        this.showNotification(`${tour.name} agregado al carrito`, 'success');
        
        return true;
    }

    removeFromCart(itemId, date = null) {
        this.cart = this.cart.filter(item => 
            !(item.id === itemId && item.date === date)
        );
        
        storage.set('cart', this.cart);
        this.updateCartCount();
        
        if (window.ShoppingCart) {
            window.ShoppingCart.refreshCart();
        }
        
        return true;
    }

    updateCartItemQuantity(itemId, date, newQuantity) {
        const item = this.cart.find(item => 
            item.id === itemId && item.date === date
        );
        
        if (item) {
            if (newQuantity <= 0) {
                this.removeFromCart(itemId, date);
            } else {
                item.quantity = newQuantity;
                storage.set('cart', this.cart);
                
                if (window.ShoppingCart) {
                    window.ShoppingCart.refreshCart();
                }
            }
        }
    }

    clearCart() {
        this.cart = [];
        storage.set('cart', this.cart);
        this.updateCartCount();
        
        if (window.ShoppingCart) {
            window.ShoppingCart.refreshCart();
        }
        
        this.showNotification('Carrito vaciado', 'info');
    }

    // Autenticación
    async login(email, password) {
        try {
            // Simulación de login - reemplazar con API real
            const user = {
                id: Helpers.generateId(),
                email,
                name: email.split('@')[0],
                avatar: 'assets/img/users/default-avatar.jpg'
            };
            
            this.user = user;
            storage.set('user', user);
            
            this.showNotification('¡Bienvenido!', 'success');
            this.updateAuthUI();
            
            return { success: true, user };
        } catch (error) {
            this.showNotification('Error al iniciar sesión', 'error');
            return { success: false, error: error.message };
        }
    }

    logout() {
        this.user = null;
        storage.remove('user');
        this.updateAuthUI();
        this.showNotification('Sesión cerrada', 'info');
    }

    checkAuthStatus() {
        if (this.user) {
            this.updateAuthUI();
        }
    }

    updateAuthUI() {
        const authElements = document.querySelectorAll('[data-auth]');
        
        authElements.forEach(element => {
            const authState = element.getAttribute('data-auth');
            
            if (authState === 'authenticated' && this.user) {
                element.style.display = 'block';
                const userName = element.querySelector('[data-user-name]');
                if (userName) userName.textContent = this.user.name;
            } else if (authState === 'unauthenticated' && !this.user) {
                element.style.display = 'block';
            } else {
                element.style.display = 'none';
            }
        });
    }

    // Favoritos
    toggleFavorite(tourId) {
        const index = this.favorites.indexOf(tourId);
        
        if (index > -1) {
            this.favorites.splice(index, 1);
            this.showNotification('Eliminado de favoritos', 'info');
        } else {
            this.favorites.push(tourId);
            this.showNotification('Agregado a favoritos', 'success');
        }
        
        storage.set('favorites', this.favorites);
        this.updateFavoritesUI();
    }

    updateFavoritesUI() {
        const favoriteButtons = document.querySelectorAll('[data-favorite]');
        
        favoriteButtons.forEach(button => {
            const tourId = parseInt(button.getAttribute('data-favorite'));
            const isFavorite = this.favorites.includes(tourId);
            
            const icon = button.querySelector('i');
            if (icon) {
                icon.className = isFavorite ? 'bi bi-heart-fill text-danger' : 'bi bi-heart';
            }
            
            button.setAttribute('data-bs-original-title', isFavorite ? 'Quitar de favoritos' : 'Agregar a favoritos');
        });
    }

    // Utilidades
    showNotification(message, type = 'info') {
        // Crear notificación toast
        const toastContainer = document.getElementById('toast-container') || this.createToastContainer();
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-bg-${type} border-0`;
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remover después de ocultar
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }

    loadInitialData() {
        // Cargar datos iniciales según la página
        const path = window.location.pathname;
        
        if (path.includes('tours.html') && window.ToursManager) {
            window.ToursManager.loadTours();
        }
        
        if (path.includes('blog.html') && window.BlogManager) {
            window.BlogManager.loadPosts();
        }
        
        if (path.includes('gallery.html') && window.GalleryManager) {
            window.GalleryManager.loadGallery();
        }
    }

    // Métodos globales accesibles
    getCartTotal() {
        return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    }

    getCartItems() {
        return [...this.cart];
    }

    isInCart(tourId, date = null) {
        return this.cart.some(item => 
            item.id === tourId && (!date || item.date === date)
        );
    }

    isFavorite(tourId) {
        return this.favorites.includes(tourId);
    }
}

// Inicializar aplicación
const app = new TurismoCuscoApp();

// Hacerla global para acceso fácil
window.TurismoCuscoApp = app;