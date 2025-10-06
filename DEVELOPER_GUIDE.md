# MensCore Therapy - Developer Guide

## Project Overview

This is a professional WordPress project for men's health and wellness therapy services. The project prioritizes security, performance, and best practices while maintaining production readiness and easy developer onboarding.

## Project Structure

```
mensCoreTherapy-web/
├── wp-config-sample.php          # WordPress configuration template
├── wp-content/
│   ├── themes/
│   │   └── menscore-therapy/     # Custom theme
│   │       ├── style.css         # Main stylesheet with theme info
│   │       ├── functions.php     # Theme functions and features
│   │       ├── index.php         # Main template file
│   │       ├── header.php        # Header template
│   │       ├── footer.php        # Footer template
│   │       ├── sidebar.php       # Sidebar template
│   │       ├── single.php        # Single post template
│   │       ├── page.php          # Page template
│   │       ├── inc/              # Theme includes
│   │       │   ├── custom-hooks.php      # Custom WordPress hooks
│   │       │   ├── shortcodes.php        # Custom shortcodes
│   │       │   ├── customizer.php        # Theme customizer settings
│   │       │   └── template-functions.php # Helper functions
│   │       ├── template-parts/   # Reusable template parts
│   │       │   ├── content.php           # Post content template
│   │       │   ├── content-page.php      # Page content template
│   │       │   └── content-none.php      # No content template
│   │       └── assets/           # Theme assets
│   │           ├── css/
│   │           │   └── custom.css        # Additional CSS
│   │           └── js/
│   │               └── main.js           # Main JavaScript
│   └── plugins/
│       └── menscore-core/        # Core functionality plugin
│           ├── menscore-core.php         # Main plugin file
│           ├── includes/         # Core classes
│           │   ├── class-security.php    # Security features
│           │   ├── class-performance.php # Performance optimization
│           │   ├── class-sitemap.php     # Dynamic sitemap
│           │   ├── class-appointments.php # Appointment management
│           │   ├── class-notifications.php # Email notifications
│           │   └── class-analytics.php   # Basic analytics
│           ├── admin/            # Admin interface
│           │   ├── class-admin.php       # Admin dashboard
│           │   └── class-settings.php    # Settings page
│           └── public/           # Public functionality
│               └── class-public.php      # Public-facing features
├── .gitignore                    # Git ignore rules
└── DEVELOPER_GUIDE.md           # This file
```

## Key Features

### Custom Theme Features
- **Responsive Design**: Mobile-first approach with accessibility features
- **Custom Post Types**: Services and Testimonials
- **Theme Customizer**: Extensive customization options
- **Security Enhancements**: Version hiding, XML-RPC disable, etc.
- **Performance Optimizations**: Script deferring, query optimization
- **Custom Hooks System**: Extensible hook architecture
- **Advanced Shortcodes**: Ready-to-use components

### Core Plugin Features
- **Security Module**: Login protection, malware scanning, security headers
- **Performance Module**: Caching, compression, script optimization
- **Dynamic Sitemap**: Auto-generating XML sitemaps
- **Appointment System**: Complete booking and management system
- **Email Notifications**: Template-based email system
- **Analytics**: Basic tracking and reporting

## Custom Shortcodes

### [therapy_services]
Displays a grid of therapy services.
```
[therapy_services columns="3" limit="6" featured="true"]
```
- `columns`: Number of columns (2, 3, 4)
- `limit`: Number of services to show
- `featured`: Show only featured services (true/false)

### [therapy_testimonials]
Displays testimonials in a slider format.
```
[therapy_testimonials limit="5" autoplay="true"]
```
- `limit`: Number of testimonials
- `autoplay`: Auto-advance slider (true/false)

### [therapy_contact_form]
Displays a contact form.
```
[therapy_contact_form title="Contact Us"]
```
- `title`: Form title

### [therapy_booking]
Displays an appointment booking form.
```
[therapy_booking service="consultation"]
```
- `service`: Pre-select a service

### [therapy_faq]
Displays an FAQ accordion.
```
[therapy_faq category="general" limit="10"]
```
- `category`: FAQ category
- `limit`: Number of FAQs

### [therapy_team]
Displays team members.
```
[therapy_team limit="4" columns="2"]
```
- `limit`: Number of team members
- `columns`: Grid columns

## Custom Hooks

### Theme Hooks
- `menscore_after_header`: Adds content after header (breadcrumbs)
- `menscore_before_footer`: Adds content before footer (CTA section)
- `menscore_theme_loaded`: Fired when theme is fully loaded

### Plugin Hooks
- `menscore_security_scan_complete`: Fired after security scan
- `menscore_performance_cache_cleared`: When cache is cleared
- `menscore_appointment_created`: When new appointment is created

## Database Tables

### menscore_appointments
Stores appointment bookings.
```sql
- id (INT): Primary key
- name (VARCHAR): Client name
- email (VARCHAR): Client email
- phone (VARCHAR): Client phone
- service (VARCHAR): Requested service
- appointment_date (DATETIME): Preferred date/time
- status (VARCHAR): pending/confirmed/completed/cancelled
- notes (TEXT): Additional notes
- created_at (DATETIME): Creation timestamp
- updated_at (DATETIME): Last update timestamp
```

### menscore_contacts
Stores contact form submissions.
```sql
- id (INT): Primary key
- name (VARCHAR): Contact name
- email (VARCHAR): Contact email
- phone (VARCHAR): Contact phone (optional)
- service (VARCHAR): Service interest (optional)
- message (TEXT): Contact message
- status (VARCHAR): unread/read/replied
- created_at (DATETIME): Creation timestamp
```

### menscore_analytics
Stores analytics events.
```sql
- id (INT): Primary key
- event_type (VARCHAR): Type of event
- event_data (TEXT): JSON event data
- user_id (INT): WordPress user ID (if logged in)
- session_id (VARCHAR): Session identifier
- ip_address (VARCHAR): Client IP address
- user_agent (TEXT): Browser user agent
- created_at (DATETIME): Event timestamp
```

## Development Setup

### Prerequisites
- PHP 7.4 or higher
- WordPress 5.0 or higher
- MySQL 5.6 or higher

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/techsoyo/mensCoreTherapy-web.git
   cd mensCoreTherapy-web
   ```

2. **Set up WordPress**
   - Download and extract WordPress core files
   - Copy `wp-config-sample.php` to `wp-config.php`
   - Update database credentials and security keys

3. **Activate Theme and Plugin**
   - Activate the "MensCore Therapy" theme
   - Activate the "MensCore Core" plugin

4. **Import Sample Content** (Optional)
   - Create sample services and testimonials
   - Set up menu structure
   - Configure theme customizer options

### Theme Customization

#### Adding New Post Types
Edit `wp-content/themes/menscore-therapy/functions.php`:
```php
function menscore_register_custom_post_type() {
    register_post_type( 'your_post_type', array(
        'labels' => array(
            'name' => __( 'Your Post Type', 'menscore-therapy' ),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array( 'title', 'editor', 'thumbnail' ),
    ) );
}
add_action( 'init', 'menscore_register_custom_post_type' );
```

#### Adding Custom Fields
Use the meta box system in `inc/custom-hooks.php` as a reference.

#### Customizing Styles
- Main styles: `style.css`
- Additional styles: `assets/css/custom.css`

### Plugin Customization

#### Adding New Features
Create new classes in the `includes/` directory following the existing pattern.

#### Extending Security
Add new security rules in `class-security.php`.

#### Performance Tweaks
Modify caching and optimization rules in `class-performance.php`.

## Security Best Practices

### Implemented Security Features
- WordPress version hiding
- XML-RPC disabled
- Login attempt limiting
- File upload restrictions
- Security headers (CSP, XSS protection)
- Directory browsing disabled
- User enumeration blocked

### Additional Recommendations
- Use strong passwords and 2FA
- Keep WordPress core and plugins updated
- Regular security scans
- Database backups
- SSL/TLS encryption

## Performance Optimizations

### Implemented Optimizations
- GZIP compression
- Script deferring and async loading
- CSS/JS minification and combining
- Database query optimization
- Object caching
- Lazy image loading
- CDN-ready asset structure

### Monitoring Performance
- Use the plugin's analytics dashboard
- Monitor page load times
- Check database query counts
- Optimize images before upload

## Production Deployment

### Pre-deployment Checklist
- [ ] Update wp-config.php with production settings
- [ ] Set WP_DEBUG to false
- [ ] Configure caching appropriately
- [ ] Test all forms and functionality
- [ ] Verify SSL certificate
- [ ] Set up regular backups
- [ ] Configure monitoring

### Environment Variables
Set these in wp-config.php:
```php
// Security
define( 'FORCE_SSL_ADMIN', true );
define( 'DISALLOW_FILE_EDIT', true );

// Performance
define( 'WP_CACHE', true );
define( 'COMPRESS_CSS', true );
define( 'COMPRESS_SCRIPTS', true );

// Debug (set to false in production)
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
```

## Troubleshooting

### Common Issues

#### Plugin Activation Errors
- Check PHP version (7.4+ required)
- Verify WordPress version (5.0+ required)
- Check file permissions

#### Theme Display Issues
- Clear any caching
- Check for plugin conflicts
- Verify theme files are complete

#### Form Submission Problems
- Check email configuration
- Verify nonce security
- Review server error logs

### Debug Mode
Enable debug mode in wp-config.php:
```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

## Support and Maintenance

### Regular Maintenance Tasks
- Update WordPress core, themes, and plugins
- Review security logs
- Clean up expired transients
- Optimize database
- Monitor performance metrics

### Getting Help
- Check the WordPress Codex
- Review error logs
- Use WordPress debugging tools
- Consult the theme/plugin documentation

## Contributing

### Code Standards
- Follow WordPress Coding Standards
- Use proper sanitization and validation
- Comment complex functions
- Test thoroughly before committing

### Git Workflow
1. Create feature branches from main
2. Make focused commits with clear messages
3. Test changes locally
4. Submit pull requests for review

## License

This project is licensed under the GPL v2 or later - see WordPress licensing for details.