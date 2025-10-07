<?php

/**
 * Template Name: Reservas
 * Página de reservas con nueva estructura CSS ITCSS
 */

get_header();
?>

<main id="primary" class="page-reservas" role="main">
  <!-- Hero Section con formulario de reservas -->
  <section class="hero hero--fullscreen hero--reservas">
    <div class="hero__bg" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/reservas.webp');"></div>
    <div class="hero__overlay"></div>

    <div class="hero__content">
      <div class="container">
        <div class="reservas__wrapper max-w-4xl mx-auto">

          <!-- Título principal -->
          <div class="text-center mb-xl">
            <h1 class="hero__title text-white mb-md">
              <?php _e('Reserva tu Cita', 'masajista-masculino'); ?>
            </h1>
            <p class="hero__subtitle text-white opacity-90">
              <?php _e('Selecciona el servicio y horario que mejor se adapte a ti', 'masajista-masculino'); ?>
            </p>
          </div>

          <!-- Formulario de reservas -->
          <div class="reservas__form">
            <div class="form-container neo-surface p-xl rounded-lg">

              <form id="reserva-form" class="form" method="post" action="">
                <?php wp_nonce_field('reserva_form', 'reserva_nonce'); ?>

                <!-- Información personal -->
                <div class="form-section mb-xl">
                  <h3 class="form-section__title text-primary mb-lg">
                    <i class="fa fa-user mr-sm"></i>
                    <?php _e('Información Personal', 'masajista-masculino'); ?>
                  </h3>

                  <div class="grid grid--2-cols gap-md">
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
                      <label for="telefono" class="form-label">
                        <?php _e('Teléfono', 'masajista-masculino'); ?>
                      </label>
                      <input type="tel"
                        id="telefono"
                        name="telefono"
                        class="form-input neo-input"
                        required>
                    </div>
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
                </div>

                <!-- Selección de servicio -->
                <div class="form-section mb-xl">
                  <h3 class="form-section__title text-primary mb-lg">
                    <i class="fa fa-spa mr-sm"></i>
                    <?php _e('Selecciona tu Servicio', 'masajista-masculino'); ?>
                  </h3>

                  <div class="servicios-selector">
                    <?php
                    // Obtener servicios disponibles
                    $servicios = get_servicios_data();

                    if (!empty($servicios)) :
                      foreach ($servicios as $servicio) :
                    ?>
                        <label class="servicio-option neo-surface p-md rounded-lg cursor-pointer">
                          <input type="radio"
                            name="servicio_id"
                            value="<?php echo $servicio['id']; ?>"
                            class="servicio-radio visually-hidden"
                            data-precio="<?php echo $servicio['precio']; ?>"
                            data-duracion="<?php echo $servicio['duracion']; ?>">

                          <div class="servicio-option__content">
                            <div class="flex flex--between flex--center">
                              <div>
                                <h4 class="servicio-option__title text-primary mb-xs">
                                  <?php echo esc_html($servicio['nombre']); ?>
                                </h4>
                                <p class="servicio-option__description text-body text-sm">
                                  <?php echo esc_html($servicio['descripcion']); ?>
                                </p>
                              </div>
                              <div class="servicio-option__meta text-right">
                                <div class="servicio-option__price text-primary font-bold">
                                  €<?php echo esc_html($servicio['precio']); ?>
                                </div>
                                <div class="servicio-option__duration text-muted text-sm">
                                  <?php echo esc_html($servicio['duracion']); ?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </label>
                      <?php
                      endforeach;
                    else :
                      ?>
                      <p class="text-muted text-center py-lg">
                        <?php _e('No hay servicios disponibles en este momento.', 'masajista-masculino'); ?>
                      </p>
                    <?php endif; ?>
                  </div>
                </div>

                <!-- Fecha y hora -->
                <div class="form-section mb-xl">
                  <h3 class="form-section__title text-primary mb-lg">
                    <i class="fa fa-calendar mr-sm"></i>
                    <?php _e('Fecha y Hora', 'masajista-masculino'); ?>
                  </h3>

                  <div class="grid grid--2-cols gap-md">
                    <div class="form-group">
                      <label for="fecha" class="form-label">
                        <?php _e('Fecha preferida', 'masajista-masculino'); ?>
                      </label>
                      <input type="date"
                        id="fecha"
                        name="fecha"
                        class="form-input neo-input"
                        min="<?php echo date('Y-m-d'); ?>"
                        required>
                    </div>

                    <div class="form-group">
                      <label for="hora" class="form-label">
                        <?php _e('Hora preferida', 'masajista-masculino'); ?>
                      </label>
                      <select id="hora"
                        name="hora"
                        class="form-select neo-input"
                        required>
                        <option value=""><?php _e('Selecciona una hora', 'masajista-masculino'); ?></option>
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="12:00">12:00</option>
                        <option value="13:00">13:00</option>
                        <option value="14:00">14:00</option>
                        <option value="15:00">15:00</option>
                        <option value="16:00">16:00</option>
                        <option value="17:00">17:00</option>
                        <option value="18:00">18:00</option>
                        <option value="19:00">19:00</option>
                        <option value="20:00">20:00</option>
                        <option value="21:00">21:00</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Información adicional -->
                <div class="form-section mb-xl">
                  <h3 class="form-section__title text-primary mb-lg">
                    <i class="fa fa-comment mr-sm"></i>
                    <?php _e('Información Adicional', 'masajista-masculino'); ?>
                  </h3>

                  <div class="form-group">
                    <label for="comentarios" class="form-label">
                      <?php _e('Comentarios o solicitudes especiales', 'masajista-masculino'); ?>
                    </label>
                    <textarea id="comentarios"
                      name="comentarios"
                      class="form-textarea neo-input"
                      rows="4"
                      placeholder="<?php esc_attr_e('Cuéntanos si tienes alguna preferencia especial, condición médica relevante, o cualquier otra información que debamos conocer...', 'masajista-masculino'); ?>"></textarea>
                  </div>
                </div>

                <!-- Resumen de reserva -->
                <div id="resumen-reserva" class="form-section mb-xl" style="display: none;">
                  <h3 class="form-section__title text-primary mb-lg">
                    <i class="fa fa-check-circle mr-sm"></i>
                    <?php _e('Resumen de tu Reserva', 'masajista-masculino'); ?>
                  </h3>

                  <div class="resumen-card neo-surface p-lg rounded-lg">
                    <div id="resumen-contenido"></div>
                  </div>
                </div>

                <!-- Términos y condiciones -->
                <div class="form-group mb-lg">
                  <label class="form-checkbox">
                    <input type="checkbox"
                      name="acepto_terminos"
                      required>
                    <span class="form-checkbox__mark"></span>
                    <span class="form-checkbox__text">
                      <?php _e('Acepto los términos y condiciones y la política de cancelación', 'masajista-masculino'); ?>
                    </span>
                  </label>
                </div>

                <!-- Botón de envío -->
                <button type="submit"
                  class="btn btn--primary btn--block btn--lg">
                  <i class="fa fa-calendar-check-o mr-sm"></i>
                  <?php _e('Confirmar Reserva', 'masajista-masculino'); ?>
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
    const form = document.getElementById('reserva-form');
    const servicioRadios = document.querySelectorAll('.servicio-radio');
    const resumenSection = document.getElementById('resumen-reserva');
    const resumenContenido = document.getElementById('resumen-contenido');

    // Manejar selección de servicio
    servicioRadios.forEach(radio => {
      radio.addEventListener('change', function() {
        // Actualizar estilos visuales
        servicioRadios.forEach(r => {
          r.closest('.servicio-option').classList.remove('is-selected');
        });
        this.closest('.servicio-option').classList.add('is-selected');

        // Actualizar resumen
        actualizarResumen();
      });
    });

    // Actualizar resumen cuando cambien otros campos
    ['nombre', 'fecha', 'hora'].forEach(fieldId => {
      const field = document.getElementById(fieldId);
      if (field) {
        field.addEventListener('change', actualizarResumen);
      }
    });

    function actualizarResumen() {
      const servicioSeleccionado = document.querySelector('.servicio-radio:checked');
      const nombre = document.getElementById('nombre').value;
      const fecha = document.getElementById('fecha').value;
      const hora = document.getElementById('hora').value;

      if (servicioSeleccionado && nombre && fecha && hora) {
        const servicioOption = servicioSeleccionado.closest('.servicio-option');
        const servicioNombre = servicioOption.querySelector('.servicio-option__title').textContent;
        const servicioPrecio = servicioSeleccionado.dataset.precio;
        const servicioDuracion = servicioSeleccionado.dataset.duracion;

        const fechaFormateada = new Date(fecha).toLocaleDateString('es-ES', {
          weekday: 'long',
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });

        resumenContenido.innerHTML = `
        <div class="space-y-sm">
          <div class="flex flex--between">
            <span class="font-medium"><?php _e('Cliente:', 'masajista-masculino'); ?></span>
            <span>${nombre}</span>
          </div>
          <div class="flex flex--between">
            <span class="font-medium"><?php _e('Servicio:', 'masajista-masculino'); ?></span>
            <span>${servicioNombre}</span>
          </div>
          <div class="flex flex--between">
            <span class="font-medium"><?php _e('Fecha:', 'masajista-masculino'); ?></span>
            <span>${fechaFormateada}</span>
          </div>
          <div class="flex flex--between">
            <span class="font-medium"><?php _e('Hora:', 'masajista-masculino'); ?></span>
            <span>${hora}</span>
          </div>
          <div class="flex flex--between">
            <span class="font-medium"><?php _e('Duración:', 'masajista-masculino'); ?></span>
            <span>${servicioDuracion}</span>
          </div>
          <hr class="my-md">
          <div class="flex flex--between text-lg font-bold text-primary">
            <span><?php _e('Total:', 'masajista-masculino'); ?></span>
            <span>€${servicioPrecio}</span>
          </div>
        </div>
      `;

        resumenSection.style.display = 'block';
      } else {
        resumenSection.style.display = 'none';
      }
    }

    // Manejar envío del formulario
    if (form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Aquí puedes agregar la lógica de envío de la reserva
        // Por ejemplo, envío via AJAX o integración con sistema de reservas

        // Simulación de reserva exitosa
        alert('<?php _e("Reserva confirmada. Te contactaremos pronto para confirmar los detalles.", "masajista-masculino"); ?>');

        // Resetear formulario
        form.reset();
        resumenSection.style.display = 'none';
        servicioRadios.forEach(r => {
          r.closest('.servicio-option').classList.remove('is-selected');
        });
      });
    }
  });
</script>

<?php get_footer(); ?>