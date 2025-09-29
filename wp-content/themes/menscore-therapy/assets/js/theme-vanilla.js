/**
 * MensCore Therapy Theme JavaScript (Vanilla JS)
 */

document.addEventListener('DOMContentLoaded', function() {
    initParallax();
    initSmoothScrolling();
    initMobileMenu();
    initScrollEffects();
    initFormValidation();
    initNeomorphicEffects();
});

/**
 * Parallax effect for hero background
 */
function initParallax() {
    const parallaxBg = document.getElementById('parallax-bg');
    
    if (parallaxBg) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
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
    const anchorLinks = document.querySelectorAll('a[href*="#"]:not([href="#"])');
    
    anchorLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href').split('#')[1];
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                e.preventDefault();
                
                const headerHeight = document.querySelector('.site-header').offsetHeight;
                const targetPosition = targetElement.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Mobile menu functionality
 */
function initMobileMenu() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navigation = document.querySelector('.main-navigation');
    
    if (menuToggle && navigation) {
        menuToggle.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true';
            
            this.setAttribute('aria-expanded', !expanded);
            navigation.classList.toggle('menu-open');
            this.classList.toggle('menu-open');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideMenu = navigation.contains(event.target) || menuToggle.contains(event.target);
            
            if (!isClickInsideMenu && navigation.classList.contains('menu-open')) {
                navigation.classList.remove('menu-open');
                menuToggle.classList.remove('menu-open');
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        });

        // Close menu when window is resized to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                navigation.classList.remove('menu-open');
                menuToggle.classList.remove('menu-open');
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
}

/**
 * Header scroll effects
 */
function initScrollEffects() {
    const header = document.querySelector('.site-header');
    let lastScrollTop = 0;

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset;
        
        // Add/remove scrolled class for styling
        if (scrollTop > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        // Hide/show header on scroll (optional)
        if (scrollTop > lastScrollTop && scrollTop > 200) {
            // Scrolling down
            header.classList.add('header-hidden');
        } else {
            // Scrolling up
            header.classList.remove('header-hidden');
        }
        
        lastScrollTop = scrollTop;
    });
}

/**
 * Form validation and enhancement
 */
function initFormValidation() {
    // Newsletter form
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('input[type="email"]');
            const submitBtn = this.querySelector('button[type="submit"]');
            const email = emailInput.value;
            
            if (validateEmail(email)) {
                // Simulate form submission
                submitBtn.textContent = 'Subscribing...';
                submitBtn.disabled = true;
                
                setTimeout(function() {
                    submitBtn.textContent = 'Subscribed!';
                    submitBtn.classList.add('success');
                    emailInput.value = '';
                    
                    setTimeout(function() {
                        submitBtn.textContent = 'Subscribe';
                        submitBtn.classList.remove('success');
                        submitBtn.disabled = false;
                    }, 3000);
                }, 1000);
            } else {
                showFormError(newsletterForm, 'Please enter a valid email address');
            }
        });
    }

    // Contact forms
    const contactForms = document.querySelectorAll('.contact-form');
    contactForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');

            // Validate required fields
            requiredFields.forEach(function(field) {
                const value = field.value.trim();

                if (!value) {
                    showFieldError(field, 'This field is required');
                    isValid = false;
                } else if (field.type === 'email' && !validateEmail(value)) {
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
    });
}

/**
 * Enhanced neomorphic button interactions
 */
function initNeomorphicEffects() {
    const neomorphicElements = document.querySelectorAll('.neomorphic, .neomorphic-inset');
    
    neomorphicElements.forEach(function(element) {
        element.addEventListener('mouseenter', function() {
            this.classList.add('hover-active');
        });
        
        element.addEventListener('mouseleave', function() {
            this.classList.remove('hover-active');
        });
    });
}

/**
 * Utility functions
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function showFormError(form, message) {
    // Remove existing error
    const existingError = form.querySelector('.form-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Create new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'form-error neomorphic-inset';
    errorDiv.style.cssText = 'color: #e74c3c; padding: 10px; margin: 10px 0; border-radius: 10px;';
    errorDiv.textContent = message;
    
    form.appendChild(errorDiv);

    setTimeout(function() {
        if (errorDiv && errorDiv.parentNode) {
            errorDiv.style.opacity = '0';
            setTimeout(function() {
                if (errorDiv && errorDiv.parentNode) {
                    errorDiv.remove();
                }
            }, 300);
        }
    }, 5000);
}

function showFieldError(field, message) {
    clearFieldError(field);
    
    const errorSpan = document.createElement('span');
    errorSpan.className = 'field-error';
    errorSpan.style.cssText = 'color: #e74c3c; font-size: 0.9em; display: block; margin-top: 5px;';
    errorSpan.textContent = message;
    
    field.parentNode.insertBefore(errorSpan, field.nextSibling);
    field.classList.add('error');
}

function clearFieldError(field) {
    field.classList.remove('error');
    const errorSpan = field.parentNode.querySelector('.field-error');
    if (errorSpan) {
        errorSpan.remove();
    }
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
    const animatedElements = document.querySelectorAll('.service-card, .about-content, .footer-section');
    animatedElements.forEach(function(el) {
        animateOnScroll.observe(el);
    });
}

/**
 * Lazy loading for images (if not supported natively)
 */
if ('loading' in HTMLImageElement.prototype === false) {
    const images = document.querySelectorAll('img[data-src]');
    
    if ('IntersectionObserver' in window) {
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
}