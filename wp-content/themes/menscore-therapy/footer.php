    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container">
            <div class="footer-content">
                <?php if (is_active_sidebar('footer-1')) : ?>
                    <div class="footer-section">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                <?php else : ?>
                    <div class="footer-section">
                        <h3>Contact Information</h3>
                        <p>Men's Core Therapy<br>
                        123 Wellness Street<br>
                        Health City, HC 12345<br>
                        Phone: (555) 123-4567<br>
                        Email: info@menscore-therapy.com</p>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar('footer-2')) : ?>
                    <div class="footer-section">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                <?php else : ?>
                    <div class="footer-section">
                        <h3>Quick Links</h3>
                        <ul>
                            <li><a href="<?php echo esc_url(home_url('/about')); ?>">About Us</a></li>
                            <li><a href="<?php echo esc_url(home_url('/services')); ?>">Our Services</a></li>
                            <li><a href="<?php echo esc_url(home_url('/appointment')); ?>">Book Appointment</a></li>
                            <li><a href="<?php echo esc_url(home_url('/resources')); ?>">Resources</a></li>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar('footer-3')) : ?>
                    <div class="footer-section">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                <?php else : ?>
                    <div class="footer-section">
                        <h3>Follow Us</h3>
                        <div class="social-links">
                            <a href="#" class="social-link neomorphic" aria-label="Facebook">
                                <span>Facebook</span>
                            </a>
                            <a href="#" class="social-link neomorphic" aria-label="Twitter">
                                <span>Twitter</span>
                            </a>
                            <a href="#" class="social-link neomorphic" aria-label="LinkedIn">
                                <span>LinkedIn</span>
                            </a>
                            <a href="#" class="social-link neomorphic" aria-label="Instagram">
                                <span>Instagram</span>
                            </a>
                        </div>
                        <div class="newsletter-signup">
                            <h4>Newsletter</h4>
                            <p>Stay updated with our latest wellness tips and news.</p>
                            <form class="newsletter-form">
                                <input type="email" placeholder="Your email address" class="neomorphic-inset" required>
                                <button type="submit" class="neomorphic">Subscribe</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved. | 
                   <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">Privacy Policy</a> | 
                   <a href="<?php echo esc_url(home_url('/terms-of-service')); ?>">Terms of Service</a>
                </p>
                <p>Designed with <span style="color: #e74c3c;">&hearts;</span> by TechSoyo</p>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>