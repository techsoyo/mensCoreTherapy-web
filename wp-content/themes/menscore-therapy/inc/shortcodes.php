<?php
/**
 * Custom Shortcodes for MensCore Therapy Theme
 * 
 * @package MensCoreTherapy
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Services Grid Shortcode
 * Usage: [therapy_services columns="3" limit="6" featured="true"]
 */
function menscore_services_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'columns' => '3',
        'limit'   => '6',
        'featured' => 'false',
        'category' => '',
    ), $atts, 'therapy_services' );
    
    $args = array(
        'post_type'      => 'services',
        'posts_per_page' => intval( $atts['limit'] ),
        'post_status'    => 'publish',
    );
    
    if ( $atts['featured'] === 'true' ) {
        $args['meta_query'] = array(
            array(
                'key'   => '_service_featured',
                'value' => '1',
            ),
        );
    }
    
    $services = new WP_Query( $args );
    
    if ( ! $services->have_posts() ) {
        return '<p>' . __( 'No services found.', 'menscore-therapy' ) . '</p>';
    }
    
    ob_start();
    ?>
    <div class="therapy-services-grid columns-<?php echo esc_attr( $atts['columns'] ); ?>">
        <?php while ( $services->have_posts() ) : $services->the_post(); ?>
            <div class="service-item">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="service-thumbnail">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( 'medium' ); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="service-content">
                    <h3 class="service-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    
                    <div class="service-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                    
                    <div class="service-meta">
                        <?php
                        $duration = get_post_meta( get_the_ID(), '_service_duration', true );
                        $price = get_post_meta( get_the_ID(), '_service_price', true );
                        
                        if ( $duration ) {
                            echo '<span class="service-duration">' . sprintf( __( 'Duration: %s min', 'menscore-therapy' ), $duration ) . '</span>';
                        }
                        
                        if ( $price ) {
                            echo '<span class="service-price">' . sprintf( __( 'Price: %s', 'menscore-therapy' ), $price ) . '</span>';
                        }
                        ?>
                    </div>
                    
                    <a href="<?php the_permalink(); ?>" class="btn btn-primary service-link">
                        <?php esc_html_e( 'Learn More', 'menscore-therapy' ); ?>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'therapy_services', 'menscore_services_shortcode' );

/**
 * Testimonials Slider Shortcode
 * Usage: [therapy_testimonials limit="5" autoplay="true"]
 */
function menscore_testimonials_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'limit'    => '5',
        'autoplay' => 'true',
        'dots'     => 'true',
    ), $atts, 'therapy_testimonials' );
    
    $args = array(
        'post_type'      => 'testimonials',
        'posts_per_page' => intval( $atts['limit'] ),
        'post_status'    => 'publish',
        'orderby'        => 'rand',
    );
    
    $testimonials = new WP_Query( $args );
    
    if ( ! $testimonials->have_posts() ) {
        return '<p>' . __( 'No testimonials found.', 'menscore-therapy' ) . '</p>';
    }
    
    ob_start();
    ?>
    <div class="therapy-testimonials-slider" data-autoplay="<?php echo esc_attr( $atts['autoplay'] ); ?>" data-dots="<?php echo esc_attr( $atts['dots'] ); ?>">
        <?php while ( $testimonials->have_posts() ) : $testimonials->the_post(); ?>
            <div class="testimonial-item">
                <div class="testimonial-content">
                    <?php the_content(); ?>
                </div>
                
                <div class="testimonial-author">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="author-image">
                            <?php the_post_thumbnail( 'thumbnail' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="author-info">
                        <h4 class="author-name"><?php the_title(); ?></h4>
                        <?php
                        $author_title = get_post_meta( get_the_ID(), '_testimonial_author_title', true );
                        if ( $author_title ) {
                            echo '<span class="author-title">' . esc_html( $author_title ) . '</span>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'therapy_testimonials', 'menscore_testimonials_shortcode' );

/**
 * Contact Form Shortcode
 * Usage: [therapy_contact_form title="Contact Us"]
 */
function menscore_contact_form_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'title' => __( 'Contact Us', 'menscore-therapy' ),
        'email' => get_option( 'admin_email' ),
    ), $atts, 'therapy_contact_form' );
    
    ob_start();
    ?>
    <div class="therapy-contact-form">
        <?php if ( $atts['title'] ) : ?>
            <h3 class="contact-form-title"><?php echo esc_html( $atts['title'] ); ?></h3>
        <?php endif; ?>
        
        <form id="therapy-contact-form" class="contact-form" method="post" action="">
            <?php wp_nonce_field( 'therapy_contact_form', 'therapy_contact_nonce' ); ?>
            
            <div class="form-group">
                <label for="contact-name"><?php esc_html_e( 'Name', 'menscore-therapy' ); ?> *</label>
                <input type="text" id="contact-name" name="contact_name" required>
            </div>
            
            <div class="form-group">
                <label for="contact-email"><?php esc_html_e( 'Email', 'menscore-therapy' ); ?> *</label>
                <input type="email" id="contact-email" name="contact_email" required>
            </div>
            
            <div class="form-group">
                <label for="contact-phone"><?php esc_html_e( 'Phone', 'menscore-therapy' ); ?></label>
                <input type="tel" id="contact-phone" name="contact_phone">
            </div>
            
            <div class="form-group">
                <label for="contact-service"><?php esc_html_e( 'Service Interest', 'menscore-therapy' ); ?></label>
                <select id="contact-service" name="contact_service">
                    <option value=""><?php esc_html_e( 'Select a service', 'menscore-therapy' ); ?></option>
                    <?php
                    $services = get_posts( array(
                        'post_type' => 'services',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                    ) );
                    foreach ( $services as $service ) {
                        echo '<option value="' . esc_attr( $service->post_title ) . '">' . esc_html( $service->post_title ) . '</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="contact-message"><?php esc_html_e( 'Message', 'menscore-therapy' ); ?> *</label>
                <textarea id="contact-message" name="contact_message" rows="5" required></textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary contact-submit">
                    <?php esc_html_e( 'Send Message', 'menscore-therapy' ); ?>
                </button>
            </div>
        </form>
        
        <div id="contact-form-response" style="display: none;"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'therapy_contact_form', 'menscore_contact_form_shortcode' );

/**
 * Appointment Booking Shortcode
 * Usage: [therapy_booking service="consultation"]
 */
function menscore_booking_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'service' => '',
        'title'   => __( 'Book Appointment', 'menscore-therapy' ),
    ), $atts, 'therapy_booking' );
    
    ob_start();
    ?>
    <div class="therapy-booking-form">
        <?php if ( $atts['title'] ) : ?>
            <h3 class="booking-form-title"><?php echo esc_html( $atts['title'] ); ?></h3>
        <?php endif; ?>
        
        <form id="therapy-booking-form" class="booking-form" method="post" action="">
            <?php wp_nonce_field( 'therapy_booking_form', 'therapy_booking_nonce' ); ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="booking-name"><?php esc_html_e( 'Full Name', 'menscore-therapy' ); ?> *</label>
                    <input type="text" id="booking-name" name="booking_name" required>
                </div>
                
                <div class="form-group">
                    <label for="booking-email"><?php esc_html_e( 'Email', 'menscore-therapy' ); ?> *</label>
                    <input type="email" id="booking-email" name="booking_email" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="booking-phone"><?php esc_html_e( 'Phone', 'menscore-therapy' ); ?> *</label>
                    <input type="tel" id="booking-phone" name="booking_phone" required>
                </div>
                
                <div class="form-group">
                    <label for="booking-date"><?php esc_html_e( 'Preferred Date', 'menscore-therapy' ); ?> *</label>
                    <input type="date" id="booking-date" name="booking_date" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="booking-service"><?php esc_html_e( 'Service', 'menscore-therapy' ); ?> *</label>
                <select id="booking-service" name="booking_service" required>
                    <option value=""><?php esc_html_e( 'Select a service', 'menscore-therapy' ); ?></option>
                    <?php
                    $services = get_posts( array(
                        'post_type' => 'services',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                    ) );
                    foreach ( $services as $service ) {
                        $selected = ( $atts['service'] === $service->post_name ) ? 'selected' : '';
                        echo '<option value="' . esc_attr( $service->post_title ) . '" ' . $selected . '>' . esc_html( $service->post_title ) . '</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="booking-notes"><?php esc_html_e( 'Additional Notes', 'menscore-therapy' ); ?></label>
                <textarea id="booking-notes" name="booking_notes" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary booking-submit">
                    <?php esc_html_e( 'Request Appointment', 'menscore-therapy' ); ?>
                </button>
            </div>
        </form>
        
        <div id="booking-form-response" style="display: none;"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'therapy_booking', 'menscore_booking_shortcode' );

/**
 * FAQ Accordion Shortcode
 * Usage: [therapy_faq category="general"]
 */
function menscore_faq_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'category' => '',
        'limit'    => '10',
    ), $atts, 'therapy_faq' );
    
    // For now, we'll use regular posts with FAQ category
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => intval( $atts['limit'] ),
        'post_status'    => 'publish',
        'category_name'  => 'faq',
    );
    
    if ( $atts['category'] ) {
        $args['tag'] = $atts['category'];
    }
    
    $faqs = new WP_Query( $args );
    
    if ( ! $faqs->have_posts() ) {
        return '<p>' . __( 'No FAQs found.', 'menscore-therapy' ) . '</p>';
    }
    
    ob_start();
    ?>
    <div class="therapy-faq-accordion">
        <?php $counter = 0; while ( $faqs->have_posts() ) : $faqs->the_post(); $counter++; ?>
            <div class="faq-item">
                <button class="faq-question" type="button" data-toggle="collapse" data-target="#faq-<?php echo $counter; ?>">
                    <?php the_title(); ?>
                    <span class="faq-toggle">+</span>
                </button>
                <div id="faq-<?php echo $counter; ?>" class="faq-answer collapse">
                    <div class="faq-content">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'therapy_faq', 'menscore_faq_shortcode' );

/**
 * Team Members Shortcode
 * Usage: [therapy_team limit="4"]
 */
function menscore_team_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'limit' => '4',
        'columns' => '2',
    ), $atts, 'therapy_team' );
    
    // Use posts with 'team' category for team members
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => intval( $atts['limit'] ),
        'post_status'    => 'publish',
        'category_name'  => 'team',
    );
    
    $team_members = new WP_Query( $args );
    
    if ( ! $team_members->have_posts() ) {
        return '<p>' . __( 'No team members found.', 'menscore-therapy' ) . '</p>';
    }
    
    ob_start();
    ?>
    <div class="therapy-team-grid columns-<?php echo esc_attr( $atts['columns'] ); ?>">
        <?php while ( $team_members->have_posts() ) : $team_members->the_post(); ?>
            <div class="team-member">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="member-photo">
                        <?php the_post_thumbnail( 'medium' ); ?>
                    </div>
                <?php endif; ?>
                
                <div class="member-info">
                    <h3 class="member-name"><?php the_title(); ?></h3>
                    
                    <div class="member-bio">
                        <?php the_excerpt(); ?>
                    </div>
                    
                    <?php
                    $member_title = get_post_meta( get_the_ID(), '_member_title', true );
                    $member_credentials = get_post_meta( get_the_ID(), '_member_credentials', true );
                    
                    if ( $member_title ) {
                        echo '<span class="member-title">' . esc_html( $member_title ) . '</span>';
                    }
                    
                    if ( $member_credentials ) {
                        echo '<span class="member-credentials">' . esc_html( $member_credentials ) . '</span>';
                    }
                    ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'therapy_team', 'menscore_team_shortcode' );

/**
 * Handle contact form submission
 */
function menscore_handle_contact_form() {
    if ( ! isset( $_POST['therapy_contact_nonce'] ) || ! wp_verify_nonce( $_POST['therapy_contact_nonce'], 'therapy_contact_form' ) ) {
        wp_die( __( 'Security check failed.', 'menscore-therapy' ) );
    }
    
    $name = sanitize_text_field( $_POST['contact_name'] );
    $email = sanitize_email( $_POST['contact_email'] );
    $phone = sanitize_text_field( $_POST['contact_phone'] );
    $service = sanitize_text_field( $_POST['contact_service'] );
    $message = sanitize_textarea_field( $_POST['contact_message'] );
    
    $to = get_option( 'admin_email' );
    $subject = sprintf( __( 'Contact Form Submission from %s', 'menscore-therapy' ), $name );
    $body = sprintf(
        __( "Name: %s\nEmail: %s\nPhone: %s\nService Interest: %s\n\nMessage:\n%s", 'menscore-therapy' ),
        $name, $email, $phone, $service, $message
    );
    
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
    
    if ( wp_mail( $to, $subject, $body, $headers ) ) {
        wp_redirect( add_query_arg( 'contact', 'success', wp_get_referer() ) );
    } else {
        wp_redirect( add_query_arg( 'contact', 'error', wp_get_referer() ) );
    }
    exit;
}
add_action( 'wp', function() {
    if ( isset( $_POST['contact_name'] ) ) {
        menscore_handle_contact_form();
    }
} );