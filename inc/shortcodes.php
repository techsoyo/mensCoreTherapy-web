<?php
if (!defined('ABSPATH')) exit;

add_shortcode('mm_hero', function($atts){
    $atts = shortcode_atts([ 'video' => '' ], $atts);
    ob_start();
    get_template_part('template-parts/hero', null, [
        'video_url' => esc_url($atts['video'])
    ]);
    return ob_get_clean();
});
