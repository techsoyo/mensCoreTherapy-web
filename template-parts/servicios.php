<?php if (!defined('ABSPATH')) exit; ?>

<!-- Hero Section con nueva estructura ITCSS -->
<section class="hero hero--servicios">
  <div class="hero__bg" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/torso.jpg');"></div>
  <div class="hero__overlay"></div>

  <div class="hero__content">
    <div class="container">

      <!-- Título principal -->
      <div class="text-center mb-xl">
        <h1 class="hero__title text-white">
          <?php _e('Servicios', 'masajista-masculino'); ?>
        </h1>
      </div>

      <?php
      // Obtener servicios de la base de datos usando la función actualizada
      $servicios = get_servicios_data();

      if (!empty($servicios)) :
      ?>
        <!-- Grid de servicios con nueva estructura -->
        <div class="services-grid grid grid--auto-fit gap-lg">

          <?php foreach ($servicios as $servicio) : ?>
            <article class="card card--flip card--servicios"
              data-servicio-id="<?php echo $servicio['id']; ?>">

              <!-- Contenedor del flip -->
              <div class="card__flip-inner">

                <!-- Cara frontal -->
                <div class="card__flip-front neo-surface">

                  <!-- Imagen del servicio -->
                  <?php if ($servicio['imagen']) : ?>
                    <div class="card__image">
                      <img src="<?php echo esc_url($servicio['imagen']); ?>"
                        alt="<?php echo esc_attr($servicio['nombre']); ?>"
                        class="img-cover">
                    </div>
                  <?php else : ?>
                    <div class="card__image-placeholder flex flex--center">
                      <i class="fa fa-spa text-primary text-2xl"></i>
                    </div>
                  <?php endif; ?>

                  <!-- Contenido de la tarjeta -->
                  <div class="card__content p-lg">
                    <h3 class="card__title text-primary mb-sm">
                      <?php echo esc_html($servicio['nombre']); ?>
                    </h3>

                    <div class="card__description text-body mb-md">
                      <?php echo esc_html($servicio['descripcion']); ?>
                    </div>

                    <!-- Meta información -->
                    <div class="card__meta flex flex--between flex--center">
                      <?php if ($servicio['duracion']) : ?>
                        <span class="text-sm text-muted">
                          <i class="fa fa-clock-o mr-xs"></i>
                          <?php echo esc_html($servicio['duracion']); ?>
                        </span>
                      <?php endif; ?>

                      <span class="card__flip-hint text-xs text-primary">
                        <?php _e('Clic para más información', 'masajista-masculino'); ?>
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Cara trasera -->
                <div class="card__flip-back neo-primary text-white">
                  <div class="card__content p-lg flex flex--column h-full text-center">

                    <h3 class="card__title mb-lg">
                      <?php echo esc_html($servicio['nombre']); ?>
                    </h3>

                    <!-- Precio -->
                    <?php if ($servicio['precio']) : ?>
                      <div class="card__price-section mb-lg">
                        <div class="text-sm opacity-90 mb-xs uppercase tracking-wide">
                          <?php _e('Precio', 'masajista-masculino'); ?>
                        </div>
                        <div class="card__price text-4xl font-light">
                          €<?php echo esc_html($servicio['precio']); ?>
                        </div>
                      </div>
                    <?php endif; ?>

                    <!-- Duración -->
                    <?php if ($servicio['duracion']) : ?>
                      <div class="card__duration-section mb-lg">
                        <div class="text-sm opacity-90 mb-xs uppercase tracking-wide">
                          <?php _e('Duración', 'masajista-masculino'); ?>
                        </div>
                        <div class="card__duration text-xl font-light">
                          <?php echo esc_html($servicio['duracion']); ?>
                        </div>
                      </div>
                    <?php endif; ?>

                    <!-- Mensaje si no hay precio/duración -->
                    <?php if (!$servicio['precio'] && !$servicio['duracion']) : ?>
                      <div class="card__no-info mb-lg opacity-80 italic">
                        <div><?php _e('Información', 'masajista-masculino'); ?></div>
                        <div><?php _e('disponible próximamente', 'masajista-masculino'); ?></div>
                      </div>
                    <?php endif; ?>

                    <!-- Botón de acción -->
                    <div class="card__actions mt-auto">
                      <a href="<?php echo esc_url(home_url('/reservas/')); ?>"
                        class="btn btn--white btn--block mb-sm">
                        <?php _e('Reservar Ahora', 'masajista-masculino'); ?>
                      </a>
                      <span class="card__flip-hint text-xs opacity-70">
                        <?php _e('Clic para volver', 'masajista-masculino'); ?>
                      </span>
                    </div>

                  </div>
                </div>

              </div>
            </article>
          <?php endforeach; ?>

        </div>

      <?php else : ?>
        <!-- Estado vacío -->
        <div class="empty-state text-center py-xl">
          <i class="fa fa-spa text-white text-4xl mb-md opacity-70"></i>
          <h3 class="text-white mb-sm">
            <?php _e('No hay servicios disponibles', 'masajista-masculino'); ?>
          </h3>
          <p class="text-white opacity-70">
            <?php _e('Pronto tendremos nuevos servicios para ti.', 'masajista-masculino'); ?>
          </p>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Manejar flip de tarjetas con nueva estructura
    const flipCards = document.querySelectorAll('.card--flip');

    flipCards.forEach(card => {
      card.addEventListener('click', function(e) {
        // No hacer flip si se hace clic en un botón o enlace
        if (!e.target.closest('.btn') && !e.target.closest('a')) {
          this.classList.toggle('is-flipped');
        }
      });

      // Accesibilidad con teclado
      card.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          this.classList.toggle('is-flipped');
        }
      });

      // Hacer las tarjetas focusables
      card.setAttribute('tabindex', '0');
    });
  });
</script>