/**
 * Efecto Parallax para la página de servicios
 */
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si estamos en la página de servicios
    const serviciosSection = document.querySelector('.mm-servicios');
    
    if (!serviciosSection) return;
    
    // Variables para el efecto parallax
    let ticking = false;
    
    function updateParallax() {
        const scrolled = window.pageYOffset;
        const parallaxElement = serviciosSection;
        
        if (parallaxElement) {
            // Calcular el desplazamiento parallax
            const speed = 0.5; // Velocidad del efecto (0.5 = mitad de velocidad del scroll)
            const yPos = -(scrolled * speed);
            
            // Aplicar la transformación
            parallaxElement.style.setProperty('--parallax-offset', `${yPos}px`);
        }
        
        ticking = false;
    }
    
    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(updateParallax);
            ticking = true;
        }
    }
    
    // Escuchar el evento de scroll
    window.addEventListener('scroll', requestTick, { passive: true });
    
    // Ejecutar una vez al cargar
    updateParallax();
    
    // Mejorar el rendimiento en dispositivos móviles
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            updateParallax();
        }, 250);
    });
});

/**
 * Efecto parallax alternativo usando CSS transform
 */
document.addEventListener('DOMContentLoaded', function() {
    const serviciosSection = document.querySelector('.mm-servicios');
    
    if (!serviciosSection) return;
    
    // Crear un observer para detectar cuando la sección está visible
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Activar el efecto parallax cuando la sección es visible
                window.addEventListener('scroll', handleParallaxScroll, { passive: true });
            } else {
                // Desactivar cuando no es visible para mejorar rendimiento
                window.removeEventListener('scroll', handleParallaxScroll);
            }
        });
    }, {
        threshold: 0.1
    });
    
    observer.observe(serviciosSection);
    
    function handleParallaxScroll() {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.3; // Velocidad del parallax
        
        // Aplicar el efecto usando CSS custom property
        document.documentElement.style.setProperty('--scroll-offset', `${rate}px`);
    }
});