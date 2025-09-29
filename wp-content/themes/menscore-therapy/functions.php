<?php
/**
 * MensCore Therapy Theme Functions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup
 */
function menscore_theme_setup() {
    // Add theme support for various WordPress features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('custom-logo');
    add_theme_support('responsive-embeds');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'menscore-therapy'),
        'footer' => __('Footer Menu', 'menscore-therapy'),
    ));
}
add_action('after_setup_theme', 'menscore_theme_setup');

/**
 * Enqueue scripts and styles
 */
function menscore_scripts() {
    // Enqueue theme stylesheet
    wp_enqueue_style('menscore-style', get_stylesheet_uri(), array(), '1.0');
    
    // Enqueue custom JavaScript
    wp_enqueue_script('menscore-script', get_template_directory_uri() . '/assets/js/theme.js', array('jquery'), '1.0', true);
    
    // Enqueue Google Fonts (backup if CSS import fails)
    wp_enqueue_style('menscore-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Montserrat+Alternates:wght@300;400;500;600;700&display=swap', array(), null);
}
add_action('wp_enqueue_scripts', 'menscore_scripts');

/**
 * Register widget areas
 */
function menscore_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'menscore-therapy'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'menscore-therapy'),
        'before_widget' => '<div id="%1$s" class="widget %2$s neomorphic">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 1', 'menscore-therapy'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in the first footer column.', 'menscore-therapy'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 2', 'menscore-therapy'),
        'id'            => 'footer-2',
        'description'   => __('Add widgets here to appear in the second footer column.', 'menscore-therapy'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 3', 'menscore-therapy'),
        'id'            => 'footer-3',
        'description'   => __('Add widgets here to appear in the third footer column.', 'menscore-therapy'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'menscore_widgets_init');

/**
 * Custom theme features
 */

// Add body classes for styling
function menscore_body_classes($classes) {
    if (is_front_page()) {
        $classes[] = 'home-page';
    }
    return $classes;
}
add_filter('body_class', 'menscore_body_classes');

// Add theme customizer options
function menscore_customize_register($wp_customize) {
    // Hero section settings
    $wp_customize->add_section('hero_section', array(
        'title' => __('Hero Section', 'menscore-therapy'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('hero_title', array(
        'default' => 'Men\'s Core Therapy',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hero_title', array(
        'label' => __('Hero Title', 'menscore-therapy'),
        'section' => 'hero_section',
        'type' => 'text',
    ));

    $wp_customize->add_setting('hero_subtitle', array(
        'default' => 'Transforming Lives Through Holistic Wellness',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hero_subtitle', array(
        'label' => __('Hero Subtitle', 'menscore-therapy'),
        'section' => 'hero_section',
        'type' => 'text',
    ));

    $wp_customize->add_setting('hero_bg_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_bg_image', array(
        'label' => __('Hero Background Image', 'menscore-therapy'),
        'section' => 'hero_section',
    )));
}
add_action('customize_register', 'menscore_customize_register');

/**
 * Get customizer values with fallbacks
 */
function get_theme_option($option, $default = '') {
    return get_theme_mod($option, $default);
}

/**
 * Add admin styles for better theme management
 */
function menscore_admin_styles() {
    wp_enqueue_style('menscore-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), '1.0');
}
add_action('admin_enqueue_scripts', 'menscore_admin_styles');

/**
 * Security enhancements
 */
// Remove WordPress version from head
remove_action('wp_head', 'wp_generator');

// Remove unnecessary WordPress features
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

/**
 * Performance optimizations
 */
// Remove query strings from static resources
function menscore_remove_query_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'menscore_remove_query_strings', 10, 1);
add_filter('script_loader_src', 'menscore_remove_query_strings', 10, 1);

// Disable emojis
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');