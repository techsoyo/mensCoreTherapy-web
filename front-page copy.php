<?php
if (!defined('ABSPATH')) exit;

// Carga el header de WordPress, o el de Elementor si está activo y configurado
if (! function_exists('elementor_theme_do_location') || ! elementor_theme_do_location('header')) {
  get_header();
}

// Incluye el hero section desde el partial, pasando la URL del vídeo
get_template_part('template-parts/hero', null, [
  'video_url' => get_stylesheet_directory_uri() . '/assets/images/Vídeo1.mp4'
]);

// Cierra con el footer
get_footer(); 
?>