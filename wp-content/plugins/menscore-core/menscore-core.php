<?php
/**
 * Plugin Name: MensCore Therapy - Core Plugin
 * Plugin URI: https://menscore-therapy.com/plugins/core
 * Description: Core functionality plugin for MensCore Therapy website. Provides essential features for therapy services, appointment booking, and security enhancements.
 * Version: 1.0.0
 * Author: TechSoyo Development Team
 * Author URI: https://techsoyo.com
 * Text Domain: menscore-core
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.3
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Network: false
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'MENSCORE_CORE_VERSION', '1.0.0' );
define( 'MENSCORE_CORE_PLUGIN_FILE', __FILE__ );
define( 'MENSCORE_CORE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MENSCORE_CORE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MENSCORE_CORE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main Plugin Class
 */
final class MensCore_Core_Plugin {
    
    /**
     * Plugin instance
     */
    private static $instance = null;
    
    /**
     * Get plugin instance
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
        add_action( 'init', array( $this, 'init' ), 0 );
        add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
        
        // Activation and deactivation hooks
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Load plugin text domain
        load_plugin_textdomain( 'menscore-core', false, dirname( MENSCORE_CORE_PLUGIN_BASENAME ) . '/languages' );
        
        // Include required files
        $this->includes();
        
        // Initialize components
        $this->init_components();
    }
    
    /**
     * Include required files
     */
    private function includes() {
        // Core includes
        require_once MENSCORE_CORE_PLUGIN_DIR . 'includes/class-security.php';
        require_once MENSCORE_CORE_PLUGIN_DIR . 'includes/class-performance.php';
        require_once MENSCORE_CORE_PLUGIN_DIR . 'includes/class-sitemap.php';
        require_once MENSCORE_CORE_PLUGIN_DIR . 'includes/class-appointments.php';
        require_once MENSCORE_CORE_PLUGIN_DIR . 'includes/class-notifications.php';
        require_once MENSCORE_CORE_PLUGIN_DIR . 'includes/class-analytics.php';
        
        // Admin includes
        if ( is_admin() ) {
            require_once MENSCORE_CORE_PLUGIN_DIR . 'admin/class-admin.php';
            require_once MENSCORE_CORE_PLUGIN_DIR . 'admin/class-settings.php';
        }
        
        // Public includes
        if ( ! is_admin() ) {
            require_once MENSCORE_CORE_PLUGIN_DIR . 'public/class-public.php';
        }
    }
    
    /**
     * Initialize components
     */
    private function init_components() {
        // Initialize security features
        MensCore_Security::get_instance();
        
        // Initialize performance optimizations
        MensCore_Performance::get_instance();
        
        // Initialize sitemap functionality
        MensCore_Sitemap::get_instance();
        
        // Initialize appointments system
        MensCore_Appointments::get_instance();
        
        // Initialize notifications
        MensCore_Notifications::get_instance();
        
        // Initialize analytics
        MensCore_Analytics::get_instance();
        
        // Initialize admin area
        if ( is_admin() ) {
            MensCore_Admin::get_instance();
        }
        
        // Initialize public functionality
        if ( ! is_admin() ) {
            MensCore_Public::get_instance();
        }
    }
    
    /**
     * Plugin loaded
     */
    public function plugins_loaded() {
        // Check dependencies
        $this->check_dependencies();
    }
    
    /**
     * Check plugin dependencies
     */
    private function check_dependencies() {
        // Check WordPress version
        if ( version_compare( get_bloginfo( 'version' ), '5.0', '<' ) ) {
            add_action( 'admin_notices', array( $this, 'wp_version_notice' ) );
            return;
        }
        
        // Check PHP version
        if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
            add_action( 'admin_notices', array( $this, 'php_version_notice' ) );
            return;
        }
    }
    
    /**
     * WordPress version notice
     */
    public function wp_version_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php esc_html_e( 'MensCore Core Plugin requires WordPress 5.0 or higher.', 'menscore-core' ); ?></p>
        </div>
        <?php
    }
    
    /**
     * PHP version notice
     */
    public function php_version_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php esc_html_e( 'MensCore Core Plugin requires PHP 7.4 or higher.', 'menscore-core' ); ?></p>
        </div>
        <?php
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Create database tables
        $this->create_tables();
        
        // Set default options
        $this->set_default_options();
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Set activation flag
        set_transient( 'menscore_core_activated', true, 30 );
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Clear scheduled events
        wp_clear_scheduled_hook( 'menscore_daily_cleanup' );
        wp_clear_scheduled_hook( 'menscore_weekly_report' );
    }
    
    /**
     * Create database tables
     */
    private function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Appointments table
        $appointments_table = $wpdb->prefix . 'menscore_appointments';
        $appointments_sql = "CREATE TABLE $appointments_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            phone varchar(50) NOT NULL,
            service varchar(255) NOT NULL,
            appointment_date datetime NOT NULL,
            status varchar(20) DEFAULT 'pending',
            notes text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Contact submissions table
        $contacts_table = $wpdb->prefix . 'menscore_contacts';
        $contacts_sql = "CREATE TABLE $contacts_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            phone varchar(50),
            service varchar(255),
            message text NOT NULL,
            status varchar(20) DEFAULT 'unread',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Analytics table
        $analytics_table = $wpdb->prefix . 'menscore_analytics';
        $analytics_sql = "CREATE TABLE $analytics_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            event_type varchar(50) NOT NULL,
            event_data text,
            user_id mediumint(9),
            session_id varchar(255),
            ip_address varchar(45),
            user_agent text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY event_type (event_type),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $appointments_sql );
        dbDelta( $contacts_sql );
        dbDelta( $analytics_sql );
    }
    
    /**
     * Set default options
     */
    private function set_default_options() {
        $default_options = array(
            'menscore_security_enabled' => 1,
            'menscore_performance_enabled' => 1,
            'menscore_sitemap_enabled' => 1,
            'menscore_analytics_enabled' => 1,
            'menscore_email_notifications' => 1,
            'menscore_admin_email' => get_option( 'admin_email' ),
        );
        
        foreach ( $default_options as $option => $value ) {
            if ( ! get_option( $option ) ) {
                add_option( $option, $value );
            }
        }
    }
    
    /**
     * Get plugin version
     */
    public function get_version() {
        return MENSCORE_CORE_VERSION;
    }
}

/**
 * Initialize the plugin
 */
function menscore_core() {
    return MensCore_Core_Plugin::get_instance();
}

// Start the plugin
menscore_core();