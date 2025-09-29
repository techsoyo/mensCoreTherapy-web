<?php
/* Template: Servicios (por slug) */
get_header();
get_template_part('template-parts/servicios');
?>
<section class="page-wrap">
  <div class="container">
    <?php while (have_posts()) { the_post(); the_content(); } ?>
  </div>
</section>
<?php get_footer(); ?>
