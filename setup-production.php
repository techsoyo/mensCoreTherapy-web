<?php
/**
 * Production Setup Script for MensCore Therapy WordPress Project
 * 
 * This script helps configure the WordPress installation for production use.
 * Run this once after deploying to production environment.
 * 
 * Usage: php setup-production.php
 * 
 * @package MensCoreTherapy
 * @since 1.0.0
 */

// Prevent web access
if ( isset( $_SERVER['HTTP_HOST'] ) ) {
    die( 'This script must be run from command line.' );
}

echo "MensCore Therapy - Production Setup Script\n";
echo "==========================================\n\n";

// Check if WordPress is available
if ( ! file_exists( 'wp-config.php' ) ) {
    echo "ERROR: wp-config.php not found. Please set up WordPress first.\n";
    exit( 1 );
}

// Load WordPress
define( 'WP_USE_THEMES', false );
require_once( 'wp-config.php' );
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

echo "Setting up production environment...\n";

// 1. Update WordPress options for production
update_option( 'blog_public', 1 ); // Allow search engine indexing
update_option( 'default_ping_status', 'closed' ); // Disable pingbacks by default
update_option( 'default_comment_status', 'closed' ); // Disable comments by default

// 2. Create necessary pages
create_essential_pages();

// 3. Set up menus
setup_navigation_menus();

// 4. Configure theme customizer defaults
setup_theme_defaults();

// 5. Activate and configure plugins
activate_core_plugin();

// 6. Set up security configurations
setup_security_configs();

// 7. Create sample content (optional)
$create_sample = readline( "Create sample content? (y/n): " );
if ( strtolower( $create_sample ) === 'y' ) {
    create_sample_content();
}

// 8. Final optimizations
run_final_optimizations();

echo "\n✅ Production setup completed successfully!\n";
echo "\nNext steps:\n";
echo "1. Configure your theme in Appearance > Customize\n";
echo "2. Set up your services in the Services post type\n";
echo "3. Configure MensCore plugin settings\n";
echo "4. Test all functionality\n";
echo "5. Set up regular backups\n\n";

/**
 * Create essential pages
 */
function create_essential_pages() {
    echo "Creating essential pages...\n";
    
    $pages = array(
        'Home' => array(
            'title' => 'Home',
            'content' => get_homepage_content(),
            'template' => 'page',
            'set_as_front' => true,
        ),
        'About' => array(
            'title' => 'About Us',
            'content' => get_about_content(),
            'template' => 'page',
        ),
        'Services' => array(
            'title' => 'Our Services',
            'content' => get_services_content(),
            'template' => 'page',
        ),
        'Contact' => array(
            'title' => 'Contact Us',
            'content' => get_contact_content(),
            'template' => 'page',
        ),
        'Book Appointment' => array(
            'title' => 'Book Appointment',
            'content' => get_booking_content(),
            'template' => 'page',
        ),
        'Privacy Policy' => array(
            'title' => 'Privacy Policy',
            'content' => get_privacy_content(),
            'template' => 'page',
        ),
    );
    
    foreach ( $pages as $slug => $page_data ) {
        $existing_page = get_page_by_title( $page_data['title'] );
        
        if ( ! $existing_page ) {
            $page_id = wp_insert_post( array(
                'post_title' => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => sanitize_title( $page_data['title'] ),
            ) );
            
            if ( $page_id && isset( $page_data['set_as_front'] ) ) {
                update_option( 'show_on_front', 'page' );
                update_option( 'page_on_front', $page_id );
            }
            
            echo "  ✅ Created: {$page_data['title']}\n";
        } else {
            echo "  ⚠️  Already exists: {$page_data['title']}\n";
        }
    }
}

/**
 * Set up navigation menus
 */
function setup_navigation_menus() {
    echo "Setting up navigation menus...\n";
    
    // Create primary menu
    $menu_name = 'Primary Menu';
    $menu_exists = wp_get_nav_menu_object( $menu_name );
    
    if ( ! $menu_exists ) {
        $menu_id = wp_create_nav_menu( $menu_name );
        
        // Add menu items
        $menu_items = array(
            'Home' => home_url(),
            'About' => get_page_link( get_page_by_title( 'About Us' )->ID ?? 0 ),
            'Services' => get_page_link( get_page_by_title( 'Our Services' )->ID ?? 0 ),
            'Contact' => get_page_link( get_page_by_title( 'Contact Us' )->ID ?? 0 ),
        );
        
        foreach ( $menu_items as $title => $url ) {
            wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title' => $title,
                'menu-item-url' => $url,
                'menu-item-status' => 'publish',
            ) );
        }
        
        // Assign menu to theme location
        $locations = get_theme_mod( 'nav_menu_locations' );
        $locations['primary'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
        
        echo "  ✅ Created primary menu\n";
    } else {
        echo "  ⚠️  Primary menu already exists\n";
    }
}

/**
 * Set up theme defaults
 */
function setup_theme_defaults() {
    echo "Configuring theme defaults...\n";
    
    // Set theme customizer defaults
    set_theme_mod( 'menscore_primary_color', '#3498db' );
    set_theme_mod( 'menscore_secondary_color', '#2c3e50' );
    set_theme_mod( 'menscore_hero_title', 'Your Journey to Wellness Starts Here' );
    set_theme_mod( 'menscore_hero_subtitle', 'Professional therapy services for men\'s health and wellness' );
    set_theme_mod( 'menscore_cta_title', 'Ready to Start Your Wellness Journey?' );
    set_theme_mod( 'menscore_cta_text', 'Take the first step towards better health and wellness. Contact us today to schedule your consultation.' );
    set_theme_mod( 'menscore_cta_button_text', 'Get Started' );
    set_theme_mod( 'menscore_cta_button_url', get_page_link( get_page_by_title( 'Contact Us' )->ID ?? 0 ) );
    
    echo "  ✅ Theme defaults configured\n";
}

/**
 * Activate core plugin
 */
function activate_core_plugin() {
    echo "Activating MensCore Core plugin...\n";
    
    if ( ! is_plugin_active( 'menscore-core/menscore-core.php' ) ) {
        activate_plugin( 'menscore-core/menscore-core.php' );
        echo "  ✅ MensCore Core plugin activated\n";
    } else {
        echo "  ⚠️  MensCore Core plugin already active\n";
    }
}

/**
 * Set up security configurations
 */
function setup_security_configs() {
    echo "Configuring security settings...\n";
    
    // Create .htaccess security rules
    $htaccess_content = "# Security configurations added by MensCore setup\n\n";
    $htaccess_content .= "# Prevent access to wp-config.php\n";
    $htaccess_content .= "<files wp-config.php>\norder allow,deny\ndeny from all\n</files>\n\n";
    $htaccess_content .= "# Prevent directory browsing\nOptions -Indexes\n\n";
    $htaccess_content .= "# Protect against script injection\n";
    $htaccess_content .= "RewriteEngine On\n";
    $htaccess_content .= "RewriteCond %{QUERY_STRING} (\\/|%2F).*(\\/|%2F) [NC]\n";
    $htaccess_content .= "RewriteRule .* - [F]\n\n";
    
    file_put_contents( ABSPATH . '.htaccess', $htaccess_content, FILE_APPEND );
    
    echo "  ✅ Security configurations added\n";
}

/**
 * Create sample content
 */
function create_sample_content() {
    echo "Creating sample content...\n";
    
    // Create sample services
    create_sample_services();
    
    // Create sample testimonials
    create_sample_testimonials();
    
    echo "  ✅ Sample content created\n";
}

/**
 * Create sample services
 */
function create_sample_services() {
    $services = array(
        array(
            'title' => 'Individual Therapy',
            'content' => 'One-on-one therapy sessions focused on your personal growth and mental health needs.',
            'duration' => '60',
            'price' => '$120',
            'featured' => '1',
        ),
        array(
            'title' => 'Couples Counseling',
            'content' => 'Professional relationship counseling to help couples strengthen their bond and communication.',
            'duration' => '90',
            'price' => '$150',
            'featured' => '1',
        ),
        array(
            'title' => 'Group Therapy',
            'content' => 'Supportive group sessions where you can connect with others facing similar challenges.',
            'duration' => '90',
            'price' => '$80',
            'featured' => '0',
        ),
    );
    
    foreach ( $services as $service ) {
        $post_id = wp_insert_post( array(
            'post_title' => $service['title'],
            'post_content' => $service['content'],
            'post_status' => 'publish',
            'post_type' => 'services',
        ) );
        
        if ( $post_id ) {
            update_post_meta( $post_id, '_service_duration', $service['duration'] );
            update_post_meta( $post_id, '_service_price', $service['price'] );
            update_post_meta( $post_id, '_service_featured', $service['featured'] );
        }
    }
}

/**
 * Create sample testimonials
 */
function create_sample_testimonials() {
    $testimonials = array(
        array(
            'title' => 'John D.',
            'content' => 'The therapy sessions have been life-changing. I finally feel like I have the tools to manage my stress and anxiety effectively.',
        ),
        array(
            'title' => 'Michael S.',
            'content' => 'Professional, caring, and effective. The therapist created a safe space where I could open up about my challenges.',
        ),
    );
    
    foreach ( $testimonials as $testimonial ) {
        wp_insert_post( array(
            'post_title' => $testimonial['title'],
            'post_content' => $testimonial['content'],
            'post_status' => 'publish',
            'post_type' => 'testimonials',
        ) );
    }
}

/**
 * Run final optimizations
 */
function run_final_optimizations() {
    echo "Running final optimizations...\n";
    
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Clear any existing caches
    if ( function_exists( 'wp_cache_flush' ) ) {
        wp_cache_flush();
    }
    
    echo "  ✅ Optimizations complete\n";
}

/**
 * Get homepage content
 */
function get_homepage_content() {
    return '
<h2>Welcome to MensCore Therapy</h2>
<p>Professional therapy and wellness services designed specifically for men. Our experienced therapists provide a safe, confidential environment where you can address your mental health needs and personal growth goals.</p>

[therapy_services columns="3" limit="3" featured="true"]

<h3>Why Choose MensCore Therapy?</h3>
<ul>
<li>Specialized in men\'s mental health</li>
<li>Experienced, licensed therapists</li>
<li>Confidential and supportive environment</li>
<li>Flexible scheduling options</li>
</ul>

[therapy_testimonials limit="3" autoplay="true"]
';
}

/**
 * Get about content
 */
function get_about_content() {
    return '
<h2>About MensCore Therapy</h2>
<p>At MensCore Therapy, we understand that men face unique challenges when it comes to mental health and wellness. Our team of experienced therapists specializes in providing targeted support for men\'s specific needs.</p>

<h3>Our Mission</h3>
<p>To provide accessible, effective mental health services that empower men to overcome challenges, build resilience, and achieve their personal and professional goals.</p>

<h3>Our Approach</h3>
<p>We believe in creating a judgment-free environment where men can openly discuss their concerns and work toward positive change. Our evidence-based therapeutic approaches are tailored to address the unique aspects of men\'s mental health.</p>

[therapy_team limit="4" columns="2"]
';
}

/**
 * Get services content
 */
function get_services_content() {
    return '
<h2>Our Services</h2>
<p>We offer a comprehensive range of therapy and wellness services designed to support men\'s mental health and personal development.</p>

[therapy_services columns="3" limit="6"]

<h3>Specialized Areas</h3>
<ul>
<li>Anxiety and Depression</li>
<li>Relationship Issues</li>
<li>Work-Life Balance</li>
<li>Anger Management</li>
<li>Life Transitions</li>
<li>Personal Development</li>
</ul>

<p>Contact us today to learn more about how we can support your wellness journey.</p>
';
}

/**
 * Get contact content
 */
function get_contact_content() {
    return '
<h2>Contact Us</h2>
<p>Ready to take the first step? We\'re here to help. Reach out to schedule a consultation or ask any questions you may have.</p>

[therapy_contact_form title="Get in Touch"]

<h3>Office Information</h3>
<p>Our office is conveniently located and offers a comfortable, private setting for all appointments.</p>

<h3>Frequently Asked Questions</h3>
[therapy_faq category="contact" limit="5"]
';
}

/**
 * Get booking content
 */
function get_booking_content() {
    return '
<h2>Book Your Appointment</h2>
<p>Schedule your consultation with one of our experienced therapists. We offer flexible scheduling to accommodate your needs.</p>

[therapy_booking]

<h3>What to Expect</h3>
<p>Your first session will be an opportunity to discuss your goals, concerns, and questions. We\'ll work together to develop a personalized treatment plan that addresses your specific needs.</p>

<h3>Preparation</h3>
<ul>
<li>Arrive 10 minutes early for paperwork</li>
<li>Bring a list of any medications you\'re taking</li>
<li>Think about your goals for therapy</li>
<li>Prepare any questions you\'d like to ask</li>
</ul>
';
}

/**
 * Get privacy content
 */
function get_privacy_content() {
    return '
<h2>Privacy Policy</h2>
<p>Your privacy is important to us. This policy explains how we collect, use, and protect your personal information.</p>

<h3>Information We Collect</h3>
<p>We collect information you provide when you contact us, book appointments, or use our services.</p>

<h3>How We Use Your Information</h3>
<p>We use your information to provide services, communicate with you, and improve our offerings.</p>

<h3>Information Security</h3>
<p>We implement appropriate security measures to protect your personal information.</p>

<h3>Contact Us</h3>
<p>If you have questions about this privacy policy, please contact us.</p>
';
}
?>