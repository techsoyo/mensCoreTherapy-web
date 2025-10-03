<?php

/**
 * Template Name: Reservas
 */

get_header();
get_template_part('template-parts/reservas');
?>
<?php
$bg = get_template_directory_uri() . '/assets/images/reservas.webp';
?>
<main class="page-reservas-hero" style="background-image: url('<?php echo esc_url($bg); ?>'); background-size: cover; background-position: center;">
  <section class="page-wrap">
    <div class="container">
      <?php while (have_posts()) { the_post(); the_content(); } ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
