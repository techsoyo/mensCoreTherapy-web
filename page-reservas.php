<?php

/**
 * Template Name: Reservas
 * Description: Página de reservas con imagen de fondo y formulario neomórfico
 */

get_header();

// Definir imagen de fondo para la página de reservas
$bg_image = get_template_directory_uri() .
  '/assets/images/reservas.webp';
?>

<main class="page-reservas-hero" style="
  background-image: url('<?php echo esc_url($bg_image); ?>');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
">
  <?php
  // Incluir el template part del formulario de reservas
  get_template_part('template-parts/reservas');
  ?>
</main>

<?php get_footer(); ?>