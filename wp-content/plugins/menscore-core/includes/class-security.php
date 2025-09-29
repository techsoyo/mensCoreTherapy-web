<?php
/**
 * Security Class for MensCore Core Plugin
 * 
 * @package MensCoreCore
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * MensCore Security Class
 */
class MensCore_Security {
    
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
        if ( get_option( 'menscore_security_enabled', 1 ) ) {
            add_action( 'init', array( $this, 'init_security_features' ) );
            add_action( 'wp_login_failed', array( $this, 'log_failed_login' ) );
            add_action( 'wp_login', array( $this, 'log_successful_login' ), 10, 2 );
            add_filter( 'authenticate', array( $this, 'limit_login_attempts' ), 30, 3 );
            add_action( 'wp_head', array( $this, 'add_security_headers' ) );
            add_filter( 'wp_headers', array( $this, 'add_http_security_headers' ) );
        }
    }
    
    /**
     * Initialize security features
     */
    public function init_security_features() {
        // Disable file editing
        if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
            define( 'DISALLOW_FILE_EDIT', true );
        }
        
        // Hide WordPress version
        remove_action( 'wp_head', 'wp_generator' );
        add_filter( 'the_generator', '__return_empty_string' );
        
        // Remove Windows Live Writer manifest
        remove_action( 'wp_head', 'wlwmanifest_link' );
        
        // Remove RSD link
        remove_action( 'wp_head', 'rsd_link' );
        
        // Disable XML-RPC
        add_filter( 'xmlrpc_enabled', '__return_false' );
        
        // Remove WordPress version from RSS
        add_filter( 'the_generator', array( $this, 'remove_version_from_rss' ) );
        
        // Disable user enumeration
        add_action( 'template_redirect', array( $this, 'disable_user_enumeration' ) );
        
        // Secure wp-config.php
        add_action( 'template_redirect', array( $this, 'protect_wp_config' ) );
        
        // Hide login errors
        add_filter( 'login_errors', array( $this, 'hide_login_errors' ) );
        
        // Remove version from scripts and styles
        add_filter( 'script_loader_src', array( $this, 'remove_version_scripts_styles' ), 15, 1 );
        add_filter( 'style_loader_src', array( $this, 'remove_version_scripts_styles' ), 15, 1 );
        
        // Secure uploads directory
        add_action( 'init', array( $this, 'secure_uploads_directory' ) );
        
        // Add security headers
        add_action( 'send_headers', array( $this, 'send_security_headers' ) );
    }
    
    /**
     * Remove version from RSS
     */
    public function remove_version_from_rss() {
        return '';
    }
    
    /**
     * Disable user enumeration
     */
    public function disable_user_enumeration() {
        if ( isset( $_GET['author'] ) || preg_match( '/\?author=([0-9]*)/', $_SERVER['QUERY_STRING'] ) ) {
            wp_redirect( home_url(), 301 );
            exit;
        }
    }
    
    /**
     * Protect wp-config.php
     */
    public function protect_wp_config() {
        if ( strpos( $_SERVER['REQUEST_URI'], 'wp-config.php' ) !== false ) {
            wp_die( 'Access denied.' );
        }
    }
    
    /**
     * Hide login errors
     */
    public function hide_login_errors() {
        return __( 'Login failed. Please check your credentials.', 'menscore-core' );
    }
    
    /**
     * Remove version from scripts and styles
     */
    public function remove_version_scripts_styles( $src ) {
        if ( strpos( $src, 'ver=' ) ) {
            $src = remove_query_arg( 'ver', $src );
        }
        return $src;
    }
    
    /**
     * Secure uploads directory
     */
    public function secure_uploads_directory() {
        $upload_dir = wp_upload_dir();
        $htaccess_file = $upload_dir['basedir'] . '/.htaccess';
        
        if ( ! file_exists( $htaccess_file ) ) {
            $htaccess_content = "# Secure uploads directory\n";
            $htaccess_content .= "Options -Indexes\n";
            $htaccess_content .= "Options -ExecCGI\n";
            $htaccess_content .= "<FilesMatch \"\.(php|php3|php4|php5|phtml|pl|py|jsp|asp|sh|cgi)$\">\n";
            $htaccess_content .= "Order allow,deny\n";
            $htaccess_content .= "Deny from all\n";
            $htaccess_content .= "</FilesMatch>\n";
            
            @file_put_contents( $htaccess_file, $htaccess_content );
        }
    }
    
    /**
     * Send security headers
     */
    public function send_security_headers() {
        // X-Content-Type-Options
        header( 'X-Content-Type-Options: nosniff' );
        
        // X-Frame-Options
        header( 'X-Frame-Options: SAMEORIGIN' );
        
        // X-XSS-Protection
        header( 'X-XSS-Protection: 1; mode=block' );
        
        // Referrer Policy
        header( 'Referrer-Policy: strict-origin-when-cross-origin' );
        
        // Content Security Policy (basic)
        $csp = "default-src 'self'; ";
        $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' *.googleapis.com *.gstatic.com; ";
        $csp .= "style-src 'self' 'unsafe-inline' *.googleapis.com; ";
        $csp .= "img-src 'self' data: *.gravatar.com; ";
        $csp .= "font-src 'self' *.googleapis.com *.gstatic.com;";
        
        header( "Content-Security-Policy: $csp" );
    }
    
    /**
     * Add security headers to wp_head
     */
    public function add_security_headers() {
        echo '<meta http-equiv="X-Content-Type-Options" content="nosniff">' . "\n";
        echo '<meta http-equiv="X-Frame-Options" content="SAMEORIGIN">' . "\n";
        echo '<meta http-equiv="X-XSS-Protection" content="1; mode=block">' . "\n";
    }
    
    /**
     * Add HTTP security headers
     */
    public function add_http_security_headers( $headers ) {
        $headers['X-Content-Type-Options'] = 'nosniff';
        $headers['X-Frame-Options'] = 'SAMEORIGIN';
        $headers['X-XSS-Protection'] = '1; mode=block';
        $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
        
        return $headers;
    }
    
    /**
     * Log failed login attempts
     */
    public function log_failed_login( $username ) {
        $ip = $this->get_client_ip();
        $attempts = get_transient( 'menscore_failed_login_' . $ip );
        
        if ( ! $attempts ) {
            $attempts = 0;
        }
        
        $attempts++;
        set_transient( 'menscore_failed_login_' . $ip, $attempts, 900 ); // 15 minutes
        
        // Log to database
        $this->log_security_event( 'failed_login', array(
            'username' => $username,
            'ip' => $ip,
            'attempts' => $attempts,
        ) );
        
        // Send alert if too many attempts
        if ( $attempts >= 5 ) {
            $this->send_security_alert( 'Multiple failed login attempts', array(
                'username' => $username,
                'ip' => $ip,
                'attempts' => $attempts,
            ) );
        }
    }
    
    /**
     * Log successful login
     */
    public function log_successful_login( $user_login, $user ) {
        $ip = $this->get_client_ip();
        
        // Clear failed attempts
        delete_transient( 'menscore_failed_login_' . $ip );
        
        // Log successful login
        $this->log_security_event( 'successful_login', array(
            'username' => $user_login,
            'user_id' => $user->ID,
            'ip' => $ip,
        ) );
    }
    
    /**
     * Limit login attempts
     */
    public function limit_login_attempts( $user, $username, $password ) {
        $ip = $this->get_client_ip();
        $attempts = get_transient( 'menscore_failed_login_' . $ip );
        
        // Block after 5 failed attempts
        if ( $attempts >= 5 ) {
            return new WP_Error( 'too_many_attempts', 
                __( 'Too many failed login attempts. Please try again later.', 'menscore-core' ) 
            );
        }
        
        return $user;
    }
    
    /**
     * Get client IP address
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
        
        return isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
    }
    
    /**
     * Log security event
     */
    private function log_security_event( $event_type, $data ) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'menscore_analytics';
        
        $wpdb->insert(
            $table,
            array(
                'event_type' => 'security_' . $event_type,
                'event_data' => wp_json_encode( $data ),
                'ip_address' => $this->get_client_ip(),
                'user_agent' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '',
                'created_at' => current_time( 'mysql' ),
            ),
            array( '%s', '%s', '%s', '%s', '%s' )
        );
    }
    
    /**
     * Send security alert
     */
    private function send_security_alert( $subject, $data ) {
        $admin_email = get_option( 'menscore_admin_email', get_option( 'admin_email' ) );
        
        $message = "Security Alert: $subject\n\n";
        $message .= "Details:\n";
        
        foreach ( $data as $key => $value ) {
            $message .= ucfirst( $key ) . ": $value\n";
        }
        
        $message .= "\nTime: " . current_time( 'Y-m-d H:i:s' ) . "\n";
        $message .= "Site: " . get_site_url() . "\n";
        
        wp_mail( $admin_email, "[Security Alert] $subject", $message );
    }
    
    /**
     * Scan for malware (basic file integrity check)
     */
    public function scan_for_malware() {
        $suspicious_patterns = array(
            'eval(',
            'base64_decode(',
            'gzinflate(',
            'str_rot13(',
            'system(',
            'exec(',
            'shell_exec(',
            'passthru(',
        );
        
        $scan_dirs = array(
            WP_CONTENT_DIR . '/themes',
            WP_CONTENT_DIR . '/plugins',
        );
        
        $suspicious_files = array();
        
        foreach ( $scan_dirs as $dir ) {
            $files = $this->get_php_files( $dir );
            
            foreach ( $files as $file ) {
                $content = file_get_contents( $file );
                
                foreach ( $suspicious_patterns as $pattern ) {
                    if ( strpos( $content, $pattern ) !== false ) {
                        $suspicious_files[] = $file;
                        break;
                    }
                }
            }
        }
        
        if ( ! empty( $suspicious_files ) ) {
            $this->send_security_alert( 'Suspicious files detected', array(
                'files' => implode( ', ', $suspicious_files ),
            ) );
        }
        
        return $suspicious_files;
    }
    
    /**
     * Get PHP files recursively
     */
    private function get_php_files( $dir ) {
        $files = array();
        
        if ( is_dir( $dir ) ) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator( $dir, RecursiveDirectoryIterator::SKIP_DOTS )
            );
            
            foreach ( $iterator as $file ) {
                if ( $file->getExtension() === 'php' ) {
                    $files[] = $file->getPathname();
                }
            }
        }
        
        return $files;
    }
    
    /**
     * Clean up old security logs
     */
    public function cleanup_security_logs() {
        global $wpdb;
        
        $table = $wpdb->prefix . 'menscore_analytics';
        
        // Delete logs older than 30 days
        $wpdb->query( $wpdb->prepare(
            "DELETE FROM $table WHERE event_type LIKE 'security_%' AND created_at < %s",
            date( 'Y-m-d H:i:s', strtotime( '-30 days' ) )
        ) );
    }
}