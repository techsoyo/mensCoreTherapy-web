<?php
/**
 * MensCore Therapy Theme Functions and Definitions
 * 
 * @package MensCoreTherapy
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme Constants
 */
define( 'MENSCORE_VERSION', '1.0.0' );
define( 'MENSCORE_THEME_DIR', get_template_directory() );
define( 'MENSCORE_THEME_URL', get_template_directory_uri() );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function menscore_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'menscore-therapy' ),
        'footer'  => esc_html__( 'Footer Menu', 'menscore-therapy' ),
    ) );

    // Switch default core markup for search form, comment form, and comments
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ) );

    // Add support for wide and full alignment
    add_theme_support( 'align-wide' );

    // Add support for responsive embedded content
    add_theme_support( 'responsive-embeds' );

    // Add support for custom background
    add_theme_support( 'custom-background', array(
        'default-color' => 'ffffff',
    ) );

    // Set content width
    $GLOBALS['content_width'] = 800;
}
add_action( 'after_setup_theme', 'menscore_setup' );

/**
 * Enqueue scripts and styles.
 */
function menscore_scripts() {
    // Main stylesheet
    wp_enqueue_style( 
        'menscore-style', 
        get_stylesheet_uri(), 
        array(), 
        MENSCORE_VERSION 
    );

    // Custom CSS file
    wp_enqueue_style(
        'menscore-custom',
        MENSCORE_THEME_URL . '/assets/css/custom.css',
        array( 'menscore-style' ),
        MENSCORE_VERSION
    );

    // Main JavaScript file
    wp_enqueue_script(
        'menscore-script',
        MENSCORE_THEME_URL . '/assets/js/main.js',
        array( 'jquery' ),
        MENSCORE_VERSION,
        true
    );

    // Localize script for AJAX
    wp_localize_script( 'menscore-script', 'menscore_ajax', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'menscore_nonce' ),
    ) );

    // Comment reply script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'menscore_scripts' );

/**
 * Register widget areas.
 */
function menscore_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Primary Sidebar', 'menscore-therapy' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here.', 'menscore-therapy' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 1', 'menscore-therapy' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Add widgets here.', 'menscore-therapy' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 2', 'menscore-therapy' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Add widgets here.', 'menscore-therapy' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 3', 'menscore-therapy' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Add widgets here.', 'menscore-therapy' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'menscore_widgets_init' );

/**
 * Security Enhancements
 */

// Remove WordPress version from head
remove_action( 'wp_head', 'wp_generator' );

// Remove Windows Live Writer manifest link
remove_action( 'wp_head', 'wlwmanifest_link' );

// Remove RSD link
remove_action( 'wp_head', 'rsd_link' );

// Disable XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );

// Remove WordPress version from RSS feeds
function menscore_remove_version_from_rss() {
    return '';
}
add_filter( 'the_generator', 'menscore_remove_version_from_rss' );

// Hide WordPress version in styles and scripts
function menscore_remove_wp_version_strings( $src ) {
    if ( strpos( $src, 'ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}
add_filter( 'style_loader_src', 'menscore_remove_wp_version_strings', 9999 );
add_filter( 'script_loader_src', 'menscore_remove_wp_version_strings', 9999 );

/**
 * Performance Optimizations
 */

// Remove query strings from static resources
function menscore_remove_script_version( $src ) {
    $parts = explode( '?ver', $src );
    return $parts[0];
}
add_filter( 'script_loader_src', 'menscore_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'menscore_remove_script_version', 15, 1 );

// Defer parsing of JavaScript
function menscore_defer_parsing_of_js( $url ) {
    if ( is_admin() ) return $url;
    if ( FALSE === strpos( $url, '.js' ) ) return $url;
    if ( strpos( $url, 'jquery.js' ) ) return $url;
    return str_replace( ' src', ' defer src', $url );
}
add_filter( 'script_loader_tag', 'menscore_defer_parsing_of_js', 10 );

// Remove unnecessary meta tags and links
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

/**
 * Custom Post Types and Taxonomies
 */
function menscore_register_post_types() {
    // Register Services post type
    register_post_type( 'services', array(
        'labels' => array(
            'name'          => __( 'Services', 'menscore-therapy' ),
            'singular_name' => __( 'Service', 'menscore-therapy' ),
        ),
        'public'        => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-heart',
        'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'rewrite'       => array( 'slug' => 'services' ),
    ) );

    // Register Testimonials post type
    register_post_type( 'testimonials', array(
        'labels' => array(
            'name'          => __( 'Testimonials', 'menscore-therapy' ),
            'singular_name' => __( 'Testimonial', 'menscore-therapy' ),
        ),
        'public'        => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-format-quote',
        'supports'      => array( 'title', 'editor', 'thumbnail' ),
        'rewrite'       => array( 'slug' => 'testimonials' ),
    ) );
}
add_action( 'init', 'menscore_register_post_types' );

/**
 * Include additional theme files
 */
require_once MENSCORE_THEME_DIR . '/inc/custom-hooks.php';
require_once MENSCORE_THEME_DIR . '/inc/shortcodes.php';
require_once MENSCORE_THEME_DIR . '/inc/customizer.php';
require_once MENSCORE_THEME_DIR . '/inc/template-functions.php';

/**
 * Theme Customization Hook
 */
do_action( 'menscore_theme_loaded' );