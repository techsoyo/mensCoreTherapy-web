<?php if (!defined('ABSPATH')) exit; ?>
<?php get_header(); ?>

<main id="primary" class="site-main">
  <?php
get_template_part('template-parts/hero', null, [
  // 'video_url' => get_stylesheet_directory_uri() . '/assets/images/video.mp4'
]);
?>
  <section class="home-after-hero container">
    <!-- contenido adicional opcional -->
  </section>
</main>

<?php get_footer(); ?>
