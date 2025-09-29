<?php if (!defined('ABSPATH')) exit; ?>
<section class="mm-precios">
  <div class="container">
    <h1><?php _e('Precios', 'masajista-masculino'); ?></h1>

    <?php
    // Si existe CPT 'servicio' y meta _mm_precio, generamos tabla de precios automáticamente.
    if (post_type_exists('servicio')) {
        $q = new WP_Query([
            'post_type'      => 'servicio',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
        ]);
        if ($q->have_posts()) {
            echo '<table class="mm-table mm-table--precios"><thead><tr>';
            echo '<th>'.esc_html__('Servicio','masajista-masculino').'</th>';
            echo '<th>'.esc_html__('Duración','masajista-masculino').'</th>';
            echo '<th>'.esc_html__('Precio','masajista-masculino').'</th>';
            echo '</tr></thead><tbody>';
            while ($q->have_posts()) { $q->the_post();
                $price = get_post_meta(get_the_ID(), '_mm_precio', true);
                $dur   = get_post_meta(get_the_ID(), '_mm_duracion', true);
                echo '<tr>';
                echo '<td>'.esc_html(get_the_title()).'</td>';
                echo '<td>'.esc_html($dur ?: '—').'</td>';
                echo '<td>'.esc_html($price ?: '—').'</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
            wp_reset_postdata();
        } else {
            echo '<p>'.__('No hay servicios para mostrar precios.', 'masajista-masculino').'</p>';
        }
    } else {
        // Fallback 100% funcional: tabla estática editable desde Elementor si se desea.
        ?>
        <table class="mm-table mm-table--precios">
          <thead>
            <tr><th>Servicio</th><th>Duración</th><th>Precio</th></tr>
          </thead>
          <tbody>
            <tr><td>Masaje Relajante</td><td>60 min</td><td>50€</td></tr>
            <tr><td>Masaje Descontracturante</td><td>60 min</td><td>55€</td></tr>
            <tr><td>Masaje Deportivo</td><td>75 min</td><td>65€</td></tr>
          </tbody>
        </table>
        <?php
    }
    ?>
  </div>
</section>
