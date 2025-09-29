<?php
/**
 * Custom Hooks for MensCore Therapy Theme
 * 
 * @package MensCoreTherapy
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Hook: menscore_after_header
 * Add breadcrumbs after header
 */
function menscore_breadcrumbs() {
    if ( is_front_page() ) {
        return;
    }
    
    echo '<div class="breadcrumbs-container">';
    echo '<div class="container">';
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb navigation', 'menscore-therapy' ) . '">';
    
    echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'menscore-therapy' ) . '</a>';
    
    if ( is_category() || is_single() ) {
        echo ' &raquo; ';
        if ( is_single() ) {
            $categories = get_the_category();
            if ( ! empty( $categories ) ) {
                echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
                echo ' &raquo; ';
            }
            echo '<span class="current">' . get_the_title() . '</span>';
        } else {
            echo '<span class="current">' . single_cat_title( '', false ) . '</span>';
        }
    } elseif ( is_page() ) {
        echo ' &raquo; <span class="current">' . get_the_title() . '</span>';
    } elseif ( is_search() ) {
        echo ' &raquo; <span class="current">' . esc_html__( 'Search Results', 'menscore-therapy' ) . '</span>';
    } elseif ( is_404() ) {
        echo ' &raquo; <span class="current">' . esc_html__( 'Page Not Found', 'menscore-therapy' ) . '</span>';
    }
    
    echo '</nav>';
    echo '</div>';
    echo '</div>';
}
add_action( 'menscore_after_header', 'menscore_breadcrumbs', 10 );

/**
 * Hook: menscore_before_footer
 * Add call-to-action section before footer
 */
function menscore_cta_section() {
    if ( is_front_page() || is_page() ) {
        $cta_title = get_theme_mod( 'menscore_cta_title', __( 'Ready to Start Your Wellness Journey?', 'menscore-therapy' ) );
        $cta_text = get_theme_mod( 'menscore_cta_text', __( 'Take the first step towards better health and wellness. Contact us today to schedule your consultation.', 'menscore-therapy' ) );
        $cta_button_text = get_theme_mod( 'menscore_cta_button_text', __( 'Get Started', 'menscore-therapy' ) );
        $cta_button_url = get_theme_mod( 'menscore_cta_button_url', '#contact' );
        
        if ( $cta_title || $cta_text ) {
            echo '<section class="cta-section">';
            echo '<div class="container">';
            echo '<div class="cta-content">';
            
            if ( $cta_title ) {
                echo '<h2 class="cta-title">' . esc_html( $cta_title ) . '</h2>';
            }
            
            if ( $cta_text ) {
                echo '<p class="cta-text">' . esc_html( $cta_text ) . '</p>';
            }
            
            if ( $cta_button_text && $cta_button_url ) {
                echo '<a href="' . esc_url( $cta_button_url ) . '" class="btn btn-primary cta-button">' . esc_html( $cta_button_text ) . '</a>';
            }
            
            echo '</div>';
            echo '</div>';
            echo '</section>';
        }
    }
}
add_action( 'menscore_before_footer', 'menscore_cta_section', 10 );

/**
 * Custom hook for theme customization
 */
function menscore_custom_styles() {
    $primary_color = get_theme_mod( 'menscore_primary_color', '#3498db' );
    $secondary_color = get_theme_mod( 'menscore_secondary_color', '#2c3e50' );
    
    if ( $primary_color !== '#3498db' || $secondary_color !== '#2c3e50' ) {
        echo '<style type="text/css">';
        echo ':root {';
        echo '--primary-color: ' . esc_attr( $primary_color ) . ';';
        echo '--secondary-color: ' . esc_attr( $secondary_color ) . ';';
        echo '}';
        echo '.btn { background-color: ' . esc_attr( $primary_color ) . '; }';
        echo '.btn:hover { background-color: ' . esc_attr( $secondary_color ) . '; }';
        echo 'a { color: ' . esc_attr( $primary_color ) . '; }';
        echo 'a:hover { color: ' . esc_attr( $secondary_color ) . '; }';
        echo '</style>';
    }
}
add_action( 'wp_head', 'menscore_custom_styles' );

/**
 * Add custom body classes
 */
function menscore_custom_body_classes( $classes ) {
    // Add class for pages without sidebar
    if ( is_page_template( 'templates/full-width.php' ) || is_front_page() ) {
        $classes[] = 'no-sidebar';
    }
    
    // Add class for therapy-related pages
    if ( is_page() || is_single() ) {
        global $post;
        if ( $post && ( strpos( $post->post_content, '[therapy_' ) !== false || 
                       strpos( $post->post_title, 'therapy' ) !== false ||
                       strpos( $post->post_title, 'wellness' ) !== false ) ) {
            $classes[] = 'therapy-page';
        }
    }
    
    return $classes;
}
add_filter( 'body_class', 'menscore_custom_body_classes' );

/**
 * Customize excerpt length
 */
function menscore_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'menscore_excerpt_length', 999 );

/**
 * Customize excerpt more text
 */
function menscore_excerpt_more( $more ) {
    return '... <a href="' . get_permalink() . '" class="read-more">' . __( 'Read More', 'menscore-therapy' ) . '</a>';
}
add_filter( 'excerpt_more', 'menscore_excerpt_more' );

/**
 * Add custom meta boxes for therapy services
 */
function menscore_add_service_meta_boxes() {
    add_meta_box(
        'menscore_service_details',
        __( 'Service Details', 'menscore-therapy' ),
        'menscore_service_details_callback',
        'services',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'menscore_add_service_meta_boxes' );

/**
 * Service details meta box callback
 */
function menscore_service_details_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'menscore_service_nonce' );
    
    $duration = get_post_meta( $post->ID, '_service_duration', true );
    $price = get_post_meta( $post->ID, '_service_price', true );
    $featured = get_post_meta( $post->ID, '_service_featured', true );
    
    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th><label for="service_duration">' . __( 'Duration (minutes)', 'menscore-therapy' ) . '</label></th>';
    echo '<td><input type="number" id="service_duration" name="service_duration" value="' . esc_attr( $duration ) . '" /></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th><label for="service_price">' . __( 'Price', 'menscore-therapy' ) . '</label></th>';
    echo '<td><input type="text" id="service_price" name="service_price" value="' . esc_attr( $price ) . '" /></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th><label for="service_featured">' . __( 'Featured Service', 'menscore-therapy' ) . '</label></th>';
    echo '<td><input type="checkbox" id="service_featured" name="service_featured" value="1" ' . checked( $featured, 1, false ) . ' /></td>';
    echo '</tr>';
    echo '</table>';
}

/**
 * Save service meta box data
 */
function menscore_save_service_meta( $post_id ) {
    if ( ! isset( $_POST['menscore_service_nonce'] ) || ! wp_verify_nonce( $_POST['menscore_service_nonce'], basename( __FILE__ ) ) ) {
        return $post_id;
    }
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }
    
    $fields = array( 'service_duration', 'service_price', 'service_featured' );
    
    foreach ( $fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
        } else {
            delete_post_meta( $post_id, '_' . $field );
        }
    }
}
add_action( 'save_post', 'menscore_save_service_meta' );