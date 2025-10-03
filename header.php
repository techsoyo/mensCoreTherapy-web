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
  <header id="site-header" class="mm-header neo-header" role="banner" style="
    background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/fonde-header.webp');
    background-size: cover;
    background-position: center;
    color: #FFEDE6;
    margin: 0;
    padding: 0.5rem 0;
    border-radius: 0 0 20px 20px;
    box-shadow: none;
    overflow: hidden;
    position: relative;
    height: auto;
    min-height: 70px;
  ">
    <!-- Overlay sutil para mantener la textura visible -->
    <div style="
      position: absolute;
    
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(170, 48, 0, 0.25);
      z-index: 0;
    "></div>
    
    <a id="skip-to-content" class="screen-reader-text" href="#primary"><?php esc_html_e("Saltar al contenido", "masajista-masculino"); ?></a>
    <div class="mm-header__inner" style="
      position: relative; 
      z-index: 1; 
      display: flex; 
      justify-content: space-between; 
      align-items: center; 
      padding: 0 1rem;
      max-width: 1200px;
      margin: 0 auto;
      height: 100%;
    ">
      <div class="mm-logo">
        <a href="<?php echo esc_url(home_url("/")); ?>">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-sin-fondo.png" alt="Men's Core Therapy" style="max-height: 4.5rem; width: auto;">
        </a>
      </div>
      <nav id="site-navigation" role="navigation" aria-label="<?php esc_attr_e("Menú principal", "masajista-masculino"); ?>">
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
          echo "<ul id=\"primary-menu\" class=\"mm-menu\">";
          echo "<li><a href=\"" . esc_url(home_url("/")) . "\">Inicio</a></li>";
          echo "<li><a href=\"" . esc_url(home_url("/servicios/")) . "\">Servicios</a></li>";
          echo "<li><a href=\"" . esc_url(home_url("/contactos/")) . "\">Contactos</a></li>";
          echo "<li><a href=\"" . esc_url(home_url("/reservas/")) . "\">Reservas</a></li>";
          echo "<li><a href=\"" . esc_url(home_url("/productos/")) . "\">¡Nuestros Productos!</a></li>";
          echo "</ul>";
        }
        ?>
      </nav>
    </div>
  </header>
