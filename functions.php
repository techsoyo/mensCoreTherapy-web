<?php
if (!defined('ABSPATH')) exit;

/**
 * Enqueue styles & scripts
 */
add_action('wp_enqueue_scripts', function () {
    // Google Fonts: Solo las fuentes necesarias
    wp_enqueue_style(
        'mm-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;1,100&family=Montserrat+Alternates:wght@400&display=swap',
        [],
        '1.0.0'
    );

    // Font Awesome
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        [],
        '6.4.0'
    );

    // Main CSS
    wp_enqueue_style(
        'mm-main',
        get_template_directory_uri() . '/assets/css/main.css',
        ['mm-fonts', 'font-awesome'],
        '1.0.0'
    );

    // Neomorphic Effects CSS - Se carga antes que parallax para evitar conflictos de renderizado
    wp_enqueue_style(
        'mm-neomorphic',
        get_template_directory_uri() . '/assets/css/neomorphic-effects.css',
        ['mm-main'],
        '1.0.0'
    );

    // Parallax CSS - Solo en la página de servicios, depende de neomorphic
    if (is_page('servicios') || is_page('services') || is_page_template('page-servicios.php') || is_post_type_archive('servicio') || get_post_meta(get_the_ID(), '_wp_page_template', true) === 'page-servicios.php') {
        wp_enqueue_style(
            'mm-parallax',
            get_template_directory_uri() . '/assets/css/parallax.css',
            ['mm-main', 'mm-neomorphic'],
            '1.0.0'
        );
    }


    // Main JS
    wp_enqueue_script(
        'mm-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        filemtime(get_template_directory() . '/assets/js/main.js'),
        true
    );

    // Parallax JS - Solo en la página de servicios
    if (is_page('servicios') || is_page('services') || is_page_template('page-servicios.php') || is_post_type_archive('servicio') || get_post_meta(get_the_ID(), '_wp_page_template', true) === 'page-servicios.php') {
        wp_enqueue_script(
            'mm-parallax',
            get_template_directory_uri() . '/assets/js/parallax.js',
            [],
            filemtime(get_template_directory() . '/assets/js/parallax.js'),
            true
        );
    }

    // Header Visibility JS
    wp_enqueue_script(
        'mm-header-visibility',
        get_template_directory_uri() . '/assets/js/header-visibility.js',
        [],
        filemtime(get_template_directory() . '/assets/js/header-visibility.js'),
        true
    );
});

/**
 * Theme supports & nav menu
 */
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');

    register_nav_menus([
        'primary' => __('Menú principal', 'masajista-masculino'),
    ]);

    add_image_size('mm-card', 800, 600, true);
});


/**
 * Permitir subida de MP4 si está restringida
 */
add_filter('upload_mimes', function ($mimes) {
    $mimes['mp4'] = 'video/mp4';
    return $mimes;
});

/**
 * Helper: link por slug
 */
function mm_link_by_slug($slug, $fallback = '#')
{
    $page = get_page_by_path($slug);
    return $page ? get_permalink($page) : $fallback;
}

/**
 * Customizer: vídeo de Home y datos de contacto
 */
add_action('customize_register', function ($wp_customize) {
    $wp_customize->add_section('mm_home', [
        'title' => __('Portada (Home)', 'masajista-masculino'),
        'priority' => 30,
    ]);

    $wp_customize->add_setting('mm_home_video', ['default' => '', 'sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control('mm_home_video', [
        'label' => __('URL del vídeo MP4 de fondo', 'masajista-masculino'),
        'section' => 'mm_home',
        'type' => 'url',
    ]);

    $wp_customize->add_setting('mm_phone', ['default' => '+34 666 777 888', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('mm_phone', [
        'label' => __('Teléfono', 'masajista-masculino'),
        'section' => 'mm_home',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('mm_hours', ['default' => 'Lunes a Domingo: 10:00 - 22:00', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('mm_hours', [
        'label' => __('Horario', 'masajista-masculino'),
        'section' => 'mm_home',
        'type' => 'text',
    ]);
});

/**
 * Datos de contacto centralizados
 */
function mm_get_contact_info()
{
    return [
        'phone' => get_theme_mod('mm_phone', '+34 666 777 888'),
        'hours' => get_theme_mod('mm_hours', 'Lunes a Domingo: 10:00 - 22:00'),
    ];
}

/**
 * Sembrado de páginas (UNA sola vez tras activar tema)
 * - Usa slugs canon: servicios, contactos, reservas,
 * - Si existe la versión singular (contacto/reserva), la renombra a plural si el plural no existe.
 * - Si no existe ninguna, crea la página con contenido placeholder.
 */
add_action('after_switch_theme', function () {
    if (get_option('mm_pages_seeded')) return;

    $map = [
        'servicios' => ['Servicios', []],
        'contactos' => ['Contactos', ['contacto']],
        'reservas'  => ['Reservas',  ['reserva']],
    ];

    foreach ($map as $slugPlural => [$titlePlural, $alternativos]) {
        $existsPlural = get_page_by_path($slugPlural);
        if ($existsPlural) {
            // Ya existe el plural → nada que hacer
            continue;
        }

        // Buscar alternativas singulares para renombrar
        $renamed = false;
        foreach ($alternativos as $alt) {
            $pAlt = get_page_by_path($alt);
            if ($pAlt) {
                // Renombrar a plural (si no hay colisión)
                wp_update_post([
                    'ID'         => $pAlt->ID,
                    'post_name'  => sanitize_title($slugPlural),
                    'post_title' => $titlePlural,
                    'post_status' => 'publish',
                    'post_type'  => 'page',
                ]);
                $renamed = true;
                break;
            }
        }

        if (!$renamed) {
            // Crear página nueva en plural
            wp_insert_post([
                'post_title'   => $titlePlural,
                'post_name'    => $slugPlural,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => "Contenido de la página {$titlePlural}",
            ]);
        }
    }

    update_option('mm_pages_seeded', 1);
});

/**
 * Redirecciones 301 (singular → plural)
 */
add_action('template_redirect', function () {
    if (is_page('contacto')) {
        wp_safe_redirect(home_url('/contactos/'), 301);
        exit;
    }
    if (is_page('reserva')) {
        wp_safe_redirect(home_url('/reservas/'), 301);
        exit;
    }
});

/**
 * Shortcode [mm_hero video="URL"]
 */
require_once get_template_directory() . '/inc/shortcodes.php';

/**
 * Seguridad básica de formularios (nonce verification helper)
 */
function mm_verify_nonce($action, $field = '_wpnonce')
{
    if (!isset($_POST[$field]) || !wp_verify_nonce($_POST[$field], $action)) {
        return false;
    }
    return true;
}


add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'mm-hotfix',
        get_stylesheet_directory_uri() . '/assets/css/hotfix.css',
        ['mm-main', 'mm-neomorphic'], // dependencias explícitas para cargar después
        '1.0.0'
    );
}, 999);


add_action('init', function () {
    register_post_type('servicio', [
        'labels'      => ['name' => 'Servicios', 'singular_name' => 'Servicio'],
        'public'      => true,
        'has_archive' => true,
        'rewrite'     => ['slug' => 'servicios'],
        'supports'    => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'],
        'show_in_rest' => true,
        'menu_icon'   => 'dashicons-heart',
    ]);
});

// Registrar CPT "servicio"
add_action('init', function () {
    register_post_type('servicio', [
        'labels'      => ['name' => 'Servicios', 'singular_name' => 'Servicio'],
        'public'      => true,
        'has_archive' => true,
        'rewrite'     => ['slug' => 'servicios'],
        'supports'    => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'],
        'show_in_rest' => true,
        'menu_icon'   => 'dashicons-heart',
    ]);
});

// Añadir metaboxes precio / duración
add_action('add_meta_boxes', function () {
    add_meta_box('mm_datos', 'Datos del servicio', 'mm_servicio_mb_html', 'servicio', 'side');
});
function mm_servicio_mb_html($post)
{
    wp_nonce_field('mm_servicio_save', 'mm_servicio_nonce');
    $price = get_post_meta($post->ID, '_mm_precio', true);
    $dur   = get_post_meta($post->ID, '_mm_duracion', true);
    echo '<p><label>Precio </label><input type="text" name="_mm_precio" value="' . esc_attr($price) . '" style="width:100%"></p>';
    echo '<p><label>Duración</label><input type="text" name="_mm_duracion" value="' . esc_attr($dur) . '" style="width:100%"></p>';
}
add_action('save_post', function ($id) {
    if (get_post_type($id) !== 'servicio') return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $id)) return;
    
    if (isset($_POST['_mm_precio'])) {
        update_post_meta($id, '_mm_precio', sanitize_text_field($_POST['_mm_precio']));
    }
    if (isset($_POST['_mm_duracion'])) {
        update_post_meta($id, '_mm_duracion', sanitize_text_field($_POST['_mm_duracion']));
    }
});
