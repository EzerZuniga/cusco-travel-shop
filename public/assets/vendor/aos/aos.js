class AOS {
  constructor() {
    this.elements = [];
    this.observer = null;
    this.init();
  }

  init() {
    this.elements = Array.from(document.querySelectorAll('[data-aos]'));
    this.setupIntersectionObserver();
    this.checkElements();
  }

  setupIntersectionObserver() {
    this.observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          this.animateElement(entry.target);
          this.observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });

    this.elements.forEach(element => {
      this.observer.observe(element);
    });
  }

  animateElement(element) {
    const duration = element.getAttribute('data-aos-duration') || '400';
    const delay = element.getAttribute('data-aos-delay') || '0';
    const easing = element.getAttribute('data-aos-easing') || 'ease';

    element.style.transition = `all ${duration}ms ${easing} ${delay}ms`;
    element.classList.add('aos-animate');
  }

  checkElements() {
    // Check initial elements in viewport
    this.elements.forEach(element => {
      const rect = element.getBoundingClientRect();
      const isInViewport = (
        rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.bottom >= 0 &&
        rect.left <= (window.innerWidth || document.documentElement.clientWidth) &&
        rect.right >= 0
      );

      if (isInViewport) {
        this.animateElement(element);
        this.observer.unobserve(element);
      }
    });
  }

  refresh() {
    this.elements.forEach(element => {
      this.observer.unobserve(element);
    });
    
    this.elements = Array.from(document.querySelectorAll('[data-aos]'));
    this.elements.forEach(element => {
      this.observer.observe(element);
    });
    
    this.checkElements();
  }

  // Public API
  static init() {
    window.AOS = new AOS();
  }
}

// Auto-initialize
document.addEventListener('DOMContentLoaded', () => {
  AOS.init();
});