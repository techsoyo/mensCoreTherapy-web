<?php
/**
 * Template Name: Archive Servicio
 * Template for displaying the servicio custom post type archive
 */

get_header();
?>

<section class="hero-section" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/torso.jpg'); background-size: cover; background-position: center; background-attachment: fixed; min-height: 70vh; position: relative; color: white;">
  <div class="hero-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; "></div>

  <div class="cards-container" style="position: relative; z-index: 1; padding: 5rem 1.25rem; max-width: 90rem; margin: 0 auto;">
    <h1 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; text-shadow: 0.125rem 0.125rem 0.5rem rgba(0, 0, 0, 0.8);"><?php _e('Servicios', 'masajista-masculino'); ?></h1>

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
                      
                      <?php 
                      // Leer los campos personalizados
                      $precio_servicio = get_post_meta(get_the_ID(), '_mm_precio', true);
                      $duracion_servicio = get_post_meta(get_the_ID(), '_mm_duracion', true);
                      ?>
                      
                      <?php if ($precio_servicio) : ?>
                        <div style="margin-bottom: 2.5rem;">
                          <div style="font-size: 1rem; opacity: 0.9; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em;">Precio</div>
                          <div style="font-size: 3rem; font-weight: 100; line-height: 1;"><?php echo esc_html($precio_servicio); ?></div>
                        </div>
                      <?php endif; ?>
                      
                      <?php if ($duracion_servicio) : ?>
                        <div style="margin-bottom: 2.5rem;">
                          <div style="font-size: 1rem; opacity: 0.9; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em;">Duración</div>
                          <div style="font-size: 1.8rem; font-weight: 100; line-height: 1;"><?php echo esc_html($duracion_servicio); ?></div>
                        </div>
                      <?php endif; ?>
                      
                      <?php if (!$precio_servicio && !$duracion_servicio) : ?>
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
            echo '<p style="text-align: center; font-size: 1.2rem;">'.__('No hay servicios publicados.', 'masajista-masculino').'</p>';
        }
    } else {
        echo '<p style="text-align: center; font-size: 1.2rem;">'.__('Registra el CPT "servicio" para mostrar un grid automático de servicios.', 'masajista-masculino').'</p>';
    }
    ?>
  </div>
</section>

<style>
.flip-card.flipped .flip-card-inner {
  transform: rotateY(180deg);
}

.flip-card:hover {
  transform: scale(1.02);
  transition: transform 0.3s ease;
}

.flip-card-back a:hover {
  background: rgba(255, 255, 255, 0.3) !important;
  transform: translateY(-0.125rem);
}

@media (max-width: 48rem) {
  .flip-card {
    height: 22rem !important;
  }
  
  .flip-card-back {
    padding: 1rem !important;
  }
  
  .flip-card-back h3 {
    font-size: 1.2rem !important;
  }
  
  .flip-card-back div[style*="font-size: 2.5rem"] {
    font-size: 2rem !important;
  }
}
</style>

<?php get_footer(); ?>