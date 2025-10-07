<?php

/**
 * Template Name: Contactos
 * Página de contacto con nueva estructura CSS ITCSS
 */

if (!defined('ABSPATH')) exit;

get_header();
?>

<main id="primary" class="page-contactos" role="main">
  <!-- Hero Section con formulario -->
  <section class="hero hero--fullscreen hero--contactos">
    <div class="hero__bg" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/contact.webp');"></div>
    <div class="hero__overlay"></div>

    <div class="hero__content">
      <div class="container">
        <div class="grid grid--2-cols gap-xl align-center">

          <!-- Información de contacto -->
          <div class="contactos__info">
            <div class="text-white">
              <h1 class="hero__title mb-lg">
                <?php _e('Contáctanos', 'masajista-masculino'); ?>
              </h1>
              <p class="hero__subtitle mb-xl opacity-90">
                <?php _e('Estamos aquí para ayudarte a encontrar tu momento de bienestar perfecto', 'masajista-masculino'); ?>
              </p>

              <!-- Información de contacto -->
              <div class="contact-info space-y-md">
                <div class="contact-info__item flex flex--start gap-md">
                  <div class="contact-info__icon neo-surface p-sm rounded-full">
                    <i class="fa fa-phone text-primary"></i>
                  </div>
                  <div>
                    <h3 class="text-white mb-xs"><?php _e('Teléfono', 'masajista-masculino'); ?></h3>
                    <p class="text-white opacity-90">+34 666 777 888</p>
                  </div>
                </div>

                <div class="contact-info__item flex flex--start gap-md">
                  <div class="contact-info__icon neo-surface p-sm rounded-full">
                    <i class="fa fa-envelope text-primary"></i>
                  </div>
                  <div>
                    <h3 class="text-white mb-xs"><?php _e('Email', 'masajista-masculino'); ?></h3>
                    <p class="text-white opacity-90">info@masajes.com</p>
                  </div>
                </div>

                <div class="contact-info__item flex flex--start gap-md">
                  <div class="contact-info__icon neo-surface p-sm rounded-full">
                    <i class="fa fa-map-marker text-primary"></i>
                  </div>
                  <div>
                    <h3 class="text-white mb-xs"><?php _e('Ubicación', 'masajista-masculino'); ?></h3>
                    <p class="text-white opacity-90">Barcelona, España</p>
                  </div>
                </div>

                <div class="contact-info__item flex flex--start gap-md">
                  <div class="contact-info__icon neo-surface p-sm rounded-full">
                    <i class="fa fa-clock-o text-primary"></i>
                  </div>
                  <div>
                    <h3 class="text-white mb-xs"><?php _e('Horario', 'masajista-masculino'); ?></h3>
                    <p class="text-white opacity-90">Lun-Dom: 10:00-22:00</p>
                  </div>
                </div>
              </div>

              <!-- Botones de acción rápida -->
              <div class="contact-actions flex gap-md mt-xl">
                <a href="https://wa.me/34666777888"
                  class="btn btn--primary btn--lg flex flex--center gap-sm"
                  target="_blank">
                  <i class="fa fa-whatsapp"></i>
                  <?php _e('WhatsApp', 'masajista-masculino'); ?>
                </a>
                <a href="mailto:info@masajes.com"
                  class="btn btn--white btn--lg flex flex--center gap-sm">
                  <i class="fa fa-envelope"></i>
                  <?php _e('Email', 'masajista-masculino'); ?>
                </a>
              </div>
            </div>
          </div>

          <!-- Formulario de contacto -->
          <div class="contactos__form">
            <div class="form-container neo-surface p-xl rounded-lg">
              <h2 class="text-primary mb-lg text-center">
                <?php _e('Envíanos un mensaje', 'masajista-masculino'); ?>
              </h2>

              <form id="contacto-form" class="form space-y-md" method="post" action="">
                <?php wp_nonce_field('contacto_form', 'contacto_nonce'); ?>

                <div class="form-group">
                  <label for="nombre" class="form-label">
                    <?php _e('Nombre completo', 'masajista-masculino'); ?>
                  </label>
                  <input type="text"
                    id="nombre"
                    name="nombre"
                    class="form-input neo-input"
                    required>
                </div>

                <div class="form-group">
                  <label for="email" class="form-label">
                    <?php _e('Email', 'masajista-masculino'); ?>
                  </label>
                  <input type="email"
                    id="email"
                    name="email"
                    class="form-input neo-input"
                    required>
                </div>

                <div class="form-group">
                  <label for="telefono" class="form-label">
                    <?php _e('Teléfono', 'masajista-masculino'); ?>
                  </label>
                  <input type="tel"
                    id="telefono"
                    name="telefono"
                    class="form-input neo-input">
                </div>

                <div class="form-group">
                  <label for="asunto" class="form-label">
                    <?php _e('Asunto', 'masajista-masculino'); ?>
                  </label>
                  <select id="asunto"
                    name="asunto"
                    class="form-select neo-input"
                    required>
                    <option value=""><?php _e('Selecciona un asunto', 'masajista-masculino'); ?></option>
                    <option value="informacion"><?php _e('Información general', 'masajista-masculino'); ?></option>
                    <option value="reserva"><?php _e('Reserva de cita', 'masajista-masculino'); ?></option>
                    <option value="productos"><?php _e('Consulta sobre productos', 'masajista-masculino'); ?></option>
                    <option value="otro"><?php _e('Otro', 'masajista-masculino'); ?></option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="mensaje" class="form-label">
                    <?php _e('Mensaje', 'masajista-masculino'); ?>
                  </label>
                  <textarea id="mensaje"
                    name="mensaje"
                    class="form-textarea neo-input"
                    rows="5"
                    required
                    placeholder="<?php esc_attr_e('Cuéntanos cómo podemos ayudarte...', 'masajista-masculino'); ?>"></textarea>
                </div>

                <div class="form-group">
                  <label class="form-checkbox">
                    <input type="checkbox"
                      name="acepto_privacidad"
                      required>
                    <span class="form-checkbox__mark"></span>
                    <span class="form-checkbox__text">
                      <?php _e('Acepto la política de privacidad', 'masajista-masculino'); ?>
                    </span>
                  </label>
                </div>

                <button type="submit"
                  class="btn btn--primary btn--block btn--lg">
                  <i class="fa fa-paper-plane mr-sm"></i>
                  <?php _e('Enviar mensaje', 'masajista-masculino'); ?>
                </button>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contacto-form');

    if (form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Aquí puedes agregar la lógica de envío del formulario
        // Por ejemplo, envío via AJAX o integración con servicio de email

        // Simulación de envío exitoso
        alert('<?php _e("Mensaje enviado correctamente. Te contactaremos pronto.", "masajista-masculino"); ?>');

        // Resetear formulario
        form.reset();
      });
    }
  });
</script>

<?php get_footer(); ?>