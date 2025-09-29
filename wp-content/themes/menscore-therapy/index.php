<?php
/**
 * The main template file
 * 
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * 
 * @package MensCoreTherapy
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="content-area">
        <section class="site-content">
            <?php
            if ( have_posts() ) :
                if ( is_home() && ! is_front_page() ) :
                    ?>
                    <header class="page-header">
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                    </header>
                    <?php
                endif;

                // Start the loop
                while ( have_posts() ) :
                    the_post();
                    
                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    get_template_part( 'template-parts/content', get_post_type() );

                endwhile;

                // Previous/next navigation
                the_posts_navigation( array(
                    'prev_text' => __( '← Older posts', 'menscore-therapy' ),
                    'next_text' => __( 'Newer posts →', 'menscore-therapy' ),
                ) );

            else :

                get_template_part( 'template-parts/content', 'none' );

            endif;
            ?>
        </section>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();