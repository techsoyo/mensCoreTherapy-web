<?php if (!defined('ABSPATH')) exit; ?>
<?php get_header(); ?>

<main id="primary" class="site-main">
  <?php
get_template_part('template-parts/hero', null, [
  // 'bg_image' => get_template_directory_uri() . '/assets/images/hoem.jpg' // Ya está como default
]);
?>
  <section class="home-after-hero container">
    <!-- contenido adicional opcional -->
  </section>
</main>

<?php get_footer(); ?>
