<?php

/**
 * Template Name: Contactos
 * Description: Página de contacto con imagen de fondo y formulario flotante
 */

if (!defined('ABSPATH')) exit;

get_header();
?>

<main id="primary" role="main" style="margin: 0; padding: 0;">
  <?php
  // URL de la imagen de fondo
  $bg_image = get_template_directory_uri() . '/assets/images/contact.webp';
  ?>

  <div class="page-contactos-hero" style="
    background-image: url('<?php echo esc_url($bg_image); ?>');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
  ">
    <?php
    // Incluir el template part del formulario de contacto
    get_template_part('template-parts/contactos');
    ?>
  </div>
</main>

<?php
get_footer();
?>