<?php
/**
 * The template for displaying the footer
 * 
 * @package MensCoreTherapy
 * @since 1.0.0
 */
?>

    <?php
    /**
     * Hook: menscore_before_footer
     */
    do_action( 'menscore_before_footer' );
    ?>

    <footer id="colophon" class="site-footer">
        <div class="container">
            <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) : ?>
                <div class="footer-widgets">
                    <div class="footer-widget-area">
                        <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                            <div class="footer-widget-column">
                                <?php dynamic_sidebar( 'footer-1' ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                            <div class="footer-widget-column">
                                <?php dynamic_sidebar( 'footer-2' ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                            <div class="footer-widget-column">
                                <?php dynamic_sidebar( 'footer-3' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="site-info">
                <div class="copyright">
                    <p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. 
                    <?php esc_html_e( 'All rights reserved.', 'menscore-therapy' ); ?></p>
                </div>
                
                <div class="footer-navigation">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer',
                        'menu_id'        => 'footer-menu',
                        'container'      => false,
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ) );
                    ?>
                </div>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php
/**
 * Hook: menscore_before_closing_body
 */
do_action( 'menscore_before_closing_body' );

wp_footer();
?>

</body>
</html>