<?php
/**
 * Dynamic Sitemap Class for MensCore Core Plugin
 * 
 * @package MensCoreCore
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * MensCore Sitemap Class
 */
class MensCore_Sitemap {
    
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
        if ( get_option( 'menscore_sitemap_enabled', 1 ) ) {
            add_action( 'init', array( $this, 'add_rewrite_rules' ) );
            add_action( 'template_redirect', array( $this, 'handle_sitemap_request' ) );
            add_action( 'wp_head', array( $this, 'add_sitemap_meta' ) );
            add_action( 'save_post', array( $this, 'clear_sitemap_cache' ) );
            add_action( 'delete_post', array( $this, 'clear_sitemap_cache' ) );
            add_action( 'menscore_daily_cleanup', array( $this, 'clear_sitemap_cache' ) );
        }
    }
    
    /**
     * Add rewrite rules for sitemap
     */
    public function add_rewrite_rules() {
        add_rewrite_rule( '^sitemap\.xml$', 'index.php?menscore_sitemap=main', 'top' );
        add_rewrite_rule( '^sitemap-([^/]+?)\.xml$', 'index.php?menscore_sitemap=$matches[1]', 'top' );
        
        // Add query vars
        add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
    }
    
    /**
     * Add query vars
     */
    public function add_query_vars( $vars ) {
        $vars[] = 'menscore_sitemap';
        return $vars;
    }
    
    /**
     * Handle sitemap requests
     */
    public function handle_sitemap_request() {
        $sitemap_type = get_query_var( 'menscore_sitemap' );
        
        if ( $sitemap_type ) {
            $this->generate_sitemap( $sitemap_type );
            exit;
        }
    }
    
    /**
     * Generate sitemap
     */
    private function generate_sitemap( $type ) {
        // Set proper headers
        header( 'Content-Type: application/xml; charset=UTF-8' );
        
        // Check cache first
        $cache_key = 'menscore_sitemap_' . $type;
        $cached_sitemap = get_transient( $cache_key );
        
        if ( $cached_sitemap && ! WP_DEBUG ) {
            echo $cached_sitemap;
            return;
        }
        
        // Generate sitemap based on type
        switch ( $type ) {
            case 'main':
                $sitemap = $this->generate_sitemap_index();
                break;
            case 'posts':
                $sitemap = $this->generate_posts_sitemap();
                break;
            case 'pages':
                $sitemap = $this->generate_pages_sitemap();
                break;
            case 'services':
                $sitemap = $this->generate_services_sitemap();
                break;
            case 'testimonials':
                $sitemap = $this->generate_testimonials_sitemap();
                break;
            case 'categories':
                $sitemap = $this->generate_categories_sitemap();
                break;
            default:
                $sitemap = $this->generate_404_sitemap();
                break;
        }
        
        // Cache sitemap for 24 hours
        set_transient( $cache_key, $sitemap, DAY_IN_SECONDS );
        
        echo $sitemap;
    }
    
    /**
     * Generate sitemap index
     */
    private function generate_sitemap_index() {
        $lastmod = $this->get_last_modified_date();
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Add sub-sitemaps
        $sitemaps = array(
            'posts' => __( 'Posts', 'menscore-core' ),
            'pages' => __( 'Pages', 'menscore-core' ),
            'services' => __( 'Services', 'menscore-core' ),
            'testimonials' => __( 'Testimonials', 'menscore-core' ),
            'categories' => __( 'Categories', 'menscore-core' ),
        );
        
        foreach ( $sitemaps as $type => $name ) {
            $xml .= "\t<sitemap>\n";
            $xml .= "\t\t<loc>" . esc_url( home_url( "/sitemap-{$type}.xml" ) ) . "</loc>\n";
            $xml .= "\t\t<lastmod>" . $lastmod . "</lastmod>\n";
            $xml .= "\t</sitemap>\n";
        }
        
        $xml .= '</sitemapindex>';
        
        return $xml;
    }
    
    /**
     * Generate posts sitemap
     */
    private function generate_posts_sitemap() {
        $posts = get_posts( array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'modified',
            'order' => 'DESC',
        ) );
        
        return $this->generate_urlset_xml( $posts, 'post' );
    }
    
    /**
     * Generate pages sitemap
     */
    private function generate_pages_sitemap() {
        $pages = get_posts( array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'modified',
            'order' => 'DESC',
        ) );
        
        return $this->generate_urlset_xml( $pages, 'page' );
    }
    
    /**
     * Generate services sitemap
     */
    private function generate_services_sitemap() {
        $services = get_posts( array(
            'post_type' => 'services',
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'modified',
            'order' => 'DESC',
        ) );
        
        return $this->generate_urlset_xml( $services, 'services' );
    }
    
    /**
     * Generate testimonials sitemap
     */
    private function generate_testimonials_sitemap() {
        $testimonials = get_posts( array(
            'post_type' => 'testimonials',
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'modified',
            'order' => 'DESC',
        ) );
        
        return $this->generate_urlset_xml( $testimonials, 'testimonials' );
    }
    
    /**
     * Generate categories sitemap
     */
    private function generate_categories_sitemap() {
        $categories = get_categories( array(
            'hide_empty' => true,
            'exclude' => array( 1 ), // Exclude uncategorized
        ) );
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ( $categories as $category ) {
            $xml .= "\t<url>\n";
            $xml .= "\t\t<loc>" . esc_url( get_category_link( $category->term_id ) ) . "</loc>\n";
            $xml .= "\t\t<lastmod>" . $this->get_term_last_modified( $category->term_id ) . "</lastmod>\n";
            $xml .= "\t\t<changefreq>weekly</changefreq>\n";
            $xml .= "\t\t<priority>0.6</priority>\n";
            $xml .= "\t</url>\n";
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }
    
    /**
     * Generate 404 sitemap
     */
    private function generate_404_sitemap() {
        header( 'HTTP/1.0 404 Not Found' );
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<!-- Sitemap not found -->' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $xml .= '</urlset>';
        
        return $xml;
    }
    
    /**
     * Generate URL set XML
     */
    private function generate_urlset_xml( $posts, $post_type ) {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ( $posts as $post ) {
            $priority = $this->get_post_priority( $post, $post_type );
            $changefreq = $this->get_post_changefreq( $post, $post_type );
            
            $xml .= "\t<url>\n";
            $xml .= "\t\t<loc>" . esc_url( get_permalink( $post->ID ) ) . "</loc>\n";
            $xml .= "\t\t<lastmod>" . mysql2date( 'c', $post->post_modified_gmt ) . "</lastmod>\n";
            $xml .= "\t\t<changefreq>{$changefreq}</changefreq>\n";
            $xml .= "\t\t<priority>{$priority}</priority>\n";
            $xml .= "\t</url>\n";
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }
    
    /**
     * Get post priority
     */
    private function get_post_priority( $post, $post_type ) {
        switch ( $post_type ) {
            case 'page':
                // Homepage gets highest priority
                if ( $post->ID == get_option( 'page_on_front' ) ) {
                    return '1.0';
                }
                // Important pages
                if ( in_array( $post->post_name, array( 'about', 'services', 'contact' ) ) ) {
                    return '0.9';
                }
                return '0.8';
                
            case 'services':
                // Featured services get higher priority
                if ( get_post_meta( $post->ID, '_service_featured', true ) ) {
                    return '0.9';
                }
                return '0.8';
                
            case 'post':
                // Recent posts get higher priority
                $days_old = ( time() - strtotime( $post->post_date ) ) / DAY_IN_SECONDS;
                if ( $days_old < 30 ) {
                    return '0.8';
                } elseif ( $days_old < 90 ) {
                    return '0.6';
                }
                return '0.4';
                
            default:
                return '0.5';
        }
    }
    
    /**
     * Get post change frequency
     */
    private function get_post_changefreq( $post, $post_type ) {
        switch ( $post_type ) {
            case 'page':
                if ( $post->ID == get_option( 'page_on_front' ) ) {
                    return 'daily';
                }
                return 'weekly';
                
            case 'services':
                return 'monthly';
                
            case 'testimonials':
                return 'monthly';
                
            case 'post':
                $days_old = ( time() - strtotime( $post->post_date ) ) / DAY_IN_SECONDS;
                if ( $days_old < 7 ) {
                    return 'daily';
                } elseif ( $days_old < 30 ) {
                    return 'weekly';
                }
                return 'monthly';
                
            default:
                return 'monthly';
        }
    }
    
    /**
     * Get last modified date
     */
    private function get_last_modified_date() {
        global $wpdb;
        
        $last_modified = $wpdb->get_var(
            "SELECT MAX(post_modified_gmt) FROM {$wpdb->posts} WHERE post_status = 'publish'"
        );
        
        return mysql2date( 'c', $last_modified );
    }
    
    /**
     * Get term last modified date
     */
    private function get_term_last_modified( $term_id ) {
        global $wpdb;
        
        $last_modified = $wpdb->get_var( $wpdb->prepare(
            "SELECT MAX(p.post_modified_gmt) 
             FROM {$wpdb->posts} p 
             INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id 
             WHERE tr.term_taxonomy_id = %d AND p.post_status = 'publish'",
            $term_id
        ) );
        
        return $last_modified ? mysql2date( 'c', $last_modified ) : mysql2date( 'c', current_time( 'mysql', true ) );
    }
    
    /**
     * Add sitemap meta to head
     */
    public function add_sitemap_meta() {
        if ( is_home() || is_front_page() ) {
            echo '<link rel="sitemap" type="application/xml" title="Sitemap" href="' . esc_url( home_url( '/sitemap.xml' ) ) . '">' . "\n";
        }
    }
    
    /**
     * Clear sitemap cache
     */
    public function clear_sitemap_cache() {
        $sitemap_types = array( 'main', 'posts', 'pages', 'services', 'testimonials', 'categories' );
        
        foreach ( $sitemap_types as $type ) {
            delete_transient( 'menscore_sitemap_' . $type );
        }
    }
    
    /**
     * Ping search engines
     */
    public function ping_search_engines() {
        $sitemap_url = home_url( '/sitemap.xml' );
        
        $search_engines = array(
            'google' => 'http://www.google.com/webmasters/sitemaps/ping?sitemap=' . urlencode( $sitemap_url ),
            'bing' => 'http://www.bing.com/webmaster/ping.aspx?siteMap=' . urlencode( $sitemap_url ),
        );
        
        foreach ( $search_engines as $engine => $ping_url ) {
            wp_remote_get( $ping_url, array(
                'timeout' => 10,
                'user-agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url(),
            ) );
        }
    }
    
    /**
     * Get sitemap statistics
     */
    public function get_sitemap_stats() {
        $stats = array();
        
        $post_types = array( 'post', 'page', 'services', 'testimonials' );
        
        foreach ( $post_types as $post_type ) {
            $count = wp_count_posts( $post_type );
            $stats[ $post_type ] = $count->publish;
        }
        
        $categories = get_categories( array( 'hide_empty' => true, 'exclude' => array( 1 ) ) );
        $stats['categories'] = count( $categories );
        
        $stats['total'] = array_sum( $stats );
        $stats['last_updated'] = $this->get_last_modified_date();
        
        return $stats;
    }
}