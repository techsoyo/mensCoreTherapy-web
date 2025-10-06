<?php
/**
 * Script de migración manual para productos
 * 
 * Para usar este script:
 * 1. Accede a: tu-sitio.com/wp-content/themes/masajista-masculino/migrar-productos.php
 * 2. O ejecuta desde wp-admin utilizando un plugin como Code Snippets
 * 
 * IMPORTANTE: Este archivo debe eliminarse después de usar
 */

// Evitar acceso directo sin WordPress
if (!function_exists('wp_insert_post')) {
    // Cargar WordPress si no está cargado
    require_once('../../../../wp-load.php');
}

// Verificar permisos
if (!current_user_can('manage_options')) {
    wp_die('No tienes permisos para ejecutar este script.');
}

echo "<h2>Migración de Productos - Men's Core Therapy</h2>";

// Verificar si ya se migró
if (get_option('productos_migrados')) {
    echo "<p><strong>⚠️ Los productos ya fueron migrados anteriormente.</strong></p>";
    echo "<p>Si quieres forzar una nueva migración, elimina la opción 'productos_migrados' de la base de datos.</p>";
    
    // Mostrar productos existentes
    $productos_existentes = get_posts(array(
        'post_type' => 'producto',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));
    
    if ($productos_existentes) {
        echo "<h3>Productos existentes (" . count($productos_existentes) . "):</h3>";
        echo "<ul>";
        foreach ($productos_existentes as $producto) {
            $precio = get_post_meta($producto->ID, '_producto_precio', true);
            echo "<li><strong>" . $producto->post_title . "</strong> - €" . $precio . " (ID: " . $producto->ID . ")</li>";
        }
        echo "</ul>";
    }
    
    echo "<hr>";
    echo "<p><a href='javascript:history.back()'>← Volver</a></p>";
    exit;
}

// Datos de productos para migrar
$productos_iniciales = array(
    array(
        'titulo' => 'Aceite Relajante Premium',
        'descripcion' => 'Aceite premium de almendra con esencias naturales para masajes relajantes y terapéuticos. Formulado con ingredientes naturales de la más alta calidad para proporcionar una experiencia de bienestar completa.',
        'precio' => '19.90',
        'beneficios' => "Hidrata profundamente\nAroma relajante duradero\n250ml de duración\nBase 100% natural\nFácil absorción",
        'icono' => 'leaf'
    ),
    array(
        'titulo' => 'Velas Aromáticas Premium',
        'descripcion' => 'Set de 3 velas con aromas relajantes especialmente seleccionados para crear un ambiente de paz y tranquilidad durante los tratamientos de masaje y relajación.',
        'precio' => '25.00',
        'beneficios' => "3 aromas diferentes\n40h de duración total\nCera natural de soja\nPackaging elegante\nAroma balanceado",
        'icono' => 'fire'
    ),
    array(
        'titulo' => 'Kit Completo Premium',
        'descripcion' => 'Pack premium con aceites, velas y accesorios para una experiencia completa de bienestar y relajación en casa. Todo lo necesario para crear un ambiente profesional.',
        'precio' => '75.00',
        'beneficios' => "3 aceites premium incluidos\nSet completo de velas\nToallas especiales suaves\nGuía de masajes profesional\nCaja de regalo elegante",
        'icono' => 'star'
    )
);

echo "<h3>Iniciando migración...</h3>";

$productos_creados = 0;
$errores = 0;

foreach ($productos_iniciales as $index => $producto_data) {
    echo "<p>Creando producto: <strong>" . $producto_data['titulo'] . "</strong>... ";
    
    $post_id = wp_insert_post(array(
        'post_title' => $producto_data['titulo'],
        'post_content' => $producto_data['descripcion'],
        'post_excerpt' => wp_trim_words($producto_data['descripcion'], 20),
        'post_status' => 'publish',
        'post_type' => 'producto',
        'post_author' => get_current_user_id(),
        'menu_order' => $index + 1
    ));

    if ($post_id && !is_wp_error($post_id)) {
        // Agregar meta fields
        update_post_meta($post_id, '_producto_precio', $producto_data['precio']);
        update_post_meta($post_id, '_producto_icono', $producto_data['icono']);
        update_post_meta($post_id, '_producto_beneficios', $producto_data['beneficios']);
        
        echo "<span style='color: green;'>✅ Creado exitosamente (ID: $post_id)</span></p>";
        $productos_creados++;
    } else {
        echo "<span style='color: red;'>❌ Error al crear</span></p>";
        if (is_wp_error($post_id)) {
            echo "<p style='color: red; margin-left: 20px;'>Error: " . $post_id->get_error_message() . "</p>";
        }
        $errores++;
    }
}

if ($productos_creados > 0) {
    // Marcar migración como completada
    update_option('productos_migrados', true);
    echo "<h3 style='color: green;'>✅ Migración completada exitosamente!</h3>";
    echo "<p><strong>Productos creados:</strong> $productos_creados</p>";
    if ($errores > 0) {
        echo "<p><strong>Errores:</strong> $errores</p>";
    }
    
    echo "<h4>Próximos pasos:</h4>";
    echo "<ol>";
    echo "<li>Ve al panel de administración de WordPress</li>";
    echo "<li>Navega a '<strong>Productos</strong>' en el menú lateral</li>";
    echo "<li>Edita cada producto para añadir imágenes si lo deseas</li>";
    echo "<li>Visita la página de productos en tu sitio web para ver los resultados</li>";
    echo "<li><strong>IMPORTANTE:</strong> Elimina este archivo (migrar-productos.php) por seguridad</li>";
    echo "</ol>";
    
} else {
    echo "<h3 style='color: red;'>❌ La migración falló</h3>";
    echo "<p>No se pudo crear ningún producto. Revisa los errores anteriores.</p>";
}

echo "<hr>";
echo "<p><a href='" . admin_url('edit.php?post_type=producto') . "'>Ver productos en Admin</a> | ";
echo "<a href='" . home_url('/productos/') . "'>Ver página de productos</a> | ";
echo "<a href='javascript:history.back()'>← Volver</a></p>";

?>