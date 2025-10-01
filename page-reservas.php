<?php

/**
 * Template Name: Reservas
 */

if ( ! function_exists('elementor_theme_do_location') || ! elementor_theme_do_location('header') ) {
  get_header();
}
get_template_part('template-parts/reservas');
?>
<section class="page-wrap">
  <div class="container">
    <?php while (have_posts()) { the_post(); the_content(); } ?>
  </div>
</section>
<?php get_footer(); ?>
