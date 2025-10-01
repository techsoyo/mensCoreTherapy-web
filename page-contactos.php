<?php

/**
 * Template Name: Contactos
 */
if (! function_exists('elementor_theme_do_location') || ! elementor_theme_do_location('header')) {
  get_header();
}
?>
<main id="primary" role="main">
  <?php get_template_part('template-parts/contactos'); ?>
</main>
<?php get_footer(); ?>