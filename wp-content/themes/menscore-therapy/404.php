<?php
/**
 * The template for displaying 404 pages (not found)
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <section class="error-404 not-found neomorphic-inset">
            <header class="page-header">
                <h1 class="page-title"><?php _e('Oops! That page can&rsquo;t be found.', 'menscore-therapy'); ?></h1>
            </header>

            <div class="page-content">
                <p><?php _e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'menscore-therapy'); ?></p>

                <?php get_search_form(); ?>

                <div class="widget-area">
                    <div class="widget neomorphic">
                        <h2 class="widget-title"><?php _e('Most Used Categories', 'menscore-therapy'); ?></h2>
                        <ul>
                            <?php
                            wp_list_categories(array(
                                'orderby'    => 'count',
                                'order'      => 'DESC',
                                'show_count' => 1,
                                'title_li'   => '',
                                'number'     => 10,
                            ));
                            ?>
                        </ul>
                    </div>

                    <div class="widget neomorphic">
                        <h2 class="widget-title"><?php _e('Recent Posts', 'menscore-therapy'); ?></h2>
                        <ul>
                            <?php
                            wp_get_archives(array(
                                'type'            => 'postbypost',
                                'limit'           => 10,
                                'format'          => 'html',
                                'before'          => '',
                                'after'           => '',
                                'show_post_count' => false,
                                'echo'            => 1,
                                'order'           => 'DESC',
                            ));
                            ?>
                        </ul>
                    </div>

                    <div class="widget neomorphic">
                        <h2 class="widget-title"><?php _e('Quick Navigation', 'menscore-therapy'); ?></h2>
                        <ul>
                            <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('Home', 'menscore-therapy'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/about')); ?>"><?php _e('About Us', 'menscore-therapy'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/services')); ?>"><?php _e('Our Services', 'menscore-therapy'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/contact')); ?>"><?php _e('Contact', 'menscore-therapy'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/blog')); ?>"><?php _e('Blog', 'menscore-therapy'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<?php get_footer(); ?>