<?php

/**
 * Template Name: Productos
 */
if (!defined('ABSPATH')) exit;
get_header(); ?>
<main id="primary" class="site-main">
  <?php get_template_part('template-parts/productos'); ?>
  <div class="container entry-content"><?php while (have_posts()): the_post(); the_content(); endwhile; ?></div>
</main>
<?php get_footer(); ?>
