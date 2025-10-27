// Servicio para comunicación con la API
class ApiService {
    constructor() {
        this.baseURL = 'http://localhost:8000/api'; // Cambiar en producción
        this.defaultHeaders = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
    }

    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        const config = {
            headers: { ...this.defaultHeaders, ...options.headers },
            ...options
        };

        try {
            const response = await fetch(url, config);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return { success: true, data };
        } catch (error) {
            console.error('API Request failed:', error);
            return { 
                success: false, 
                error: error.message,
                data: null 
            };
        }
    }

    // Tours
    async getTours(filters = {}) {
        const queryString = new URLSearchParams(filters).toString();
        return await this.request(`/tours?${queryString}`);
    }

    async getTour(id) {
        return await this.request(`/tours/${id}`);
    }

    async createTour(tourData) {
        return await this.request('/tours', {
            method: 'POST',
            body: JSON.stringify(tourData)
        });
    }

    // Reservas
    async createReservation(reservationData) {
        return await this.request('/reservations', {
            method: 'POST',
            body: JSON.stringify(reservationData)
        });
    }

    async getReservations(userId) {
        return await this.request(`/reservations?user_id=${userId}`);
    }

    // Contacto
    async sendContact(formData) {
        return await this.request('/contact', {
            method: 'POST',
            body: JSON.stringify(formData)
        });
    }

    // Autenticación
    async login(credentials) {
        return await this.request('/auth/login', {
            method: 'POST',
            body: JSON.stringify(credentials)
        });
    }

    async register(userData) {
        return await this.request('/auth/register', {
            method: 'POST',
            body: JSON.stringify(userData)
        });
    }

    // Archivos (para subir imágenes)
    async uploadFile(file, onProgress = null) {
        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await fetch(`${this.baseURL}/upload`, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`Upload failed: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('File upload failed:', error);
            return { success: false, error: error.message };
        }
    }
}

// Instancia global
const apiService = new ApiService();