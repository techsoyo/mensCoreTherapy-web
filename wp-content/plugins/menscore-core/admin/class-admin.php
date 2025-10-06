<?php
/**
 * Admin Class for MensCore Core Plugin
 * 
 * @package MensCoreCore
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * MensCore Admin Class
 */
class MensCore_Admin {
    
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
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        add_action( 'admin_notices', array( $this, 'show_admin_notices' ) );
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __( 'MensCore', 'menscore-core' ),
            __( 'MensCore', 'menscore-core' ),
            'manage_options',
            'menscore-core',
            array( $this, 'admin_dashboard_page' ),
            'dashicons-heart',
            30
        );
        
        add_submenu_page(
            'menscore-core',
            __( 'Appointments', 'menscore-core' ),
            __( 'Appointments', 'menscore-core' ),
            'manage_options',
            'menscore-appointments',
            array( $this, 'appointments_page' )
        );
        
        add_submenu_page(
            'menscore-core',
            __( 'Contacts', 'menscore-core' ),
            __( 'Contacts', 'menscore-core' ),
            'manage_options',
            'menscore-contacts',
            array( $this, 'contacts_page' )
        );
        
        add_submenu_page(
            'menscore-core',
            __( 'Analytics', 'menscore-core' ),
            __( 'Analytics', 'menscore-core' ),
            'manage_options',
            'menscore-analytics',
            array( $this, 'analytics_page' )
        );
        
        add_submenu_page(
            'menscore-core',
            __( 'Settings', 'menscore-core' ),
            __( 'Settings', 'menscore-core' ),
            'manage_options',
            'menscore-settings',
            array( $this, 'settings_page' )
        );
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts( $hook ) {
        if ( strpos( $hook, 'menscore' ) !== false ) {
            wp_enqueue_script( 'menscore-admin', MENSCORE_CORE_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), MENSCORE_CORE_VERSION, true );
            wp_enqueue_style( 'menscore-admin', MENSCORE_CORE_PLUGIN_URL . 'assets/css/admin.css', array(), MENSCORE_CORE_VERSION );
        }
    }
    
    /**
     * Show admin notices
     */
    public function show_admin_notices() {
        if ( get_transient( 'menscore_core_activated' ) ) {
            delete_transient( 'menscore_core_activated' );
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e( 'MensCore Core Plugin activated successfully!', 'menscore-core' ); ?></p>
            </div>
            <?php
        }
    }
    
    /**
     * Admin dashboard page
     */
    public function admin_dashboard_page() {
        $appointment_stats = MensCore_Appointments::get_instance()->get_appointment_stats();
        $sitemap_stats = MensCore_Sitemap::get_instance()->get_sitemap_stats();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'MensCore Dashboard', 'menscore-core' ); ?></h1>
            
            <div class="menscore-dashboard">
                <div class="menscore-stats-row">
                    <div class="menscore-stat-box">
                        <h3><?php esc_html_e( 'Total Appointments', 'menscore-core' ); ?></h3>
                        <span class="stat-number"><?php echo esc_html( $appointment_stats['total'] ); ?></span>
                    </div>
                    
                    <div class="menscore-stat-box">
                        <h3><?php esc_html_e( 'Pending Appointments', 'menscore-core' ); ?></h3>
                        <span class="stat-number"><?php echo esc_html( $appointment_stats['pending'] ); ?></span>
                    </div>
                    
                    <div class="menscore-stat-box">
                        <h3><?php esc_html_e( 'This Month', 'menscore-core' ); ?></h3>
                        <span class="stat-number"><?php echo esc_html( $appointment_stats['this_month'] ); ?></span>
                    </div>
                    
                    <div class="menscore-stat-box">
                        <h3><?php esc_html_e( 'Sitemap URLs', 'menscore-core' ); ?></h3>
                        <span class="stat-number"><?php echo esc_html( $sitemap_stats['total'] ); ?></span>
                    </div>
                </div>
                
                <div class="menscore-dashboard-widgets">
                    <div class="menscore-widget">
                        <h3><?php esc_html_e( 'Recent Appointments', 'menscore-core' ); ?></h3>
                        <?php $this->display_recent_appointments(); ?>
                    </div>
                    
                    <div class="menscore-widget">
                        <h3><?php esc_html_e( 'Popular Services', 'menscore-core' ); ?></h3>
                        <?php $this->display_popular_services( $appointment_stats['popular_services'] ); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Appointments page
     */
    public function appointments_page() {
        $appointments = MensCore_Appointments::get_instance()->get_appointments();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Appointments', 'menscore-core' ); ?></h1>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Name', 'menscore-core' ); ?></th>
                        <th><?php esc_html_e( 'Email', 'menscore-core' ); ?></th>
                        <th><?php esc_html_e( 'Phone', 'menscore-core' ); ?></th>
                        <th><?php esc_html_e( 'Service', 'menscore-core' ); ?></th>
                        <th><?php esc_html_e( 'Date', 'menscore-core' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'menscore-core' ); ?></th>
                        <th><?php esc_html_e( 'Actions', 'menscore-core' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $appointments as $appointment ) : ?>
                        <tr>
                            <td><?php echo esc_html( $appointment->name ); ?></td>
                            <td><?php echo esc_html( $appointment->email ); ?></td>
                            <td><?php echo esc_html( $appointment->phone ); ?></td>
                            <td><?php echo esc_html( $appointment->service ); ?></td>
                            <td><?php echo esc_html( $appointment->appointment_date ); ?></td>
                            <td>
                                <span class="status-<?php echo esc_attr( $appointment->status ); ?>">
                                    <?php echo esc_html( ucfirst( $appointment->status ) ); ?>
                                </span>
                            </td>
                            <td>
                                <select onchange="updateAppointmentStatus(<?php echo $appointment->id; ?>, this.value)">
                                    <option value="pending" <?php selected( $appointment->status, 'pending' ); ?>><?php esc_html_e( 'Pending', 'menscore-core' ); ?></option>
                                    <option value="confirmed" <?php selected( $appointment->status, 'confirmed' ); ?>><?php esc_html_e( 'Confirmed', 'menscore-core' ); ?></option>
                                    <option value="completed" <?php selected( $appointment->status, 'completed' ); ?>><?php esc_html_e( 'Completed', 'menscore-core' ); ?></option>
                                    <option value="cancelled" <?php selected( $appointment->status, 'cancelled' ); ?>><?php esc_html_e( 'Cancelled', 'menscore-core' ); ?></option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    /**
     * Contacts page
     */
    public function contacts_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Contact Form Submissions', 'menscore-core' ); ?></h1>
            <p><?php esc_html_e( 'Contact management functionality coming soon.', 'menscore-core' ); ?></p>
        </div>
        <?php
    }
    
    /**
     * Analytics page
     */
    public function analytics_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Analytics', 'menscore-core' ); ?></h1>
            <p><?php esc_html_e( 'Analytics dashboard coming soon.', 'menscore-core' ); ?></p>
        </div>
        <?php
    }
    
    /**
     * Settings page
     */
    public function settings_page() {
        MensCore_Settings::get_instance()->display_settings_page();
    }
    
    /**
     * Display recent appointments
     */
    private function display_recent_appointments() {
        $appointments = MensCore_Appointments::get_instance()->get_appointments( null, 5 );
        
        if ( empty( $appointments ) ) {
            echo '<p>' . esc_html__( 'No appointments yet.', 'menscore-core' ) . '</p>';
            return;
        }
        
        echo '<ul>';
        foreach ( $appointments as $appointment ) {
            echo '<li>';
            echo '<strong>' . esc_html( $appointment->name ) . '</strong> - ';
            echo esc_html( $appointment->service ) . ' ';
            echo '<small>(' . esc_html( $appointment->appointment_date ) . ')</small>';
            echo '</li>';
        }
        echo '</ul>';
        
        echo '<p><a href="' . admin_url( 'admin.php?page=menscore-appointments' ) . '">' . 
             esc_html__( 'View all appointments', 'menscore-core' ) . '</a></p>';
    }
    
    /**
     * Display popular services
     */
    private function display_popular_services( $services ) {
        if ( empty( $services ) ) {
            echo '<p>' . esc_html__( 'No services data yet.', 'menscore-core' ) . '</p>';
            return;
        }
        
        echo '<ul>';
        foreach ( $services as $service ) {
            echo '<li>';
            echo '<strong>' . esc_html( $service->service ) . '</strong> ';
            echo '<span class="count">(' . esc_html( $service->count ) . ')</span>';
            echo '</li>';
        }
        echo '</ul>';
    }
}