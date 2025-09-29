<?php
/**
 * The template for displaying all single posts
 * 
 * @package MensCoreTherapy
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="content-area">
        <section class="site-content">
            <?php
            while ( have_posts() ) :
                the_post();
                
                get_template_part( 'template-parts/content', get_post_type() );
                
                // Navigation between posts
                the_post_navigation( array(
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'menscore-therapy' ) . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'menscore-therapy' ) . '</span> <span class="nav-title">%title</span>',
                ) );
                
                // Comments
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
                
            endwhile;
            ?>
        </section>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();