/**
 * MensCore Therapy Theme JavaScript
 */

(function($) {
    'use strict';

    /**
     * Initialize theme functionality
     */
    $(document).ready(function() {
        initParallax();
        initSmoothScrolling();
        initMobileMenu();
        initScrollEffects();
        initFormValidation();
    });

    /**
     * Parallax effect for hero background
     */
    function initParallax() {
        const parallaxBg = document.getElementById('parallax-bg');
        
        if (parallaxBg) {
            $(window).on('scroll', function() {
                const scrolled = $(window).scrollTop();
                const rate = scrolled * -0.5;
                
                // Apply parallax transform
                parallaxBg.style.transform = `translate3d(0, ${rate}px, 0)`;
            });
        }
    }

    /**
     * Smooth scrolling for anchor links
     */
    function initSmoothScrolling() {
        $('a[href*="#"]:not([href="#"])').click(function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && 
                location.hostname == this.hostname) {
                
                let target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80 // Account for fixed header
                    }, 1000);
                    return false;
                }
            }
        });
    }

    /**
     * Mobile menu functionality
     */
    function initMobileMenu() {
        const menuToggle = $('.menu-toggle');
        const navigation = $('.main-navigation');
        
        menuToggle.on('click', function() {
            const expanded = $(this).attr('aria-expanded') === 'true';
            
            $(this).attr('aria-expanded', !expanded);
            navigation.toggleClass('menu-open');
            
            // Animate menu toggle button
            $(this).toggleClass('menu-open');
        });

        // Close menu when clicking outside
        $(document).on('click', function(event) {
            if (!$(event.target).closest('.main-navigation, .menu-toggle').length) {
                if (navigation.hasClass('menu-open')) {
                    navigation.removeClass('menu-open');
                    menuToggle.removeClass('menu-open').attr('aria-expanded', 'false');
                }
            }
        });

        // Close menu when window is resized to desktop
        $(window).on('resize', function() {
            if ($(window).width() > 768) {
                navigation.removeClass('menu-open');
                menuToggle.removeClass('menu-open').attr('aria-expanded', 'false');
            }
        });
    }

    /**
     * Header scroll effects
     */
    function initScrollEffects() {
        const header = $('.site-header');
        let lastScrollTop = 0;

        $(window).on('scroll', function() {
            const scrollTop = $(this).scrollTop();
            
            // Add/remove scrolled class for styling
            if (scrollTop > 100) {
                header.addClass('scrolled');
            } else {
                header.removeClass('scrolled');
            }

            // Hide/show header on scroll (optional)
            if (scrollTop > lastScrollTop && scrollTop > 200) {
                // Scrolling down
                header.addClass('header-hidden');
            } else {
                // Scrolling up
                header.removeClass('header-hidden');
            }
            
            lastScrollTop = scrollTop;
        });
    }

    /**
     * Form validation and enhancement
     */
    function initFormValidation() {
        // Newsletter form
        $('.newsletter-form').on('submit', function(e) {
            e.preventDefault();
            
            const email = $(this).find('input[type="email"]').val();
            const submitBtn = $(this).find('button[type="submit"]');
            
            if (validateEmail(email)) {
                // Simulate form submission
                submitBtn.text('Subscribing...').prop('disabled', true);
                
                setTimeout(function() {
                    submitBtn.text('Subscribed!').addClass('success');
                    $(this).find('input[type="email"]').val('');
                    
                    setTimeout(function() {
                        submitBtn.text('Subscribe').removeClass('success').prop('disabled', false);
                    }, 3000);
                }.bind(this), 1000);
            } else {
                showFormError('Please enter a valid email address');
            }
        });

        // Contact forms
        $('.contact-form').on('submit', function(e) {
            const form = $(this);
            let isValid = true;

            // Validate required fields
            form.find('[required]').each(function() {
                const field = $(this);
                const value = field.val().trim();

                if (!value) {
                    showFieldError(field, 'This field is required');
                    isValid = false;
                } else if (field.attr('type') === 'email' && !validateEmail(value)) {
                    showFieldError(field, 'Please enter a valid email address');
                    isValid = false;
                } else {
                    clearFieldError(field);
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    /**
     * Utility functions
     */
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function showFormError(message) {
        // Create or update error message
        let errorDiv = $('.form-error');
        if (errorDiv.length === 0) {
            errorDiv = $('<div class="form-error neomorphic-inset" style="color: #e74c3c; padding: 10px; margin: 10px 0; border-radius: 10px;"></div>');
            $('.newsletter-form').append(errorDiv);
        }
        errorDiv.text(message).show();

        setTimeout(function() {
            errorDiv.fadeOut();
        }, 5000);
    }

    function showFieldError(field, message) {
        clearFieldError(field);
        
        const errorSpan = $('<span class="field-error" style="color: #e74c3c; font-size: 0.9em;">' + message + '</span>');
        field.after(errorSpan);
        field.addClass('error');
    }

    function clearFieldError(field) {
        field.removeClass('error').next('.field-error').remove();
    }

    /**
     * Intersection Observer for animations
     */
    if ('IntersectionObserver' in window) {
        const animateOnScroll = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, {
            threshold: 0.1
        });

        // Observe elements that should animate
        document.querySelectorAll('.service-card, .about-content, .footer-section').forEach(function(el) {
            animateOnScroll.observe(el);
        });
    }

    /**
     * Enhanced neomorphic button interactions
     */
    $('.neomorphic, .neomorphic-inset').on('mouseenter', function() {
        $(this).addClass('hover-active');
    }).on('mouseleave', function() {
        $(this).removeClass('hover-active');
    });

    /**
     * Lazy loading for images (if not supported natively)
     */
    if ('loading' in HTMLImageElement.prototype === false) {
        // Implement custom lazy loading
        const images = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(function(img) {
            imageObserver.observe(img);
        });
    }

})(jQuery);