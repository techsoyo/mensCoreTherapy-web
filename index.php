<?php get_header(); ?>

<div class="container">
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class('post-content'); ?>>
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <div class="entry-content">
          <?php the_content(); ?>
        </div>
      </article>
    <?php endwhile; ?>

    <div class="mm-pagination"><?php the_posts_pagination(); ?></div>
  <?php else: ?>
    <p><?php _e('No hay contenido.', 'masajista-masculino'); ?></p>
  <?php endif; ?>
</div>

<?php get_footer(); ?>
</main>
