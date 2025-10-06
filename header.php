<?php if (!defined("ABSPATH")) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo("charset"); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>

  <!-- 
    NOTA: Todos los estilos visuales están en header.css
    Solo usamos estilos inline para la imagen de fondo
  -->
  <header
    id="site-header"
    class="mm-header"
    role="banner"
    style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/fonde-header.webp');">
    <!-- Overlay sutil -->
    <div class="mm-header__overlay"></div>

    <!-- Skip to content para accesibilidad -->
    <a id="skip-to-content" class="screen-reader-text" href="#primary">
      <?php esc_html_e("Saltar al contenido", "masajista-masculino"); ?>
    </a>

    <!-- Contenido del header -->
    <div class="mm-header__inner">

      <!-- Logo -->
      <div class="mm-header__logo">
        <a href="<?php echo esc_url(home_url("/")); ?>" aria-label="<?php bloginfo('name'); ?> - Ir a inicio">
          <img
            src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-sin-fondo.webp"
            alt="<?php bloginfo('name'); ?>"
            class="mm-logo-img">
        </a>
      </div>

      <!-- Navegación -->
      <nav
        id="site-navigation"
        class="mm-header__nav"
        role="navigation"
        aria-label="<?php esc_attr_e("Menú principal", "masajista-masculino"); ?>">
        <?php
        if (has_nav_menu("primary")) {
          wp_nav_menu([
            "theme_location" => "primary",
            "container"      => false,
            "menu_class"     => "mm-menu",
            "menu_id"        => "primary-menu",
            "fallback_cb"    => false
          ]);
        } else {
          // Menú de fallback si no hay menú configurado
          echo '<ul id="primary-menu" class="mm-menu">';
          echo '<li><a href="' . esc_url(home_url("/")) . '">Inicio</a></li>';
          echo '<li><a href="' . esc_url(home_url("/servicios/")) . '">Servicios</a></li>';
          echo '<li><a href="' . esc_url(home_url("/contactos/")) . '">Contactos</a></li>';
          echo '<li><a href="' . esc_url(home_url("/reservas/")) . '">Reservas</a></li>';
          echo '<li><a href="' . esc_url(home_url("/productos/")) . '">Nuestros Productos</a></li>';
          echo '</ul>';
        }
        ?>
      </nav>

      <!-- Botón hamburguesa para móvil (opcional) -->
      <button
        class="mm-header__toggle"
        aria-label="Abrir menú de navegación"
        aria-expanded="false"
        aria-controls="primary-menu">
        <span class="mm-header__toggle-icon"></span>
      </button>

    </div>
  </header>