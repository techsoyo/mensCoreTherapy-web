<?php
/**
 * The template for displaying archive pages
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <header class="page-header neomorphic">
            <?php
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
        </header>

        <?php if (have_posts()) : ?>
            <div class="posts-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card neomorphic'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="post-content">
                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                
                                <div class="entry-meta">
                                    <span class="posted-on"><?php echo get_the_date(); ?></span>
                                    <span class="byline">by <?php the_author(); ?></span>
                                </div>
                            </header>

                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="entry-footer">
                                <a href="<?php the_permalink(); ?>" class="read-more neomorphic">
                                    <?php _e('Read More', 'menscore-therapy'); ?>
                                </a>
                            </footer>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            the_posts_navigation(array(
                'prev_text' => __('Older posts', 'menscore-therapy'),
                'next_text' => __('Newer posts', 'menscore-therapy'),
            ));
            ?>

        <?php else : ?>
            <section class="no-results not-found neomorphic-inset">
                <header class="page-header">
                    <h1 class="page-title"><?php _e('Nothing here', 'menscore-therapy'); ?></h1>
                </header>

                <div class="page-content">
                    <p><?php _e('It looks like nothing was found at this location. Maybe try a search?', 'menscore-therapy'); ?></p>
                    <?php get_search_form(); ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>