/**
 * Main JavaScript for MensCore Therapy Theme
 * 
 * @package MensCoreTherapy
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // DOM Ready
    $(document).ready(function() {
        initMobileMenu();
        initSmoothScrolling();
        initFAQAccordion();
        initContactForm();
        initBookingForm();
        initTestimonialsSlider();
        initServiceFilters();
        initAccessibility();
    });

    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        $('.menu-toggle').on('click', function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var $menu = $('#primary-menu');
            
            $this.toggleClass('active');
            $menu.slideToggle(300);
            
            // Update aria-expanded
            var expanded = $this.attr('aria-expanded') === 'true' ? 'false' : 'true';
            $this.attr('aria-expanded', expanded);
        });
        
        // Close menu on window resize
        $(window).on('resize', function() {
            if ($(window).width() > 768) {
                $('#primary-menu').removeAttr('style');
                $('.menu-toggle').removeClass('active').attr('aria-expanded', 'false');
            }
        });
    }

    /**
     * Smooth Scrolling for Anchor Links
     */
    function initSmoothScrolling() {
        $('a[href^="#"]').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            
            if (target.length) {
                e.preventDefault();
                
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });
    }

    /**
     * FAQ Accordion
     */
    function initFAQAccordion() {
        $('.faq-question').on('click', function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var $answer = $this.next('.faq-answer');
            var $toggle = $this.find('.faq-toggle');
            
            // Close other FAQs
            $('.faq-answer').not($answer).slideUp(300);
            $('.faq-toggle').not($toggle).text('+');
            
            // Toggle current FAQ
            $answer.slideToggle(300);
            $toggle.text($answer.is(':visible') ? '-' : '+');
        });
    }

    /**
     * Contact Form Handler
     */
    function initContactForm() {
        $('#therapy-contact-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $response = $('#contact-form-response');
            var $submit = $form.find('.contact-submit');
            
            // Validation
            if (!validateForm($form)) {
                return false;
            }
            
            // Show loading state
            $submit.prop('disabled', true).text('Sending...');
            
            // Simulate AJAX submission (replace with actual AJAX call)
            setTimeout(function() {
                $response.html('<div class="success-message">Thank you for your message! We\'ll get back to you soon.</div>').fadeIn();
                $form[0].reset();
                $submit.prop('disabled', false).text('Send Message');
            }, 2000);
        });
    }

    /**
     * Booking Form Handler
     */
    function initBookingForm() {
        $('#therapy-booking-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $response = $('#booking-form-response');
            var $submit = $form.find('.booking-submit');
            
            // Validation
            if (!validateForm($form)) {
                return false;
            }
            
            // Show loading state
            $submit.prop('disabled', true).text('Processing...');
            
            // Simulate AJAX submission
            setTimeout(function() {
                $response.html('<div class="success-message">Appointment request submitted! We\'ll contact you to confirm.</div>').fadeIn();
                $form[0].reset();
                $submit.prop('disabled', false).text('Request Appointment');
            }, 2000);
        });
        
        // Set minimum date to today
        var today = new Date().toISOString().split('T')[0];
        $('#booking-date').attr('min', today);
    }

    /**
     * Form Validation
     */
    function validateForm($form) {
        var isValid = true;
        
        // Remove previous error messages
        $form.find('.field-error').remove();
        
        $form.find('input[required], textarea[required], select[required]').each(function() {
            var $field = $(this);
            var value = $field.val().trim();
            
            if (!value) {
                showFieldError($field, 'This field is required.');
                isValid = false;
            } else if ($field.attr('type') === 'email' && !isValidEmail(value)) {
                showFieldError($field, 'Please enter a valid email address.');
                isValid = false;
            }
        });
        
        return isValid;
    }

    /**
     * Show Field Error
     */
    function showFieldError($field, message) {
        $field.addClass('error');
        $field.after('<span class="field-error">' + message + '</span>');
    }

    /**
     * Email Validation
     */
    function isValidEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    /**
     * Testimonials Slider
     */
    function initTestimonialsSlider() {
        var $slider = $('.therapy-testimonials-slider');
        
        if ($slider.length && $slider.children().length > 1) {
            var autoplay = $slider.data('autoplay') === 'true';
            var dots = $slider.data('dots') === 'true';
            
            $slider.slick({
                dots: dots,
                infinite: true,
                speed: 500,
                fade: true,
                autoplay: autoplay,
                autoplaySpeed: 5000,
                pauseOnHover: true,
                arrows: true,
                prevArrow: '<button type="button" class="slick-prev" aria-label="Previous testimonial">‹</button>',
                nextArrow: '<button type="button" class="slick-next" aria-label="Next testimonial">›</button>',
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            arrows: false
                        }
                    }
                ]
            });
        }
    }

    /**
     * Service Filters
     */
    function initServiceFilters() {
        $('.service-filter').on('click', function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var filter = $this.data('filter');
            
            // Update active filter
            $('.service-filter').removeClass('active');
            $this.addClass('active');
            
            // Filter services
            if (filter === 'all') {
                $('.service-item').fadeIn(300);
            } else {
                $('.service-item').hide();
                $('.service-item[data-category="' + filter + '"]').fadeIn(300);
            }
        });
    }

    /**
     * Accessibility Enhancements
     */
    function initAccessibility() {
        // Skip link functionality
        $('.skip-link').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                target.focus();
            }
        });
        
        // Keyboard navigation for custom elements
        $('.btn, .service-item, .testimonial-item').attr('tabindex', '0');
        
        // ARIA labels for interactive elements
        $('.menu-toggle').attr('aria-label', 'Toggle navigation menu');
        $('.search-toggle').attr('aria-label', 'Toggle search form');
        
        // Focus management for modal-like elements
        $(document).on('keydown', function(e) {
            if (e.keyCode === 27) { // ESC key
                // Close any open modals or dropdowns
                $('.mobile-menu-open').removeClass('mobile-menu-open');
                $('#primary-menu').slideUp(300);
                $('.menu-toggle').removeClass('active').attr('aria-expanded', 'false');
            }
        });
    }

    /**
     * Lazy Loading for Images
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            var imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Performance Optimization - Debounce Function
     */
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    /**
     * Scroll-based Animations
     */
    function initScrollAnimations() {
        var $animElements = $('.animate-on-scroll');
        
        if ($animElements.length) {
            var animateElement = debounce(function() {
                var windowTop = $(window).scrollTop();
                var windowBottom = windowTop + $(window).height();
                
                $animElements.each(function() {
                    var $element = $(this);
                    var elementTop = $element.offset().top;
                    
                    if (elementTop < windowBottom - 100 && !$element.hasClass('animated')) {
                        $element.addClass('animated fadeInUp');
                    }
                });
            }, 100);
            
            $(window).on('scroll', animateElement);
            animateElement(); // Initial check
        }
    }

    // Initialize scroll animations
    initScrollAnimations();
    
    // Initialize lazy loading
    initLazyLoading();

})(jQuery);