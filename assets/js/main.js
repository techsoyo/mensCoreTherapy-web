/**
 * JavaScript principal del tema
 * Adaptado para nueva estructura CSS ITCSS
 */

(function () {
  'use strict';

  // Elementos con nueva estructura CSS
  const topEdge = document.getElementById('mm-top-edge');
  const bottomEdge = document.getElementById('mm-bottom-edge');
  const header = document.getElementById('site-header');
  const footer = document.getElementById('site-footer');

  /**
   * Manejo de visibilidad del header
   */
  if (topEdge && header) {
    topEdge.addEventListener('mouseenter', () => {
      header.classList.add('header--visible');
    });
    
    header.addEventListener('mouseleave', () => {
      header.classList.remove('header--visible');
    });
  }

  /**
   * Manejo de visibilidad del footer
   */
  if (bottomEdge && footer) {
    bottomEdge.addEventListener('mouseenter', () => {
      footer.classList.add('footer--visible');
    });
    
    footer.addEventListener('mouseleave', () => {
      footer.classList.remove('footer--visible');
    });
  }

  /**
   * Inicialización de componentes globales
   */
  document.addEventListener('DOMContentLoaded', function() {
    initGlobalComponents();
  });

  function initGlobalComponents() {
    // Inicializar flip cards globales
    initFlipCards();
    
    // Inicializar formularios
    initForms();
    
    // Inicializar botones neomórficos
    initNeoButtons();
    
    // Inicializar alertas
    initAlerts();
    
    // Inicializar smooth scroll
    initSmoothScroll();
  }

  /**
   * Inicializar flip cards con nueva estructura
   */
  function initFlipCards() {
    const flipCards = document.querySelectorAll('.card--flip');
    
    flipCards.forEach((card, index) => {
      // Hacer accesibles por teclado
      card.setAttribute('tabindex', '0');
      card.setAttribute('role', 'button');
      card.setAttribute('aria-label', `Tarjeta ${index + 1} - Clic para voltear`);
      
      // Evento de clic
      card.addEventListener('click', function(e) {
        // No hacer flip si se hace clic en botones o enlaces
        if (!e.target.closest('.btn') && !e.target.closest('a')) {
          this.classList.toggle('is-flipped');
          
          // Actualizar aria-label
          const isFlipped = this.classList.contains('is-flipped');
          const newLabel = isFlipped 
            ? `Tarjeta ${index + 1} - Clic para volver`
            : `Tarjeta ${index + 1} - Clic para voltear`;
          this.setAttribute('aria-label', newLabel);
        }
      });
      
      // Navegación por teclado
      card.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          this.click();
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
   * Inicializar formularios con nueva estructura
   */
  function initForms() {
    const forms = document.querySelectorAll('.form');
    
    forms.forEach(form => {
      // Validación en tiempo real
      const inputs = form.querySelectorAll('.form-input, .form-textarea, .form-select');
      
      inputs.forEach(input => {
        input.addEventListener('blur', function() {
          validateField(this);
        });
        
        input.addEventListener('input', function() {
          if (this.classList.contains('is-invalid')) {
            validateField(this);
          }
        });
      });
      
      // Envío del formulario
      form.addEventListener('submit', function(e) {
        let isValid = true;
        
        inputs.forEach(input => {
          if (!validateField(input)) {
            isValid = false;
          }
        });
        
        if (!isValid) {
          e.preventDefault();
          // Enfocar el primer campo inválido
          const firstInvalid = form.querySelector('.is-invalid');
          if (firstInvalid) {
            firstInvalid.focus();
          }
        }
      });
    });
  }

  /**
   * Validar campo individual
   */
  function validateField(field) {
    const value = field.value.trim();
    const isRequired = field.hasAttribute('required');
    let isValid = true;
    
    // Limpiar estados previos
    field.classList.remove('is-valid', 'is-invalid');
    
    // Validación requerido
    if (isRequired && value === '') {
      isValid = false;
    }
    
    // Validación email
    if (field.type === 'email' && value !== '') {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(value)) {
        isValid = false;
      }
    }
    
    // Aplicar clase de estado
    field.classList.add(isValid ? 'is-valid' : 'is-invalid');
    
    return isValid;
  }

  /**
   * Inicializar botones neomórficos
   */
  function initNeoButtons() {
    const neoButtons = document.querySelectorAll('.btn--neo');
    
    neoButtons.forEach(button => {
      button.addEventListener('mousedown', function() {
        this.classList.add('is-pressed');
      });
      
      button.addEventListener('mouseup', function() {
        this.classList.remove('is-pressed');
      });
      
      button.addEventListener('mouseleave', function() {
        this.classList.remove('is-pressed');
      });
    });
  }

  /**
   * Inicializar alertas
   */
  function initAlerts() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
      // Auto-hide para alertas de éxito
      if (alert.classList.contains('alert--success')) {
        setTimeout(() => {
          alert.classList.add('alert--fade-out');
          setTimeout(() => {
            alert.remove();
          }, 300);
        }, 5000);
      }
      
      // Botón de cerrar si existe
      const closeBtn = alert.querySelector('.alert__close');
      if (closeBtn) {
        closeBtn.addEventListener('click', function() {
          alert.classList.add('alert--fade-out');
          setTimeout(() => {
            alert.remove();
          }, 300);
        });
      }
    });
  }

  /**
   * Smooth scroll mejorado
   */
  function initSmoothScroll() {
    // Respetar preferencias de accesibilidad
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    
    if (!prefersReducedMotion) {
      // Enlaces internos
      const internalLinks = document.querySelectorAll('a[href^="#"]');
      
      internalLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          const targetId = this.getAttribute('href');
          const targetElement = document.querySelector(targetId);
          
          if (targetElement) {
            e.preventDefault();
            targetElement.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        });
      });
    }
  }

  /**
   * Utilidades globales
   */
  window.MasajistaTheme = {
    // Anunciar a lectores de pantalla
    announceToScreenReader: function(message) {
      const announcement = document.createElement('div');
      announcement.setAttribute('aria-live', 'polite');
      announcement.setAttribute('aria-atomic', 'true');
      announcement.className = 'visually-hidden';
      announcement.textContent = message;
      
      document.body.appendChild(announcement);
      
      setTimeout(() => {
        document.body.removeChild(announcement);
      }, 1000);
    },
    
    // Mostrar alerta
    showAlert: function(message, type = 'info') {
      const alert = document.createElement('div');
      alert.className = `alert alert--${type}`;
      alert.innerHTML = `
        <div class="alert__content">
          <i class="fa fa-${type === 'error' ? 'exclamation-triangle' : 'info-circle'} mr-sm"></i>
          ${message}
        </div>
        <button class="alert__close btn btn--ghost btn--sm">
          <i class="fa fa-times"></i>
        </button>
      `;
      
      document.body.appendChild(alert);
      
      // Inicializar el comportamiento de la alerta
      initAlerts();
    }
  };

})();