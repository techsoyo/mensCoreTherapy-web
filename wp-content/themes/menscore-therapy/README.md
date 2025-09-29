# MensCore Therapy WordPress Theme

A modern, responsive WordPress theme designed specifically for men's mental health and wellness services, featuring a beautiful neomorphic design system.

## 🎨 Features

### Design
- **Neomorphic Design System**: Soft, elevated UI elements with subtle shadows
- **Responsive Layout**: Optimized for all device sizes and screen resolutions
- **Modern Typography**: Montserrat and Montserrat Alternates Google Fonts
- **Accessible Color Palette**: High contrast ratios and WCAG compliant
- **Smooth Animations**: CSS transitions and JavaScript interactions

### Functionality
- **Parallax Hero Section**: Engaging background effect with smooth scrolling
- **Mobile-First Navigation**: Collapsible menu with smooth transitions
- **Contact Forms**: Built-in validation and user feedback
- **Newsletter Signup**: Email collection with form validation
- **Social Media Integration**: Ready-to-use social media links
- **SEO Optimized**: Clean HTML structure and semantic markup

### WordPress Features
- **Theme Customizer**: Live preview customization options
- **Widget Areas**: Multiple widget-ready areas (sidebar + 3 footer areas)
- **Menu Support**: Primary and footer navigation menus
- **Custom Logo**: Upload and display custom branding
- **Post Formats**: Support for various content types
- **Comments System**: Styled comment forms and display

## 📁 File Structure

```
menscore-therapy/
├── style.css                 # Main stylesheet with theme info
├── functions.php             # Theme functionality and hooks
├── index.php                 # Main template file
├── header.php                # Header template
├── footer.php                # Footer template
├── page.php                  # Page template
├── single.php                # Single post template
├── archive.php               # Archive template
├── search.php                # Search results template
├── 404.php                   # 404 error template
├── comments.php              # Comments template
├── sidebar.php               # Sidebar template
├── searchform.php            # Search form template
├── assets/
│   ├── css/
│   │   └── additional.css    # Additional styles
│   ├── js/
│   │   ├── theme.js          # jQuery version (deprecated)
│   │   └── theme-vanilla.js  # Vanilla JS version (active)
│   └── images/
│       └── hero-bg.jpg       # Hero background image
└── inc/                      # Theme includes (future use)
```

## 🚀 Installation

1. **Upload Theme**: Upload the `menscore-therapy` folder to `/wp-content/themes/`
2. **Activate Theme**: Go to Appearance > Themes and activate "MensCore Therapy"
3. **Configure Menus**: Create menus at Appearance > Menus and assign to theme locations
4. **Customize**: Use Appearance > Customize to configure theme options
5. **Add Content**: Create pages and posts to populate your site

## ⚙️ Configuration

### Required Setup
1. **Navigation Menus**:
   - Create a "Primary Menu" and assign to "Primary Menu" location
   - Create a "Footer Menu" and assign to "Footer Menu" location

2. **Customizer Options**:
   - Set hero title and subtitle
   - Upload hero background image
   - Configure site logo and branding

3. **Widget Areas**:
   - Sidebar (right sidebar for pages/posts)
   - Footer Widget Area 1 (contact information)
   - Footer Widget Area 2 (quick links)
   - Footer Widget Area 3 (social media and newsletter)

### Recommended Plugins
- **Contact Form 7**: For advanced contact forms
- **Yoast SEO**: For search engine optimization
- **Akismet**: For spam protection on comments
- **Jetpack**: For additional functionality and security

## 🎯 Customization

### CSS Variables
The theme uses CSS custom properties for easy customization:

```css
:root {
    --primary-bg: #e6e6e6;       /* Main background color */
    --secondary-bg: #f0f0f0;     /* Secondary background */
    --text-primary: #333333;     /* Primary text color */
    --text-secondary: #666666;   /* Secondary text color */
    --accent-color: #4a90e2;     /* Accent/brand color */
    --shadow-light: #ffffff;     /* Light shadow color */
    --shadow-dark: #cccccc;      /* Dark shadow color */
    --border-radius: 20px;       /* Standard border radius */
}
```

### Neomorphic Elements
Apply neomorphic styling to elements using these classes:
- `.neomorphic`: Elevated/raised appearance
- `.neomorphic-inset`: Inset/pressed appearance

### JavaScript Customization
The theme uses vanilla JavaScript for all interactions. Main functions:
- `initParallax()`: Parallax scrolling effect
- `initSmoothScrolling()`: Smooth anchor link navigation
- `initMobileMenu()`: Mobile menu functionality
- `initFormValidation()`: Form validation and feedback

## 🔧 Development

### Prerequisites
- WordPress 5.0 or higher
- PHP 7.4 or higher
- Modern browser support (ES6+)

### Local Development
1. Use a local WordPress environment (Local, XAMPP, etc.)
2. Enable WordPress debug mode for development
3. Use browser developer tools for testing and debugging

### Build Process
Currently using direct file editing. Future versions will include:
- Sass/SCSS preprocessing
- JavaScript bundling and minification
- Image optimization
- Asset versioning

## 🐛 Known Issues

### Current Limitations
1. **Font Loading**: Google Fonts may not load in some environments due to CDN restrictions
2. **Image Assets**: Placeholder background images need to be replaced with actual content
3. **Mobile Menu**: Advanced mobile interactions need refinement
4. **Accessibility**: Additional ARIA labels and keyboard navigation needed

### Workarounds
- **Fonts**: Theme includes fallback fonts and will degrade gracefully
- **Images**: Replace placeholder images in `assets/images/` directory
- **Mobile**: Basic mobile menu functionality is working

## 📱 Browser Support

### Fully Supported
- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

### Partially Supported
- Internet Explorer 11 (basic functionality only)
- Older mobile browsers (may lack some animations)

## 🤝 Contributing

### Bug Reports
Please report bugs by creating an issue with:
- WordPress version
- Theme version
- Browser and version
- Steps to reproduce
- Expected vs actual behavior

### Feature Requests
We welcome feature requests! Please describe:
- Use case and benefit
- Proposed implementation
- Compatibility considerations

## 📄 License

This theme is released under the GPL v2 or later license.

## 🆘 Support

### Documentation
- [WordPress Codex](https://codex.wordpress.org/)
- [Theme Development Handbook](https://developer.wordpress.org/themes/)

### Community
- WordPress.org support forums
- Theme-specific GitHub issues (if applicable)

## 📋 Changelog

### Version 1.0.0-alpha (Current)
- Initial theme development
- Core WordPress functionality
- Neomorphic design system
- Responsive layout implementation
- JavaScript interactions
- Form validation and feedback

### Planned Updates
- Version 1.0.0-beta: Bug fixes and refinements
- Version 1.0.0: Production-ready release
- Version 1.1.0: Additional customizer options
- Version 1.2.0: Advanced features and integrations

---

**Created by**: TechSoyo  
**Last Updated**: September 2024  
**Version**: 1.0.0-alpha  
**WordPress Compatibility**: 5.0+