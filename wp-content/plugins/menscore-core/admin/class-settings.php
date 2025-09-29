<?php
/**
 * Settings Class for MensCore Core Plugin
 * 
 * @package MensCoreCore
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * MensCore Settings Class
 */
class MensCore_Settings {
    
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
        add_action( 'admin_init', array( $this, 'init_settings' ) );
    }
    
    /**
     * Initialize settings
     */
    public function init_settings() {
        register_setting( 'menscore_settings', 'menscore_security_enabled' );
        register_setting( 'menscore_settings', 'menscore_performance_enabled' );
        register_setting( 'menscore_settings', 'menscore_sitemap_enabled' );
        register_setting( 'menscore_settings', 'menscore_analytics_enabled' );
        register_setting( 'menscore_settings', 'menscore_email_notifications' );
        register_setting( 'menscore_settings', 'menscore_admin_email' );
    }
    
    /**
     * Display settings page
     */
    public function display_settings_page() {
        if ( isset( $_POST['submit'] ) ) {
            $this->save_settings();
        }
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'MensCore Settings', 'menscore-core' ); ?></h1>
            
            <form method="post" action="">
                <?php wp_nonce_field( 'menscore_settings', 'menscore_settings_nonce' ); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Enable Security Features', 'menscore-core' ); ?></th>
                        <td>
                            <input type="checkbox" name="menscore_security_enabled" value="1" <?php checked( get_option( 'menscore_security_enabled', 1 ) ); ?> />
                            <p class="description"><?php esc_html_e( 'Enable enhanced security features.', 'menscore-core' ); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Enable Performance Optimization', 'menscore-core' ); ?></th>
                        <td>
                            <input type="checkbox" name="menscore_performance_enabled" value="1" <?php checked( get_option( 'menscore_performance_enabled', 1 ) ); ?> />
                            <p class="description"><?php esc_html_e( 'Enable performance optimization features.', 'menscore-core' ); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Enable Dynamic Sitemap', 'menscore-core' ); ?></th>
                        <td>
                            <input type="checkbox" name="menscore_sitemap_enabled" value="1" <?php checked( get_option( 'menscore_sitemap_enabled', 1 ) ); ?> />
                            <p class="description"><?php esc_html_e( 'Enable dynamic XML sitemap generation.', 'menscore-core' ); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Enable Analytics', 'menscore-core' ); ?></th>
                        <td>
                            <input type="checkbox" name="menscore_analytics_enabled" value="1" <?php checked( get_option( 'menscore_analytics_enabled', 1 ) ); ?> />
                            <p class="description"><?php esc_html_e( 'Enable basic analytics tracking.', 'menscore-core' ); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Enable Email Notifications', 'menscore-core' ); ?></th>
                        <td>
                            <input type="checkbox" name="menscore_email_notifications" value="1" <?php checked( get_option( 'menscore_email_notifications', 1 ) ); ?> />
                            <p class="description"><?php esc_html_e( 'Enable email notifications for appointments and contacts.', 'menscore-core' ); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Admin Email', 'menscore-core' ); ?></th>
                        <td>
                            <input type="email" name="menscore_admin_email" value="<?php echo esc_attr( get_option( 'menscore_admin_email', get_option( 'admin_email' ) ) ); ?>" class="regular-text" />
                            <p class="description"><?php esc_html_e( 'Email address for notifications.', 'menscore-core' ); ?></p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * Save settings
     */
    private function save_settings() {
        if ( ! wp_verify_nonce( $_POST['menscore_settings_nonce'], 'menscore_settings' ) ) {
            return;
        }
        
        update_option( 'menscore_security_enabled', isset( $_POST['menscore_security_enabled'] ) ? 1 : 0 );
        update_option( 'menscore_performance_enabled', isset( $_POST['menscore_performance_enabled'] ) ? 1 : 0 );
        update_option( 'menscore_sitemap_enabled', isset( $_POST['menscore_sitemap_enabled'] ) ? 1 : 0 );
        update_option( 'menscore_analytics_enabled', isset( $_POST['menscore_analytics_enabled'] ) ? 1 : 0 );
        update_option( 'menscore_email_notifications', isset( $_POST['menscore_email_notifications'] ) ? 1 : 0 );
        update_option( 'menscore_admin_email', sanitize_email( $_POST['menscore_admin_email'] ) );
        
        echo '<div class="notice notice-success"><p>' . esc_html__( 'Settings saved successfully!', 'menscore-core' ) . '</p></div>';
    }
}