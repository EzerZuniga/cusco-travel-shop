// Gestor del Carrito de Compras
class ShoppingCart {
    constructor() {
        this.cart = TurismoCuscoApp.getCartItems();
        this.services = [];
        this.coupon = null;
        this.init();
    }

    init() {
        this.loadAdditionalServices();
        this.setupEventListeners();
        this.refreshCart();
    }

    setupEventListeners() {
        // Checkout
        const checkoutBtn = document.getElementById('proceed-checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', () => this.startCheckout());
        }

        // Cupones
        const couponInput = document.getElementById('coupon-code');
        if (couponInput) {
            couponInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.applyCoupon();
                }
            });
        }
    }

    refreshCart() {
        this.cart = TurismoCuscoApp.getCartItems();
        this.renderCartItems();
        this.updateCartSummary();
        this.updateCheckoutButton();
    }

    renderCartItems() {
        const container = document.getElementById('cart-items');
        const emptyMessage = document.getElementById('empty-cart-message');
        const cartContent = document.getElementById('cart-content');

        if (!container) return;

        if (this.cart.length === 0) {
            if (emptyMessage) emptyMessage.classList.remove('d-none');
            if (cartContent) cartContent.classList.add('d-none');
            return;
        }

        if (emptyMessage) emptyMessage.classList.add('d-none');
        if (cartContent) cartContent.classList.remove('d-none');

        container.innerHTML = this.cart.map(item => `
            <div class="cart-item">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <img src="${item.image}" alt="${item.name}" class="img-fluid rounded">
                    </div>
                    <div class="col-md-4">
                        <h6 class="mb-1">${item.name}</h6>
                        <small class="text-muted">${item.category}</small>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                ${Helpers.formatDate(item.date)}
                            </small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <span class="h6 mb-0">${Helpers.formatCurrency(item.price)}</span>
                    </div>
                    <div class="col-md-2">
                        <div class="quantity-controls">
                            <button class="quantity-btn" 
                                    onclick="ShoppingCart.updateQuantity(${item.id}, '${item.date}', ${item.quantity - 1})">
                                <i class="bi bi-dash"></i>
                            </button>
                            <span class="mx-2">${item.quantity}</span>
                            <button class="quantity-btn" 
                                    onclick="ShoppingCart.updateQuantity(${item.id}, '${item.date}', ${item.quantity + 1})">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <span class="h6 mb-0">${Helpers.formatCurrency(item.price * item.quantity)}</span>
                            <button class="btn btn-outline-danger btn-sm" 
                                    onclick="ShoppingCart.removeItem(${item.id}, '${item.date}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    updateCartSummary() {
        const subtotal = this.getSubtotal();
        const servicesTotal = this.getServicesTotal();
        const discount = this.getDiscountAmount();
        const taxes = this.calculateTaxes(subtotal + servicesTotal - discount);
        const total = subtotal + servicesTotal - discount + taxes;

        // Actualizar UI
        this.updateElement('cart-subtotal', Helpers.formatCurrency(subtotal));
        this.updateElement('cart-services', Helpers.formatCurrency(servicesTotal));
        this.updateElement('cart-discount', `-${Helpers.formatCurrency(discount)}`);
        this.updateElement('cart-taxes', Helpers.formatCurrency(taxes));
        this.updateElement('cart-total', Helpers.formatCurrency(total));

        // Mostrar/ocultar filas
        this.toggleElement('services-row', servicesTotal > 0);
        this.toggleElement('discount-row', discount > 0);
    }

    getSubtotal() {
        return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    }

    getServicesTotal() {
        return this.services.reduce((total, service) => total + service.price, 0);
    }

    getDiscountAmount() {
        if (!this.coupon) return 0;

        const subtotal = this.getSubtotal() + this.getServicesTotal();

        switch (this.coupon.type) {
            case 'percentage':
                return subtotal * (this.coupon.value / 100);
            case 'fixed':
                return Math.min(this.coupon.value, subtotal);
            default:
                return 0;
        }
    }

    calculateTaxes(amount) {
        // IGV 18% en Perú
        return amount * 0.18;
    }

    updateElement(id, value) {
        const element = document.getElementById(id);
        if (element) element.textContent = value;
    }

    toggleElement(id, show) {
        const element = document.getElementById(id);
        if (element) {
            element.style.display = show ? 'flex' : 'none';
        }
    }

    updateCheckoutButton() {
        const button = document.getElementById('proceed-checkout-btn');
        if (button) {
            button.disabled = this.cart.length === 0;
        }
    }

    // Métodos estáticos para acceso desde HTML
    static updateQuantity(itemId, date, newQuantity) {
        TurismoCuscoApp.updateCartItemQuantity(itemId, date, newQuantity);
        window.ShoppingCart.refreshCart();
    }

    static removeItem(itemId, date) {
        TurismoCuscoApp.removeFromCart(itemId, date);
        window.ShoppingCart.refreshCart();
    }

    static clearCart() {
        TurismoCuscoApp.clearCart();
        window.ShoppingCart.refreshCart();
    }

    async applyCoupon() {
        const couponCode = document.getElementById('coupon-code')?.value.trim();
        const messageElement = document.getElementById('coupon-message');
        const appliedElement = document.getElementById('applied-coupon');

        if (!couponCode) {
            this.showCouponMessage('Ingresa un código de cupón', 'danger');
            return;
        }

        // Simular validación de cupón
        const validCoupons = {
            'BIENVENIDO10': { type: 'percentage', value: 10, description: '10% de descuento' },
            'VERANO25': { type: 'percentage', value: 25, description: '25% de descuento' },
            'SANTIAGO50': { type: 'fixed', value: 50, description: 'S/ 50 de descuento' }
        };

        const coupon = validCoupons[couponCode.toUpperCase()];

        if (coupon) {
            this.coupon = { code: couponCode, ...coupon };
            this.showCouponMessage('¡Cupón aplicado correctamente!', 'success');
            this.showAppliedCoupon(coupon.description);
        } else {
            this.showCouponMessage('Cupón inválido o expirado', 'danger');
            this.coupon = null;
            this.hideAppliedCoupon();
        }

        this.updateCartSummary();
    }

    showCouponMessage(message, type) {
        const element = document.getElementById('coupon-message');
        if (element) {
            element.innerHTML = `<div class="alert alert-${type} mb-0">${message}</div>`;
        }
    }

    showAppliedCoupon(description) {
        const element = document.getElementById('applied-coupon');
        const descriptionElement = document.getElementById('coupon-description');
        
        if (element && descriptionElement) {
            descriptionElement.textContent = description;
            element.classList.remove('d-none');
        }
    }

    hideAppliedCoupon() {
        const element = document.getElementById('applied-coupon');
        if (element) {
            element.classList.add('d-none');
        }
    }

    removeCoupon() {
        this.coupon = null;
        document.getElementById('coupon-code').value = '';
        this.hideAppliedCoupon();
        this.showCouponMessage('Cupón removido', 'info');
        this.updateCartSummary();
    }

    loadAdditionalServices() {
        // Servicios adicionales disponibles
        this.services = [
            {
                id: 1,
                name: 'Seguro de viaje',
                description: 'Cobertura médica y de cancelación',
                price: 25,
                selected: false
            },
            {
                id: 2,
                name: 'Fotos profesionales',
                description: 'Sesión fotográfica durante el tour',
                price: 50,
                selected: false
            },
            {
                id: 3,
                name: 'Almuerzo gourmet',
                description: 'Almuerzo en restaurante premium',
                price: 35,
                selected: false
            }
        ];

        this.renderAdditionalServices();
    }

    renderAdditionalServices() {
        const container = document.getElementById('additional-services');
        if (!container) return;

        container.innerHTML = this.services.map(service => `
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" 
                       id="service-${service.id}" 
                       ${service.selected ? 'checked' : ''}
                       onchange="ShoppingCart.toggleService(${service.id}, this.checked)">
                <label class="form-check-label w-100" for="service-${service.id}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${service.name}</strong>
                            <small class="d-block text-muted">${service.description}</small>
                        </div>
                        <span class="text-primary">+${Helpers.formatCurrency(service.price)}</span>
                    </div>
                </label>
            </div>
        `).join('');
    }

    static toggleService(serviceId, selected) {
        const cart = window.ShoppingCart;
        const service = cart.services.find(s => s.id === serviceId);
        
        if (service) {
            service.selected = selected;
            cart.updateCartSummary();
        }
    }

    startCheckout() {
        if (this.cart.length === 0) {
            TurismoCuscoApp.showNotification('Agrega tours al carrito primero', 'warning');
            return;
        }

        const checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
        this.updateCheckoutModal();
        checkoutModal.show();
    }

    updateCheckoutModal() {
        const orderSummary = document.getElementById('modal-order-summary');
        if (orderSummary) {
            orderSummary.innerHTML = this.generateOrderSummary();
        }
    }

    generateOrderSummary() {
        const subtotal = this.getSubtotal();
        const servicesTotal = this.getServicesTotal();
        const discount = this.getDiscountAmount();
        const taxes = this.calculateTaxes(subtotal + servicesTotal - discount);
        const total = subtotal + servicesTotal - discount + taxes;

        return `
            <div class="small">
                <div class="d-flex justify-content-between mb-1">
                    <span>Tours:</span>
                    <span>${Helpers.formatCurrency(subtotal)}</span>
                </div>
                ${servicesTotal > 0 ? `
                <div class="d-flex justify-content-between mb-1">
                    <span>Servicios:</span>
                    <span>${Helpers.formatCurrency(servicesTotal)}</span>
                </div>
                ` : ''}
                ${discount > 0 ? `
                <div class="d-flex justify-content-between mb-1 text-success">
                    <span>Descuento:</span>
                    <span>-${Helpers.formatCurrency(discount)}</span>
                </div>
                ` : ''}
                <div class="d-flex justify-content-between mb-1">
                    <span>IGV (18%):</span>
                    <span>${Helpers.formatCurrency(taxes)}</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total:</span>
                    <span>${Helpers.formatCurrency(total)}</span>
                </div>
            </div>
        `;
    }
}

// Inicializar carrito
document.addEventListener('DOMContentLoaded', () => {
    window.ShoppingCart = new ShoppingCart();
});