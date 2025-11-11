// Gestor de Tours
class ToursManager {
    constructor() {
        this.tours = [];
        this.filteredTours = [];
        this.currentFilters = {
            category: '',
            destination: '',
            priceRange: '',
            difficulty: '',
            duration: '',
            searchQuery: ''
        };
        this.init();
    }

    async init() {
        await this.loadTours();
        this.setupEventListeners();
        this.setupFilters();
    }

    async loadTours() {
        try {
            // Intentar cargar desde API
            const response = await apiService.getTours();
            
            if (response.success) {
                this.tours = response.data;
            } else {
                // Fallback a JSON local
                const localResponse = await fetch('data/tours.json');
                this.tours = await localResponse.json();
            }
            
            this.filteredTours = [...this.tours];
            this.renderTours();
        } catch (error) {
            console.error('Error loading tours:', error);
            this.showError('Error al cargar los tours');
        }
    }

    setupEventListeners() {
        // Filtros
        const filterElements = {
            category: '#filter-category',
            destination: '#filter-destination',
            priceRange: '#filter-price',
            difficulty: '#filter-difficulty',
            duration: '#filter-duration'
        };

        Object.entries(filterElements).forEach(([key, selector]) => {
            const element = document.querySelector(selector);
            if (element) {
                element.addEventListener('change', (e) => {
                    this.currentFilters[key] = e.target.value;
                    this.applyFilters();
                });
            }
        });

        // Buscador
        const searchInput = document.querySelector('#search-tours');
        if (searchInput) {
            searchInput.addEventListener('input', Helpers.debounce((e) => {
                this.currentFilters.searchQuery = e.target.value;
                this.applyFilters();
            }, 300));
        }

        // Vista (grid/list)
        const gridViewBtn = document.querySelector('#grid-view');
        const listViewBtn = document.querySelector('#list-view');

        if (gridViewBtn && listViewBtn) {
            gridViewBtn.addEventListener('click', () => this.switchView('grid'));
            listViewBtn.addEventListener('click', () => this.switchView('list'));
        }

        // Limpiar filtros
        const clearFiltersBtn = document.querySelector('#clear-filters');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => this.clearFilters());
        }
    }

    setupFilters() {
        // Inicializar filtros desde URL
        const urlParams = new URLSearchParams(window.location.search);
        
        ['category', 'destination', 'priceRange', 'difficulty', 'duration'].forEach(param => {
            const value = urlParams.get(param);
            if (value) {
                this.currentFilters[param] = value;
                const element = document.querySelector(`#filter-${param}`);
                if (element) element.value = value;
            }
        });

        const searchQuery = urlParams.get('search');
        if (searchQuery) {
            this.currentFilters.searchQuery = searchQuery;
            const searchInput = document.querySelector('#search-tours');
            if (searchInput) searchInput.value = searchQuery;
        }

        this.applyFilters();
    }

    applyFilters() {
        this.filteredTours = this.tours.filter(tour => {
            // Filtro por categoría
            if (this.currentFilters.category && tour.category !== this.currentFilters.category) {
                return false;
            }

            // Filtro por destino
            if (this.currentFilters.destination && !tour.destinations?.includes(this.currentFilters.destination)) {
                return false;
            }

            // Filtro por precio
            if (this.currentFilters.priceRange) {
                const [min, max] = this.currentFilters.priceRange.split('-').map(Number);
                if (min && tour.price < min) return false;
                if (max && tour.price > max) return false;
            }

            // Filtro por dificultad
            if (this.currentFilters.difficulty && tour.difficulty !== this.currentFilters.difficulty) {
                return false;
            }

            // Filtro por duración
            if (this.currentFilters.duration) {
                const tourDuration = parseInt(tour.duration);
                const filterDuration = parseInt(this.currentFilters.duration);
                
                if (filterDuration === 1 && tourDuration > 0.5) return false; // Medio día
                if (filterDuration === 2 && tourDuration !== 1) return false; // 1 día
                if (filterDuration === 3 && (tourDuration < 2 || tourDuration > 3)) return false; // 2-3 días
                if (filterDuration === 4 && tourDuration < 4) return false; // 4+ días
            }

            // Filtro por búsqueda
            if (this.currentFilters.searchQuery) {
                const query = this.currentFilters.searchQuery.toLowerCase();
                const searchableText = `
                    ${tour.name} 
                    ${tour.description} 
                    ${tour.category} 
                    ${tour.destinations?.join(' ')} 
                    ${tour.includes?.join(' ')}
                `.toLowerCase();

                if (!searchableText.includes(query)) {
                    return false;
                }
            }

            return true;
        });

        this.renderTours();
        this.updateFiltersURL();
    }

    updateFiltersURL() {
        const url = new URL(window.location);
        
        // Limpiar parámetros existentes
        ['category', 'destination', 'priceRange', 'difficulty', 'duration', 'search'].forEach(param => {
            url.searchParams.delete(param);
        });

        // Agregar parámetros activos
        Object.entries(this.currentFilters).forEach(([key, value]) => {
            if (value) {
                url.searchParams.set(key, value);
            }
        });

        // Actualizar URL sin recargar
        window.history.replaceState({}, '', url);
    }

    clearFilters() {
        // Resetear filtros
        this.currentFilters = {
            category: '',
            destination: '',
            priceRange: '',
            difficulty: '',
            duration: '',
            searchQuery: ''
        };

        // Resetear inputs
        document.querySelectorAll('select, input[type="text"]').forEach(element => {
            if (element.id && element.id.startsWith('filter-')) {
                element.value = '';
            }
        });

        const searchInput = document.querySelector('#search-tours');
        if (searchInput) searchInput.value = '';

        this.applyFilters();
        TurismoCuscoApp.showNotification('Filtros limpiados', 'info');
    }

    renderTours() {
        const container = document.getElementById('tours-container');
        const listContainer = document.getElementById('tours-list');
        
        if (!container && !listContainer) return;

        const toursToRender = this.filteredTours;
        const isListView = container?.classList.contains('list-view');
        
        if (container) {
            container.innerHTML = this.generateToursGrid(toursToRender, isListView);
        }
        
        if (listContainer) {
            listContainer.innerHTML = this.generateToursList(toursToRender);
        }

        this.updateResultsCount(toursToRender.length);
        this.attachTourEventListeners();
    }

    generateToursGrid(tours, isListView = false) {
        if (tours.length === 0) {
            return `
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search display-1 text-muted mb-3"></i>
                    <h3>No se encontraron tours</h3>
                    <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                    <button class="btn btn-primary" onclick="ToursManager.clearFilters()">
                        Limpiar filtros
                    </button>
                </div>
            `;
        }

        return tours.map(tour => `
            <div class="col-lg-${isListView ? '12' : '4'} col-md-6 mb-4">
                <div class="card tour-card h-100 ${isListView ? 'tour-card-list' : ''}">
                    <div class="position-relative">
                        <img src="${tour.image}" class="card-img-top" alt="${tour.name}" 
                             style="height: ${isListView ? '200px' : '200px'}; object-fit: cover;">
                        <div class="card-img-overlay d-flex justify-content-between align-items-start p-3">
                            <span class="badge bg-${this.getCategoryColor(tour.category)}">${tour.category}</span>
                            <button class="btn btn-light btn-sm" 
                                    data-favorite="${tour.id}"
                                    onclick="TurismoCuscoApp.toggleFavorite(${tour.id})"
                                    data-bs-toggle="tooltip" title="Agregar a favoritos">
                                <i class="bi ${TurismoCuscoApp.isFavorite(tour.id) ? 'bi-heart-fill text-danger' : 'bi-heart'}"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title">${tour.name}</h5>
                            <span class="text-warning small">
                                <i class="bi bi-star-fill"></i> ${tour.rating}
                            </span>
                        </div>
                        
                        <p class="card-text text-muted small flex-grow-1">${tour.description}</p>
                        
                        <div class="tour-features mb-3">
                            <div class="d-flex gap-3 text-muted small">
                                <span><i class="bi bi-clock me-1"></i> ${tour.duration}</span>
                                <span><i class="bi bi-people me-1"></i> ${tour.maxPeople} pers.</span>
                                <span><i class="bi bi-geo-alt me-1"></i> ${tour.difficulty}</span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div>
                                <span class="h5 text-primary mb-0">${Helpers.formatCurrency(tour.price)}</span>
                                <small class="text-muted d-block">por persona</small>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" 
                                        onclick="ToursManager.showTourDetails(${tour.id})">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-primary btn-sm" 
                                        onclick="TurismoCuscoApp.addToCart(${JSON.stringify(tour).replace(/"/g, '&quot;')})">
                                    <i class="bi bi-cart-plus"></i> Reservar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    generateToursList(tours) {
        if (tours.length === 0) {
            return '<div class="alert alert-info">No se encontraron tours con los filtros aplicados.</div>';
        }

        return tours.map(tour => `
            <div class="col-12 mb-4">
                <div class="card tour-card-list">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="${tour.image}" class="img-fluid h-100" alt="${tour.name}" style="object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body d-flex flex-column h-100">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="badge bg-${this.getCategoryColor(tour.category)} mb-2">${tour.category}</span>
                                        <h5 class="card-title">${tour.name}</h5>
                                    </div>
                                    <span class="text-warning">
                                        <i class="bi bi-star-fill"></i> ${tour.rating}
                                    </span>
                                </div>
                                
                                <p class="card-text text-muted flex-grow-1">${tour.description}</p>
                                
                                <div class="tour-features mb-3">
                                    <div class="row g-3">
                                        <div class="col-auto">
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i> ${tour.duration}
                                            </small>
                                        </div>
                                        <div class="col-auto">
                                            <small class="text-muted">
                                                <i class="bi bi-people me-1"></i> ${tour.maxPeople} personas
                                            </small>
                                        </div>
                                        <div class="col-auto">
                                            <small class="text-muted">
                                                <i class="bi bi-geo-alt me-1"></i> ${tour.difficulty}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="h4 text-primary mb-0">${Helpers.formatCurrency(tour.price)}</span>
                                        <small class="text-muted d-block">por persona</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-outline-primary" 
                                                onclick="ToursManager.showTourDetails(${tour.id})">
                                            <i class="bi bi-info-circle me-1"></i> Detalles
                                        </button>
                                        <button class="btn btn-primary" 
                                                onclick="TurismoCuscoApp.addToCart(${JSON.stringify(tour).replace(/"/g, '&quot;')})">
                                            <i class="bi bi-cart-plus me-1"></i> Reservar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    getCategoryColor(category) {
        const colors = {
            'Aventura': 'success',
            'Cultural': 'info',
            'Arqueológico': 'warning',
            'Naturaleza': 'success',
            'Gastronómico': 'danger'
        };
        return colors[category] || 'primary';
    }

    updateResultsCount(count) {
        const countElement = document.getElementById('results-count');
        if (countElement) {
            countElement.textContent = `${count} tours encontrados`;
        }
    }

    attachTourEventListeners() {
        // Los event listeners están en el HTML generado
    }

    switchView(viewType) {
        const container = document.getElementById('tours-container');
        const gridViewBtn = document.querySelector('#grid-view');
        const listViewBtn = document.querySelector('#list-view');

        if (!container) return;

        if (viewType === 'grid') {
            container.classList.remove('list-view');
            container.classList.add('grid-view');
            gridViewBtn?.classList.add('active');
            listViewBtn?.classList.remove('active');
        } else {
            container.classList.remove('grid-view');
            container.classList.add('list-view');
            listViewBtn?.classList.add('active');
            gridViewBtn?.classList.remove('active');
        }

        this.renderTours();
    }

    async showTourDetails(tourId) {
        const tour = this.tours.find(t => t.id === tourId);
        if (!tour) return;

        // Aquí puedes implementar un modal de detalles
        const modal = new bootstrap.Modal(document.getElementById('tourQuickView'));
        const modalTitle = document.getElementById('quickViewTitle');
        const modalContent = document.getElementById('quickViewContent');

        if (modalTitle && modalContent) {
            modalTitle.textContent = tour.name;
            modalContent.innerHTML = this.generateTourDetailsHTML(tour);
            modal.show();
        }
    }

    generateTourDetailsHTML(tour) {
        return `
            <div class="row">
                <div class="col-md-6">
                    <img src="${tour.image}" class="img-fluid rounded" alt="${tour.name}">
                </div>
                <div class="col-md-6">
                    <h4>${tour.name}</h4>
                    <p class="text-muted">${tour.description}</p>
                    
                    <div class="mb-3">
                        <h6>Incluye:</h6>
                        <ul>
                            ${tour.includes?.map(item => `<li>${item}</li>`).join('') || '<li>No se especifican inclusiones</li>'}
                        </ul>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="h3 text-primary">${Helpers.formatCurrency(tour.price)}</span>
                            <small class="text-muted d-block">por persona</small>
                        </div>
                        <button class="btn btn-primary btn-lg" 
                                onclick="TurismoCuscoApp.addToCart(${JSON.stringify(tour).replace(/"/g, '&quot;')}); bootstrap.Modal.getInstance(document.getElementById('tourQuickView')).hide();">
                            <i class="bi bi-cart-plus me-2"></i>Reservar
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    searchTours(query) {
        this.currentFilters.searchQuery = query;
        this.applyFilters();
    }

    filterByDestination(destination) {
        this.currentFilters.destination = destination;
        const element = document.querySelector('#filter-destination');
        if (element) element.value = destination;
        this.applyFilters();
    }

    filterByCategory(category) {
        this.currentFilters.category = category;
        const element = document.querySelector('#filter-category');
        if (element) element.value = category;
        this.applyFilters();
    }

    showError(message) {
        TurismoCuscoApp.showNotification(message, 'danger');
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.ToursManager = new ToursManager();
});