# MensCore Therapy - WordPress Website

Professional WordPress website for men's health and wellness therapy services. Built with security, performance, and best practices as top priorities.

## 🌟 Features

### Custom WordPress Theme
- **Responsive Design**: Mobile-first approach with full accessibility support
- **Custom Post Types**: Services and Testimonials with advanced meta fields  
- **Theme Customizer**: Extensive customization options for colors, content, and layout
- **Performance Optimized**: Lazy loading, script optimization, and caching ready
- **Security Hardened**: Version hiding, XML-RPC disabled, and enhanced security headers

### Core Functionality Plugin
- **🔒 Advanced Security**: Login protection, malware scanning, security monitoring
- **⚡ Performance Optimization**: Caching, compression, database optimization
- **🗺️ Dynamic Sitemap**: Auto-generating XML sitemaps for SEO
- **📅 Appointment System**: Complete booking and management system
- **📧 Email Notifications**: Professional templated email system
- **📊 Analytics**: Basic tracking and reporting dashboard

### Custom Shortcodes
- `[therapy_services]` - Display services in customizable grid
- `[therapy_testimonials]` - Testimonials slider with autoplay
- `[therapy_contact_form]` - Professional contact form
- `[therapy_booking]` - Appointment booking form
- `[therapy_faq]` - FAQ accordion interface
- `[therapy_team]` - Team members display

## 🚀 Quick Start

### Prerequisites
- PHP 7.4 or higher
- WordPress 5.0 or higher  
- MySQL 5.6 or higher

### Installation

1. **Set up WordPress**
   ```bash
   # Download WordPress core files to your web directory
   # Copy wp-config-sample.php to wp-config.php and configure
   ```

2. **Configure Database**
   ```php
   // Update wp-config.php with your database credentials
   define( 'DB_NAME', 'menscore_therapy' );
   define( 'DB_USER', 'your_username' );
   define( 'DB_PASSWORD', 'your_password' );
   define( 'DB_HOST', 'localhost' );
   ```

3. **Activate Theme and Plugin**
   - Go to Appearance → Themes and activate "MensCore Therapy"
   - Go to Plugins and activate "MensCore Core"

4. **Run Production Setup** (Optional)
   ```bash
   php setup-production.php
   ```
   This creates essential pages, menus, and sample content.

## 📁 Project Structure

```
mensCoreTherapy-web/
├── wp-config-sample.php              # WordPress configuration template
├── wp-content/
│   ├── themes/menscore-therapy/       # Custom theme
│   │   ├── style.css                  # Main stylesheet  
│   │   ├── functions.php              # Theme functionality
│   │   ├── inc/                       # Theme includes
│   │   │   ├── custom-hooks.php       # WordPress hooks
│   │   │   ├── shortcodes.php         # Custom shortcodes
│   │   │   ├── customizer.php         # Theme customizer
│   │   │   └── template-functions.php # Helper functions
│   │   ├── template-parts/            # Reusable templates
│   │   └── assets/                    # CSS, JS, images
│   └── plugins/menscore-core/         # Core functionality plugin
│       ├── includes/                  # Core classes
│       ├── admin/                     # Admin interface  
│       └── public/                    # Public features
├── setup-production.php               # Production setup script
├── DEVELOPER_GUIDE.md                 # Detailed developer documentation
└── README.md                          # This file
```

## 🎨 Customization

### Theme Customization
Access **Appearance → Customize** to configure:
- **Colors**: Primary and secondary theme colors
- **Contact Info**: Phone, email, address
- **Business Hours**: Operating hours display
- **Call-to-Action**: Homepage CTA section
- **Social Media**: Social platform links
- **SEO Settings**: Meta descriptions and keywords

### Adding Services
1. Go to **Services → Add New**
2. Add title, description, and featured image
3. Set service duration, price, and featured status
4. Use `[therapy_services]` shortcode to display

### Managing Appointments  
- View appointments in **MensCore → Appointments**
- Update appointment status (pending/confirmed/completed/cancelled)
- Email notifications sent automatically

## 🔧 Advanced Configuration

### Security Settings
Configure security features in **MensCore → Settings**:
- Enable/disable security monitoring
- Configure login attempt limits
- Set up malware scanning schedule

### Performance Optimization
- **Caching**: Automatic page and object caching
- **Compression**: GZIP compression enabled
- **Script Optimization**: Defer/async loading
- **Image Optimization**: Lazy loading implemented

### Analytics & Reporting
- Track page views and user interactions
- Monitor appointment conversion rates
- View popular services and content

## 🛡️ Security Features

- **Login Protection**: Limit failed login attempts
- **Malware Scanning**: Regular file integrity checks  
- **Security Headers**: CSP, XSS protection, frame options
- **File Upload Security**: Restricted file types and execution
- **Database Security**: SQL injection prevention
- **Version Hiding**: Remove WordPress version exposure

## ⚡ Performance Features

- **Page Caching**: Smart caching for non-logged-in users
- **Database Optimization**: Query optimization and object caching
- **Asset Optimization**: CSS/JS minification and combining
- **Image Optimization**: WebP support and lazy loading
- **CDN Ready**: Optimized for content delivery networks

## 📱 Mobile Optimization

- **Responsive Design**: Optimized for all device sizes
- **Touch-Friendly**: Large tap targets and gestures
- **Fast Loading**: Optimized for mobile networks
- **Accessibility**: WCAG 2.1 compliance

## 🔍 SEO Features

- **Dynamic Sitemaps**: Automatically generated XML sitemaps
- **Schema Markup**: Rich snippets for services and organization
- **Meta Optimization**: Customizable meta descriptions and titles
- **Social Sharing**: Open Graph and Twitter Card support
- **Page Speed**: Optimized for Google Core Web Vitals

## 🚀 Production Deployment

### Environment Setup
1. Set production constants in wp-config.php:
   ```php
   define( 'WP_DEBUG', false );
   define( 'FORCE_SSL_ADMIN', true );
   define( 'WP_CACHE', true );
   ```

2. Configure security settings
3. Set up SSL certificate
4. Configure backups
5. Test all functionality

### Monitoring
- Set up uptime monitoring
- Configure error logging
- Monitor performance metrics
- Regular security scans

## 📚 Documentation

- **[Developer Guide](DEVELOPER_GUIDE.md)**: Comprehensive development documentation
- **WordPress Codex**: Standard WordPress documentation
- **Theme Customization**: Built-in WordPress customizer help

## 🤝 Support

### Common Issues
- **Plugin Conflicts**: Deactivate other plugins to test
- **Theme Issues**: Switch to default theme to isolate
- **Performance**: Check caching and optimization settings
- **Security**: Review security logs and settings

### Getting Help
1. Check error logs in wp-content/debug.log
2. Review WordPress and plugin documentation  
3. Contact support team for assistance

## 📄 License

This project is licensed under the GPL v2 or later - see [WordPress licensing](https://wordpress.org/about/license/) for details.

## 🎯 Features Roadmap

- [ ] Advanced booking calendar integration
- [ ] Payment processing integration  
- [ ] Client portal with session history
- [ ] Advanced analytics dashboard
- [ ] Multi-language support
- [ ] API integrations for third-party services

---

**Built with ❤️ for professional therapy practices**
