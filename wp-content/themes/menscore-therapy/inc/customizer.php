<?php
/**
 * Theme Customizer for MensCore Therapy Theme
 * 
 * @package MensCoreTherapy
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add customizer settings
 */
function menscore_customize_register( $wp_customize ) {
    
    /**
     * Colors Section
     */
    $wp_customize->add_section( 'menscore_colors', array(
        'title'    => __( 'Theme Colors', 'menscore-therapy' ),
        'priority' => 30,
    ) );
    
    // Primary Color
    $wp_customize->add_setting( 'menscore_primary_color', array(
        'default'           => '#3498db',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menscore_primary_color', array(
        'label'   => __( 'Primary Color', 'menscore-therapy' ),
        'section' => 'menscore_colors',
    ) ) );
    
    // Secondary Color
    $wp_customize->add_setting( 'menscore_secondary_color', array(
        'default'           => '#2c3e50',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menscore_secondary_color', array(
        'label'   => __( 'Secondary Color', 'menscore-therapy' ),
        'section' => 'menscore_colors',
    ) ) );
    
    /**
     * Contact Information Section
     */
    $wp_customize->add_section( 'menscore_contact', array(
        'title'    => __( 'Contact Information', 'menscore-therapy' ),
        'priority' => 40,
    ) );
    
    // Phone Number
    $wp_customize->add_setting( 'menscore_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'menscore_phone', array(
        'label'   => __( 'Phone Number', 'menscore-therapy' ),
        'section' => 'menscore_contact',
        'type'    => 'text',
    ) );
    
    // Email Address
    $wp_customize->add_setting( 'menscore_email', array(
        'default'           => get_option( 'admin_email' ),
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'menscore_email', array(
        'label'   => __( 'Email Address', 'menscore-therapy' ),
        'section' => 'menscore_contact',
        'type'    => 'email',
    ) );
    
    // Address
    $wp_customize->add_setting( 'menscore_address', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'menscore_address', array(
        'label'   => __( 'Business Address', 'menscore-therapy' ),
        'section' => 'menscore_contact',
        'type'    => 'textarea',
    ) );
    
    /**
     * Business Hours Section
     */
    $wp_customize->add_section( 'menscore_hours', array(
        'title'    => __( 'Business Hours', 'menscore-therapy' ),
        'priority' => 50,
    ) );
    
    $days = array(
        'monday'    => __( 'Monday', 'menscore-therapy' ),
        'tuesday'   => __( 'Tuesday', 'menscore-therapy' ),
        'wednesday' => __( 'Wednesday', 'menscore-therapy' ),
        'thursday'  => __( 'Thursday', 'menscore-therapy' ),
        'friday'    => __( 'Friday', 'menscore-therapy' ),
        'saturday'  => __( 'Saturday', 'menscore-therapy' ),
        'sunday'    => __( 'Sunday', 'menscore-therapy' ),
    );
    
    foreach ( $days as $day => $label ) {
        $default = ( $day === 'saturday' || $day === 'sunday' ) ? 'Closed' : '9:00 AM - 5:00 PM';
        
        $wp_customize->add_setting( 'menscore_hours_' . $day, array(
            'default'           => $default,
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );
        
        $wp_customize->add_control( 'menscore_hours_' . $day, array(
            'label'   => $label,
            'section' => 'menscore_hours',
            'type'    => 'text',
        ) );
    }
    
    /**
     * Call-to-Action Section
     */
    $wp_customize->add_section( 'menscore_cta', array(
        'title'    => __( 'Call-to-Action Section', 'menscore-therapy' ),
        'priority' => 60,
    ) );
    
    // CTA Title
    $wp_customize->add_setting( 'menscore_cta_title', array(
        'default'           => __( 'Ready to Start Your Wellness Journey?', 'menscore-therapy' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'menscore_cta_title', array(
        'label'   => __( 'CTA Title', 'menscore-therapy' ),
        'section' => 'menscore_cta',
        'type'    => 'text',
    ) );
    
    // CTA Text
    $wp_customize->add_setting( 'menscore_cta_text', array(
        'default'           => __( 'Take the first step towards better health and wellness. Contact us today to schedule your consultation.', 'menscore-therapy' ),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'menscore_cta_text', array(
        'label'   => __( 'CTA Text', 'menscore-therapy' ),
        'section' => 'menscore_cta',
        'type'    => 'textarea',
    ) );
    
    // CTA Button Text
    $wp_customize->add_setting( 'menscore_cta_button_text', array(
        'default'           => __( 'Get Started', 'menscore-therapy' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'menscore_cta_button_text', array(
        'label'   => __( 'CTA Button Text', 'menscore-therapy' ),
        'section' => 'menscore_cta',
        'type'    => 'text',
    ) );
    
    // CTA Button URL
    $wp_customize->add_setting( 'menscore_cta_button_url', array(
        'default'           => '#contact',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'menscore_cta_button_url', array(
        'label'   => __( 'CTA Button URL', 'menscore-therapy' ),
        'section' => 'menscore_cta',
        'type'    => 'url',
    ) );
    
    /**
     * Social Media Section
     */
    $wp_customize->add_section( 'menscore_social', array(
        'title'    => __( 'Social Media Links', 'menscore-therapy' ),
        'priority' => 70,
    ) );
    
    $social_platforms = array(
        'facebook'  => __( 'Facebook URL', 'menscore-therapy' ),
        'twitter'   => __( 'Twitter URL', 'menscore-therapy' ),
        'instagram' => __( 'Instagram URL', 'menscore-therapy' ),
        'linkedin'  => __( 'LinkedIn URL', 'menscore-therapy' ),
        'youtube'   => __( 'YouTube URL', 'menscore-therapy' ),
    );
    
    foreach ( $social_platforms as $platform => $label ) {
        $wp_customize->add_setting( 'menscore_social_' . $platform, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );
        
        $wp_customize->add_control( 'menscore_social_' . $platform, array(
            'label'   => $label,
            'section' => 'menscore_social',
            'type'    => 'url',
        ) );
    }
    
    /**
     * Homepage Settings Section
     */
    $wp_customize->add_section( 'menscore_homepage', array(
        'title'    => __( 'Homepage Settings', 'menscore-therapy' ),
        'priority' => 80,
    ) );
    
    // Hero Section Title
    $wp_customize->add_setting( 'menscore_hero_title', array(
        'default'           => __( 'Your Journey to Wellness Starts Here', 'menscore-therapy' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'menscore_hero_title', array(
        'label'   => __( 'Hero Section Title', 'menscore-therapy' ),
        'section' => 'menscore_homepage',
        'type'    => 'text',
    ) );
    
    // Hero Section Subtitle
    $wp_customize->add_setting( 'menscore_hero_subtitle', array(
        'default'           => __( 'Professional therapy services for men\'s health and wellness', 'menscore-therapy' ),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'menscore_hero_subtitle', array(
        'label'   => __( 'Hero Section Subtitle', 'menscore-therapy' ),
        'section' => 'menscore_homepage',
        'type'    => 'textarea',
    ) );
    
    // Hero Background Image
    $wp_customize->add_setting( 'menscore_hero_background', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'menscore_hero_background', array(
        'label'   => __( 'Hero Background Image', 'menscore-therapy' ),
        'section' => 'menscore_homepage',
    ) ) );
    
    /**
     * SEO Settings Section
     */
    $wp_customize->add_section( 'menscore_seo', array(
        'title'    => __( 'SEO Settings', 'menscore-therapy' ),
        'priority' => 90,
    ) );
    
    // Meta Description
    $wp_customize->add_setting( 'menscore_meta_description', array(
        'default'           => __( 'Professional men\'s health and wellness therapy services. Expert care for mental health, relationship counseling, and personal development.', 'menscore-therapy' ),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'menscore_meta_description', array(
        'label'       => __( 'Site Meta Description', 'menscore-therapy' ),
        'description' => __( 'Used for search engine results and social sharing.', 'menscore-therapy' ),
        'section'     => 'menscore_seo',
        'type'        => 'textarea',
    ) );
    
    // Keywords
    $wp_customize->add_setting( 'menscore_meta_keywords', array(
        'default'           => 'mens therapy, wellness, mental health, counseling, mens health',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'menscore_meta_keywords', array(
        'label'       => __( 'Meta Keywords', 'menscore-therapy' ),
        'description' => __( 'Comma-separated keywords for SEO.', 'menscore-therapy' ),
        'section'     => 'menscore_seo',
        'type'        => 'text',
    ) );
}
add_action( 'customize_register', 'menscore_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function menscore_customize_preview_js() {
    wp_enqueue_script( 'menscore-customizer', 
        MENSCORE_THEME_URL . '/assets/js/customizer.js', 
        array( 'customize-preview' ), 
        MENSCORE_VERSION, 
        true 
    );
}
add_action( 'customize_preview_init', 'menscore_customize_preview_js' );

/**
 * Add meta tags to head based on customizer settings
 */
function menscore_add_meta_tags() {
    $meta_description = get_theme_mod( 'menscore_meta_description' );
    $meta_keywords = get_theme_mod( 'menscore_meta_keywords' );
    
    if ( $meta_description && is_front_page() ) {
        echo '<meta name="description" content="' . esc_attr( $meta_description ) . '">' . "\n";
    }
    
    if ( $meta_keywords && is_front_page() ) {
        echo '<meta name="keywords" content="' . esc_attr( $meta_keywords ) . '">' . "\n";
    }
    
    // Open Graph tags
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
    echo '<meta property="og:type" content="website">' . "\n";
    
    if ( is_front_page() && $meta_description ) {
        echo '<meta property="og:description" content="' . esc_attr( $meta_description ) . '">' . "\n";
    }
    
    // Twitter Card tags
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:site" content="@' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
}
add_action( 'wp_head', 'menscore_add_meta_tags' );