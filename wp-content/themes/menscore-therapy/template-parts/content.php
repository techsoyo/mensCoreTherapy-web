<?php
/**
 * Template part for displaying posts
 * 
 * @package MensCoreTherapy
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
    <header class="entry-header">
        <?php
        if ( is_sticky() && is_home() ) :
            echo '<span class="featured-post">' . esc_html__( 'Featured', 'menscore-therapy' ) . '</span>';
        endif;

        if ( is_singular() ) :
            the_title( '<h1 class="entry-title">', '</h1>' );
        else :
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
        endif;

        if ( 'post' === get_post_type() ) :
            ?>
            <div class="entry-meta">
                <?php
                menscore_posted_on();
                menscore_posted_by();
                ?>
            </div>
            <?php
        endif;
        ?>
    </header>

    <?php if ( has_post_thumbnail() && ! is_single() ) : ?>
        <div class="entry-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'medium_large' ); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
        if ( is_singular() || is_search() ) :
            the_content( sprintf(
                wp_kses(
                    __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'menscore-therapy' ),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            ) );

            wp_link_pages( array(
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'menscore-therapy' ),
                'after'  => '</div>',
            ) );
        else :
            the_excerpt();
        endif;
        ?>
    </div>

    <footer class="entry-footer">
        <?php
        if ( 'post' === get_post_type() ) :
            $categories_list = get_the_category_list( esc_html__( ', ', 'menscore-therapy' ) );
            if ( $categories_list ) :
                printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'menscore-therapy' ) . '</span>', $categories_list );
            endif;

            $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'menscore-therapy' ) );
            if ( $tags_list ) :
                printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'menscore-therapy' ) . '</span>', $tags_list );
            endif;
        endif;

        if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'menscore-therapy' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
                )
            );
            echo '</span>';
        endif;

        edit_post_link(
            sprintf(
                wp_kses(
                    __( 'Edit <span class="screen-reader-text">%s</span>', 'menscore-therapy' ),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            ),
            '<span class="edit-link">',
            '</span>'
        );
        ?>
    </footer>
</article>