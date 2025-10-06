<?php
/**
 * Public Class for MensCore Core Plugin
 * 
 * @package MensCoreCore
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * MensCore Public Class
 */
class MensCore_Public {
    
    /**
     * Instance
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts' ) );
        add_action( 'wp_head', array( $this, 'add_structured_data' ) );
    }
    
    /**
     * Enqueue public scripts
     */
    public function enqueue_public_scripts() {
        wp_enqueue_script( 'menscore-public', MENSCORE_CORE_PLUGIN_URL . 'assets/js/public.js', array( 'jquery' ), MENSCORE_CORE_VERSION, true );
        
        wp_localize_script( 'menscore-public', 'menscore_ajax', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'menscore_ajax_nonce' ),
        ) );
    }
    
    /**
     * Add structured data
     */
    public function add_structured_data() {
        if ( is_front_page() ) {
            $this->add_organization_schema();
        }
        
        if ( is_singular( 'services' ) ) {
            $this->add_service_schema();
        }
    }
    
    /**
     * Add organization schema
     */
    private function add_organization_schema() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo( 'name' ),
            'url' => home_url(),
            'description' => get_bloginfo( 'description' ),
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
    }
    
    /**
     * Add service schema
     */
    private function add_service_schema() {
        global $post;
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => get_the_title(),
            'description' => get_the_excerpt(),
            'provider' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo( 'name' ),
            ),
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
    }
}