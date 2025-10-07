/**
 * Script para asegurar la visibilidad del header en todas las páginas
 * Adaptado para nueva estructura CSS ITCSS
 */

document.addEventListener('DOMContentLoaded', function() {
    // Intentamos localizar el header por varios IDs y clases conocidos
    let header = document.getElementById('auto-header');
    if (!header) header = document.getElementById('site-header');
    if (!header) header = document.querySelector('.header');
    if (!header) header = document.querySelector('header');
    
    if (header) {
        // Asegurar que el header tenga las clases correctas de la nueva estructura
        header.classList.add('header', 'header--fixed');
        
        // Estilos de respaldo por si las clases CSS no están cargadas
        header.style.position = 'fixed';
        header.style.top = '0';
        header.style.left = '0';
        header.style.width = '100%';
        header.style.zIndex = '9999';
        
        console.log('Header fijo aplicado correctamente con nueva estructura CSS');
    } else {
        // Log informativo para debugging
        console.info('header-visibility: no se encontró un header conocido.');
    }
    
    // Remover cualquier espaciador existente que pueda interferir
    const existingSpacer = document.getElementById('header-spacer');
    if (existingSpacer) {
        existingSpacer.remove();
        console.log('Espaciador de header eliminado');
    }
    
    /**
     * Crear espaciador dinámico si es necesario
     */
    function createHeaderSpacer() {
        if (header && header.classList.contains('header--fixed')) {
            const headerHeight = header.offsetHeight;
            
            // Solo crear espaciador si no existe
            if (!document.getElementById('header-spacer')) {
                const spacer = document.createElement('div');
                spacer.id = 'header-spacer';
                spacer.className = 'header-spacer';
                spacer.style.height = `${headerHeight}px`;
                
                // Insertar después del header
                header.parentNode.insertBefore(spacer, header.nextSibling);
            }
        }
    }
    
    /**
     * Actualizar espaciador en resize
     */
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            const spacer = document.getElementById('header-spacer');
            if (header && spacer) {
                spacer.style.height = `${header.offsetHeight}px`;
            }
        }, 250);
    });
    
    /**
     * Scroll behavior mejorado
     */
    let lastScrollTop = 0;
    let scrollTimer;
    
    window.addEventListener('scroll', function() {
        if (scrollTimer) {
            clearTimeout(scrollTimer);
        }
        
        scrollTimer = setTimeout(function() {
            if (!header) return;
            
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Header que se oculta al hacer scroll hacia abajo
            if (header.classList.contains('header--autohide')) {
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scrolling hacia abajo
                    header.classList.add('header--hidden');
                } else {
                    // Scrolling hacia arriba
                    header.classList.remove('header--hidden');
                }
            }
            
            // Cambiar apariencia en scroll
            if (scrollTop > 50) {
                header.classList.add('header--scrolled');
            } else {
                header.classList.remove('header--scrolled');
            }
            
            lastScrollTop = scrollTop;
        }, 10);
    }, { passive: true });
    
    // Crear espaciador inicial si es necesario
    createHeaderSpacer();
});

/**
 * Utilidad para forzar visibilidad del header
 */
window.forceHeaderVisibility = function() {
    const header = document.querySelector('.header');
    if (header) {
        header.classList.remove('header--hidden');
        header.classList.add('header--visible');
    }
};

/**
 * Utilidad para toggle del header
 */
window.toggleHeaderVisibility = function() {
    const header = document.querySelector('.header');
    if (header) {
        header.classList.toggle('header--hidden');
    }
};