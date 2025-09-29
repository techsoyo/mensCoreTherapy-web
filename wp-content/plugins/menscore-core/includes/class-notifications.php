<?php
/**
 * Notifications Class for MensCore Core Plugin
 * 
 * @package MensCoreCore
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * MensCore Notifications Class
 */
class MensCore_Notifications {
    
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
        if ( get_option( 'menscore_email_notifications', 1 ) ) {
            add_action( 'wp_mail_failed', array( $this, 'log_email_failure' ) );
            add_filter( 'wp_mail_from', array( $this, 'set_email_from' ) );
            add_filter( 'wp_mail_from_name', array( $this, 'set_email_from_name' ) );
        }
    }
    
    /**
     * Set email from address
     */
    public function set_email_from( $from_email ) {
        return get_option( 'menscore_email_from', get_option( 'admin_email' ) );
    }
    
    /**
     * Set email from name
     */
    public function set_email_from_name( $from_name ) {
        return get_option( 'menscore_email_from_name', get_bloginfo( 'name' ) );
    }
    
    /**
     * Log email failure
     */
    public function log_email_failure( $wp_error ) {
        error_log( 'MensCore Email Failed: ' . $wp_error->get_error_message() );
    }
    
    /**
     * Send notification
     */
    public function send_notification( $to, $subject, $message, $type = 'general' ) {
        $headers = array( 'Content-Type: text/html; charset=UTF-8' );
        
        $template_message = $this->wrap_message_in_template( $message, $subject, $type );
        
        return wp_mail( $to, $subject, $template_message, $headers );
    }
    
    /**
     * Wrap message in template
     */
    private function wrap_message_in_template( $message, $subject, $type ) {
        $template = $this->get_email_template( $type );
        
        $replacements = array(
            '{site_name}' => get_bloginfo( 'name' ),
            '{site_url}' => home_url(),
            '{subject}' => $subject,
            '{message}' => $message,
            '{year}' => date( 'Y' ),
        );
        
        return str_replace( array_keys( $replacements ), array_values( $replacements ), $template );
    }
    
    /**
     * Get email template
     */
    private function get_email_template( $type ) {
        $template = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>{subject}</title>
        </head>
        <body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;">
            <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px;">
                <header style="text-align: center; padding: 20px 0; border-bottom: 2px solid #3498db;">
                    <h1 style="color: #2c3e50; margin: 0;">{site_name}</h1>
                </header>
                
                <main style="padding: 30px 0;">
                    <h2 style="color: #2c3e50; margin-bottom: 20px;">{subject}</h2>
                    <div style="line-height: 1.6; color: #333;">
                        {message}
                    </div>
                </main>
                
                <footer style="text-align: center; padding: 20px 0; border-top: 1px solid #e1e8ed; margin-top: 30px;">
                    <p style="color: #6c757d; margin: 0; font-size: 14px;">
                        &copy; {year} {site_name}. All rights reserved.
                    </p>
                    <p style="color: #6c757d; margin: 5px 0 0 0; font-size: 12px;">
                        <a href="{site_url}" style="color: #3498db; text-decoration: none;">{site_url}</a>
                    </p>
                </footer>
            </div>
        </body>
        </html>';
        
        return apply_filters( 'menscore_email_template', $template, $type );
    }
}