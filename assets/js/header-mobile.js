/**
 * Header Mobile Menu
 * Adaptado para nueva estructura CSS ITCSS
 */

(function() {
  'use strict';

  // Esperar a que el DOM esté listo
  document.addEventListener('DOMContentLoaded', function() {
    
    // Elementos con nueva estructura CSS
    const toggle = document.querySelector('.header__toggle');
    const nav = document.querySelector('.header__nav');
    const menu = document.querySelector('.nav__list');
    
    // Verificar que existen los elementos
    if (!toggle || !nav) {
      return;
    }

    /**
     * Toggle del menú móvil
     */
    toggle.addEventListener('click', function() {
      const isExpanded = this.getAttribute('aria-expanded') === 'true';
      
      // Cambiar estado con nuevas clases CSS
      this.setAttribute('aria-expanded', !isExpanded);
      nav.classList.toggle('nav--open');
      toggle.classList.toggle('hamburger--open');
      
      // Prevenir scroll cuando el menú está abierto
      document.body.classList.toggle('scroll-locked', !isExpanded);
    });

    /**
     * Cerrar menú al hacer click en un enlace
     */
    if (menu) {
      const menuLinks = menu.querySelectorAll('.nav__link');
      
      menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          const parentLi = this.closest('.nav__item');
          const hasSubmenu = parentLi.classList.contains('nav__item--has-children');
          
          // Si tiene sub-menú, solo expandir/contraer
          if (hasSubmenu && window.innerWidth <= 768) {
            e.preventDefault();
            parentLi.classList.toggle('nav__item--open');
            return;
          }
          
          // Si es un enlace normal, cerrar el menú
          if (window.innerWidth <= 768) {
            closeMenu();
          }
        });
      });
    }

    /**
     * Cerrar menú al presionar Escape
     */
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && nav.classList.contains('nav--open')) {
        closeMenu();
        toggle.focus(); // Devolver foco al botón
      }
    });

    /**
     * Cerrar menú al hacer click fuera
     */
    document.addEventListener('click', function(e) {
      const header = document.querySelector('.header');
      
      if (nav.classList.contains('nav--open') && 
          !header.contains(e.target)) {
        closeMenu();
      }
    });

    /**
     * Función para cerrar el menú
     */
    function closeMenu() {
      nav.classList.remove('nav--open');
      toggle.classList.remove('hamburger--open');
      toggle.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('scroll-locked');
    }

    /**
     * Resetear estado al cambiar tamaño de pantalla
     */
    let resizeTimer;
    window.addEventListener('resize', function() {
      clearTimeout(resizeTimer);
      
      resizeTimer = setTimeout(function() {
        if (window.innerWidth > 768) {
          // Resetear todo en desktop
          closeMenu();
          
          // Cerrar todos los sub-menús abiertos
          if (menu) {
            const openItems = menu.querySelectorAll('.nav__item--open');
            openItems.forEach(item => item.classList.remove('nav__item--open'));
          }
        }
      }, 250);
    });

    /**
     * Accesibilidad: Navegación con teclado en sub-menús
     */
    if (menu) {
      const menuItems = menu.querySelectorAll('.nav__item--has-children');
      
      menuItems.forEach(item => {
        const link = item.querySelector('.nav__link');
        
        link.addEventListener('keydown', function(e) {
          // Abrir sub-menú con flecha abajo
          if (e.key === 'ArrowDown' && window.innerWidth > 768) {
            e.preventDefault();
            const submenu = item.querySelector('.nav__submenu');
            if (submenu) {
              const firstLink = submenu.querySelector('.nav__link');
              if (firstLink) firstLink.focus();
            }
          }
          
          // En móvil, Enter abre/cierra
          if (e.key === 'Enter' && window.innerWidth <= 768) {
            e.preventDefault();
            item.classList.toggle('nav__item--open');
          }
        });
      });
    }

    /**
     * Trap focus dentro del menú móvil cuando está abierto
     */
    function trapFocus(element) {
      const focusableElements = element.querySelectorAll(
        'a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'
      );
      
      const firstFocusable = focusableElements[0];
      const lastFocusable = focusableElements[focusableElements.length - 1];
      
      element.addEventListener('keydown', function(e) {
        if (e.key !== 'Tab') return;
        
        if (e.shiftKey) {
          // Shift + Tab
          if (document.activeElement === firstFocusable) {
            e.preventDefault();
            lastFocusable.focus();
          }
        } else {
          // Tab
          if (document.activeElement === lastFocusable) {
            e.preventDefault();
            firstFocusable.focus();
          }
        }
      });
    }

    // Aplicar trapFocus al nav si es necesario
    if (nav) {
      trapFocus(nav);
    }

    /**
     * Mejorar experiencia táctil en dispositivos móviles
     */
    if ('ontouchstart' in window) {
      toggle.addEventListener('touchstart', function() {
        this.classList.add('is-touch-active');
      }, { passive: true });
      
      toggle.addEventListener('touchend', function() {
        setTimeout(() => {
          this.classList.remove('is-touch-active');
        }, 150);
      }, { passive: true });
    }

  }); // Fin DOMContentLoaded
})(); // Fin IIFE