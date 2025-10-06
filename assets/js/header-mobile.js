/**
 * Header Mobile Menu
 * Archivo: assets/js/header-mobile.js
 * 
 * Controla la apertura/cierre del menú en dispositivos móviles
 */

(function() {
  'use strict';

  // Esperar a que el DOM esté listo
  document.addEventListener('DOMContentLoaded', function() {
    
    // Elementos
    const toggle = document.querySelector('.mm-header__toggle');
    const nav = document.querySelector('.mm-header__nav');
    const menu = document.querySelector('.mm-menu');
    
    // Verificar que existen los elementos
    if (!toggle || !nav) {
      return;
    }

    /**
     * Toggle del menú móvil
     */
    toggle.addEventListener('click', function() {
      const isExpanded = this.getAttribute('aria-expanded') === 'true';
      
      // Cambiar estado
      this.setAttribute('aria-expanded', !isExpanded);
      nav.classList.toggle('is-open');
      
      // Prevenir scroll cuando el menú está abierto
      document.body.style.overflow = isExpanded ? '' : 'hidden';
    });

    /**
     * Cerrar menú al hacer click en un enlace (excepto si tiene sub-menú)
     */
    if (menu) {
      const menuLinks = menu.querySelectorAll('a');
      
      menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          const parentLi = this.parentElement;
          const hasSubmenu = parentLi.classList.contains('menu-item-has-children');
          
          // Si tiene sub-menú, solo expandir/contraer
          if (hasSubmenu && window.innerWidth <= 768) {
            e.preventDefault();
            parentLi.classList.toggle('is-open');
            return;
          }
          
          // Si es un enlace normal, cerrar el menú
          if (window.innerWidth <= 768) {
            nav.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
          }
        });
      });
    }

    /**
     * Cerrar menú al presionar Escape
     */
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && nav.classList.contains('is-open')) {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
        toggle.focus(); // Devolver foco al botón
      }
    });

    /**
     * Cerrar menú al hacer click fuera
     */
    document.addEventListener('click', function(e) {
      const header = document.querySelector('.mm-header');
      
      if (nav.classList.contains('is-open') && 
          !header.contains(e.target)) {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      }
    });

    /**
     * Resetear estado al cambiar tamaño de pantalla
     */
    let resizeTimer;
    window.addEventListener('resize', function() {
      clearTimeout(resizeTimer);
      
      resizeTimer = setTimeout(function() {
        if (window.innerWidth > 768) {
          // Resetear todo en desktop
          nav.classList.remove('is-open');
          toggle.setAttribute('aria-expanded', 'false');
          document.body.style.overflow = '';
          
          // Cerrar todos los sub-menús abiertos
          const openItems = menu.querySelectorAll('.is-open');
          openItems.forEach(item => item.classList.remove('is-open'));
        }
      }, 250);
    });

    /**
     * Accesibilidad: Navegación con teclado en sub-menús
     */
    if (menu) {
      const menuItems = menu.querySelectorAll('.menu-item-has-children');
      
      menuItems.forEach(item => {
        const link = item.querySelector('a');
        
        link.addEventListener('keydown', function(e) {
          // Abrir sub-menú con flecha abajo
          if (e.key === 'ArrowDown' && window.innerWidth > 768) {
            e.preventDefault();
            const submenu = item.querySelector('.sub-menu');
            if (submenu) {
              const firstLink = submenu.querySelector('a');
              if (firstLink) firstLink.focus();
            }
          }
          
          // En móvil, Enter abre/cierra
          if (e.key === 'Enter' && window.innerWidth <= 768) {
            e.preventDefault();
            item.classList.toggle('is-open');
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

  }); // Fin DOMContentLoaded
})(); // Fin IIFE
