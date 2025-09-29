<?php
/**
 * The header for our theme
 * 
 * @package MensCoreTherapy
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary">
    <?php esc_html_e( 'Skip to content', 'menscore-therapy' ); ?>
</a>

<div id="page" class="site">
    <header id="masthead" class="site-header">
        <div class="container">
            <div class="site-branding">
                <?php
                if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) :
                    the_custom_logo();
                else :
                    ?>
                    <h1 class="site-logo">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                            <?php bloginfo( 'name' ); ?>
                        </a>
                    </h1>
                    <?php
                    $description = get_bloginfo( 'description', 'display' );
                    if ( $description || is_customize_preview() ) :
                        ?>
                        <p class="site-description"><?php echo $description; ?></p>
                        <?php
                    endif;
                endif;
                ?>
            </div>

            <nav id="site-navigation" class="main-navigation">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'menscore-therapy' ); ?></span>
                    <span class="hamburger"></span>
                </button>
                
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => 'menscore_default_menu',
                ) );
                ?>
            </nav>
        </div>
    </header>

<?php
/**
 * Hook: menscore_after_header
 * 
 * @hooked menscore_breadcrumbs - 10
 */
do_action( 'menscore_after_header' );
?>