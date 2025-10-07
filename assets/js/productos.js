/**
 * JavaScript para la página de productos
 * Adaptado para nueva estructura CSS ITCSS
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Inicializar efectos de la página de productos
    initProductosEffects();
    
    function initProductosEffects() {
        // Efecto parallax suave
        initParallaxEffect();
        
        // Mejorar accesibilidad de flip cards
        initFlipCardAccessibility();
        
        // Lazy loading de animaciones
        initLazyAnimations();
        
        // Smooth scroll para mejor UX
        initSmoothScroll();
        
        // Preloader para imágenes de fondo
        initBackgroundPreloader();
    }
    
    /**
     * Efecto parallax suave en scroll - adaptado para nueva estructura
     */
    function initParallaxEffect() {
        let ticking = false;
        
        function updateParallax() {
            const scrolled = window.pageYOffset;
            const parallaxElement = document.querySelector('.hero--productos');
            
            if (parallaxElement) {
                // Efecto parallax más sutil para mejor rendimiento
                const speed = scrolled * 0.3;
                parallaxElement.style.transform = `translateY(${speed}px)`;
            }
            
            ticking = false;
        }
        
        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateParallax);
                ticking = true;
            }
        }
        
        // Solo aplicar parallax en dispositivos que lo soporten bien
        if (!window.matchMedia('(max-width: 768px)').matches) {
            window.addEventListener('scroll', requestTick, { passive: true });
        }
    }
    
    /**
     * Mejorar accesibilidad de las flip cards - nueva estructura CSS
     */
    function initFlipCardAccessibility() {
        const flipCards = document.querySelectorAll('.card--flip');
        
        flipCards.forEach((card, index) => {
            // Hacer las cards accesibles por teclado
            card.setAttribute('tabindex', '0');
            card.setAttribute('role', 'button');
            card.setAttribute('aria-label', `Producto ${index + 1} - Click para ver detalles`);
            
            // Manejar eventos de teclado
            card.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleFlipCard(this);
                }
                
                // Navegación con flechas
                if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                    e.preventDefault();
                    focusNextCard(index, flipCards);
                }
                
                if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    focusPrevCard(index, flipCards);
                }
            });
            
            // Manejar click - evitar conflicto con botones
            card.addEventListener('click', function(e) {
                if (!e.target.closest('.btn')) {
                    toggleFlipCard(this);
                }
            });
            
            // Feedback visual en focus
            card.addEventListener('focus', function() {
                this.classList.add('is-focused');
            });
            
            card.addEventListener('blur', function() {
                this.classList.remove('is-focused');
            });
        });
    }
    
    /**
     * Toggle del estado flip de una card - nueva estructura CSS
     */
    function toggleFlipCard(card) {
        card.classList.toggle('is-flipped');
        
        // Actualizar aria-label
        const isFlipped = card.classList.contains('is-flipped');
        const currentLabel = card.getAttribute('aria-label');
        const newLabel = isFlipped 
            ? currentLabel.replace('Click para ver detalles', 'Click para volver')
            : currentLabel.replace('Click para volver', 'Click para ver detalles');
        
        card.setAttribute('aria-label', newLabel);
        
        // Anunciar cambio a lectores de pantalla
        if (window.MasajistaTheme && window.MasajistaTheme.announceToScreenReader) {
            window.MasajistaTheme.announceToScreenReader(
                isFlipped ? 'Detalles mostrados' : 'Vista principal'
            );
        }
    }
    
    /**
     * Navegar a la siguiente card
     */
    function focusNextCard(currentIndex, cards) {
        const nextIndex = (currentIndex + 1) % cards.length;
        cards[nextIndex].focus();
    }
    
    /**
     * Navegar a la card anterior
     */
    function focusPrevCard(currentIndex, cards) {
        const prevIndex = currentIndex === 0 ? cards.length - 1 : currentIndex - 1;
        cards[prevIndex].focus();
    }
    
    /**
     * Lazy loading de animaciones para mejor rendimiento
     */
    function initLazyAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        // Observar todas las cards para animación lazy
        const cards = document.querySelectorAll('.card--flip');
        cards.forEach(card => {
            observer.observe(card);
        });
    }
    
    /**
     * Smooth scroll mejorado
     */
    function initSmoothScroll() {
        // Detectar si el usuario prefiere movimiento reducido
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        if (!prefersReducedMotion) {
            // Aplicar smooth scroll solo si no hay preferencia por movimiento reducido
            document.documentElement.style.scrollBehavior = 'smooth';
        }
    }
    
    /**
     * Preloader para imagen de fondo - nueva estructura CSS
     */
    function initBackgroundPreloader() {
        const heroSection = document.querySelector('.hero--productos');
        
        if (heroSection) {
            const bgElement = heroSection.querySelector('.hero__bg');
            
            if (bgElement) {
                const bgImage = new Image();
                const bgUrl = window.getComputedStyle(bgElement).backgroundImage;
                
                if (bgUrl && bgUrl !== 'none') {
                    // Extraer URL de la imagen
                    const imageUrl = bgUrl.slice(4, -1).replace(/["']/g, "");
                    
                    bgImage.onload = function() {
                        heroSection.classList.add('hero--bg-loaded');
                    };
                    
                    bgImage.onerror = function() {
                        console.warn('Error cargando imagen de fondo de productos');
                        // Fallback: aplicar color de fondo
                        bgElement.style.backgroundColor = 'var(--color-primary)';
                    };
                    
                    bgImage.src = imageUrl;
                }
            }
        }
    }
    
    /**
     * Optimizaciones de rendimiento
     */
    function initPerformanceOptimizations() {
        // Throttle para eventos de scroll
        let scrollTimeout;
        const originalScrollHandler = window.onscroll;
        
        window.onscroll = function() {
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }
            
            scrollTimeout = setTimeout(() => {
                if (originalScrollHandler) {
                    originalScrollHandler();
                }
            }, 16); // ~60fps
        };
        
        // Preconnect a dominios de fuentes si es necesario
        const preconnectLink = document.createElement('link');
        preconnectLink.rel = 'preconnect';
        preconnectLink.href = 'https://fonts.googleapis.com';
        document.head.appendChild(preconnectLink);
    }
    
    // Inicializar optimizaciones
    initPerformanceOptimizations();
    
    /**
     * Manejo de errores global para la página
     */
    window.addEventListener('error', function(e) {
        console.error('Error en página de productos:', e.error);
        // Aquí podrías enviar el error a un servicio de logging
    });
    
    /**
     * Cleanup al salir de la página
     */
    window.addEventListener('beforeunload', function() {
        // Limpiar event listeners si es necesario
        const cards = document.querySelectorAll('.card--flip');
        cards.forEach(card => {
            card.removeEventListener('click', toggleFlipCard);
        });
    });
    
    // Exponer funciones útiles globalmente
    window.ProductosPage = {
        toggleFlipCard: toggleFlipCard
    };
});

/**
 * Utilidades adicionales - adaptadas para nueva estructura
 */

// Detectar soporte para efectos avanzados
function supportsAdvancedEffects() {
    return CSS.supports('backdrop-filter', 'blur(10px)') && 
           CSS.supports('transform-style', 'preserve-3d');
}

// Aplicar fallbacks si no hay soporte
if (!supportsAdvancedEffects()) {
    document.documentElement.classList.add('no-advanced-effects');
}

// Media query listener para cambios de orientación
window.matchMedia('(orientation: portrait)').addEventListener('change', function(e) {
    // Reajustar grid si es necesario
    const grid = document.querySelector('.grid--auto-fit');
    if (grid && e.matches) {
        // Modo portrait - ajustar grid
        grid.style.gridTemplateColumns = 'repeat(auto-fit, minmax(250px, 1fr))';
    }
});

// Detectar dispositivos táctiles
function isTouchDevice() {
    return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
}

// Ajustar comportamiento para dispositivos táctiles
if (isTouchDevice()) {
    document.documentElement.classList.add('is-touch-device');
    
    // En dispositivos táctiles, mejorar la experiencia táctil
    const cards = document.querySelectorAll('.card--flip');
    cards.forEach(card => {
        card.addEventListener('touchstart', function() {
            this.classList.add('is-touch-active');
        }, { passive: true });
        
        card.addEventListener('touchend', function() {
            setTimeout(() => {
                this.classList.remove('is-touch-active');
            }, 300);
        }, { passive: true });
    });
}