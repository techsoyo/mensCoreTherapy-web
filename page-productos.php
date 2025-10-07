<?php

/**
 * Template Name: Productos
 * Página de productos con nueva estructura CSS ITCSS
 */

get_header();
?>

<main id="primary" class="page-productos" role="main">
  <!-- Hero Section -->
  <section class="hero hero--fullscreen hero--productos">
    <div class="hero__bg" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/ns-productos.webp');"></div>
    <div class="hero__overlay"></div>

    <!-- Contenedor scrolleable -->
    <div class="hero__scroll-container">
      <div class="container">

        <!-- Título principal -->
        <div class="hero__header text-center mb-xl">
          <h1 class="hero__title text-white">
            <?php _e('Nuestros Productos', 'masajista-masculino'); ?>
          </h1>
          <p class="hero__subtitle text-white opacity-90">
            <?php _e('Productos premium para tu bienestar', 'masajista-masculino'); ?>
          </p>
        </div>

        <!-- Grid de productos -->
        <div class="productos-grid grid grid--auto-fit gap-lg">

          <?php
          // Obtener productos de la base de datos
          $productos = get_productos_data();

          if (!empty($productos)) :
            foreach ($productos as $producto) :
          ?>
              <article class="card card--flip card--productos"
                data-producto-id="<?php echo $producto['id']; ?>">

                <!-- Contenedor del flip -->
                <div class="card__flip-inner">

                  <!-- Cara frontal -->
                  <div class="card__flip-front neo-surface">
                    <div class="card__image">
                      <?php if ($producto['imagen']) : ?>
                        <img src="<?php echo esc_url($producto['imagen']); ?>"
                          alt="<?php echo esc_attr($producto['nombre']); ?>"
                          class="img-cover"
                          onerror="this.src='<?php echo get_template_directory_uri(); ?>/assets/images/logo.webp'">
                      <?php else : ?>
                        <div class="card__image-placeholder flex flex--center">
                          <i class="fa fa-<?php echo esc_attr($producto['icono'] ?: 'star'); ?> text-primary text-2xl"></i>
                        </div>
                      <?php endif; ?>

                      <!-- Badge de precio -->
                      <div class="card__badge">
                        €<?php echo esc_html($producto['precio']); ?>
                      </div>
                    </div>

                    <div class="card__content p-lg">
                      <h3 class="card__title text-primary mb-sm">
                        <?php echo esc_html($producto['nombre']); ?>
                      </h3>
                      <p class="card__description text-body mb-md">
                        <?php echo esc_html($producto['descripcion']); ?>
                      </p>
                      <div class="card__footer">
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
                        <?php echo esc_html($producto['nombre']); ?>
                      </h3>

                      <div class="card__price-highlight text-center mb-md">
                        <span class="text-3xl font-bold">€<?php echo esc_html($producto['precio']); ?></span>
                      </div>

                      <p class="card__description-long mb-md">
                        <?php echo esc_html($producto['descripcion']); ?>
                      </p>

                      <?php if (!empty($producto['beneficios'])) : ?>
                        <ul class="card__benefits mb-md">
                          <?php foreach ($producto['beneficios'] as $beneficio) : ?>
                            <li class="card__benefit">
                              <i class="fa fa-check mr-xs"></i>
                              <?php echo esc_html($beneficio); ?>
                            </li>
                          <?php endforeach; ?>
                        </ul>
                      <?php endif; ?>

                      <div class="card__actions mt-auto">
                        <button class="btn btn--white btn--block mb-sm"
                          onclick="comprarProducto(<?php echo $producto['id']; ?>)">
                          <?php _e('Comprar Ahora', 'masajista-masculino'); ?>
                        </button>
                        <span class="card__flip-hint text-xs opacity-70">
                          <?php _e('Clic para volver', 'masajista-masculino'); ?>
                        </span>
                      </div>
                    </div>
                  </div>

                </div>
              </article>

            <?php
            endforeach;
          else :
            ?>
            <div class="empty-state text-center py-xl" style="grid-column: 1 / -1;">
              <i class="fa fa-shopping-bag text-white text-4xl mb-md opacity-70"></i>
              <h3 class="text-white mb-sm">
                <?php _e('No hay productos disponibles', 'masajista-masculino'); ?>
              </h3>
              <p class="text-white opacity-70">
                <?php _e('Pronto tendremos nuevos productos para ti.', 'masajista-masculino'); ?>
              </p>
            </div>
          <?php endif; ?>

        </div>
      </div>
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

  // Función para manejar compra
  function comprarProducto(id) {
    // Integración con sistema de e-commerce
    alert('Funcionalidad de compra para producto ID: ' + id + '\n\nPróximamente disponible.');

    // Ejemplo de integración con WhatsApp
    // const mensaje = encodeURIComponent(`Hola, estoy interesado en el producto ID: ${id}`);
    // window.open(`https://wa.me/TUNUMERO?text=${mensaje}`, '_blank');
  }
</script>

<?php get_footer(); ?>