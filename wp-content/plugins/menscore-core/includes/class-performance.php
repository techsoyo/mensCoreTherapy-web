<?php
/**
 * Performance Optimization Class for MensCore Core Plugin
 * 
 * @package MensCoreCore
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * MensCore Performance Class
 */
class MensCore_Performance {
    
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
        if ( get_option( 'menscore_performance_enabled', 1 ) ) {
            add_action( 'init', array( $this, 'init_performance_features' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'optimize_scripts_styles' ), 999 );
            add_action( 'wp_head', array( $this, 'add_preconnect_links' ), 1 );
            add_action( 'wp_head', array( $this, 'add_dns_prefetch' ), 1 );
            add_filter( 'script_loader_tag', array( $this, 'add_async_defer_attributes' ), 10, 2 );
            add_action( 'template_redirect', array( $this, 'enable_gzip_compression' ) );
            add_action( 'wp', array( $this, 'setup_caching' ) );
            add_action( 'menscore_daily_cleanup', array( $this, 'cleanup_expired_transients' ) );
        }
    }
    
    /**
     * Initialize performance features
     */
    public function init_performance_features() {
        // Remove unnecessary WordPress features
        $this->remove_unnecessary_features();
        
        // Optimize database queries
        $this->optimize_database_queries();
        
        // Clean up wp_head
        $this->cleanup_wp_head();
        
        // Optimize images
        add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_lazy_loading' ), 10, 3 );
    }
    
    /**
     * Remove unnecessary WordPress features
     */
    private function remove_unnecessary_features() {
        // Remove emoji support
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        
        // Disable embeds
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
        remove_action( 'wp_head', 'wp_oembed_add_host_js' );
        
        // Remove shortlink from head
        remove_action( 'wp_head', 'wp_shortlink_wp_head' );
        
        // Remove adjacent posts links
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
        
        // Remove REST API links
        remove_action( 'wp_head', 'rest_output_link_wp_head' );
        
        // Disable block editor styles on frontend
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'wc-blocks-style' );
    }
    
    /**
     * Optimize database queries
     */
    private function optimize_database_queries() {
        // Limit post revisions
        if ( ! defined( 'WP_POST_REVISIONS' ) ) {
            define( 'WP_POST_REVISIONS', 3 );
        }
        
        // Set autosave interval
        if ( ! defined( 'AUTOSAVE_INTERVAL' ) ) {
            define( 'AUTOSAVE_INTERVAL', 300 );
        }
        
        // Enable object caching if available
        if ( ! defined( 'WP_CACHE' ) && function_exists( 'wp_cache_init' ) ) {
            define( 'WP_CACHE', true );
        }
    }
    
    /**
     * Clean up wp_head
     */
    private function cleanup_wp_head() {
        // Remove unnecessary meta tags
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'wp_generator' );
        
        // Remove feed links if not needed
        if ( ! get_option( 'menscore_enable_feeds', 0 ) ) {
            remove_action( 'wp_head', 'feed_links', 2 );
            remove_action( 'wp_head', 'feed_links_extra', 3 );
        }
    }
    
    /**
     * Optimize scripts and styles
     */
    public function optimize_scripts_styles() {
        // Remove query strings from static resources
        add_filter( 'script_loader_src', array( $this, 'remove_query_strings' ), 15, 1 );
        add_filter( 'style_loader_src', array( $this, 'remove_query_strings' ), 15, 1 );
        
        // Combine and minify CSS (basic implementation)
        if ( get_option( 'menscore_combine_css', 1 ) ) {
            add_action( 'wp_print_styles', array( $this, 'combine_css_files' ), 999 );
        }
    }
    
    /**
     * Remove query strings from static resources
     */
    public function remove_query_strings( $src ) {
        if ( strpos( $src, '?ver=' ) ) {
            $src = remove_query_arg( 'ver', $src );
        }
        return $src;
    }
    
    /**
     * Add async/defer attributes to scripts
     */
    public function add_async_defer_attributes( $tag, $handle ) {
        // Skip admin and jQuery
        if ( is_admin() || strpos( $handle, 'jquery' ) !== false ) {
            return $tag;
        }
        
        // Scripts to defer
        $defer_scripts = array(
            'menscore-script',
            'comment-reply',
            'wp-embed',
        );
        
        // Scripts to load async
        $async_scripts = array(
            'google-analytics',
            'gtag',
        );
        
        if ( in_array( $handle, $defer_scripts ) ) {
            $tag = str_replace( ' src', ' defer src', $tag );
        } elseif ( in_array( $handle, $async_scripts ) ) {
            $tag = str_replace( ' src', ' async src', $tag );
        }
        
        return $tag;
    }
    
    /**
     * Add preconnect links
     */
    public function add_preconnect_links() {
        $preconnect_domains = array(
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com',
            'https://www.google-analytics.com',
        );
        
        foreach ( $preconnect_domains as $domain ) {
            echo '<link rel="preconnect" href="' . esc_url( $domain ) . '">' . "\n";
        }
    }
    
    /**
     * Add DNS prefetch
     */
    public function add_dns_prefetch() {
        $prefetch_domains = array(
            '//fonts.googleapis.com',
            '//fonts.gstatic.com',
            '//www.google-analytics.com',
            '//www.googletagmanager.com',
        );
        
        foreach ( $prefetch_domains as $domain ) {
            echo '<link rel="dns-prefetch" href="' . esc_url( $domain ) . '">' . "\n";
        }
    }
    
    /**
     * Enable GZIP compression
     */
    public function enable_gzip_compression() {
        if ( ! headers_sent() && ! ob_get_level() && ! ini_get( 'zlib.output_compression' ) ) {
            if ( extension_loaded( 'zlib' ) && ! ini_get( 'zlib.output_compression' ) ) {
                ob_start( 'ob_gzhandler' );
            }
        }
    }
    
    /**
     * Setup caching
     */
    public function setup_caching() {
        // Page caching for non-logged-in users
        if ( ! is_user_logged_in() && ! is_admin() && ! is_customize_preview() ) {
            $this->setup_page_caching();
        }
        
        // Object caching for database queries
        $this->setup_object_caching();
    }
    
    /**
     * Setup page caching
     */
    private function setup_page_caching() {
        $cache_key = $this->get_page_cache_key();
        $cached_page = get_transient( $cache_key );
        
        if ( $cached_page && ! WP_DEBUG ) {
            echo $cached_page;
            exit;
        }
        
        // Start output buffering to cache the page
        ob_start( array( $this, 'cache_page_output' ) );
    }
    
    /**
     * Cache page output
     */
    public function cache_page_output( $content ) {
        if ( ! is_404() && ! is_search() && ! is_feed() ) {
            $cache_key = $this->get_page_cache_key();
            $cache_duration = $this->get_cache_duration();
            
            set_transient( $cache_key, $content, $cache_duration );
        }
        
        return $content;
    }
    
    /**
     * Get page cache key
     */
    private function get_page_cache_key() {
        global $wp;
        
        $key = 'menscore_page_cache_' . md5( home_url( $wp->request ) );
        
        // Add mobile detection
        if ( wp_is_mobile() ) {
            $key .= '_mobile';
        }
        
        return $key;
    }
    
    /**
     * Get cache duration
     */
    private function get_cache_duration() {
        if ( is_front_page() ) {
            return 4 * HOUR_IN_SECONDS; // 4 hours for homepage
        } elseif ( is_page() ) {
            return 12 * HOUR_IN_SECONDS; // 12 hours for pages
        } elseif ( is_single() ) {
            return 6 * HOUR_IN_SECONDS; // 6 hours for posts
        }
        
        return 2 * HOUR_IN_SECONDS; // 2 hours default
    }
    
    /**
     * Setup object caching
     */
    private function setup_object_caching() {
        // Cache expensive queries
        add_filter( 'posts_pre_query', array( $this, 'cache_expensive_queries' ), 10, 2 );
    }
    
    /**
     * Cache expensive queries
     */
    public function cache_expensive_queries( $posts, $query ) {
        // Only cache main queries that are not in admin
        if ( ! $query->is_main_query() || is_admin() ) {
            return $posts;
        }
        
        $cache_key = 'menscore_query_' . md5( serialize( $query->query_vars ) );
        $cached_posts = get_transient( $cache_key );
        
        if ( $cached_posts !== false && ! WP_DEBUG ) {
            return $cached_posts;
        }
        
        return $posts; // Let WordPress handle the query normally
    }
    
    /**
     * Add lazy loading to images
     */
    public function add_lazy_loading( $attr, $attachment, $size ) {
        if ( ! is_admin() && ! is_feed() ) {
            $attr['loading'] = 'lazy';
        }
        
        return $attr;
    }
    
    /**
     * Combine CSS files
     */
    public function combine_css_files() {
        global $wp_styles;
        
        if ( is_admin() || ! isset( $wp_styles->queue ) ) {
            return;
        }
        
        $combined_css = '';
        $combined_handles = array();
        
        foreach ( $wp_styles->queue as $handle ) {
            if ( isset( $wp_styles->registered[ $handle ] ) ) {
                $style = $wp_styles->registered[ $handle ];
                
                // Only combine local CSS files
                if ( strpos( $style->src, home_url() ) === 0 ) {
                    $css_file = str_replace( home_url(), ABSPATH, $style->src );
                    
                    if ( file_exists( $css_file ) ) {
                        $combined_css .= file_get_contents( $css_file ) . "\n";
                        $combined_handles[] = $handle;
                    }
                }
            }
        }
        
        if ( ! empty( $combined_css ) ) {
            // Create combined CSS file
            $upload_dir = wp_upload_dir();
            $css_dir = $upload_dir['basedir'] . '/menscore-cache/';
            
            if ( ! file_exists( $css_dir ) ) {
                wp_mkdir_p( $css_dir );
            }
            
            $css_hash = md5( $combined_css );
            $css_file = $css_dir . 'combined-' . $css_hash . '.css';
            $css_url = $upload_dir['baseurl'] . '/menscore-cache/combined-' . $css_hash . '.css';
            
            if ( ! file_exists( $css_file ) ) {
                file_put_contents( $css_file, $this->minify_css( $combined_css ) );
            }
            
            // Dequeue combined styles and enqueue the combined file
            foreach ( $combined_handles as $handle ) {
                wp_dequeue_style( $handle );
            }
            
            wp_enqueue_style( 'menscore-combined', $css_url, array(), MENSCORE_CORE_VERSION );
        }
    }
    
    /**
     * Minify CSS
     */
    private function minify_css( $css ) {
        // Remove comments
        $css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
        
        // Remove unnecessary whitespace
        $css = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $css );
        $css = preg_replace( '/\s+/', ' ', $css );
        
        // Remove space around specific characters
        $css = str_replace( array( ' {', '{ ', ' }', '} ', '; ', ' ;', ', ', ' ,' ), 
                           array( '{', '{', '}', '}', ';', ';', ',', ',' ), $css );
        
        return trim( $css );
    }
    
    /**
     * Clear page cache
     */
    public function clear_page_cache() {
        global $wpdb;
        
        $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_menscore_page_cache_%'" );
        $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_menscore_page_cache_%'" );
    }
    
    /**
     * Clear query cache
     */
    public function clear_query_cache() {
        global $wpdb;
        
        $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_menscore_query_%'" );
        $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_menscore_query_%'" );
    }
    
    /**
     * Cleanup expired transients
     */
    public function cleanup_expired_transients() {
        global $wpdb;
        
        // Delete expired transients
        $wpdb->query( 
            "DELETE a, b FROM {$wpdb->options} a, {$wpdb->options} b 
             WHERE a.option_name LIKE '_transient_%' 
             AND a.option_name NOT LIKE '_transient_timeout_%' 
             AND b.option_name = CONCAT('_transient_timeout_', SUBSTRING(a.option_name, 12)) 
             AND b.option_value < UNIX_TIMESTAMP()"
        );
    }
    
    /**
     * Get performance statistics
     */
    public function get_performance_stats() {
        global $wpdb;
        
        $stats = array();
        
        // Page load time (if available)
        if ( defined( 'WP_START_TIMESTAMP' ) ) {
            $stats['page_load_time'] = microtime( true ) - WP_START_TIMESTAMP;
        }
        
        // Database queries
        $stats['database_queries'] = get_num_queries();
        
        // Memory usage
        $stats['memory_usage'] = memory_get_usage( true );
        $stats['memory_peak'] = memory_get_peak_usage( true );
        
        // Cache statistics
        $cache_count = $wpdb->get_var( 
            "SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name LIKE '_transient_menscore_%'"
        );
        $stats['cached_items'] = intval( $cache_count );
        
        return $stats;
    }
}