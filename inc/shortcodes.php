<?php
if (!defined('ABSPATH')) exit;

add_shortcode('mm_hero', function($atts){
    $atts = shortcode_atts([ 'bg_image' => '' ], $atts);
    ob_start();
    get_template_part('template-parts/hero', null, [
        'bg_image' => esc_url($atts['bg_image'])
    ]);
    return ob_get_clean();
});
