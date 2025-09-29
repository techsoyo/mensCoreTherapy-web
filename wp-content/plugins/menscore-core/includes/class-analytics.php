<?php
/**
 * Analytics Class for MensCore Core Plugin
 * 
 * @package MensCoreCore
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * MensCore Analytics Class
 */
class MensCore_Analytics {
    
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
        if ( get_option( 'menscore_analytics_enabled', 1 ) ) {
            add_action( 'wp', array( $this, 'track_page_view' ) );
            add_action( 'wp_footer', array( $this, 'add_tracking_script' ) );
        }
    }
    
    /**
     * Track page view
     */
    public function track_page_view() {
        if ( is_admin() || is_robot() ) {
            return;
        }
        
        $this->log_event( 'page_view', array(
            'url' => $_SERVER['REQUEST_URI'] ?? '',
            'referrer' => $_SERVER['HTTP_REFERER'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ) );
    }
    
    /**
     * Log event
     */
    public function log_event( $event_type, $data = array() ) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'menscore_analytics';
        
        $wpdb->insert(
            $table,
            array(
                'event_type' => $event_type,
                'event_data' => wp_json_encode( $data ),
                'user_id' => get_current_user_id() ?: null,
                'session_id' => $this->get_session_id(),
                'ip_address' => $this->get_client_ip(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'created_at' => current_time( 'mysql' ),
            ),
            array( '%s', '%s', '%d', '%s', '%s', '%s', '%s' )
        );
    }
    
    /**
     * Get session ID
     */
    private function get_session_id() {
        if ( ! session_id() ) {
            session_start();
        }
        return session_id();
    }
    
    /**
     * Get client IP
     */
    private function get_client_ip() {
        $ip_keys = array( 'HTTP_CF_CONNECTING_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' );
        
        foreach ( $ip_keys as $key ) {
            if ( array_key_exists( $key, $_SERVER ) === true ) {
                foreach ( explode( ',', $_SERVER[ $key ] ) as $ip ) {
                    $ip = trim( $ip );
                    
                    if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Add tracking script
     */
    public function add_tracking_script() {
        ?>
        <script>
        (function() {
            // Basic event tracking
            document.addEventListener('click', function(e) {
                if (e.target.matches('a[href^="tel:"]')) {
                    // Track phone clicks
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'phone_click', {
                            'event_category': 'engagement',
                            'event_label': e.target.href
                        });
                    }
                }
                
                if (e.target.matches('a[href^="mailto:"]')) {
                    // Track email clicks
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'email_click', {
                            'event_category': 'engagement',
                            'event_label': e.target.href
                        });
                    }
                }
            });
        })();
        </script>
        <?php
    }
}