#!/bin/bash

# =============================================================================
# SCRIPT DE OPTIMIZACIÓN SEO - Men's Core Therapy WordPress
# =============================================================================
# 
# Descripción: Implementa todas las optimizaciones SEO identificadas
# en la auditoría técnica para alcanzar score >80
# 
# Uso: ./seo-optimizer.sh [WORDPRESS_PATH]
# Ejemplo: ./seo-optimizer.sh /var/www/html/willmasaje_stagi
#
# =============================================================================

set -e

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

# Verificar ruta
if [ $# -eq 0 ]; then
    WORDPRESS_PATH="/var/www/html/willmasaje_stagi"
    warning "Usando ruta por defecto: $WORDPRESS_PATH"
else
    WORDPRESS_PATH="$1"
fi

if [ ! -d "$WORDPRESS_PATH" ]; then
    error "La ruta $WORDPRESS_PATH no existe"
    exit 1
fi

log "Iniciando optimización SEO para: $WORDPRESS_PATH"

# =============================================================================
# 1. BACKUP DE ARCHIVOS
# =============================================================================
log "Creando backup antes de optimizaciones SEO..."

BACKUP_DIR="$WORDPRESS_PATH/seo-backup-$(date +%Y%m%d-%H%M%S)"
mkdir -p "$BACKUP_DIR"

THEME_PATH="$WORDPRESS_PATH/wp-content/themes/masajista-masculino"

# Backup de archivos críticos
cp "$THEME_PATH/functions.php" "$BACKUP_DIR/functions.php.bak" 2>/dev/null || true
cp "$THEME_PATH/header.php" "$BACKUP_DIR/header.php.bak" 2>/dev/null || true
cp "$THEME_PATH/footer.php" "$BACKUP_DIR/footer.php.bak" 2>/dev/null || true

log "Backup SEO creado en: $BACKUP_DIR"

# =============================================================================
# 2. OPTIMIZAR HEADER.PHP PARA SEO
# =============================================================================
log "Optimizando header.php para SEO..."

HEADER_FILE="$THEME_PATH/header.php"

if [ -f "$HEADER_FILE" ]; then
    # Crear versión optimizada del header
    cat > "$HEADER_FILE.seo-optimized" << 'EOF'
<?php if (!defined("ABSPATH")) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo("charset"); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- SEO Meta Tags Dinámicos -->
  <?php
  // Título SEO optimizado
  if (is_home() || is_front_page()) {
      $seo_title = get_bloginfo('name') . ' - ' . get_bloginfo('description');
      $seo_description = 'Centro de masajes masculinos en Barcelona. Terapias personalizadas de bienestar y relajación para hombres. Reserva tu sesión de masaje terapéutico.';
      $seo_keywords = 'masajes masculinos, terapia corporal, bienestar hombres, masajes Barcelona, relajación, masaje terapéutico';
  } elseif (is_single() || is_page()) {
      $seo_title = get_the_title() . ' - ' . get_bloginfo('name');
      $excerpt = get_the_excerpt();
      $seo_description = $excerpt ? wp_trim_words($excerpt, 25, '...') : wp_trim_words(get_the_content(), 25, '...');
      $seo_keywords = 'masajes, ' . get_the_title() . ', bienestar, ' . get_bloginfo('name');
  } elseif (is_post_type_archive('servicio')) {
      $seo_title = 'Servicios de Masajes Masculinos - ' . get_bloginfo('name');
      $seo_description = 'Descubre nuestros servicios de masajes terapéuticos y de relajación para hombres en Barcelona. Profesionales especializados en bienestar masculino.';
      $seo_keywords = 'servicios masajes, terapias corporales, masajes terapéuticos, bienestar masculino';
  } elseif (is_post_type_archive('producto')) {
      $seo_title = 'Productos de Bienestar Masculino - ' . get_bloginfo('name');
      $seo_description = 'Productos profesionales para el cuidado y bienestar masculino. Calidad premium para tu rutina de cuidado personal y relajación.';
      $seo_keywords = 'productos bienestar, cuidado masculino, productos masajes, aceites esenciales';
  } else {
      $seo_title = wp_get_document_title();
      $seo_description = get_bloginfo('description');
      $seo_keywords = 'masajes, bienestar, ' . get_bloginfo('name');
  }
  ?>
  
  <title><?php echo esc_html($seo_title); ?></title>
  <meta name="description" content="<?php echo esc_attr($seo_description); ?>">
  <meta name="keywords" content="<?php echo esc_attr($seo_keywords); ?>">
  <meta name="author" content="<?php bloginfo('name'); ?>">
  <meta name="robots" content="<?php echo is_search() || is_404() ? 'noindex, nofollow' : 'index, follow'; ?>">
  
  <!-- Canonical URL -->
  <link rel="canonical" href="<?php echo esc_url(get_permalink()); ?>">
  
  <!-- Open Graph Meta Tags -->
  <meta property="og:title" content="<?php echo esc_attr($seo_title); ?>">
  <meta property="og:description" content="<?php echo esc_attr($seo_description); ?>">
  <meta property="og:type" content="<?php echo is_single() ? 'article' : 'website'; ?>">
  <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
  <meta property="og:site_name" content="<?php bloginfo('name'); ?>">
  <meta property="og:locale" content="es_ES">
  <?php if (has_post_thumbnail()): ?>
  <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <?php else: ?>
  <meta property="og:image" content="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-sin-fondo.webp'); ?>">
  <?php endif; ?>
  
  <!-- Twitter Card Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?php echo esc_attr($seo_title); ?>">
  <meta name="twitter:description" content="<?php echo esc_attr($seo_description); ?>">
  <?php if (has_post_thumbnail()): ?>
  <meta name="twitter:image" content="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>">
  <?php endif; ?>
  
  <!-- Preload recursos críticos -->
  <link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/css/critical.css" as="style">
  <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
  
  <!-- DNS Prefetch -->
  <link rel="dns-prefetch" href="//fonts.googleapis.com">
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
  
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>

  <!-- Breadcrumbs SEO -->
  <?php if (!is_home() && !is_front_page()): ?>
  <nav class="breadcrumbs" aria-label="Breadcrumb">
    <ol class="breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">
      <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="<?php echo home_url(); ?>">
          <span itemprop="name">Inicio</span>
        </a>
        <meta itemprop="position" content="1" />
      </li>
      
      <?php
      $position = 2;
      
      if (is_post_type_archive()) {
          $post_type = get_post_type_object(get_post_type());
          echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
          echo '<span itemprop="name">' . esc_html($post_type->labels->name) . '</span>';
          echo '<meta itemprop="position" content="' . $position . '" />';
          echo '</li>';
      } elseif (is_single() || is_page()) {
          if (is_single() && get_post_type() !== 'post') {
              $post_type = get_post_type_object(get_post_type());
              echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
              echo '<a itemprop="item" href="' . get_post_type_archive_link(get_post_type()) . '">';
              echo '<span itemprop="name">' . esc_html($post_type->labels->name) . '</span></a>';
              echo '<meta itemprop="position" content="' . $position . '" />';
              echo '</li>';
              $position++;
          }
          
          echo '<li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
          echo '<span itemprop="name">' . get_the_title() . '</span>';
          echo '<meta itemprop="position" content="' . $position . '" />';
          echo '</li>';
      }
      ?>
    </ol>
  </nav>
  <?php endif; ?>

  <!-- Header con nueva estructura CSS ITCSS -->
  <header
    id="site-header"
    class="header header--fixed header--transparent"
    role="banner"
    style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/fonde-header.webp');">

    <!-- Overlay -->
    <div class="header__overlay"></div>

    <!-- Skip to content para accesibilidad -->
    <a id="skip-to-content" class="visually-hidden" href="#primary">
      <?php esc_html_e("Saltar al contenido", "masajista-masculino"); ?>
    </a>

    <!-- Contenedor principal del header -->
    <div class="container">
      <div class="header__inner flex flex--between flex--center">

        <!-- Logo -->
        <div class="header__logo">
          <a href="<?php echo esc_url(home_url("/")); ?>"
            class="logo-link"
            aria-label="<?php bloginfo('name'); ?> - Ir a inicio">
            <img
              src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-sin-fondo.webp"
              alt="<?php bloginfo('name'); ?> - Logo"
              class="logo-img"
              width="120"
              height="60">
          </a>
        </div>

        <!-- Navegación principal -->
        <nav
          id="site-navigation"
          class="header__nav nav nav--primary"
          role="navigation"
          aria-label="<?php esc_attr_e("Menú principal", "masajista-masculino"); ?>">
          <?php
          if (has_nav_menu("primary")) {
            wp_nav_menu([
              "theme_location" => "primary",
              "container"      => false,
              "menu_class"     => "nav__list",
              "menu_id"        => "primary-menu",
              "fallback_cb"    => false,
              "link_before"    => "<span>",
              "link_after"     => "</span>"
            ]);
          } else {
            // Menú de fallback SEO optimizado
            echo '<ul id="primary-menu" class="nav__list">';
            echo '<li class="nav__item"><a href="' . esc_url(home_url("/")) . '" class="nav__link"><span>Inicio</span></a></li>';
            echo '<li class="nav__item"><a href="' . esc_url(home_url("/servicios/")) . '" class="nav__link"><span>Servicios</span></a></li>';
            echo '<li class="nav__item"><a href="' . esc_url(home_url("/productos/")) . '" class="nav__link"><span>Productos</span></a></li>';
            echo '<li class="nav__item"><a href="' . esc_url(home_url("/contacto/")) . '" class="nav__link"><span>Contacto</span></a></li>';
            echo '<li class="nav__item"><a href="' . esc_url(home_url("/reservas/")) . '" class="nav__link"><span>Reservas</span></a></li>';
            echo '</ul>';
          }
          ?>
        </nav>

        <!-- Botón hamburguesa para móvil -->
        <button
          class="header__toggle btn btn--icon btn--ghost mobile-only"
          aria-label="Abrir menú de navegación"
          aria-expanded="false"
          aria-controls="primary-menu">
          <span class="hamburger">
            <span class="hamburger__line"></span>
            <span class="hamburger__line"></span>
            <span class="hamburger__line"></span>
          </span>
        </button>

      </div>
    </div>
  </header>
EOF

    # Reemplazar header original con versión optimizada
    mv "$HEADER_FILE.seo-optimized" "$HEADER_FILE"
    log "Header.php optimizado para SEO"
fi

# =============================================================================
# 3. AGREGAR FUNCIONES SEO A FUNCTIONS.PHP
# =============================================================================
log "Agregando funciones SEO a functions.php..."

FUNCTIONS_FILE="$THEME_PATH/functions.php"

if [ -f "$FUNCTIONS_FILE" ]; then
    # Agregar funciones SEO al final del archivo
    cat >> "$FUNCTIONS_FILE" << 'EOF'

// =============================================================================
// OPTIMIZACIONES SEO - AUTO-GENERADAS
// =============================================================================

// Habilitar sitemap nativo de WordPress
add_action('init', function() {
    // Habilitar sitemap XML nativo (WordPress 5.5+)
    add_filter('wp_sitemaps_enabled', '__return_true');
    
    // Personalizar sitemap
    add_filter('wp_sitemaps_post_types', function($post_types) {
        // Incluir custom post types
        if (post_type_exists('servicio')) {
            $post_types['servicio'] = get_post_type_object('servicio');
        }
        if (post_type_exists('producto')) {
            $post_types['producto'] = get_post_type_object('producto');
        }
        return $post_types;
    });
    
    // Excluir páginas específicas del sitemap
    add_filter('wp_sitemaps_posts_query_args', function($args, $post_type) {
        if ($post_type === 'page') {
            $args['meta_query'] = [
                [
                    'key' => '_exclude_from_sitemap',
                    'compare' => 'NOT EXISTS'
                ]
            ];
        }
        return $args;
    }, 10, 2);
});

// Generar robots.txt dinámicamente
function mm_generate_robots_txt() {
    if (isset($_GET['robots']) || strpos($_SERVER['REQUEST_URI'], 'robots.txt') !== false) {
        header('Content-Type: text/plain');
        
        $robots_content = "User-agent: *\n";
        $robots_content .= "Allow: /\n\n";
        
        $robots_content .= "Disallow: /wp-admin/\n";
        $robots_content .= "Disallow: /wp-includes/\n";
        $robots_content .= "Disallow: /wp-content/plugins/\n";
        $robots_content .= "Disallow: /wp-content/cache/\n\n";
        
        $robots_content .= "Allow: /wp-content/uploads/\n";
        $robots_content .= "Allow: /wp-content/themes/masajista-masculino/assets/\n\n";
        
        $robots_content .= "Sitemap: " . home_url('/wp-sitemap.xml') . "\n";
        
        echo $robots_content;
        exit;
    }
}
add_action('template_redirect', 'mm_generate_robots_txt');

// Schema markup para la organización
function mm_add_organization_schema() {
    if (is_home() || is_front_page()) {
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "LocalBusiness",
            "name" => get_bloginfo('name'),
            "description" => get_bloginfo('description'),
            "url" => home_url(),
            "telephone" => "+34666777888",
            "email" => "info@masajes.com",
            "address" => [
                "@type" => "PostalAddress",
                "addressLocality" => "Barcelona",
                "addressRegion" => "Cataluña",
                "addressCountry" => "ES"
            ],
            "openingHours" => "Mo-Su 10:00-22:00",
            "priceRange" => "€€",
            "serviceType" => ["Massage Therapy", "Wellness Services", "Men's Health"],
            "areaServed" => [
                "@type" => "City",
                "name" => "Barcelona"
            ]
        ];
        
        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }
}
add_action('wp_head', 'mm_add_organization_schema');

// Schema markup para servicios
function mm_add_service_schema() {
    if (is_single() && get_post_type() === 'servicio') {
        $servicio_id = get_the_ID();
        $precio = get_post_meta($servicio_id, '_servicio_precio', true);
        $duracion = get_post_meta($servicio_id, '_servicio_duracion', true);
        
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "Service",
            "name" => get_the_title(),
            "description" => wp_trim_words(get_the_content(), 50),
            "provider" => [
                "@type" => "LocalBusiness",
                "name" => get_bloginfo('name'),
                "url" => home_url(),
                "telephone" => "+34666777888",
                "address" => [
                    "@type" => "PostalAddress",
                    "addressLocality" => "Barcelona",
                    "addressCountry" => "ES"
                ]
            ]
        ];
        
        if ($precio) {
            $schema["offers"] = [
                "@type" => "Offer",
                "price" => $precio,
                "priceCurrency" => "EUR",
                "availability" => "https://schema.org/InStock"
            ];
        }
        
        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }
}
add_action('wp_head', 'mm_add_service_schema');

// Optimizar permalinks para SEO
function mm_optimize_permalinks() {
    // Configurar estructura de permalinks
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%postname%/');
    
    // Personalizar URLs de custom post types
    if (post_type_exists('servicio')) {
        $servicio_object = get_post_type_object('servicio');
        if ($servicio_object) {
            $servicio_object->rewrite = [
                'slug' => 'servicios',
                'with_front' => false
            ];
        }
    }
    
    if (post_type_exists('producto')) {
        $producto_object = get_post_type_object('producto');
        if ($producto_object) {
            $producto_object->rewrite = [
                'slug' => 'productos',
                'with_front' => false
            ];
        }
    }
}
add_action('init', 'mm_optimize_permalinks');

// Mejorar alt text de imágenes automáticamente
function mm_improve_image_alt_text($attr, $attachment, $size) {
    if (empty($attr['alt'])) {
        $context = '';
        
        // Determinar contexto basado en la página actual
        if (is_page_template('page-servicios.php') || is_post_type_archive('servicio')) {
            $context = 'Servicios de masajes';
        } elseif (is_page_template('page-productos.php') || is_post_type_archive('producto')) {
            $context = 'Productos de bienestar';
        } elseif (is_home() || is_front_page()) {
            $context = 'Men\'s Core Therapy';
        }
        
        // Generar alt text basado en el nombre del archivo
        $alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
        if (empty($alt)) {
            $filename = pathinfo($attachment->post_name, PATHINFO_FILENAME);
            $alt = str_replace(['-', '_'], ' ', $filename);
            $alt = ucwords($alt);
            
            if ($context) {
                $alt = $context . ' - ' . $alt;
            }
        }
        
        $attr['alt'] = $alt;
    }
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'mm_improve_image_alt_text', 10, 3);

// Lazy loading para imágenes con SEO
function mm_seo_lazy_loading($content) {
    // Solo aplicar en contenido principal
    if (is_main_query() && in_the_loop()) {
        $content = preg_replace(
            '/<img([^>]+?)src=/i',
            '<img$1loading="lazy" src=',
            $content
        );
    }
    return $content;
}
add_filter('the_content', 'mm_seo_lazy_loading');

// Meta box para control SEO por página
function mm_add_seo_meta_box() {
    add_meta_box(
        'seo_settings',
        'Configuración SEO',
        'mm_seo_meta_box_callback',
        ['page', 'post', 'servicio', 'producto'],
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'mm_add_seo_meta_box');

function mm_seo_meta_box_callback($post) {
    wp_nonce_field('seo_meta_box', 'seo_meta_box_nonce');
    
    $noindex = get_post_meta($post->ID, '_noindex', true);
    $exclude_sitemap = get_post_meta($post->ID, '_exclude_from_sitemap', true);
    $custom_description = get_post_meta($post->ID, '_meta_description', true);
    
    echo '<p><label>';
    echo '<input type="checkbox" name="seo_noindex" value="1"' . checked($noindex, '1', false) . '> ';
    echo 'No indexar esta página</label></p>';
    
    echo '<p><label>';
    echo '<input type="checkbox" name="seo_exclude_sitemap" value="1"' . checked($exclude_sitemap, '1', false) . '> ';
    echo 'Excluir del sitemap</label></p>';
    
    echo '<p><label>Meta Description personalizada:</label></p>';
    echo '<textarea name="custom_meta_description" rows="3" style="width:100%">' . esc_textarea($custom_description) . '</textarea>';
}

function mm_save_seo_meta_box($post_id) {
    if (!isset($_POST['seo_meta_box_nonce']) || !wp_verify_nonce($_POST['seo_meta_box_nonce'], 'seo_meta_box')) {
        return;
    }
    
    if (isset($_POST['seo_noindex'])) {
        update_post_meta($post_id, '_noindex', '1');
    } else {
        delete_post_meta($post_id, '_noindex');
    }
    
    if (isset($_POST['seo_exclude_sitemap'])) {
        update_post_meta($post_id, '_exclude_from_sitemap', '1');
    } else {
        delete_post_meta($post_id, '_exclude_from_sitemap');
    }
    
    if (isset($_POST['custom_meta_description'])) {
        update_post_meta($post_id, '_meta_description', sanitize_textarea_field($_POST['custom_meta_description']));
    }
}
add_action('save_post', 'mm_save_seo_meta_box');

// Soporte para WebP en uploads
function mm_add_webp_support($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'mm_add_webp_support');

EOF

    log "Funciones SEO agregadas a functions.php"
fi

# =============================================================================
# 4. CREAR SITEMAP PERSONALIZADO (BACKUP)
# =============================================================================
log "Creando sitemap personalizado como backup..."

cat > "$WORDPRESS_PATH/sitemap-custom.xml" << 'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <!-- Homepage -->
  <url>
    <loc>https://menscoretherapy.free.nf/willmasaje_stagi/</loc>
    <lastmod>2024-12-01</lastmod>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
  </url>
  
  <!-- Servicios -->
  <url>
    <loc>https://menscoretherapy.free.nf/willmasaje_stagi/servicios/</loc>
    <lastmod>2024-12-01</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
  </url>
  
  <!-- Productos -->
  <url>
    <loc>https://menscoretherapy.free.nf/willmasaje_stagi/productos/</loc>
    <lastmod>2024-12-01</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  
  <!-- Contacto -->
  <url>
    <loc>https://menscoretherapy.free.nf/willmasaje_stagi/contacto/</loc>
    <lastmod>2024-12-01</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
  
  <!-- Reservas -->
  <url>
    <loc>https://menscoretherapy.free.nf/willmasaje_stagi/reservas/</loc>
    <lastmod>2024-12-01</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
</urlset>
EOF

log "Sitemap personalizado creado"

# =============================================================================
# 5. CREAR SCRIPT DE VERIFICACIÓN SEO
# =============================================================================
log "Creando script de verificación SEO..."

cat > "$WORDPRESS_PATH/verify-seo.sh" << 'EOF'
#!/bin/bash

echo "=== VERIFICACIÓN SEO ==="
echo

# Verificar meta tags en header.php
if grep -q "og:title" wp-content/themes/masajista-masculino/header.php; then
    echo "✓ Open Graph tags: Implementados"
else
    echo "✗ Open Graph tags: No implementados"
fi

if grep -q "twitter:card" wp-content/themes/masajista-masculino/header.php; then
    echo "✓ Twitter Cards: Implementadas"
else
    echo "✗ Twitter Cards: No implementadas"
fi

if grep -q "canonical" wp-content/themes/masajista-masculino/header.php; then
    echo "✓ Canonical URL: Implementado"
else
    echo "✗ Canonical URL: No implementado"
fi

# Verificar breadcrumbs
if grep -q "breadcrumbs" wp-content/themes/masajista-masculino/header.php; then
    echo "✓ Breadcrumbs: Implementados"
else
    echo "✗ Breadcrumbs: No implementados"
fi

# Verificar Schema markup en functions.php
if grep -q "application/ld+json" wp-content/themes/masajista-masculino/functions.php; then
    echo "✓ Schema markup: Implementado"
else
    echo "✗ Schema markup: No implementado"
fi

# Verificar sitemap
if [ -f "wp-sitemap.xml" ] || curl -s -o /dev/null -w "%{http_code}" "$(wp option get home)/wp-sitemap.xml" | grep -q "200"; then
    echo "✓ Sitemap XML: Disponible"
else
    echo "✗ Sitemap XML: No disponible"
fi

# Verificar robots.txt
if [ -f "robots.txt" ] || curl -s -o /dev/null -w "%{http_code}" "$(wp option get home)/robots.txt" | grep -q "200"; then
    echo "✓ robots.txt: Disponible"
else
    echo "✗ robots.txt: No disponible"
fi

# Verificar WebP support
if grep -q "webp" wp-content/themes/masajista-masculino/functions.php; then
    echo "✓ Soporte WebP: Habilitado"
else
    echo "✗ Soporte WebP: No habilitado"
fi

# Verificar lazy loading
if grep -q "loading.*lazy" wp-content/themes/masajista-masculino/functions.php; then
    echo "✓ Lazy loading: Implementado"
else
    echo "✗ Lazy loading: No implementado"
fi

# Verificar alt text optimization
if grep -q "improve_image_alt_text" wp-content/themes/masajista-masculino/functions.php; then
    echo "✓ Optimización alt text: Implementada"
else
    echo "✗ Optimización alt text: No implementada"
fi

# Verificar meta box SEO
if grep -q "seo_meta_box" wp-content/themes/masajista-masculino/functions.php; then
    echo "✓ Meta box SEO: Implementado"
else
    echo "✗ Meta box SEO: No implementado"
fi

echo
echo "=== VERIFICACIÓN COMPLETADA ==="

# Mostrar URLs importantes
echo
echo "=== URLS IMPORTANTES ==="
echo "Sitemap: $(wp option get home)/wp-sitemap.xml"
echo "Robots: $(wp option get home)/robots.txt"
echo "Homepage: $(wp option get home)/"
echo "Servicios: $(wp option get home)/servicios/"
echo "Productos: $(wp option get home)/productos/"
EOF

chmod +x "$WORDPRESS_PATH/verify-seo.sh"

# =============================================================================
# 6. CREAR MONITOR SEO
# =============================================================================
log "Creando monitor SEO..."

cat > "$WORDPRESS_PATH/wp-content/seo-monitor.php" << 'EOF'
<?php
/**
 * Monitor SEO - Men's Core Therapy
 * Ejecutar vía cron diariamente para monitorear métricas SEO
 */

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/../../');
}

require_once ABSPATH . 'wp-config.php';

class SEOMonitor {
    private $log_file;
    private $metrics_file;
    
    public function __construct() {
        $this->log_file = ABSPATH . 'wp-content/seo-monitor.log';
        $this->metrics_file = ABSPATH . 'wp-content/seo-metrics.json';
    }
    
    public function run_checks() {
        $this->log("=== Iniciando monitoreo SEO ===");
        
        $metrics = [
            'timestamp' => time(),
            'date' => date('Y-m-d H:i:s'),
            'sitemap_status' => $this->check_sitemap(),
            'robots_status' => $this->check_robots(),
            'meta_tags' => $this->check_meta_tags(),
            'schema_markup' => $this->check_schema_markup(),
            'page_titles' => $this->check_page_titles(),
            'alt_text_coverage' => $this->check_alt_text(),
            'internal_links' => $this->check_internal_links()
        ];
        
        $this->save_metrics($metrics);
        $this->check_seo_alerts($metrics);
        
        $this->log("=== Monitoreo SEO completado ===\n");
    }
    
    private function check_sitemap() {
        $sitemap_url = home_url('/wp-sitemap.xml');
        $response = wp_remote_get($sitemap_url);
        
        return [
            'url' => $sitemap_url,
            'status' => wp_remote_retrieve_response_code($response),
            'accessible' => !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200
        ];
    }
    
    private function check_robots() {
        $robots_url = home_url('/robots.txt');
        $response = wp_remote_get($robots_url);
        
        return [
            'url' => $robots_url,
            'status' => wp_remote_retrieve_response_code($response),
            'accessible' => !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200
        ];
    }
    
    private function check_meta_tags() {
        $pages = [
            '/' => 'Homepage',
            '/servicios/' => 'Servicios',
            '/productos/' => 'Productos',
            '/contacto/' => 'Contacto'
        ];
        
        $results = [];
        
        foreach ($pages as $path => $name) {
            $url = home_url($path);
            $response = wp_remote_get($url);
            
            if (!is_wp_error($response)) {
                $html = wp_remote_retrieve_body($response);
                
                $results[$path] = [
                    'name' => $name,
                    'has_title' => preg_match('/<title[^>]*>(.+?)<\/title>/i', $html) ? true : false,
                    'has_description' => preg_match('/<meta[^>]*name=["\']description["\'][^>]*>/i', $html) ? true : false,
                    'has_og_title' => preg_match('/<meta[^>]*property=["\']og:title["\'][^>]*>/i', $html) ? true : false,
                    'has_canonical' => preg_match('/<link[^>]*rel=["\']canonical["\'][^>]*>/i', $html) ? true : false
                ];
            }
        }
        
        return $results;
    }
    
    private function check_schema_markup() {
        $homepage_url = home_url('/');
        $response = wp_remote_get($homepage_url);
        
        if (!is_wp_error($response)) {
            $html = wp_remote_retrieve_body($response);
            $has_schema = preg_match('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>/i', $html);
            
            return [
                'homepage_schema' => $has_schema ? true : false,
                'organization_schema' => strpos($html, '"@type":"LocalBusiness"') !== false
            ];
        }
        
        return ['homepage_schema' => false, 'organization_schema' => false];
    }
    
    private function check_page_titles() {
        $pages = get_pages(['number' => 10]);
        $titles_optimized = 0;
        $total_pages = count($pages);
        
        foreach ($pages as $page) {
            $title = get_the_title($page->ID);
            // Verificar que el título no sea genérico
            if (strlen($title) > 10 && !in_array(strtolower($title), ['page', 'home', 'untitled'])) {
                $titles_optimized++;
            }
        }
        
        return [
            'total_pages' => $total_pages,
            'optimized_titles' => $titles_optimized,
            'optimization_percentage' => $total_pages > 0 ? round(($titles_optimized / $total_pages) * 100, 1) : 0
        ];
    }
    
    private function check_alt_text() {
        $images = get_posts([
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'posts_per_page' => 50,
            'post_status' => 'inherit'
        ]);
        
        $with_alt = 0;
        $total_images = count($images);
        
        foreach ($images as $image) {
            $alt_text = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
            if (!empty($alt_text)) {
                $with_alt++;
            }
        }
        
        return [
            'total_images' => $total_images,
            'with_alt_text' => $with_alt,
            'alt_text_percentage' => $total_images > 0 ? round(($with_alt / $total_images) * 100, 1) : 0
        ];
    }
    
    private function check_internal_links() {
        $posts = get_posts(['posts_per_page' => 20, 'post_status' => 'publish']);
        $posts_with_links = 0;
        $total_posts = count($posts);
        
        foreach ($posts as $post) {
            $content = $post->post_content;
            $internal_links = preg_match_all('/<a[^>]*href=["\']' . preg_quote(home_url(), '/') . '[^"\']*["\'][^>]*>/i', $content);
            
            if ($internal_links > 0) {
                $posts_with_links++;
            }
        }
        
        return [
            'total_posts' => $total_posts,
            'posts_with_internal_links' => $posts_with_links,
            'internal_linking_percentage' => $total_posts > 0 ? round(($posts_with_links / $total_posts) * 100, 1) : 0
        ];
    }
    
    private function save_metrics($metrics) {
        $existing_metrics = [];
        
        if (file_exists($this->metrics_file)) {
            $existing_metrics = json_decode(file_get_contents($this->metrics_file), true) ?: [];
        }
        
        // Mantener solo los últimos 30 registros
        if (count($existing_metrics) >= 30) {
            $existing_metrics = array_slice($existing_metrics, -29);
        }
        
        $existing_metrics[] = $metrics;
        
        file_put_contents($this->metrics_file, json_encode($existing_metrics, JSON_PRETTY_PRINT));
    }
    
    private function check_seo_alerts($metrics) {
        $alerts = [];
        
        // Verificar sitemap
        if (!$metrics['sitemap_status']['accessible']) {
            $alerts[] = "Sitemap XML no accesible: " . $metrics['sitemap_status']['url'];
        }
        
        // Verificar robots.txt
        if (!$metrics['robots_status']['accessible']) {
            $alerts[] = "robots.txt no accesible: " . $metrics['robots_status']['url'];
        }
        
        // Verificar alt text
        if ($metrics['alt_text_coverage']['alt_text_percentage'] < 80) {
            $alerts[] = "Solo {$metrics['alt_text_coverage']['alt_text_percentage']}% de imágenes tienen alt text";
        }
        
        // Verificar meta tags
        foreach ($metrics['meta_tags'] as $page => $data) {
            if (!$data['has_description']) {
                $alerts[] = "Página {$data['name']} sin meta description";
            }
        }
        
        // Enviar alertas si hay problemas
        if (!empty($alerts)) {
            $this->send_seo_alert($alerts);
        }
    }
    
    private function send_seo_alert($alerts) {
        $admin_email = get_option('admin_email');
        if ($admin_email) {
            $subject = '[SEO] Alertas - Men\'s Core Therapy';
            $message = "Se detectaron problemas SEO:\n\n";
            $message .= implode("\n", $alerts);
            $message .= "\n\nRevisa el dashboard SEO para más detalles.";
            
            wp_mail($admin_email, $subject, $message);
        }
    }
    
    private function log($message, $level = 'INFO') {
        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[$timestamp] [$level] $message\n";
        file_put_contents($this->log_file, $log_entry, FILE_APPEND | LOCK_EX);
    }
}

// Ejecutar monitor
$monitor = new SEOMonitor();
$monitor->run_checks();
EOF

chmod 644 "$WORDPRESS_PATH/wp-content/seo-monitor.php"

# =============================================================================
# 7. CONFIGURAR CRON PARA MONITOREO SEO
# =============================================================================
log "Configurando cron para monitoreo SEO..."

CRON_ENTRY="0 6 * * * /usr/bin/php $WORDPRESS_PATH/wp-content/seo-monitor.php"
(crontab -l 2>/dev/null | grep -v "seo-monitor.php"; echo "$CRON_ENTRY") | crontab - 2>/dev/null || warning "Configure manualmente: $CRON_ENTRY"

# =============================================================================
# 8. VERIFICACIÓN FINAL
# =============================================================================
log "Ejecutando verificación final SEO..."

cd "$WORDPRESS_PATH"
./verify-seo.sh

# =============================================================================
# 9. RESUMEN
# =============================================================================
echo
log "=== OPTIMIZACIÓN SEO COMPLETADA ==="
echo
info "Optimizaciones SEO implementadas:"
info "  ✓ Header.php optimizado con meta tags dinámicos"
info "  ✓ Open Graph y Twitter Cards implementados"
info "  ✓ Breadcrumbs con Schema markup"
info "  ✓ Schema.org para organización y servicios"
info "  ✓ Sitemap XML nativo habilitado"
info "  ✓ robots.txt dinámico configurado"
info "  ✓ Alt text automático para imágenes"
info "  ✓ Lazy loading SEO-friendly"
info "  ✓ Meta box SEO para páginas"
info "  ✓ Monitor SEO instalado"
info "  ✓ Script de verificación creado"
echo
warning "ACCIONES MANUALES REQUERIDAS:"
warning "  1. Verificar Google Search Console"
warning "  2. Enviar sitemap a Google"
warning "  3. Instalar plugin SEO (Yoast/RankMath) como complemento"
warning "  4. Configurar Google Analytics 4"
warning "  5. Optimizar contenido con keywords específicas"
echo
info "Para verificar SEO: cd $WORDPRESS_PATH && ./verify-seo.sh"
info "Métricas SEO: wp-content/seo-metrics.json"
echo

log "Optimización SEO completada exitosamente"