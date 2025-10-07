<?php
if (!defined('ABSPATH')) exit;

// Enqueue styles and scripts
function masajista_masculino_scripts()
{
    // Main stylesheet (WordPress default)
    wp_enqueue_style('masajista-masculino-style', get_stylesheet_uri());

    // NUEVA ARQUITECTURA CSS - Archivo principal que importa todo
    wp_enqueue_style('mm-new-architecture', get_template_directory_uri() . '/assets/css/_main.css', array(), '1.0.0');

    // CSS específicos condicionales (solo si se necesitan overrides)
    if (is_page_template('page-servicios.php')) {
        wp_enqueue_style('mm-servicios-legacy', get_template_directory_uri() . '/assets/css/servicios.css', array('mm-new-architecture'), '1.0.0');
    }

    if (is_page_template('page-contactos.php')) {
        wp_enqueue_style('mm-contactos-legacy', get_template_directory_uri() . '/assets/css/contactos.css', array('mm-new-architecture'), '1.0.0');
    }

    if (is_page_template('page-reservas.php')) {
        wp_enqueue_style('mm-reservas-legacy', get_template_directory_uri() . '/assets/css/reservas.css', array('mm-new-architecture'), '1.0.0');
    }

    if (is_page_template('page-productos.php')) {
        wp_enqueue_style('mm-productos-legacy', get_template_directory_uri() . '/assets/css/productos.css', array('mm-new-architecture'), '1.0.0');
    }

    // Hotfix CSS (mantener al final para overrides críticos)
    // wp_enqueue_style('mm-hotfix', get_template_directory_uri() . '/assets/css/hotfix.css', array('mm-new-architecture'), '1.0.0');

    // JavaScript files (sin cambios)
    wp_enqueue_script('mm-main-js', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true);
    wp_enqueue_script('mm-parallax-js', get_template_directory_uri() . '/assets/js/parallax.js', array(), '1.0.0', true);
    wp_enqueue_script('mm-header-visibility', get_template_directory_uri() . '/assets/js/header-visibility.js', array(), '1.0.0', true);
    wp_enqueue_script('mm-header-mobile', get_template_directory_uri() . '/assets/js/header-mobile.js', array(), '1.0.0', true);

    // Productos JS - NUEVO
    wp_enqueue_script('mm-productos-js', get_template_directory_uri() . '/assets/js/productos.js', array(), '1.0.0', true);

    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0');

    // Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap', array(), null);
}
add_action('wp_enqueue_scripts', 'masajista_masculino_scripts');

// Theme support
function masajista_masculino_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'masajista-masculino'),
    ));
}
add_action('after_setup_theme', 'masajista_masculino_setup');

// Custom post type for services
function create_servicio_post_type()
{
    register_post_type(
        'servicio',
        array(
            'labels' => array(
                'name' => __('Servicios'),
                'singular_name' => __('Servicio')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon' => 'dashicons-admin-tools',
            'rewrite' => array('slug' => 'servicios'),
        )
    );
}
add_action('init', 'create_servicio_post_type');

// Custom post type for productos
function create_producto_post_type()
{
    register_post_type(
        'producto',
        array(
            'labels' => array(
                'name' => __('Productos'),
                'singular_name' => __('Producto'),
                'add_new' => __('Añadir Nuevo'),
                'add_new_item' => __('Añadir Nuevo Producto'),
                'edit_item' => __('Editar Producto'),
                'new_item' => __('Nuevo Producto'),
                'view_item' => __('Ver Producto'),
                'search_items' => __('Buscar Productos'),
                'not_found' => __('No se encontraron productos'),
                'not_found_in_trash' => __('No hay productos en la papelera')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon' => 'dashicons-cart',
            'rewrite' => array('slug' => 'productos'),
            'show_in_rest' => true, // Para Gutenberg
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => 20,
        )
    );
}
add_action('init', 'create_producto_post_type');

// Add custom meta boxes for services
function add_servicio_meta_boxes()
{
    add_meta_box(
        'servicio_details',
        'Detalles del Servicio',
        'servicio_details_callback',
        'servicio',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_servicio_meta_boxes');

function servicio_details_callback($post)
{
    wp_nonce_field('save_servicio_details', 'servicio_details_nonce');

    $precio = get_post_meta($post->ID, '_servicio_precio', true);
    $duracion = get_post_meta($post->ID, '_servicio_duracion', true);

    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th><label for="servicio_precio">Precio</label></th>';
    echo '<td><input type="text" id="servicio_precio" name="servicio_precio" value="' . esc_attr($precio) . '" /></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th><label for="servicio_duracion">Duración</label></th>';
    echo '<td><input type="text" id="servicio_duracion" name="servicio_duracion" value="' . esc_attr($duracion) . '" /></td>';
    echo '</tr>';
    echo '</table>';
}

function save_servicio_details($post_id)
{
    if (!isset($_POST['servicio_details_nonce']) || !wp_verify_nonce($_POST['servicio_details_nonce'], 'save_servicio_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['servicio_precio'])) {
        update_post_meta($post_id, '_servicio_precio', sanitize_text_field($_POST['servicio_precio']));
    }

    if (isset($_POST['servicio_duracion'])) {
        update_post_meta($post_id, '_servicio_duracion', sanitize_text_field($_POST['servicio_duracion']));
    }
}
add_action('save_post', 'save_servicio_details');

// Add custom meta boxes for productos
function add_producto_meta_boxes()
{
    add_meta_box(
        'producto_details',
        'Detalles del Producto',
        'producto_details_callback',
        'producto',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_producto_meta_boxes');

function producto_details_callback($post)
{
    wp_nonce_field('save_producto_details', 'producto_details_nonce');

    $precio = get_post_meta($post->ID, '_producto_precio', true);
    $icono = get_post_meta($post->ID, '_producto_icono', true);
    $beneficios = get_post_meta($post->ID, '_producto_beneficios', true);

    echo '<table class="form-table">';

    echo '<tr>';
    echo '<th><label for="producto_precio">Precio (€)</label></th>';
    echo '<td><input type="text" id="producto_precio" name="producto_precio" value="' . esc_attr($precio) . '" placeholder="19.90" /></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<th><label for="producto_icono">Icono</label></th>';
    echo '<td>';
    echo '<select id="producto_icono" name="producto_icono">';
    echo '<option value="leaf"' . selected($icono, 'leaf', false) . '>Hoja (leaf)</option>';
    echo '<option value="fire"' . selected($icono, 'fire', false) . '>Fuego (fire)</option>';
    echo '<option value="star"' . selected($icono, 'star', false) . '>Estrella (star)</option>';
    echo '<option value="heart"' . selected($icono, 'heart', false) . '>Corazón (heart)</option>';
    echo '<option value="gift"' . selected($icono, 'gift', false) . '>Regalo (gift)</option>';
    echo '</select>';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<th><label for="producto_beneficios">Beneficios</label></th>';
    echo '<td>';
    echo '<textarea id="producto_beneficios" name="producto_beneficios" rows="4" cols="50" placeholder="Un beneficio por línea">' . esc_textarea($beneficios) . '</textarea>';
    echo '<p class="description">Ingresa un beneficio por línea</p>';
    echo '</td>';
    echo '</tr>';

    echo '</table>';
}

function save_producto_details($post_id)
{
    if (!isset($_POST['producto_details_nonce']) || !wp_verify_nonce($_POST['producto_details_nonce'], 'save_producto_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['producto_precio'])) {
        update_post_meta($post_id, '_producto_precio', sanitize_text_field($_POST['producto_precio']));
    }

    if (isset($_POST['producto_icono'])) {
        update_post_meta($post_id, '_producto_icono', sanitize_text_field($_POST['producto_icono']));
    }

    if (isset($_POST['producto_beneficios'])) {
        update_post_meta($post_id, '_producto_beneficios', sanitize_textarea_field($_POST['producto_beneficios']));
    }
}
add_action('save_post', 'save_producto_details');

// Include shortcodes
require_once get_template_directory() . '/inc/shortcodes.php';

// Custom body classes for page-specific styling
function masajista_masculino_body_classes($classes)
{
    if (is_page_template('page-servicios.php')) {
        $classes[] = 'page-template-servicios';
    }

    if (is_page_template('page-contactos.php')) {
        $classes[] = 'page-template-contactos';
    }

    if (is_page_template('page-reservas.php')) {
        $classes[] = 'page-template-reservas';
    }

    // NUEVO: Clase para página de productos
    if (is_page_template('page-productos.php')) {
        $classes[] = 'page-template-productos';
    }

    return $classes;
}
add_filter('body_class', 'masajista_masculino_body_classes');

// Optimize images
function masajista_masculino_image_sizes()
{
    add_image_size('service-thumbnail', 400, 300, true);
    add_image_size('hero-image', 1920, 1080, true);
    // NUEVO: Tamaño para productos
    add_image_size('producto-thumbnail', 300, 300, true);
}
add_action('after_setup_theme', 'masajista_masculino_image_sizes');

// Security enhancements
function masajista_masculino_security()
{
    // Remove WordPress version from head
    remove_action('wp_head', 'wp_generator');

    // Remove RSD link
    remove_action('wp_head', 'rsd_link');

    // Remove wlwmanifest.xml
    remove_action('wp_head', 'wlwmanifest_link');
}
add_action('init', 'masajista_masculino_security');

// Performance optimizations
function masajista_masculino_performance()
{
    // Remove emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    // Remove jQuery migrate
    function remove_jquery_migrate($scripts)
    {
        if (!is_admin() && isset($scripts->registered['jquery'])) {
            $script = $scripts->registered['jquery'];
            if ($script->deps) {
                $script->deps = array_diff($script->deps, array('jquery-migrate'));
            }
        }
    }
    add_action('wp_default_scripts', 'remove_jquery_migrate');
}
add_action('init', 'masajista_masculino_performance');

/**
 * SISTEMA ROBUSTO DE ACCESO A BASE DE DATOS - SIN DATOS HARDCODEADOS
 * Todas las validaciones y controles para acceso exitoso a BD y tablas
 */

// Verificar estado de la base de datos
function mm_check_database_connection()
{
    global $wpdb;

    try {
        // Verificar conexión básica
        $result = $wpdb->get_var("SELECT 1");

        if ($result !== '1') {
            error_log('MM Error: Conexión a base de datos fallida');
            return false;
        }

        // Verificar que las tablas principales existen
        $tables_to_check = array(
            $wpdb->posts,
            $wpdb->postmeta,
            $wpdb->users
        );

        foreach ($tables_to_check as $table) {
            $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table));
            if (!$table_exists) {
                error_log("MM Error: Tabla requerida no existe: {$table}");
                return false;
            }
        }

        return true;
    } catch (Exception $e) {
        error_log('MM Error: Excepción en verificación de BD: ' . $e->getMessage());
        return false;
    }
}

// Verificar que el custom post type existe y está registrado
function mm_verify_post_type_exists($post_type)
{
    if (!post_type_exists($post_type)) {
        error_log("MM Error: Custom post type '{$post_type}' no está registrado");
        return false;
    }

    $post_type_object = get_post_type_object($post_type);
    if (!$post_type_object || !$post_type_object->public) {
        error_log("MM Error: Custom post type '{$post_type}' no es público o no está configurado correctamente");
        return false;
    }

    return true;
}

// Función principal para obtener productos con validaciones completas
function get_productos_data()
{
    // 1. Verificar conexión a base de datos
    if (!mm_check_database_connection()) {
        error_log('MM Error: No se puede conectar a la base de datos');
        return array();
    }

    // 2. Verificar que el custom post type existe
    if (!mm_verify_post_type_exists('producto')) {
        error_log('MM Error: Custom post type "producto" no disponible');
        return array();
    }

    // 3. Intentar obtener productos con manejo de errores
    try {
        $productos = get_posts(array(
            'post_type' => 'producto',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'suppress_filters' => false
        ));

        // 4. Verificar que la consulta fue exitosa
        if (is_wp_error($productos)) {
            error_log('MM Error: Error en consulta get_posts: ' . $productos->get_error_message());
            return array();
        }

        // 5. Log para debugging (solo para administradores)
        if (current_user_can('administrator')) {
            error_log('MM Info: Productos encontrados en BD: ' . count($productos));
        }

        // 6. Si no hay productos, devolver array vacío (sin fallback)
        if (empty($productos)) {
            if (current_user_can('administrator')) {
                error_log('MM Info: No hay productos publicados en la base de datos');
            }
            return array();
        }

        // 7. Procesar productos con validaciones
        $productos_data = array();

        foreach ($productos as $producto) {
            // Validar que el producto es válido
            if (!$producto || !isset($producto->ID)) {
                error_log('MM Warning: Producto inválido encontrado, saltando...');
                continue;
            }

            // Obtener meta datos con validación
            $precio = get_post_meta($producto->ID, '_producto_precio', true);
            $icono = get_post_meta($producto->ID, '_producto_icono', true);
            $beneficios_text = get_post_meta($producto->ID, '_producto_beneficios', true);

            // Validar meta datos críticos
            if (empty($precio)) {
                if (current_user_can('administrator')) {
                    error_log("MM Warning: Producto ID {$producto->ID} no tiene precio definido");
                }
                $precio = '0.00'; // Valor por defecto
            }

            if (empty($icono)) {
                $icono = 'star'; // Icono por defecto
            }

            // Procesar beneficios
            $beneficios = array();
            if (!empty($beneficios_text)) {
                $beneficios = array_filter(array_map('trim', explode("\n", $beneficios_text)));
            }

            // Obtener imagen con validación
            $imagen = get_the_post_thumbnail_url($producto->ID, 'medium');
            if (!$imagen) {
                $imagen = ''; // Sin imagen por defecto
            }

            // Construir array de datos del producto
            $productos_data[] = array(
                'id' => intval($producto->ID),
                'nombre' => sanitize_text_field($producto->post_title),
                'precio' => sanitize_text_field($precio),
                'descripcion' => $producto->post_excerpt ?
                    sanitize_text_field($producto->post_excerpt) :
                    sanitize_text_field(wp_trim_words(strip_tags($producto->post_content), 20)),
                'beneficios' => $beneficios,
                'icono' => sanitize_text_field($icono),
                'imagen' => esc_url($imagen),
                'permalink' => get_permalink($producto->ID)
            );
        }

        // 8. Log final para debugging
        if (current_user_can('administrator')) {
            error_log('MM Info: Productos procesados exitosamente: ' . count($productos_data));
        }

        return $productos_data;
    } catch (Exception $e) {
        error_log('MM Error: Excepción al obtener productos: ' . $e->getMessage());
        return array();
    }
}

// Función para obtener servicios con las mismas validaciones
function get_servicios_data()
{
    // 1. Verificar conexión a base de datos
    if (!mm_check_database_connection()) {
        error_log('MM Error: No se puede conectar a la base de datos para servicios');
        return array();
    }

    // 2. Verificar que el custom post type existe
    if (!mm_verify_post_type_exists('servicio')) {
        error_log('MM Error: Custom post type "servicio" no disponible');
        return array();
    }

    // 3. Intentar obtener servicios
    try {
        $servicios = get_posts(array(
            'post_type' => 'servicio',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'menu_order',
            'order' => 'ASC'
        ));

        if (is_wp_error($servicios)) {
            error_log('MM Error: Error en consulta servicios: ' . $servicios->get_error_message());
            return array();
        }

        if (empty($servicios)) {
            if (current_user_can('administrator')) {
                error_log('MM Info: No hay servicios publicados en la base de datos');
            }
            return array();
        }

        $servicios_data = array();

        foreach ($servicios as $servicio) {
            if (!$servicio || !isset($servicio->ID)) {
                continue;
            }

            $precio = get_post_meta($servicio->ID, '_servicio_precio', true);
            $duracion = get_post_meta($servicio->ID, '_servicio_duracion', true);

            $servicios_data[] = array(
                'id' => intval($servicio->ID),
                'nombre' => sanitize_text_field($servicio->post_title),
                'precio' => sanitize_text_field($precio ?: '0.00'),
                'duracion' => sanitize_text_field($duracion ?: 'No especificada'),
                'descripcion' => $servicio->post_excerpt ?
                    sanitize_text_field($servicio->post_excerpt) :
                    sanitize_text_field(wp_trim_words(strip_tags($servicio->post_content), 20)),
                'imagen' => get_the_post_thumbnail_url($servicio->ID, 'medium') ?: '',
                'permalink' => get_permalink($servicio->ID)
            );
        }

        return $servicios_data;
    } catch (Exception $e) {
        error_log('MM Error: Excepción al obtener servicios: ' . $e->getMessage());
        return array();
    }
}

// Shortcode para mostrar productos (sin datos hardcodeados)
function productos_grid_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'columns' => '4',
        'limit' => '24'
    ), $atts);

    $productos = get_productos_data();

    // Si no hay productos, mostrar mensaje apropiado
    if (empty($productos)) {
        return '<div class="productos-grid-shortcode productos-empty">
                    <p class="no-productos-message">No hay productos disponibles en este momento.</p>
                    <p class="no-productos-subtitle">Por favor, vuelve más tarde o contacta con nosotros.</p>
                </div>';
    }

    $output = '<div class="productos-grid-shortcode" style="display: grid; grid-template-columns: repeat(' . esc_attr($atts['columns']) . ', 1fr); gap: 1rem;">';

    $count = 0;
    foreach ($productos as $producto) {
        if ($count >= intval($atts['limit'])) break;

        $output .= '<div class="producto-card-mini">';
        $output .= '<h4>' . esc_html($producto['nombre']) . '</h4>';
        $output .= '<p class="precio">€' . esc_html($producto['precio']) . '</p>';
        $output .= '<p class="descripcion">' . esc_html($producto['descripcion']) . '</p>';
        if (!empty($producto['beneficios'])) {
            $output .= '<ul class="beneficios">';
            foreach (array_slice($producto['beneficios'], 0, 3) as $beneficio) {
                $output .= '<li>' . esc_html($beneficio) . '</li>';
            }
            $output .= '</ul>';
        }
        $output .= '</div>';

        $count++;
    }

    $output .= '</div>';
    return $output;
}
add_shortcode('productos_grid', 'productos_grid_shortcode');

// Ajax handler para productos con validaciones
function handle_producto_purchase()
{
    // Verificar nonce
    if (!check_ajax_referer('producto_purchase_nonce', 'nonce', false)) {
        wp_send_json_error(array(
            'message' => 'Error de seguridad: Token inválido'
        ));
        return;
    }

    // Verificar conexión a BD
    if (!mm_check_database_connection()) {
        wp_send_json_error(array(
            'message' => 'Error de conexión a la base de datos'
        ));
        return;
    }

    $producto_id = isset($_POST['producto_id']) ? intval($_POST['producto_id']) : 0;
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;

    // Validar producto ID
    if ($producto_id <= 0) {
        wp_send_json_error(array(
            'message' => 'ID de producto inválido'
        ));
        return;
    }

    // Verificar que el producto existe en la BD
    $producto = get_post($producto_id);
    if (!$producto || $producto->post_type !== 'producto' || $producto->post_status !== 'publish') {
        wp_send_json_error(array(
            'message' => 'Producto no encontrado o no disponible'
        ));
        return;
    }

    // Validar cantidad
    if ($cantidad <= 0 || $cantidad > 100) {
        wp_send_json_error(array(
            'message' => 'Cantidad inválida'
        ));
        return;
    }

    // Aquí iría la lógica de compra real
    // Por ahora solo devolvemos éxito con datos validados
    wp_send_json_success(array(
        'message' => 'Producto procesado correctamente',
        'producto_id' => $producto_id,
        'producto_nombre' => $producto->post_title,
        'cantidad' => $cantidad
    ));
}
add_action('wp_ajax_producto_purchase', 'handle_producto_purchase');
add_action('wp_ajax_nopriv_producto_purchase', 'handle_producto_purchase');

// Enqueue scripts conditionally con validaciones
function productos_conditional_scripts()
{
    if (is_page_template('page-productos.php')) {
        // Verificar que los archivos existen antes de encolarlos
        $js_file = get_template_directory() . '/assets/js/productos.js';
        $css_file = get_template_directory() . '/assets/css/productos.css';

        if (file_exists($js_file)) {
            wp_enqueue_script('mm-productos-js');
        } else {
            error_log('MM Warning: Archivo productos.js no encontrado');
        }

        if (file_exists($css_file)) {
            wp_enqueue_style('mm-productos-legacy');
        } else {
            error_log('MM Warning: Archivo productos.css no encontrado');
        }

        // Localizar script para Ajax con validaciones
        wp_localize_script('mm-productos-js', 'productos_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('producto_purchase_nonce'),
            'db_connected' => mm_check_database_connection() ? 'true' : 'false'
        ));
    }
}
add_action('wp_enqueue_scripts', 'productos_conditional_scripts');

/**
 * Helper functions for theme functionality
 */

// Get contact information
function mm_get_contact_info()
{
    return array(
        'phone' => '+34 123 456 789',
        'email' => 'info@masajista-masculino.com',
        'address' => 'Calle Ejemplo 123, Madrid',
        'whatsapp' => '+34 123 456 789',
        'hours' => 'Lunes a Domingo: 10:00 - 22:00'
    );
}

// Get page URL by slug
function mm_link_by_slug($slug)
{
    $page = get_page_by_path($slug);
    if ($page) {
        return get_permalink($page->ID);
    }

    // Fallback URLs if pages don't exist
    $fallback_urls = array(
        'reservas' => home_url('/page-reservas/'),
        'servicios' => home_url('/page-servicios/'),
        'contactos' => home_url('/page-contactos/'),
        'productos' => home_url('/page-productos/')
    );

    return isset($fallback_urls[$slug]) ? $fallback_urls[$slug] : home_url('/');
}

// Función para verificar integridad de datos al cargar páginas
function mm_verify_page_data_integrity()
{
    if (is_page_template('page-productos.php')) {
        $productos = get_productos_data();
        if (empty($productos) && current_user_can('administrator')) {
            add_action('wp_footer', function () {
                echo '<script>console.warn("MM: No hay productos en la base de datos. Considera crear algunos productos.");</script>';
            });
        }
    }

    if (is_page_template('page-servicios.php')) {
        $servicios = get_servicios_data();
        if (empty($servicios) && current_user_can('administrator')) {
            add_action('wp_footer', function () {
                echo '<script>console.warn("MM: No hay servicios en la base de datos. Considera crear algunos servicios.");</script>';
            });
        }
    }
}
add_action('wp', 'mm_verify_page_data_integrity');
