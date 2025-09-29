<?php
/**
 * Template Functions for MensCore Therapy Theme
 * 
 * @package MensCoreTherapy
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display posted on date
 */
function menscore_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf( $time_string,
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date() ),
        esc_attr( get_the_modified_date( DATE_W3C ) ),
        esc_html( get_the_modified_date() )
    );

    printf( '<span class="posted-on">' . esc_html_x( 'Posted on %s', 'post date', 'menscore-therapy' ) . '</span>', $time_string );
}

/**
 * Display post author
 */
function menscore_posted_by() {
    printf( '<span class="byline"> ' . esc_html_x( 'by %s', 'post author', 'menscore-therapy' ) . '</span>',
        '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
    );
}

/**
 * Default menu fallback
 */
function menscore_default_menu() {
    echo '<ul id="primary-menu" class="menu">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'menscore-therapy' ) . '</a></li>';
    
    // Add Services page if it exists
    $services_page = get_page_by_path( 'services' );
    if ( $services_page ) {
        echo '<li><a href="' . esc_url( get_permalink( $services_page->ID ) ) . '">' . esc_html__( 'Services', 'menscore-therapy' ) . '</a></li>';
    }
    
    // Add About page if it exists
    $about_page = get_page_by_path( 'about' );
    if ( $about_page ) {
        echo '<li><a href="' . esc_url( get_permalink( $about_page->ID ) ) . '">' . esc_html__( 'About', 'menscore-therapy' ) . '</a></li>';
    }
    
    // Add Contact page if it exists
    $contact_page = get_page_by_path( 'contact' );
    if ( $contact_page ) {
        echo '<li><a href="' . esc_url( get_permalink( $contact_page->ID ) ) . '">' . esc_html__( 'Contact', 'menscore-therapy' ) . '</a></li>';
    }
    
    echo '</ul>';
}

/**
 * Get featured services
 */
function menscore_get_featured_services( $limit = 3 ) {
    $args = array(
        'post_type'      => 'services',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'   => '_service_featured',
                'value' => '1',
            ),
        ),
    );
    
    return new WP_Query( $args );
}

/**
 * Get service price
 */
function menscore_get_service_price( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $price = get_post_meta( $post_id, '_service_price', true );
    return $price ? $price : __( 'Contact for pricing', 'menscore-therapy' );
}

/**
 * Get service duration
 */
function menscore_get_service_duration( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $duration = get_post_meta( $post_id, '_service_duration', true );
    return $duration ? sprintf( __( '%s minutes', 'menscore-therapy' ), $duration ) : '';
}

/**
 * Check if service is featured
 */
function menscore_is_featured_service( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    return get_post_meta( $post_id, '_service_featured', true ) === '1';
}

/**
 * Get therapy categories for filtering
 */
function menscore_get_therapy_categories() {
    return get_categories( array(
        'taxonomy'   => 'category',
        'hide_empty' => false,
        'exclude'    => array( 1 ), // Exclude uncategorized
    ) );
}

/**
 * Display therapy service card
 */
function menscore_display_service_card( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $service = get_post( $post_id );
    $price = menscore_get_service_price( $post_id );
    $duration = menscore_get_service_duration( $post_id );
    $featured = menscore_is_featured_service( $post_id );
    
    ?>
    <div class="service-card<?php echo $featured ? ' featured-service' : ''; ?>">
        <?php if ( has_post_thumbnail( $post_id ) ) : ?>
            <div class="service-image">
                <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
                    <?php echo get_the_post_thumbnail( $post_id, 'medium' ); ?>
                </a>
                <?php if ( $featured ) : ?>
                    <span class="featured-badge"><?php esc_html_e( 'Popular', 'menscore-therapy' ); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="service-content">
            <h3 class="service-title">
                <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
                    <?php echo esc_html( $service->post_title ); ?>
                </a>
            </h3>
            
            <div class="service-excerpt">
                <?php echo wp_trim_words( $service->post_excerpt, 20 ); ?>
            </div>
            
            <div class="service-details">
                <?php if ( $duration ) : ?>
                    <span class="service-duration">
                        <i class="icon-clock"></i> <?php echo esc_html( $duration ); ?>
                    </span>
                <?php endif; ?>
                
                <span class="service-price">
                    <i class="icon-tag"></i> <?php echo esc_html( $price ); ?>
                </span>
            </div>
            
            <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="btn btn-primary service-btn">
                <?php esc_html_e( 'Learn More', 'menscore-therapy' ); ?>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Generate schema markup for services
 */
function menscore_service_schema( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $service = get_post( $post_id );
    $price = get_post_meta( $post_id, '_service_price', true );
    $duration = get_post_meta( $post_id, '_service_duration', true );
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Service',
        'name'     => $service->post_title,
        'description' => wp_strip_all_tags( $service->post_excerpt ),
        'provider' => array(
            '@type' => 'Organization',
            'name'  => get_bloginfo( 'name' ),
        ),
    );
    
    if ( $price && is_numeric( str_replace( array( '$', '€', '£' ), '', $price ) ) ) {
        $schema['offers'] = array(
            '@type' => 'Offer',
            'price' => str_replace( array( '$', '€', '£' ), '', $price ),
            'priceCurrency' => 'USD', // Default, should be configurable
        );
    }
    
    return json_encode( $schema );
}

/**
 * Get business hours
 */
function menscore_get_business_hours() {
    return array(
        'monday'    => get_theme_mod( 'menscore_hours_monday', '9:00 AM - 5:00 PM' ),
        'tuesday'   => get_theme_mod( 'menscore_hours_tuesday', '9:00 AM - 5:00 PM' ),
        'wednesday' => get_theme_mod( 'menscore_hours_wednesday', '9:00 AM - 5:00 PM' ),
        'thursday'  => get_theme_mod( 'menscore_hours_thursday', '9:00 AM - 5:00 PM' ),
        'friday'    => get_theme_mod( 'menscore_hours_friday', '9:00 AM - 5:00 PM' ),
        'saturday'  => get_theme_mod( 'menscore_hours_saturday', 'Closed' ),
        'sunday'    => get_theme_mod( 'menscore_hours_sunday', 'Closed' ),
    );
}

/**
 * Display business hours
 */
function menscore_display_business_hours() {
    $hours = menscore_get_business_hours();
    $current_day = strtolower( date( 'l' ) );
    
    echo '<div class="business-hours">';
    echo '<h4>' . esc_html__( 'Business Hours', 'menscore-therapy' ) . '</h4>';
    echo '<ul class="hours-list">';
    
    foreach ( $hours as $day => $time ) {
        $current_class = ( $day === $current_day ) ? ' class="current-day"' : '';
        echo '<li' . $current_class . '>';
        echo '<span class="day">' . esc_html( ucfirst( $day ) ) . ':</span> ';
        echo '<span class="time">' . esc_html( $time ) . '</span>';
        echo '</li>';
    }
    
    echo '</ul>';
    echo '</div>';
}

/**
 * Get contact information
 */
function menscore_get_contact_info() {
    return array(
        'phone' => get_theme_mod( 'menscore_phone', '' ),
        'email' => get_theme_mod( 'menscore_email', get_option( 'admin_email' ) ),
        'address' => get_theme_mod( 'menscore_address', '' ),
    );
}

/**
 * Display contact information
 */
function menscore_display_contact_info() {
    $contact = menscore_get_contact_info();
    
    echo '<div class="contact-info">';
    
    if ( $contact['phone'] ) {
        echo '<div class="contact-item phone">';
        echo '<i class="icon-phone"></i>';
        echo '<a href="tel:' . esc_attr( $contact['phone'] ) . '">' . esc_html( $contact['phone'] ) . '</a>';
        echo '</div>';
    }
    
    if ( $contact['email'] ) {
        echo '<div class="contact-item email">';
        echo '<i class="icon-email"></i>';
        echo '<a href="mailto:' . esc_attr( $contact['email'] ) . '">' . esc_html( $contact['email'] ) . '</a>';
        echo '</div>';
    }
    
    if ( $contact['address'] ) {
        echo '<div class="contact-item address">';
        echo '<i class="icon-location"></i>';
        echo '<span>' . esc_html( $contact['address'] ) . '</span>';
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Security function to sanitize user input for therapy forms
 */
function menscore_sanitize_therapy_input( $input ) {
    // Remove any HTML tags and sanitize
    $input = wp_strip_all_tags( $input );
    $input = sanitize_text_field( $input );
    
    // Additional sanitization for therapy-specific content
    $input = preg_replace( '/[^a-zA-Z0-9\s\-.,!?()@]/', '', $input );
    
    return $input;
}

/**
 * Generate appointment booking link
 */
function menscore_get_booking_link( $service_slug = '' ) {
    $booking_page = get_page_by_path( 'book-appointment' );
    
    if ( $booking_page ) {
        $url = get_permalink( $booking_page->ID );
        if ( $service_slug ) {
            $url = add_query_arg( 'service', $service_slug, $url );
        }
        return $url;
    }
    
    return '#';
}

/**
 * Check if current page is related to therapy services
 */
function menscore_is_therapy_related() {
    global $post;
    
    if ( is_singular( 'services' ) ) {
        return true;
    }
    
    if ( is_page() && $post ) {
        $therapy_keywords = array( 'therapy', 'wellness', 'health', 'treatment', 'consultation' );
        $content = strtolower( $post->post_content . ' ' . $post->post_title );
        
        foreach ( $therapy_keywords as $keyword ) {
            if ( strpos( $content, $keyword ) !== false ) {
                return true;
            }
        }
    }
    
    return false;
}