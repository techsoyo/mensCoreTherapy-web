<?php
/**
 * Appointments Management Class for MensCore Core Plugin
 * 
 * @package MensCoreCore
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * MensCore Appointments Class
 */
class MensCore_Appointments {
    
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
        add_action( 'wp_ajax_menscore_book_appointment', array( $this, 'handle_appointment_booking' ) );
        add_action( 'wp_ajax_nopriv_menscore_book_appointment', array( $this, 'handle_appointment_booking' ) );
        add_action( 'wp_ajax_menscore_contact_form', array( $this, 'handle_contact_form' ) );
        add_action( 'wp_ajax_nopriv_menscore_contact_form', array( $this, 'handle_contact_form' ) );
        add_action( 'init', array( $this, 'handle_form_submissions' ) );
    }
    
    /**
     * Handle form submissions (non-AJAX fallback)
     */
    public function handle_form_submissions() {
        if ( isset( $_POST['booking_name'] ) && isset( $_POST['therapy_booking_nonce'] ) ) {
            $this->process_appointment_booking();
        }
        
        if ( isset( $_POST['contact_name'] ) && isset( $_POST['therapy_contact_nonce'] ) ) {
            $this->process_contact_form();
        }
    }
    
    /**
     * Handle appointment booking (AJAX)
     */
    public function handle_appointment_booking() {
        check_ajax_referer( 'menscore_ajax_nonce', 'nonce' );
        
        $result = $this->process_appointment_booking();
        
        wp_send_json( $result );
    }
    
    /**
     * Process appointment booking
     */
    private function process_appointment_booking() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['therapy_booking_nonce'] ?? '', 'therapy_booking_form' ) ) {
            return array( 'success' => false, 'message' => __( 'Security check failed.', 'menscore-core' ) );
        }
        
        // Sanitize input
        $data = $this->sanitize_appointment_data( $_POST );
        
        // Validate required fields
        $validation = $this->validate_appointment_data( $data );
        if ( ! $validation['valid'] ) {
            return array( 'success' => false, 'message' => $validation['message'] );
        }
        
        // Check for duplicate appointments
        if ( $this->is_duplicate_appointment( $data ) ) {
            return array( 
                'success' => false, 
                'message' => __( 'You already have an appointment request for this date and time.', 'menscore-core' )
            );
        }
        
        // Save appointment to database
        $appointment_id = $this->save_appointment( $data );
        
        if ( $appointment_id ) {
            // Send confirmation emails
            $this->send_appointment_confirmation( $appointment_id, $data );
            
            // Log the event
            $this->log_appointment_event( 'booking_created', $appointment_id, $data );
            
            return array( 
                'success' => true, 
                'message' => __( 'Appointment request submitted successfully! We\'ll contact you to confirm.', 'menscore-core' ),
                'appointment_id' => $appointment_id
            );
        } else {
            return array( 
                'success' => false, 
                'message' => __( 'Failed to save appointment. Please try again.', 'menscore-core' )
            );
        }
    }
    
    /**
     * Handle contact form (AJAX)
     */
    public function handle_contact_form() {
        check_ajax_referer( 'menscore_ajax_nonce', 'nonce' );
        
        $result = $this->process_contact_form();
        
        wp_send_json( $result );
    }
    
    /**
     * Process contact form
     */
    private function process_contact_form() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['therapy_contact_nonce'] ?? '', 'therapy_contact_form' ) ) {
            return array( 'success' => false, 'message' => __( 'Security check failed.', 'menscore-core' ) );
        }
        
        // Sanitize input
        $data = $this->sanitize_contact_data( $_POST );
        
        // Validate required fields
        $validation = $this->validate_contact_data( $data );
        if ( ! $validation['valid'] ) {
            return array( 'success' => false, 'message' => $validation['message'] );
        }
        
        // Save contact to database
        $contact_id = $this->save_contact( $data );
        
        if ( $contact_id ) {
            // Send confirmation emails
            $this->send_contact_confirmation( $contact_id, $data );
            
            // Log the event
            $this->log_contact_event( 'contact_created', $contact_id, $data );
            
            return array( 
                'success' => true, 
                'message' => __( 'Message sent successfully! We\'ll get back to you soon.', 'menscore-core' ),
                'contact_id' => $contact_id
            );
        } else {
            return array( 
                'success' => false, 
                'message' => __( 'Failed to send message. Please try again.', 'menscore-core' )
            );
        }
    }
    
    /**
     * Sanitize appointment data
     */
    private function sanitize_appointment_data( $data ) {
        return array(
            'name' => sanitize_text_field( $data['booking_name'] ?? '' ),
            'email' => sanitize_email( $data['booking_email'] ?? '' ),
            'phone' => sanitize_text_field( $data['booking_phone'] ?? '' ),
            'service' => sanitize_text_field( $data['booking_service'] ?? '' ),
            'appointment_date' => sanitize_text_field( $data['booking_date'] ?? '' ),
            'notes' => sanitize_textarea_field( $data['booking_notes'] ?? '' ),
        );
    }
    
    /**
     * Sanitize contact data
     */
    private function sanitize_contact_data( $data ) {
        return array(
            'name' => sanitize_text_field( $data['contact_name'] ?? '' ),
            'email' => sanitize_email( $data['contact_email'] ?? '' ),
            'phone' => sanitize_text_field( $data['contact_phone'] ?? '' ),
            'service' => sanitize_text_field( $data['contact_service'] ?? '' ),
            'message' => sanitize_textarea_field( $data['contact_message'] ?? '' ),
        );
    }
    
    /**
     * Validate appointment data
     */
    private function validate_appointment_data( $data ) {
        $errors = array();
        
        if ( empty( $data['name'] ) ) {
            $errors[] = __( 'Name is required.', 'menscore-core' );
        }
        
        if ( empty( $data['email'] ) || ! is_email( $data['email'] ) ) {
            $errors[] = __( 'Valid email is required.', 'menscore-core' );
        }
        
        if ( empty( $data['phone'] ) ) {
            $errors[] = __( 'Phone number is required.', 'menscore-core' );
        }
        
        if ( empty( $data['service'] ) ) {
            $errors[] = __( 'Service selection is required.', 'menscore-core' );
        }
        
        if ( empty( $data['appointment_date'] ) ) {
            $errors[] = __( 'Appointment date is required.', 'menscore-core' );
        } elseif ( strtotime( $data['appointment_date'] ) <= time() ) {
            $errors[] = __( 'Appointment date must be in the future.', 'menscore-core' );
        }
        
        return array(
            'valid' => empty( $errors ),
            'message' => implode( ' ', $errors ),
        );
    }
    
    /**
     * Validate contact data
     */
    private function validate_contact_data( $data ) {
        $errors = array();
        
        if ( empty( $data['name'] ) ) {
            $errors[] = __( 'Name is required.', 'menscore-core' );
        }
        
        if ( empty( $data['email'] ) || ! is_email( $data['email'] ) ) {
            $errors[] = __( 'Valid email is required.', 'menscore-core' );
        }
        
        if ( empty( $data['message'] ) ) {
            $errors[] = __( 'Message is required.', 'menscore-core' );
        }
        
        return array(
            'valid' => empty( $errors ),
            'message' => implode( ' ', $errors ),
        );
    }
    
    /**
     * Check for duplicate appointments
     */
    private function is_duplicate_appointment( $data ) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'menscore_appointments';
        
        $existing = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM $table 
             WHERE email = %s 
             AND appointment_date = %s 
             AND status != 'cancelled' 
             AND created_at > %s",
            $data['email'],
            $data['appointment_date'],
            date( 'Y-m-d H:i:s', strtotime( '-1 hour' ) )
        ) );
        
        return $existing > 0;
    }
    
    /**
     * Save appointment to database
     */
    private function save_appointment( $data ) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'menscore_appointments';
        
        $result = $wpdb->insert(
            $table,
            array(
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'service' => $data['service'],
                'appointment_date' => $data['appointment_date'],
                'notes' => $data['notes'],
                'status' => 'pending',
                'created_at' => current_time( 'mysql' ),
            ),
            array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
        );
        
        return $result ? $wpdb->insert_id : false;
    }
    
    /**
     * Save contact to database
     */
    private function save_contact( $data ) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'menscore_contacts';
        
        $result = $wpdb->insert(
            $table,
            array(
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'service' => $data['service'],
                'message' => $data['message'],
                'status' => 'unread',
                'created_at' => current_time( 'mysql' ),
            ),
            array( '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
        );
        
        return $result ? $wpdb->insert_id : false;
    }
    
    /**
     * Send appointment confirmation
     */
    private function send_appointment_confirmation( $appointment_id, $data ) {
        // Email to client
        $client_subject = __( 'Appointment Request Confirmation', 'menscore-core' );
        $client_message = $this->get_appointment_confirmation_template( $data, 'client' );
        
        wp_mail( $data['email'], $client_subject, $client_message, $this->get_email_headers() );
        
        // Email to admin
        $admin_email = get_option( 'menscore_admin_email', get_option( 'admin_email' ) );
        $admin_subject = __( 'New Appointment Request', 'menscore-core' );
        $admin_message = $this->get_appointment_confirmation_template( $data, 'admin' );
        
        wp_mail( $admin_email, $admin_subject, $admin_message, $this->get_email_headers() );
    }
    
    /**
     * Send contact confirmation
     */
    private function send_contact_confirmation( $contact_id, $data ) {
        // Email to client
        $client_subject = __( 'Message Received Confirmation', 'menscore-core' );
        $client_message = $this->get_contact_confirmation_template( $data, 'client' );
        
        wp_mail( $data['email'], $client_subject, $client_message, $this->get_email_headers() );
        
        // Email to admin
        $admin_email = get_option( 'menscore_admin_email', get_option( 'admin_email' ) );
        $admin_subject = __( 'New Contact Form Submission', 'menscore-core' );
        $admin_message = $this->get_contact_confirmation_template( $data, 'admin' );
        
        wp_mail( $admin_email, $admin_subject, $admin_message, $this->get_email_headers() );
    }
    
    /**
     * Get appointment confirmation template
     */
    private function get_appointment_confirmation_template( $data, $type ) {
        if ( $type === 'client' ) {
            $message = "Dear {$data['name']},\n\n";
            $message .= "Thank you for requesting an appointment with " . get_bloginfo( 'name' ) . ".\n\n";
            $message .= "Appointment Details:\n";
            $message .= "Service: {$data['service']}\n";
            $message .= "Preferred Date: {$data['appointment_date']}\n";
            $message .= "Phone: {$data['phone']}\n\n";
            if ( $data['notes'] ) {
                $message .= "Additional Notes: {$data['notes']}\n\n";
            }
            $message .= "We will contact you within 24 hours to confirm your appointment.\n\n";
            $message .= "Best regards,\n" . get_bloginfo( 'name' );
        } else {
            $message = "New appointment request received:\n\n";
            $message .= "Name: {$data['name']}\n";
            $message .= "Email: {$data['email']}\n";
            $message .= "Phone: {$data['phone']}\n";
            $message .= "Service: {$data['service']}\n";
            $message .= "Preferred Date: {$data['appointment_date']}\n";
            if ( $data['notes'] ) {
                $message .= "Notes: {$data['notes']}\n";
            }
            $message .= "\nPlease follow up with the client to confirm the appointment.";
        }
        
        return $message;
    }
    
    /**
     * Get contact confirmation template
     */
    private function get_contact_confirmation_template( $data, $type ) {
        if ( $type === 'client' ) {
            $message = "Dear {$data['name']},\n\n";
            $message .= "Thank you for contacting " . get_bloginfo( 'name' ) . ".\n\n";
            $message .= "We have received your message and will respond within 24 hours.\n\n";
            $message .= "Best regards,\n" . get_bloginfo( 'name' );
        } else {
            $message = "New contact form submission:\n\n";
            $message .= "Name: {$data['name']}\n";
            $message .= "Email: {$data['email']}\n";
            if ( $data['phone'] ) {
                $message .= "Phone: {$data['phone']}\n";
            }
            if ( $data['service'] ) {
                $message .= "Service Interest: {$data['service']}\n";
            }
            $message .= "\nMessage:\n{$data['message']}";
        }
        
        return $message;
    }
    
    /**
     * Get email headers
     */
    private function get_email_headers() {
        $headers = array();
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';
        $headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>';
        
        return $headers;
    }
    
    /**
     * Log appointment event
     */
    private function log_appointment_event( $event_type, $appointment_id, $data ) {
        MensCore_Analytics::get_instance()->log_event( 'appointment_' . $event_type, array(
            'appointment_id' => $appointment_id,
            'service' => $data['service'],
            'appointment_date' => $data['appointment_date'],
        ) );
    }
    
    /**
     * Log contact event
     */
    private function log_contact_event( $event_type, $contact_id, $data ) {
        MensCore_Analytics::get_instance()->log_event( 'contact_' . $event_type, array(
            'contact_id' => $contact_id,
            'service' => $data['service'] ?? '',
        ) );
    }
    
    /**
     * Get appointments by status
     */
    public function get_appointments( $status = null, $limit = 50, $offset = 0 ) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'menscore_appointments';
        
        $sql = "SELECT * FROM $table";
        $params = array();
        
        if ( $status ) {
            $sql .= " WHERE status = %s";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT %d OFFSET %d";
        $params[] = $limit;
        $params[] = $offset;
        
        if ( ! empty( $params ) ) {
            $sql = $wpdb->prepare( $sql, $params );
        }
        
        return $wpdb->get_results( $sql );
    }
    
    /**
     * Update appointment status
     */
    public function update_appointment_status( $appointment_id, $status ) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'menscore_appointments';
        
        $result = $wpdb->update(
            $table,
            array( 'status' => $status, 'updated_at' => current_time( 'mysql' ) ),
            array( 'id' => $appointment_id ),
            array( '%s', '%s' ),
            array( '%d' )
        );
        
        if ( $result ) {
            // Log status change
            $this->log_appointment_event( 'status_changed', $appointment_id, array( 'new_status' => $status ) );
        }
        
        return $result !== false;
    }
    
    /**
     * Get appointment statistics
     */
    public function get_appointment_stats() {
        global $wpdb;
        
        $table = $wpdb->prefix . 'menscore_appointments';
        
        $stats = array();
        
        // Total appointments
        $stats['total'] = $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
        
        // By status
        $statuses = array( 'pending', 'confirmed', 'completed', 'cancelled' );
        foreach ( $statuses as $status ) {
            $stats[ $status ] = $wpdb->get_var( $wpdb->prepare(
                "SELECT COUNT(*) FROM $table WHERE status = %s",
                $status
            ) );
        }
        
        // This month
        $stats['this_month'] = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE created_at >= %s",
            date( 'Y-m-01 00:00:00' )
        ) );
        
        // Popular services
        $popular_services = $wpdb->get_results(
            "SELECT service, COUNT(*) as count FROM $table 
             WHERE service != '' 
             GROUP BY service 
             ORDER BY count DESC 
             LIMIT 5"
        );
        
        $stats['popular_services'] = $popular_services;
        
        return $stats;
    }
}