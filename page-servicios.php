<?php

/**
 * Template Name: Servicios
 * Página de servicios con nueva estructura CSS ITCSS
 */

get_header();
?>

<main id="primary" class="page-servicios" role="main">
  <!-- Hero Section -->
  <section class="hero hero--fullscreen hero--servicios">
    <div class="hero__bg" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/ns-servicios.webp');"></div>
    <div class="hero__overlay"></div>

    <div class="hero__content">
      <div class="container">
        <div class="hero__text text-center">
          <h1 class="hero__title text-white">
            <?php _e('Nuestros Servicios', 'masajista-masculino'); ?>
          </h1>
          <p class="hero__subtitle text-white opacity-90">
            <?php _e('Experiencias de bienestar personalizadas', 'masajista-masculino'); ?>
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Servicios Grid -->
  <section class="servicios-grid py-xl">
    <div class="container">

      <?php
      // Obtener servicios de la base de datos
      $servicios = get_servicios_data();

      if (!empty($servicios)) :
      ?>
        <div class="grid grid--auto-fit gap-lg">
          <?php foreach ($servicios as $servicio) : ?>
            <article class="card card--flip card--servicios"
              data-servicio-id="<?php echo $servicio['id']; ?>">

              <!-- Contenedor del flip -->
              <div class="card__flip-inner">

                <!-- Cara frontal -->
                <div class="card__flip-front neo-surface">
                  <div class="card__image">
                    <?php if ($servicio['imagen']) : ?>
                      <img src="<?php echo esc_url($servicio['imagen']); ?>"
                        alt="<?php echo esc_attr($servicio['nombre']); ?>"
                        class="img-cover">
                    <?php else : ?>
                      <div class="card__image-placeholder flex flex--center">
                        <i class="fa fa-spa text-primary text-2xl"></i>
                      </div>
                    <?php endif; ?>

                    <!-- Badge de precio -->
                    <div class="card__badge">
                      €<?php echo esc_html($servicio['precio']); ?>
                    </div>
                  </div>

                  <div class="card__content p-lg">
                    <h3 class="card__title text-primary mb-sm">
                      <?php echo esc_html($servicio['nombre']); ?>
                    </h3>
                    <p class="card__description text-body mb-md">
                      <?php echo esc_html($servicio['descripcion']); ?>
                    </p>
                    <div class="card__meta flex flex--between flex--center">
                      <span class="text-sm text-muted">
                        <i class="fa fa-clock-o mr-xs"></i>
                        <?php echo esc_html($servicio['duracion']); ?>
                      </span>
                      <span class="card__flip-hint text-xs text-primary">
                        <?php _e('Clic para más detalles', 'masajista-masculino'); ?>
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Cara trasera -->
                <div class="card__flip-back neo-primary text-white">
                  <div class="card__content p-lg flex flex--column h-full">
                    <h3 class="card__title mb-md">
                      <?php echo esc_html($servicio['nombre']); ?>
                    </h3>

                    <div class="card__price-highlight text-center mb-md">
                      <span class="text-3xl font-bold">€<?php echo esc_html($servicio['precio']); ?></span>
                      <br>
                      <span class="text-sm opacity-90"><?php echo esc_html($servicio['duracion']); ?></span>
                    </div>

                    <p class="card__description-long mb-md flex-grow">
                      <?php echo esc_html($servicio['descripcion']); ?>
                    </p>

                    <div class="card__actions">
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
        <div class="text-center py-xl">
          <div class="empty-state">
            <i class="fa fa-spa text-muted text-4xl mb-md"></i>
            <h3 class="text-muted mb-sm">
              <?php _e('No hay servicios disponibles', 'masajista-masculino'); ?>
            </h3>
            <p class="text-muted">
              <?php _e('Pronto tendremos nuevos servicios para ti.', 'masajista-masculino'); ?>
            </p>
          </div>
        </div>
      <?php endif; ?>

    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta-section neo-surface py-xl">
    <div class="container text-center">
      <h2 class="text-primary mb-md">
        <?php _e('¿Listo para relajarte?', 'masajista-masculino'); ?>
      </h2>
      <p class="text-body mb-lg">
        <?php _e('Reserva tu sesión de bienestar personalizada', 'masajista-masculino'); ?>
      </p>
      <a href="<?php echo esc_url(home_url('/reservas/')); ?>"
        class="btn btn--primary btn--lg">
        <?php _e('Reservar Cita', 'masajista-masculino'); ?>
      </a>
    </div>
  </section>

</main>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Manejar flip de tarjetas con nueva estructura
    const flipCards = document.querySelectorAll('.card--flip');

    flipCards.forEach(card => {
      card.addEventListener('click', function(e) {
        // No hacer flip si se hace clic en un botón
        if (!e.target.closest('.btn')) {
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

<?php get_footer(); ?>