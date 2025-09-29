<?php
/**
 * The template for displaying search results pages
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <header class="page-header neomorphic">
            <h1 class="page-title">
                <?php
                printf(esc_html__('Search Results for: %s', 'menscore-therapy'), '<span>' . get_search_query() . '</span>');
                ?>
            </h1>
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
            the_posts_navigation();
            ?>

        <?php else : ?>
            <section class="no-results not-found neomorphic-inset">
                <header class="page-header">
                    <h1 class="page-title"><?php _e('Nothing Found', 'menscore-therapy'); ?></h1>
                </header>

                <div class="page-content">
                    <p><?php _e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'menscore-therapy'); ?></p>
                    <?php get_search_form(); ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>