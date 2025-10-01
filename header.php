<?php if (!defined('ABSPATH')) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>

  <header id="site-header" class="mm-header neo-header" role="banner">
    <a id="skip-to-content" class="screen-reader-text" href="#primary"><?php esc_html_e('Saltar al contenido', 'masajista-masculino'); ?></a>
    <div class="mm-header__inner">
      <div class="mm-logo">
        <a href="<?php echo esc_url(home_url('/')); ?>">Men’s Core Therapy</a>
      </div>
      <nav id="site-navigation" role="navigation" aria-label="<?php esc_attr_e('Menú principal', 'masajista-masculino'); ?>">
        <?php
        if (has_nav_menu('primary')) {
          wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'mm-menu',
            'menu_id'        => 'primary-menu',
            'fallback_cb'    => false
          ]);
        } else {
          echo '<ul id="primary-menu" class="mm-menu">';
          echo '<li><a href="' . esc_url(home_url('/')) . '">Inicio</a></li>';
          echo '<li><a href="' . esc_url(home_url('/servicios/')) . '">Servicios</a></li>';
          echo '<li><a href="' . esc_url(home_url('/precios/')) . '">Precios</a></li>';
          echo '<li><a href="' . esc_url(home_url('/contactos/')) . '">Contactos</a></li>';
          echo '<li><a href="' . esc_url(home_url('/reservas/')) . '">Reservas</a></li>';
          echo '<li><a href="' . esc_url(home_url('/productos/')) . '">¡Nuestros Productos!</a></li>';
          echo '</ul>';
        }
        ?>
      </nav>
    </div>
  </header>

  <div id="content-wrap" class="site-wrap">
    