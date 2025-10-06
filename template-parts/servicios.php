<?php if (!defined('ABSPATH')) exit; ?>
<section class="hero-section" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/torso.jpg'); background-size: cover; background-position: center; background-attachment: fixed; min-height: 70vh; position: relative; color: white;">
  <div class="hero-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>

  <div class="cards-container" style="position: relative; z-index: 1; padding: 5rem 1.25rem; max-width: 90rem; margin: 0 auto;">
    <h1 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; text-shadow: 0.125rem 0.125rem 0.5rem rgba(0, 0, 0, 0.8); color: white;"><?php _e('Servicios', 'masajista-masculino'); ?></h1>

    <?php
    // Obtener servicios específicos por IDs donde tenemos precios y duraciones
    $servicio_ids = [145, 146, 147, 148];

    // Títulos fallback en caso de que no haya títulos en la base de datos
    $titulos_fallback = [
      145 => 'Masaje de Piedras Calientes',
      146 => 'Masaje de Descarga',
      147 => 'Masaje Relajante',
      148 => 'Masaje Tantra'
    ];

    // Descripciones fallback
    $descripciones_fallback = [
      145 => 'Piedras de basalto se calientan a 50-55°C y se colocan sobre chakra puntos clave (espalda, manos, pies). El calor penetra en profundidad, relajando la musculatura.',
      146 => 'Trabajo profundo y preciso sobre grupos musculares exigidos. Dedos, antebrazos y codos se alternan para liberar contracturas, mejorar la circulación y acelerar la recuperación.',
      147 => 'Un abrazo lento de manos expertas que se deslizan con aceite templado por todo tu cuerpo. La presión es suave, casi aérea, como si el tiempo se detuviera.',
      148 => 'Un viaje lento y consciente en el que cada respiración guía tu energía hacia el placer. Con aceites tibios y movimientos ondulantes despertarás todos los rincones de tu piel.'
    ];

    $q = new WP_Query([
      'post_type'      => 'servicio',
      'post__in'       => $servicio_ids,
      'posts_per_page' => -1,
      'post_status'    => 'publish',
      'orderby'        => 'post__in',
      'order'          => 'ASC',
    ]);

    // Debug: mostrar información de la consulta
    echo '<!-- DEBUG: Query found ' . $q->found_posts . ' posts -->';

    if ($q->have_posts()) {
      echo '<div class="services-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(23.5rem, 1fr)); gap: 2rem; max-width: 70rem; margin: 0 auto;">';
      while ($q->have_posts()) {
        $q->the_post();
        $current_id = get_the_ID();
        $price = get_post_meta($current_id, 'precio', true);
        $dur   = get_post_meta($current_id, 'duracion', true);

        // Debug: mostrar información de cada post
        echo '<!-- DEBUG: Post ID=' . $current_id . ', Title=' . get_the_title() . ', Price=' . $price . ', Duration=' . $dur . ' -->';
    ?>
        <article class="service-card flip-card" onclick="this.classList.toggle('flipped')" style="perspective: 62.5rem; cursor: pointer; height: 25rem;">
          <div class="flip-card-inner" style="position: relative; width: 100%; height: 100%; text-align: center; transition: transform 0.8s; transform-style: preserve-3d;">

            <!-- CARA FRONTAL -->
            <div class="flip-card-front" style="position: absolute; width: 100%; height: 100%; backface-visibility: hidden; background: rgba(255, 255, 255, 0.9); border-radius: 1rem; padding: 1.5rem; box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.2); backdrop-filter: blur(15px); color: #111; display: flex; flex-direction: column;">
              <?php if (has_post_thumbnail()) : ?>
                <div class="service-thumb" style="margin-bottom: 1rem; border-radius: 0.5rem; overflow: hidden; flex-shrink: 0;"><?php the_post_thumbnail('mm-card', array('style' => 'width: 100%; height: 12.5rem; object-fit: cover;')); ?></div>
              <?php endif; ?>
              <?php
              $post_id = get_the_ID();
              $titulo = get_the_title() ?: $titulos_fallback[$post_id];
              $descripcion = get_the_excerpt() ?: $descripciones_fallback[$post_id];
              ?>
              <h3 class="service-title embossed-text" style="margin-bottom: 0.75rem; font-size: 1.2rem;"><?php echo esc_html($titulo); ?></h3>
              <div class="service-excerpt" style="font-size: 0.9rem; line-height: 1.4; flex-grow: 1; overflow: hidden;"><?php echo esc_html($descripcion); ?></div>
              <div style="margin-top: 1rem; font-size: 0.8rem; color: #666; font-style: italic;">Clic para más información</div>
            </div>

            <!-- CARA TRASERA -->
            <div class="flip-card-back" style="position: absolute; width: 100%; height: 100%; backface-visibility: hidden; background: linear-gradient(135deg, #AA3000, #cc4000); border-radius: 1rem; padding: 1.5rem; box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.2); backdrop-filter: blur(15px); color: white; transform: rotateY(180deg); display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
              <h3 style="margin-bottom: 2rem; font-size: 1.4rem; color: white;"><?php echo esc_html($titulo); ?></h3>

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
      echo '<p>' . __('No hay servicios con precios y duraciones configurados.', 'masajista-masculino') . '</p>';
    }
    ?>
  </div>
</section>