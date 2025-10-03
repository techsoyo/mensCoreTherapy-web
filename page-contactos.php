<?php

/**
 * Template Name: Contactos
 */
get_header();
?>
<main id="primary" role="main">
  <?php
  $bg = get_template_directory_uri() . '/assets/images/contact.webp';
  ?>
  <div class="page-contactos-hero" style="background-image: url('<?php echo esc_url($bg); ?>'); background-size: cover; background-position: center;">
    <?php get_template_part('template-parts/contactos'); ?>
  </div>
</main>
<?php get_footer(); ?>