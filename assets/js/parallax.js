/**
 * Efecto Parallax para la página de servicios
 */
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si estamos en la página de servicios
    const serviciosSection = document.querySelector('.mm-servicios');
    
    if (!serviciosSection) return;
    
    // Variables para el efecto parallax
    let ticking = false;
    let isVisible = false;
    
    // Crear un observer para detectar cuando la sección está visible
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            isVisible = entry.isIntersecting;
            if (isVisible) {
                // Activar el efecto parallax cuando la sección es visible
                serviciosSection.classList.add('parallax-active');
                updateParallax();
            } else {
                // Desactivar cuando no es visible para mejorar rendimiento
                serviciosSection.classList.remove('parallax-active');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '50px'
    });
    
    observer.observe(serviciosSection);
    
    function updateParallax() {
        if (!isVisible) return;
        
        const scrolled = window.pageYOffset;
        const sectionTop = serviciosSection.offsetTop;
        const sectionHeight = serviciosSection.offsetHeight;
        const windowHeight = window.innerHeight;
        
        // Calcular si la sección está en el viewport
        if (scrolled + windowHeight > sectionTop && scrolled < sectionTop + sectionHeight) {
            // Calcular el desplazamiento parallax
            const speed = 0.3; // Velocidad del efecto (0.3 = 30% de velocidad del scroll)
            const yPos = -(scrolled - sectionTop) * speed;
            
            // Aplicar la transformación usando CSS custom properties
            document.documentElement.style.setProperty('--scroll-offset', `${yPos}px`);
            document.documentElement.style.setProperty('--parallax-offset', `${yPos}px`);
        }
        
        ticking = false;
    }
    
    function requestTick() {
        if (!ticking && isVisible) {
            requestAnimationFrame(updateParallax);
            ticking = true;
        }
    }
    
    // Escuchar el evento de scroll con throttling
    window.addEventListener('scroll', requestTick, { passive: true });
    
    // Ejecutar una vez al cargar
    updateParallax();
    
    // Mejorar el rendimiento en dispositivos móviles
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (isVisible) {
                updateParallax();
            }
        }, 250);
    });
    
    // Detectar dispositivos móviles y ajustar el comportamiento
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    if (isMobile) {
        // En móviles, usar un efecto más sutil
        serviciosSection.style.setProperty('--parallax-speed', '0.1');
    }
});