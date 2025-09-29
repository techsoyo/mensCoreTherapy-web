<?php
/* Template: Blogs (por slug) */
get_header();
get_template_part('template-parts/blogs');
?>
<section class="page-wrap">
  <div class="container">
    <?php while (have_posts()) { the_post(); the_content(); } ?>
  </div>
</section>
<?php get_footer(); ?>
