#!/bin/bash

# =============================================================================
# SCRIPT DE OPTIMIZACIÓN DE RENDIMIENTO - Men's Core Therapy WordPress
# =============================================================================
# 
# Descripción: Implementa todas las optimizaciones de rendimiento identificadas
# en la auditoría WPO para alcanzar <2.5s de tiempo de carga
# 
# Uso: ./performance-optimizer.sh [WORDPRESS_PATH]
# Ejemplo: ./performance-optimizer.sh /var/www/html/willmasaje_stagi
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

log "Iniciando optimización de rendimiento para: $WORDPRESS_PATH"

# =============================================================================
# 1. INSTALACIÓN DE HERRAMIENTAS DE OPTIMIZACIÓN
# =============================================================================
log "Instalando herramientas de optimización..."

# Verificar e instalar herramientas necesarias
install_tool() {
    local tool=$1
    local package=$2
    
    if ! command -v $tool &> /dev/null; then
        info "Instalando $tool..."
        if command -v apt-get &> /dev/null; then
            sudo apt-get update && sudo apt-get install -y $package
        elif command -v yum &> /dev/null; then
            sudo yum install -y $package
        else
            warning "No se pudo instalar $tool automáticamente"
        fi
    else
        info "$tool ya está instalado"
    fi
}

# Instalar herramientas de optimización de imágenes
install_tool "jpegoptim" "jpegoptim"
install_tool "optipng" "optipng"
install_tool "cwebp" "webp"

# =============================================================================
# 2. BACKUP DE ARCHIVOS
# =============================================================================
log "Creando backup antes de optimizaciones..."

BACKUP_DIR="$WORDPRESS_PATH/performance-backup-$(date +%Y%m%d-%H%M%S)"
mkdir -p "$BACKUP_DIR"

# Backup de archivos críticos
cp -r "$WORDPRESS_PATH/wp-content/themes/masajista-masculino" "$BACKUP_DIR/theme-backup" 2>/dev/null || true
cp "$WORDPRESS_PATH/wp-config.php" "$BACKUP_DIR/wp-config.php.bak" 2>/dev/null || true

log "Backup creado en: $BACKUP_DIR"

# =============================================================================
# 3. OPTIMIZACIÓN DE IMÁGENES
# =============================================================================
log "Optimizando imágenes existentes..."

THEME_PATH="$WORDPRESS_PATH/wp-content/themes/masajista-masculino"
UPLOADS_PATH="$WORDPRESS_PATH/wp-content/uploads"

# Función para optimizar imágenes JPG
optimize_jpg() {
    local dir=$1
    find "$dir" -name "*.jpg" -o -name "*.jpeg" | while read -r img; do
        if [ -f "$img" ]; then
            original_size=$(stat -c%s "$img")
            jpegoptim --max=85 --strip-all "$img" 2>/dev/null || true
            new_size=$(stat -c%s "$img")
            if [ $new_size -lt $original_size ]; then
                saved=$((original_size - new_size))
                info "JPG optimizado: $(basename "$img") - Ahorrado: ${saved} bytes"
            fi
        fi
    done
}

# Función para optimizar imágenes PNG
optimize_png() {
    local dir=$1
    find "$dir" -name "*.png" | while read -r img; do
        if [ -f "$img" ]; then
            original_size=$(stat -c%s "$img")
            optipng -o2 -quiet "$img" 2>/dev/null || true
            new_size=$(stat -c%s "$img")
            if [ $new_size -lt $original_size ]; then
                saved=$((original_size - new_size))
                info "PNG optimizado: $(basename "$img") - Ahorrado: ${saved} bytes"
            fi
        fi
    done
}

# Función para generar WebP
generate_webp() {
    local dir=$1
    find "$dir" -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" | while read -r img; do
        if [ -f "$img" ]; then
            webp_file="${img%.*}.webp"
            if [ ! -f "$webp_file" ]; then
                cwebp -q 85 "$img" -o "$webp_file" 2>/dev/null || true
                if [ -f "$webp_file" ]; then
                    info "WebP generado: $(basename "$webp_file")"
                fi
            fi
        fi
    done
}

# Optimizar imágenes del tema
if [ -d "$THEME_PATH/assets/images" ]; then
    log "Optimizando imágenes del tema..."
    optimize_jpg "$THEME_PATH/assets/images"
    optimize_png "$THEME_PATH/assets/images"
    generate_webp "$THEME_PATH/assets/images"
fi

# Optimizar imágenes de uploads (últimos 30 días para evitar procesar todo)
if [ -d "$UPLOADS_PATH" ]; then
    log "Optimizando imágenes recientes de uploads..."
    find "$UPLOADS_PATH" -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" -mtime -30 | head -100 | while read -r img; do
        if [ -f "$img" ]; then
            # Optimizar solo si no se ha hecho antes
            if [ ! -f "${img}.optimized" ]; then
                case "${img##*.}" in
                    jpg|jpeg)
                        jpegoptim --max=85 --strip-all "$img" 2>/dev/null || true
                        ;;
                    png)
                        optipng -o2 -quiet "$img" 2>/dev/null || true
                        ;;
                esac
                
                # Generar WebP
                webp_file="${img%.*}.webp"
                if [ ! -f "$webp_file" ]; then
                    cwebp -q 85 "$img" -o "$webp_file" 2>/dev/null || true
                fi
                
                # Marcar como optimizado
                touch "${img}.optimized"
                info "Procesado: $(basename "$img")"
            fi
        fi
    done
fi

# =============================================================================
# 4. MINIFICACIÓN DE CSS Y JS
# =============================================================================
log "Minificando archivos CSS y JS..."

# Función para minificar CSS
minify_css() {
    local file=$1
    local minified="${file%.*}.min.css"
    
    if [ ! -f "$minified" ] || [ "$file" -nt "$minified" ]; then
        # Minificación básica de CSS (remover comentarios, espacios, saltos de línea)
        sed -e 's/\/\*[^*]*\*\///g' -e 's/^[[:space:]]*//' -e 's/[[:space:]]*$//' -e '/^$/d' "$file" | tr -d '\n' > "$minified"
        info "CSS minificado: $(basename "$minified")"
    fi
}

# Función para minificar JS
minify_js() {
    local file=$1
    local minified="${file%.*}.min.js"
    
    if [ ! -f "$minified" ] || [ "$file" -nt "$minified" ]; then
        # Minificación básica de JS (remover comentarios de línea y espacios excesivos)
        sed -e 's|//.*$||g' -e 's/^[[:space:]]*//' -e 's/[[:space:]]*$//' -e '/^$/d' "$file" > "$minified"
        info "JS minificado: $(basename "$minified")"
    fi
}

# Minificar archivos del tema
if [ -d "$THEME_PATH/assets" ]; then
    # CSS
    find "$THEME_PATH/assets" -name "*.css" -not -name "*.min.css" | while read -r css_file; do
        minify_css "$css_file"
    done
    
    # JS
    find "$THEME_PATH/assets" -name "*.js" -not -name "*.min.js" | while read -r js_file; do
        minify_js "$js_file"
    done
fi

# =============================================================================
# 5. CONFIGURACIÓN DE CACHÉ EN WP-CONFIG.PHP
# =============================================================================
log "Configurando optimizaciones de caché en wp-config.php..."

WP_CONFIG="$WORDPRESS_PATH/wp-config.php"

# Función para agregar configuración si no existe
add_config_if_not_exists() {
    local config_line=$1
    local config_name=$(echo "$config_line" | grep -o "define('[^']*'" | cut -d"'" -f2)
    
    if ! grep -q "$config_name" "$WP_CONFIG"; then
        # Insertar antes de la línea "/* That's all, stop editing!"
        sed -i "/That's all, stop editing/i\\$config_line" "$WP_CONFIG"
        info "Agregada configuración: $config_name"
    fi
}

# Configuraciones de rendimiento
add_config_if_not_exists "define('WP_CACHE', true);"
add_config_if_not_exists "define('COMPRESS_CSS', true);"
add_config_if_not_exists "define('COMPRESS_SCRIPTS', true);"
add_config_if_not_exists "define('CONCATENATE_SCRIPTS', false);"
add_config_if_not_exists "define('ENFORCE_GZIP', true);"
add_config_if_not_exists "define('WP_POST_REVISIONS', 3);"
add_config_if_not_exists "define('AUTOSAVE_INTERVAL', 300);"
add_config_if_not_exists "define('EMPTY_TRASH_DAYS', 7);"
add_config_if_not_exists "define('WP_MEMORY_LIMIT', '256M');"

# =============================================================================
# 6. OPTIMIZACIÓN DEL TEMA - FUNCTIONS.PHP
# =============================================================================
log "Optimizando functions.php del tema..."

FUNCTIONS_PHP="$THEME_PATH/functions.php"

if [ -f "$FUNCTIONS_PHP" ]; then
    # Crear backup específico
    cp "$FUNCTIONS_PHP" "$FUNCTIONS_PHP.performance-backup"
    
    # Agregar optimizaciones al final del archivo
    cat >> "$FUNCTIONS_PHP" << 'EOF'

// =============================================================================
// OPTIMIZACIONES DE RENDIMIENTO - AUTO-GENERADAS
// =============================================================================

// Remover scripts y estilos innecesarios
function mm_performance_optimizations() {
    if (!is_admin()) {
        // Remover emoji scripts
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');
        
        // Remover generator meta tag
        remove_action('wp_head', 'wp_generator');
        
        // Remover RSD link
        remove_action('wp_head', 'rsd_link');
        
        // Remover wlwmanifest link
        remove_action('wp_head', 'wlwmanifest_link');
        
        // Remover shortlink
        remove_action('wp_head', 'wp_shortlink_wp_head');
        
        // Desactivar embeds
        wp_deregister_script('wp-embed');
    }
}
add_action('init', 'mm_performance_optimizations');

// Optimizar jQuery
function mm_optimize_jquery() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', false, '3.6.0', true);
        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'mm_optimize_jquery');

// Preload recursos críticos
function mm_preload_resources() {
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/css/_main.css" as="style">' . "\n";
    echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//ajax.googleapis.com">' . "\n";
}
add_action('wp_head', 'mm_preload_resources', 1);

// Lazy loading para imágenes
function mm_add_lazy_loading($content) {
    if (is_admin()) {
        return $content;
    }
    
    // Agregar loading="lazy" a imágenes
    $content = preg_replace('/<img((?:[^>](?!loading=))*+)>/i', '<img$1 loading="lazy">', $content);
    
    return $content;
}
add_filter('the_content', 'mm_add_lazy_loading');

// Optimizar queries de productos
function get_productos_optimized($limit = 12, $page = 1) {
    $cache_key = "productos_page_{$page}_limit_{$limit}";
    $productos = wp_cache_get($cache_key, 'mm_productos');
    
    if (false === $productos) {
        $productos = new WP_Query(array(
            'post_type' => 'producto',
            'posts_per_page' => $limit,
            'paged' => $page,
            'post_status' => 'publish',
            'no_found_rows' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false
        ));
        
        wp_cache_set($cache_key, $productos, 'mm_productos', 1800); // 30 min
    }
    
    return $productos;
}

// Monitor de rendimiento
function mm_performance_monitor() {
    if (current_user_can('administrator')) {
        $start_time = $_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true);
        
        add_action('wp_footer', function() use ($start_time) {
            $load_time = microtime(true) - $start_time;
            $memory_usage = memory_get_peak_usage(true) / 1024 / 1024;
            
            echo "<script>console.log('Performance: {$load_time}s, Memory: {$memory_usage}MB');</script>";
            
            // Log páginas lentas
            if ($load_time > 2.5) {
                error_log("Página lenta: {$load_time}s en " . $_SERVER['REQUEST_URI']);
            }
        });
    }
}
add_action('init', 'mm_performance_monitor');

// Soporte para WebP
function mm_add_webp_support($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'mm_add_webp_support');

// Servir WebP cuando esté disponible
function mm_serve_webp_images($html, $post_id, $post_image_id) {
    if (strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false) {
        $html = preg_replace_callback('/<img[^>]+src="([^"]+)"[^>]*>/i', function($matches) {
            $img_url = $matches[1];
            $webp_url = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $img_url);
            
            // Verificar si existe la versión WebP
            $webp_path = str_replace(home_url(), ABSPATH, $webp_url);
            if (file_exists($webp_path)) {
                return str_replace($img_url, $webp_url, $matches[0]);
            }
            
            return $matches[0];
        }, $html);
    }
    
    return $html;
}
add_filter('post_thumbnail_html', 'mm_serve_webp_images', 10, 3);

EOF

    log "Optimizaciones agregadas a functions.php"
fi

# =============================================================================
# 7. CREAR ARCHIVO DE CRITICAL CSS
# =============================================================================
log "Generando Critical CSS..."

CRITICAL_CSS_FILE="$THEME_PATH/assets/css/critical.css"

cat > "$CRITICAL_CSS_FILE" << 'EOF'
/* Critical CSS - Above the fold styles */
/* Generado automáticamente para Men's Core Therapy */

/* Reset básico */
*,*::before,*::after{box-sizing:border-box}
body{margin:0;padding:0;font-family:'Montserrat',sans-serif;background:#FFEDE6;color:#240A00}

/* Header crítico */
.header{position:fixed;top:0;left:0;right:0;z-index:1000;background:#FF6C32;min-height:80px}
.header__inner{display:flex;justify-content:space-between;align-items:center;padding:1rem}
.logo-img{max-height:60px;width:auto}

/* Hero section crítico */
.hero{min-height:100vh;display:flex;align-items:center;justify-content:center;position:relative}
.hero__bg{position:absolute;top:0;left:0;right:0;bottom:0;background-size:cover;background-position:center}
.hero__overlay{position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.4)}
.hero__content{position:relative;z-index:2;text-align:center;color:white}

/* Typography crítico */
h1{font-size:2.5rem;font-weight:700;margin:0 0 1rem;line-height:1.2}
.hero__title{font-size:3rem;margin-bottom:1.5rem}
.hero__subtitle{font-size:1.2rem;opacity:0.9;margin-bottom:2rem}

/* Buttons críticos */
.btn{display:inline-block;padding:0.75rem 1.5rem;border:none;border-radius:8px;text-decoration:none;font-weight:600;transition:all 0.3s ease;cursor:pointer}
.btn--primary{background:#FF6C32;color:white}
.btn--primary:hover{background:#D73D00;transform:translateY(-2px)}

/* Container */
.container{max-width:1100px;margin:0 auto;padding:0 1.5rem}

/* Flexbox utilities */
.flex{display:flex}
.flex--center{align-items:center;justify-content:center}
.flex--between{justify-content:space-between}

/* Spacing utilities */
.mb-lg{margin-bottom:1.5rem}
.mb-xl{margin-bottom:2rem}
.py-xl{padding:2rem 0}

/* Responsive */
@media (max-width:768px){
.hero__title{font-size:2rem}
.hero__subtitle{font-size:1rem}
.container{padding:0 1rem}
}
EOF

info "Critical CSS generado: $CRITICAL_CSS_FILE"

# =============================================================================
# 8. CONFIGURAR ROBOTS.TXT OPTIMIZADO
# =============================================================================
log "Configurando robots.txt optimizado..."

ROBOTS_FILE="$WORDPRESS_PATH/robots.txt"

cat > "$ROBOTS_FILE" << 'EOF'
User-agent: *
Allow: /

# Bloquear archivos y directorios sensibles
Disallow: /wp-admin/
Disallow: /wp-includes/
Disallow: /wp-content/plugins/
Disallow: /wp-content/cache/
Disallow: /wp-content/themes/*/inc/
Disallow: /readme.html
Disallow: /license.txt

# Permitir acceso a recursos necesarios
Allow: /wp-content/uploads/
Allow: /wp-content/themes/masajista-masculino/assets/
Allow: /wp-admin/admin-ajax.php

# Sitemap
Sitemap: https://menscoretherapy.free.nf/willmasaje_stagi/wp-sitemap.xml

# Crawl-delay para bots agresivos
User-agent: AhrefsBot
Crawl-delay: 10

User-agent: MJ12bot
Crawl-delay: 10

User-agent: SemrushBot
Crawl-delay: 10
EOF

log "robots.txt optimizado creado"

# =============================================================================
# 9. CREAR SCRIPT DE MONITOREO DE RENDIMIENTO
# =============================================================================
log "Creando script de monitoreo de rendimiento..."

cat > "$WORDPRESS_PATH/wp-content/performance-monitor.php" << 'EOF'
<?php
/**
 * Monitor de Rendimiento - Men's Core Therapy
 * Ejecutar vía cron cada hora para monitorear métricas de rendimiento
 */

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/../../');
}

require_once ABSPATH . 'wp-config.php';

class PerformanceMonitor {
    private $log_file;
    private $metrics_file;
    
    public function __construct() {
        $this->log_file = ABSPATH . 'wp-content/performance.log';
        $this->metrics_file = ABSPATH . 'wp-content/performance-metrics.json';
    }
    
    public function run_checks() {
        $this->log("=== Iniciando monitoreo de rendimiento ===");
        
        $metrics = [
            'timestamp' => time(),
            'date' => date('Y-m-d H:i:s'),
            'page_load_times' => $this->check_page_load_times(),
            'database_queries' => $this->check_database_performance(),
            'cache_status' => $this->check_cache_status(),
            'image_optimization' => $this->check_image_optimization(),
            'css_js_optimization' => $this->check_assets_optimization()
        ];
        
        $this->save_metrics($metrics);
        $this->check_performance_alerts($metrics);
        
        $this->log("=== Monitoreo completado ===\n");
    }
    
    private function check_page_load_times() {
        $pages = [
            '/' => 'Homepage',
            '/servicios/' => 'Servicios',
            '/productos/' => 'Productos',
            '/contacto/' => 'Contacto'
        ];
        
        $results = [];
        
        foreach ($pages as $path => $name) {
            $url = home_url($path);
            $start_time = microtime(true);
            
            $response = wp_remote_get($url, [
                'timeout' => 30,
                'user-agent' => 'Performance Monitor'
            ]);
            
            $load_time = microtime(true) - $start_time;
            
            $results[$path] = [
                'name' => $name,
                'load_time' => round($load_time, 3),
                'status' => wp_remote_retrieve_response_code($response),
                'size' => strlen(wp_remote_retrieve_body($response))
            ];
            
            if ($load_time > 2.5) {
                $this->log("ALERTA: Página lenta - $name: {$load_time}s", 'WARNING');
            }
        }
        
        return $results;
    }
    
    private function check_database_performance() {
        global $wpdb;
        
        $start_time = microtime(true);
        
        // Query de prueba
        $wpdb->get_results("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status = 'publish'");
        
        $query_time = microtime(true) - $start_time;
        
        // Verificar número de queries
        $num_queries = $wpdb->num_queries;
        
        return [
            'test_query_time' => round($query_time, 4),
            'total_queries' => $num_queries,
            'slow_queries' => $num_queries > 50 ? 'HIGH' : 'NORMAL'
        ];
    }
    
    private function check_cache_status() {
        $cache_plugins = [
            'w3-total-cache/w3-total-cache.php',
            'wp-rocket/wp-rocket.php',
            'wp-super-cache/wp-cache.php'
        ];
        
        $active_cache = 'NONE';
        
        foreach ($cache_plugins as $plugin) {
            if (is_plugin_active($plugin)) {
                $active_cache = basename(dirname($plugin));
                break;
            }
        }
        
        return [
            'plugin' => $active_cache,
            'wp_cache_enabled' => defined('WP_CACHE') && WP_CACHE,
            'object_cache' => wp_using_ext_object_cache()
        ];
    }
    
    private function check_image_optimization() {
        $theme_images = ABSPATH . 'wp-content/themes/masajista-masculino/assets/images/';
        $webp_count = 0;
        $total_images = 0;
        
        if (is_dir($theme_images)) {
            $images = glob($theme_images . '*.{jpg,jpeg,png}', GLOB_BRACE);
            $total_images = count($images);
            
            foreach ($images as $image) {
                $webp_version = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $image);
                if (file_exists($webp_version)) {
                    $webp_count++;
                }
            }
        }
        
        return [
            'total_images' => $total_images,
            'webp_versions' => $webp_count,
            'webp_percentage' => $total_images > 0 ? round(($webp_count / $total_images) * 100, 1) : 0
        ];
    }
    
    private function check_assets_optimization() {
        $theme_path = ABSPATH . 'wp-content/themes/masajista-masculino/assets/';
        
        $css_files = glob($theme_path . 'css/*.css');
        $js_files = glob($theme_path . 'js/*.js');
        
        $minified_css = count(glob($theme_path . 'css/*.min.css'));
        $minified_js = count(glob($theme_path . 'js/*.min.js'));
        
        return [
            'css_files' => count($css_files),
            'minified_css' => $minified_css,
            'js_files' => count($js_files),
            'minified_js' => $minified_js,
            'css_optimization' => count($css_files) > 0 ? round(($minified_css / count($css_files)) * 100, 1) : 0,
            'js_optimization' => count($js_files) > 0 ? round(($minified_js / count($js_files)) * 100, 1) : 0
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
    
    private function check_performance_alerts($metrics) {
        $alerts = [];
        
        // Verificar tiempos de carga
        foreach ($metrics['page_load_times'] as $page => $data) {
            if ($data['load_time'] > 3.0) {
                $alerts[] = "Página {$data['name']} carga en {$data['load_time']}s (objetivo: <2.5s)";
            }
        }
        
        // Verificar optimización de imágenes
        if ($metrics['image_optimization']['webp_percentage'] < 80) {
            $alerts[] = "Solo {$metrics['image_optimization']['webp_percentage']}% de imágenes tienen versión WebP";
        }
        
        // Verificar caché
        if ($metrics['cache_status']['plugin'] === 'NONE') {
            $alerts[] = "No hay plugin de caché activo";
        }
        
        // Enviar alertas si hay problemas
        if (!empty($alerts)) {
            $this->send_performance_alert($alerts);
        }
    }
    
    private function send_performance_alert($alerts) {
        $admin_email = get_option('admin_email');
        if ($admin_email) {
            $subject = '[RENDIMIENTO] Alertas - Men\'s Core Therapy';
            $message = "Se detectaron problemas de rendimiento:\n\n";
            $message .= implode("\n", $alerts);
            $message .= "\n\nRevisa el dashboard de rendimiento para más detalles.";
            
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
$monitor = new PerformanceMonitor();
$monitor->run_checks();
EOF

chmod 644 "$WORDPRESS_PATH/wp-content/performance-monitor.php"

# =============================================================================
# 10. CREAR SCRIPT DE VERIFICACIÓN DE RENDIMIENTO
# =============================================================================
log "Creando script de verificación de rendimiento..."

cat > "$WORDPRESS_PATH/verify-performance.sh" << 'EOF'
#!/bin/bash

echo "=== VERIFICACIÓN DE RENDIMIENTO ==="
echo

# Verificar compresión GZIP
if grep -q "mod_deflate" .htaccess; then
    echo "✓ Compresión GZIP: Configurada"
else
    echo "✗ Compresión GZIP: No configurada"
fi

# Verificar caché del navegador
if grep -q "mod_expires" .htaccess; then
    echo "✓ Caché del navegador: Configurado"
else
    echo "✗ Caché del navegador: No configurado"
fi

# Verificar imágenes WebP
WEBP_COUNT=$(find wp-content/themes/masajista-masculino/assets/images -name "*.webp" 2>/dev/null | wc -l)
TOTAL_IMAGES=$(find wp-content/themes/masajista-masculino/assets/images -name "*.jpg" -o -name "*.png" -o -name "*.jpeg" 2>/dev/null | wc -l)

if [ $TOTAL_IMAGES -gt 0 ]; then
    WEBP_PERCENTAGE=$((WEBP_COUNT * 100 / TOTAL_IMAGES))
    echo "✓ Imágenes WebP: $WEBP_COUNT/$TOTAL_IMAGES ($WEBP_PERCENTAGE%)"
else
    echo "? Imágenes WebP: No se encontraron imágenes"
fi

# Verificar CSS minificado
MIN_CSS_COUNT=$(find wp-content/themes/masajista-masculino/assets -name "*.min.css" 2>/dev/null | wc -l)
if [ $MIN_CSS_COUNT -gt 0 ]; then
    echo "✓ CSS minificado: $MIN_CSS_COUNT archivos"
else
    echo "✗ CSS minificado: No encontrado"
fi

# Verificar JS minificado
MIN_JS_COUNT=$(find wp-content/themes/masajista-masculino/assets -name "*.min.js" 2>/dev/null | wc -l)
if [ $MIN_JS_COUNT -gt 0 ]; then
    echo "✓ JS minificado: $MIN_JS_COUNT archivos"
else
    echo "✗ JS minificado: No encontrado"
fi

# Verificar configuraciones de wp-config.php
if grep -q "WP_CACHE.*true" wp-config.php; then
    echo "✓ WP_CACHE: Habilitado"
else
    echo "✗ WP_CACHE: Deshabilitado"
fi

if grep -q "COMPRESS_CSS.*true" wp-config.php; then
    echo "✓ COMPRESS_CSS: Habilitado"
else
    echo "✗ COMPRESS_CSS: Deshabilitado"
fi

# Verificar Critical CSS
if [ -f "wp-content/themes/masajista-masculino/assets/css/critical.css" ]; then
    echo "✓ Critical CSS: Creado"
else
    echo "✗ Critical CSS: No encontrado"
fi

# Verificar robots.txt
if [ -f "robots.txt" ]; then
    echo "✓ robots.txt: Existe"
else
    echo "✗ robots.txt: No existe"
fi

echo
echo "=== VERIFICACIÓN COMPLETADA ==="

# Mostrar métricas si existen
if [ -f "wp-content/performance-metrics.json" ]; then
    echo
    echo "=== ÚLTIMAS MÉTRICAS ==="
    tail -1 wp-content/performance-metrics.json | python3 -m json.tool 2>/dev/null || echo "Métricas disponibles en wp-content/performance-metrics.json"
fi
EOF

chmod +x "$WORDPRESS_PATH/verify-performance.sh"

# =============================================================================
# 11. CONFIGURAR CRON PARA MONITOREO
# =============================================================================
log "Configurando cron para monitoreo de rendimiento..."

CRON_ENTRY="0 */6 * * * /usr/bin/php $WORDPRESS_PATH/wp-content/performance-monitor.php"
(crontab -l 2>/dev/null | grep -v "performance-monitor.php"; echo "$CRON_ENTRY") | crontab - 2>/dev/null || warning "Configure manualmente: $CRON_ENTRY"

# =============================================================================
# 12. VERIFICACIÓN FINAL
# =============================================================================
log "Ejecutando verificación final..."

cd "$WORDPRESS_PATH"
./verify-performance.sh

# =============================================================================
# 13. RESUMEN
# =============================================================================
echo
log "=== OPTIMIZACIÓN DE RENDIMIENTO COMPLETADA ==="
echo
info "Optimizaciones implementadas:"
info "  ✓ Imágenes optimizadas (JPG/PNG) y WebP generado"
info "  ✓ CSS y JS minificados"
info "  ✓ Configuraciones de caché en wp-config.php"
info "  ✓ Functions.php optimizado con mejoras de rendimiento"
info "  ✓ Critical CSS generado"
info "  ✓ robots.txt optimizado"
info "  ✓ Monitor de rendimiento instalado"
info "  ✓ Script de verificación creado"
echo
warning "ACCIONES MANUALES REQUERIDAS:"
warning "  1. Instalar plugin de caché (W3 Total Cache/WP Rocket)"
warning "  2. Configurar CDN (Cloudflare recomendado)"
warning "  3. Verificar que mod_deflate y mod_expires estén habilitados"
warning "  4. Considerar migración a hosting optimizado"
echo
info "Para verificar rendimiento: cd $WORDPRESS_PATH && ./verify-performance.sh"
info "Métricas de rendimiento: wp-content/performance-metrics.json"
echo

log "Optimización completada exitosamente"