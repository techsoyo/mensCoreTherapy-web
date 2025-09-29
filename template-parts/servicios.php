<?php if (!defined('ABSPATH')) exit; ?>
<section class="mm-servicios">
  <div class="container">
    <h1><?php _e('Servicios', 'masajista-masculino'); ?></h1>

    <?php
    // Si existe CPT 'servicio', lo usamos. Si no, mostramos un aviso amigable.
    if (post_type_exists('servicio')) {
        $q = new WP_Query([
            'post_type'      => 'servicio',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
        ]);
        if ($q->have_posts()) {
            echo '<div class="mm-grid">';
            while ($q->have_posts()) { $q->the_post();
                $price = get_post_meta(get_the_ID(), '_mm_precio', true);
                $dur   = get_post_meta(get_the_ID(), '_mm_duracion', true);
                ?>
                <article class="mm-card">
                  <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>" class="mm-thumb"><?php the_post_thumbnail('mm-card'); ?></a>
                  <?php endif; ?>
                  <h3 class="mm-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                  <div class="mm-card__meta">
                    <?php if ($price) echo '<span class="pill">'.esc_html($price).'</span>'; ?>
                    <?php if ($dur)   echo '<span class="pill">'.esc_html($dur).'</span>'; ?>
                  </div>
                  <div class="mm-card__excerpt"><?php the_excerpt(); ?></div>
                </article>
                <?php
            }
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p>'.__('No hay servicios publicados.', 'masajista-masculino').'</p>';
        }
    } else {
        echo '<p>'.__('Puedes editar esta página con Elementor o registrar el CPT "servicio" para mostrar un grid automático.', 'masajista-masculino').'</p>';
    }
    ?>
  </div>
</section>
