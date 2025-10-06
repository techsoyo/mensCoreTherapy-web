<?php
if (!defined('ABSPATH')) exit;

// Enqueue styles and scripts
function masajista_masculino_scripts()
{
    // Main stylesheet
    wp_enqueue_style('masajista-masculino-style', get_stylesheet_uri());

    // CSS files
    wp_enqueue_style('mm-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0');
    wp_enqueue_style('mm-header', get_template_directory_uri() . '/assets/css/header.css', array(), '1.0.0');
    wp_enqueue_style('mm-parallax', get_template_directory_uri() . '/assets/css/parallax.css', array(), '1.0.0');
    wp_enqueue_style('mm-neomorphic', get_template_directory_uri() . '/assets/css/neomorphic-effects.css', array(), '1.0.0');
    wp_enqueue_style('mm-contactos', get_template_directory_uri() . '/assets/css/contactos.css', array(), '1.0.0');
    wp_enqueue_style('mm-reservas', get_template_directory_uri() . '/assets/css/reservas.css', array(), '1.0.0');
    wp_enqueue_style('mm-servicios', get_template_directory_uri() . '/assets/css/servicios.css', array(), '1.0.0');
    wp_enqueue_style('mm-hotfix', get_template_directory_uri() . '/assets/css/hotfix.css', array(), '1.0.0');

    // Productos CSS - NUEVO
    wp_enqueue_style('mm-productos', get_template_directory_uri() . '/assets/css/productos.css', array(), '1.0.0');
    // wp_enqueue_style('mm-productos-integration', get_template_directory_uri() . '/assets/css/productos-integration.css', array('mm-productos'), '1.0.0');

    // JavaScript files
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

// NUEVO: Funciones específicas para productos
function get_productos_data()
{
    $productos = get_posts(array(
        'post_type' => 'producto',
        'posts_per_page' => -1, // Obtener todos
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ));

    // DEBUG: Verificar si hay productos
    if (current_user_can('administrator')) {
        error_log('DEBUG: Productos encontrados: ' . count($productos));
    }

    $productos_data = array();

    foreach ($productos as $producto) {
        $precio = get_post_meta($producto->ID, '_producto_precio', true);
        $icono = get_post_meta($producto->ID, '_producto_icono', true);
        $beneficios_text = get_post_meta($producto->ID, '_producto_beneficios', true);

        // DEBUG: Verificar meta fields
        if (current_user_can('administrator')) {
            error_log('DEBUG Producto ID ' . $producto->ID . ': precio=' . $precio . ', icono=' . $icono);
        }

        // Convertir beneficios de texto a array
        $beneficios = !empty($beneficios_text) ? array_filter(array_map('trim', explode("\n", $beneficios_text))) : array();

        $productos_data[] = array(
            'id' => $producto->ID,
            'nombre' => $producto->post_title,
            'precio' => $precio ?: '0.00',
            'descripcion' => $producto->post_excerpt ?: strip_tags($producto->post_content),
            'beneficios' => $beneficios,
            'icono' => $icono ?: 'star',
            'imagen' => get_the_post_thumbnail_url($producto->ID, 'medium'),
            'permalink' => get_permalink($producto->ID)
        );
    }

    // Si no hay productos en la BD, devolver los datos por defecto
    if (empty($productos_data)) {
        return get_productos_data_fallback();
    }

    return $productos_data;
}

// Función de respaldo con datos hardcoded
function get_productos_data_fallback()
{
    return array(
        array(
            'id' => 0,
            'nombre' => 'Aceite Relajante Premium',
            'precio' => '19.90',
            'descripcion' => 'Aceite premium de almendra con esencias naturales',
            'beneficios' => array('Hidrata profundamente', 'Aroma relajante', '250ml de duración', 'Base natural'),
            'icono' => 'leaf',
            'imagen' => '',
            'permalink' => '#'
        ),
        array(
            'id' => 0,
            'nombre' => 'Velas Aromáticas',
            'precio' => '25.00',
            'descripcion' => 'Set de 3 velas con aromas relajantes',
            'beneficios' => array('3 aromas diferentes', '40h de duración', 'Cera natural', 'Packaging elegante'),
            'icono' => 'fire',
            'imagen' => '',
            'permalink' => '#'
        ),
        array(
            'id' => 0,
            'nombre' => 'Kit Completo Premium',
            'precio' => '75.00',
            'descripcion' => 'Pack premium con aceites, velas y accesorios',
            'beneficios' => array('3 aceites premium', 'Set de velas', 'Toallas especiales', 'Guía de masajes'),
            'icono' => 'star',
            'imagen' => '',
            'permalink' => '#'
        )
    );
}

// NUEVO: Shortcode para mostrar productos
function productos_grid_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'columns' => '4',
        'limit' => '24'
    ), $atts);

    $productos = get_productos_data();
    $output = '<div class="productos-grid-shortcode" style="display: grid; grid-template-columns: repeat(' . esc_attr($atts['columns']) . ', 1fr); gap: 1rem;">';

    $count = 0;
    foreach ($productos as $producto) {
        if ($count >= intval($atts['limit'])) break;

        $output .= '<div class="producto-card-mini">';
        $output .= '<h4>' . esc_html($producto['nombre']) . '</h4>';
        $output .= '<p>€' . esc_html($producto['precio']) . '</p>';
        $output .= '<p>' . esc_html($producto['descripcion']) . '</p>';
        $output .= '</div>';

        $count++;
    }

    $output .= '</div>';
    return $output;
}
add_shortcode('productos_grid', 'productos_grid_shortcode');

// NUEVO: Ajax handler para productos (si se necesita en el futuro)
function handle_producto_purchase()
{
    check_ajax_referer('producto_purchase_nonce', 'nonce');

    $producto_id = sanitize_text_field($_POST['producto_id']);
    $cantidad = intval($_POST['cantidad']);

    // Aquí iría la lógica de compra
    // Por ahora solo devolvemos éxito

    wp_send_json_success(array(
        'message' => 'Producto añadido al carrito',
        'producto_id' => $producto_id,
        'cantidad' => $cantidad
    ));
}
add_action('wp_ajax_producto_purchase', 'handle_producto_purchase');
add_action('wp_ajax_nopriv_producto_purchase', 'handle_producto_purchase');

// Función para migrar datos iniciales de productos
function migrar_productos_iniciales()
{
    // Verificar si ya se ejecutó la migración
    if (get_option('productos_migrados')) {
        return;
    }

    // Datos iniciales para migrar
    $productos_iniciales = array(
        array(
            'titulo' => 'Aceite Relajante Premium',
            'descripcion' => 'Aceite premium de almendra con esencias naturales para masajes relajantes y terapéuticos. Formulado con ingredientes naturales de la más alta calidad.',
            'precio' => '19.90',
            'beneficios' => "Hidrata profundamente\nAroma relajante\n250ml de duración\nBase natural",
            'icono' => 'leaf'
        ),
        array(
            'titulo' => 'Velas Aromáticas',
            'descripcion' => 'Set de 3 velas con aromas relajantes especialmente seleccionados para crear un ambiente de paz y tranquilidad durante los tratamientos.',
            'precio' => '25.00',
            'beneficios' => "3 aromas diferentes\n40h de duración\nCera natural\nPackaging elegante",
            'icono' => 'fire'
        ),
        array(
            'titulo' => 'Kit Completo Premium',
            'descripcion' => 'Pack premium con aceites, velas y accesorios para una experiencia completa de bienestar y relajación en casa.',
            'precio' => '75.00',
            'beneficios' => "3 aceites premium\nSet de velas\nToallas especiales\nGuía de masajes",
            'icono' => 'star'
        )
    );

    // Insertar productos en la base de datos
    foreach ($productos_iniciales as $producto_data) {
        $post_id = wp_insert_post(array(
            'post_title' => $producto_data['titulo'],
            'post_content' => $producto_data['descripcion'],
            'post_excerpt' => wp_trim_words($producto_data['descripcion'], 20),
            'post_status' => 'publish',
            'post_type' => 'producto',
            'post_author' => 1
        ));

        if ($post_id && !is_wp_error($post_id)) {
            // Agregar meta fields
            update_post_meta($post_id, '_producto_precio', $producto_data['precio']);
            update_post_meta($post_id, '_producto_icono', $producto_data['icono']);
            update_post_meta($post_id, '_producto_beneficios', $producto_data['beneficios']);
        }
    }

    // Marcar migración como completada
    update_option('productos_migrados', true);
}

// Ejecutar migración al activar el tema o en init (una sola vez)
function ejecutar_migracion_productos()
{
    // Solo ejecutar si el custom post type existe
    if (post_type_exists('producto')) {
        migrar_productos_iniciales();
    }
}
add_action('after_switch_theme', 'ejecutar_migracion_productos');
add_action('init', 'ejecutar_migracion_productos', 20); // Prioridad alta para que se ejecute después del registro del post type

// Enqueue scripts conditionally

// NUEVO: Enqueue scripts solo en página de productos
function productos_conditional_scripts()
{
    if (is_page_template('page-productos.php')) {
        wp_enqueue_script('mm-productos-js');
        wp_enqueue_style('mm-productos');
        wp_enqueue_style('mm-productos-integration');

        // Localizar script para Ajax
        wp_localize_script('mm-productos-js', 'productos_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('producto_purchase_nonce')
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
