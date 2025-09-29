<?php if (!defined('ABSPATH')) exit; ?>
<section class="mm-blogs">
  <div class="container">
    <h1><?php _e('Blogs', 'masajista-masculino'); ?></h1>

    <?php
    $paged = max(1, get_query_var('paged') ?: get_query_var('page'));
    $q = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 10,
        'paged'          => $paged,
        'post_status'    => 'publish',
    ]);

    if ($q->have_posts()) {
        echo '<div class="mm-post-list">';
        while ($q->have_posts()) { $q->the_post(); ?>
            <article <?php post_class('mm-post-item'); ?>>
              <h3 class="mm-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <div class="mm-post-meta"><?php echo esc_html(get_the_date()); ?></div>
              <div class="mm-post-excerpt"><?php the_excerpt(); ?></div>
            </article>
        <?php }
        echo '</div>';

        // Pagination
        $big = 999999999;
        echo '<div class="mm-pagination">';
        echo paginate_links([
            'base'    => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format'  => '?paged=%#%',
            'current' => $paged,
            'total'   => $q->max_num_pages
        ]);
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p>'.__('No hay entradas.', 'masajista-masculino').'</p>';
    }
    ?>
  </div>
</section>
