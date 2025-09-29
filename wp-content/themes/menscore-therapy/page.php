<?php
/**
 * The template for displaying all pages
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
                
                get_template_part( 'template-parts/content', 'page' );
                
                // Comments
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
                
            endwhile;
            ?>
        </section>

        <?php 
        // Only show sidebar if not a full-width page template
        if ( ! is_page_template( 'templates/full-width.php' ) ) {
            get_sidebar();
        }
        ?>
    </div>
</main>

<?php
get_footer();