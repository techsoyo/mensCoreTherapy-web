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
            echo '<div class="services-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(23.5rem, 1fr)); gap: 2rem; max-width: 70rem; margin: 0 auto;">';
            while ($q->have_posts()) { $q->the_post();
        $price = get_post_meta(get_the_ID(), '_mm_precio', true);
        $dur   = get_post_meta(get_the_ID(), '_mm_duracion', true);

        // Depuración: para administradores, mostrar comentario HTML con los valores
        if (function_exists('current_user_can') && current_user_can('manage_options')) {
          $debug = sprintf('id=%d price="%s" dur="%s"', get_the_ID(), esc_attr($price), esc_attr($dur));
          echo "<!-- mm-debug-servicio: {$debug} -->\n";
          if (function_exists('error_log')) {
            error_log("mm-debug-servicio: {$debug}");
          }

          // Badge visible en la página (solo admins) para facilitar depuración en navegador
          echo '<div class="mm-debug-badge" style="position:fixed;left:1rem;bottom:1rem;background:rgba(0,0,0,0.7);color:#fff;padding:0.5rem 0.75rem;border-radius:6px;font-size:0.85rem;z-index:9999;">';
          echo 'MM debug — ID: ' . esc_html(get_the_ID()) . '<br>';
          echo 'precio: ' . ( $price ? esc_html($price) : '<em>empty</em>' ) . '<br>';
          echo 'dur: ' . ( $dur ? esc_html($dur) : '<em>empty</em>' );
          echo '</div>';
        }
                ?>
                <article class="service-card flip-card" onclick="this.classList.toggle('flipped')" style="perspective: 62.5rem; cursor: pointer; height: 25rem;">
                  <div class="flip-card-inner" style="position: relative; width: 100%; height: 100%; text-align: center; transition: transform 0.8s; transform-style: preserve-3d;">
                    
                    <!-- CARA FRONTAL -->
                    <div class="flip-card-front" style="position: absolute; width: 100%; height: 100%; backface-visibility: hidden; background: rgba(255, 255, 255, 0.9); border-radius: 1rem; padding: 1.5rem; box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.2); backdrop-filter: blur(15px); color: #111; display: flex; flex-direction: column;">
                      <?php if (has_post_thumbnail()) : ?>
                        <div class="service-thumb" style="margin-bottom: 1rem; border-radius: 0.5rem; overflow: hidden; flex-shrink: 0;"><?php the_post_thumbnail('mm-card', array('style' => 'width: 100%; height: 12.5rem; object-fit: cover;')); ?></div>
                      <?php endif; ?>
                      <h3 class="service-title" style="margin-bottom: 0.75rem; font-size: 1.2rem; color: #111;"><?php the_title(); ?></h3>
                      <div class="service-excerpt" style="font-size: 0.9rem; line-height: 1.4; flex-grow: 1; overflow: hidden;"><?php the_excerpt(); ?></div>
                      <div style="margin-top: 1rem; font-size: 0.8rem; color: #666; font-style: italic;">Clic para más información</div>
                    </div>
                    
                    <!-- CARA TRASERA -->
                    <div class="flip-card-back" style="position: absolute; width: 100%; height: 100%; backface-visibility: hidden; background: linear-gradient(135deg, #AA3000, #cc4000); border-radius: 1rem; padding: 1.5rem; box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.2); backdrop-filter: blur(15px); color: white; transform: rotateY(180deg); display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                      <h3 style="margin-bottom: 2rem; font-size: 1.4rem; color: white;"><?php the_title(); ?></h3>
                      
                      <?php if ($price) : ?>
                        <div style="margin-bottom: 2.5rem;">
                          <div style="font-size: 1rem; opacity: 0.9; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em;">Precio</div>
                          <div style="font-size: 3rem; font-weight: 100; line-height: 1;"><?php echo esc_html($price); ?></div>
                        </div>
                      <?php endif; ?>
                      
                      <?php if ($dur) : ?>
                        <div style="margin-bottom: 2.5rem;">
                          <div style="font-size: 1rem; opacity: 0.9; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em;">Duración</div>
                          <div style="font-size: 1.8rem; font-weight: 100; line-height: 1;"><?php echo esc_html($dur); ?></div>
                        </div>
                      <?php endif; ?>
                      
                      <?php if (!$price && !$dur) : ?>
                        <div style="margin-bottom: 2rem; opacity: 0.8; font-style: italic;">
                          <div>Información</div>
                          <div>disponible próximamente</div>
                        </div>
                      <?php endif; ?>
                      
                      <div style="margin-top: auto; font-size: 0.9rem; opacity: 0.8; font-style: italic;">Clic para volver</div>
                    </div>
                  </div>
                </article>
                <?php
            }
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p>'.__('No hay servicios publicados.', 'masajista-masculino').'</p>';
        }
    } else {
        echo '<p>'.__('Registra el CPT "servicio" para mostrar un grid automático de servicios.', 'masajista-masculino').'</p>';
    }
    ?>
  </div>
</section>
