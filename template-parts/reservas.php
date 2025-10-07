<?php if (!defined('ABSPATH')) exit; ?>

<section class="reservas-section">
  <div class="container">

    <?php
    // Procesamiento del formulario de reservas
    $errors = [];
    $sent = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mm_reserva_submit'])) {
      if (!mm_verify_nonce('mm_reserva')) {
        $errors[] = __('Verificación de seguridad fallida. Recarga la página.', 'masajista-masculino');
      } else {
        $name  = sanitize_text_field($_POST['name'] ?? '');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $date  = sanitize_text_field($_POST['date'] ?? '');
        $time  = sanitize_text_field($_POST['time'] ?? '');
        $serv  = sanitize_text_field($_POST['service'] ?? '');
        $msg   = wp_kses_post($_POST['notes'] ?? '');

        // Validaciones
        if ($name === '')  $errors[] = __('El nombre es obligatorio.', 'masajista-masculino');
        if ($phone === '') $errors[] = __('El teléfono es obligatorio.', 'masajista-masculino');
        if ($date === '')  $errors[] = __('La fecha es obligatoria.', 'masajista-masculino');
        if ($time === '')  $errors[] = __('La hora es obligatoria.', 'masajista-masculino');

        // Validación fecha/hora
        if ($date && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) $errors[] = __('Fecha inválida.', 'masajista-masculino');
        if ($time && !preg_match('/^\d{2}:\d{2}$/', $time)) $errors[] = __('Hora inválida.', 'masajista-masculino');

        if (!$errors) {
          $to = get_option('admin_email');
          $subject = sprintf(__('Nueva reserva de %s', 'masajista-masculino'), $name);
          $body = "Nombre: {$name}\nTeléfono: {$phone}\nFecha: {$date}\nHora: {$time}\nServicio: {$serv}\n\nNotas:\n{$msg}";
          $headers = ['Content-Type: text/plain; charset=UTF-8'];

          if (wp_mail($to, $subject, $body, $headers)) {
            $sent = true;
          } else {
            $errors[] = __('No se pudo enviar la reserva. Inténtalo más tarde.', 'masajista-masculino');
          }
        }
      }
    }

    // Mostrar mensajes de estado
    if ($sent) {
      echo '<div class="alert alert--success mb-xl" role="status" aria-live="polite">';
      echo '<i class="fa fa-check-circle mr-sm"></i>';
      echo esc_html__('Reserva enviada correctamente. Te contactaremos para confirmar.', 'masajista-masculino');
      echo '</div>';
    } elseif ($errors) {
      echo '<div class="alert alert--error mb-xl" role="alert" aria-live="assertive">';
      echo '<i class="fa fa-exclamation-triangle mr-sm"></i>';
      echo '<ul class="alert__list">';
      foreach ($errors as $e) echo '<li>' . esc_html($e) . '</li>';
      echo '</ul></div>';
    }
    ?>

    <!-- Título principal -->
    <div class="text-center mb-xl">
      <h2 class="section-title text-primary">
        <?php _e('Reservas', 'masajista-masculino'); ?>
      </h2>
      <p class="section-subtitle text-body">
        <?php _e('Completa el formulario para reservar tu cita', 'masajista-masculino'); ?>
      </p>
    </div>

    <!-- Formulario de reservas -->
    <div class="reservas__form-wrapper max-w-3xl mx-auto">
      <div class="form-container neo-surface p-xl rounded-lg">

        <form class="form" method="post" action="<?php echo esc_url(get_permalink()); ?>" novalidate>
          <?php wp_nonce_field('mm_reserva'); ?>

          <!-- Información personal -->
          <div class="form-section mb-xl">
            <h3 class="form-section__title text-primary mb-lg flex flex--center gap-sm">
              <i class="fa fa-user"></i>
              <?php _e('Información Personal', 'masajista-masculino'); ?>
            </h3>

            <div class="grid grid--2-cols gap-md">
              <!-- Nombre -->
              <div class="form-group">
                <label for="name" class="form-label">
                  <?php _e('Nombre completo', 'masajista-masculino'); ?>
                  <span class="text-error">*</span>
                </label>
                <input type="text"
                  id="name"
                  name="name"
                  class="form-input neo-input"
                  placeholder="<?php esc_attr_e('Tu nombre completo', 'masajista-masculino'); ?>"
                  required
                  maxlength="80"
                  value="<?php echo isset($_POST['name']) ? esc_attr($_POST['name']) : ''; ?>">
              </div>

              <!-- Teléfono -->
              <div class="form-group">
                <label for="phone" class="form-label">
                  <?php _e('Teléfono', 'masajista-masculino'); ?>
                  <span class="text-error">*</span>
                </label>
                <input type="tel"
                  id="phone"
                  name="phone"
                  class="form-input neo-input"
                  placeholder="<?php esc_attr_e('+34 666 777 888', 'masajista-masculino'); ?>"
                  required
                  maxlength="40"
                  value="<?php echo isset($_POST['phone']) ? esc_attr($_POST['phone']) : ''; ?>">
              </div>
            </div>
          </div>

          <!-- Fecha y hora -->
          <div class="form-section mb-xl">
            <h3 class="form-section__title text-primary mb-lg flex flex--center gap-sm">
              <i class="fa fa-calendar"></i>
              <?php _e('Fecha y Hora', 'masajista-masculino'); ?>
            </h3>

            <div class="grid grid--2-cols gap-md">
              <!-- Fecha -->
              <div class="form-group">
                <label for="date" class="form-label">
                  <?php _e('Fecha preferida', 'masajista-masculino'); ?>
                  <span class="text-error">*</span>
                </label>
                <input type="date"
                  id="date"
                  name="date"
                  class="form-input neo-input"
                  required
                  min="<?php echo date('Y-m-d'); ?>"
                  value="<?php echo isset($_POST['date']) ? esc_attr($_POST['date']) : ''; ?>">
              </div>

              <!-- Hora -->
              <div class="form-group">
                <label for="time" class="form-label">
                  <?php _e('Hora preferida', 'masajista-masculino'); ?>
                  <span class="text-error">*</span>
                </label>
                <select id="time"
                  name="time"
                  class="form-select neo-input"
                  required>
                  <option value=""><?php _e('Selecciona una hora', 'masajista-masculino'); ?></option>
                  <?php
                  $selected_time = isset($_POST['time']) ? $_POST['time'] : '';
                  $horas = ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'];
                  foreach ($horas as $hora) {
                    $selected = ($selected_time === $hora) ? 'selected' : '';
                    echo "<option value=\"{$hora}\" {$selected}>{$hora}</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>

          <!-- Servicio -->
          <div class="form-section mb-xl">
            <h3 class="form-section__title text-primary mb-lg flex flex--center gap-sm">
              <i class="fa fa-spa"></i>
              <?php _e('Servicio', 'masajista-masculino'); ?>
            </h3>

            <div class="form-group">
              <label for="service" class="form-label">
                <?php _e('Tipo de masaje', 'masajista-masculino'); ?>
                <span class="text-muted text-sm">(<?php _e('opcional', 'masajista-masculino'); ?>)</span>
              </label>
              <input type="text"
                id="service"
                name="service"
                class="form-input neo-input"
                placeholder="<?php esc_attr_e('Ej: Masaje relajante, deportivo, piedras calientes...', 'masajista-masculino'); ?>"
                maxlength="100"
                value="<?php echo isset($_POST['service']) ? esc_attr($_POST['service']) : ''; ?>">
            </div>
          </div>

          <!-- Comentarios -->
          <div class="form-section mb-xl">
            <h3 class="form-section__title text-primary mb-lg flex flex--center gap-sm">
              <i class="fa fa-comment"></i>
              <?php _e('Información Adicional', 'masajista-masculino'); ?>
            </h3>

            <div class="form-group">
              <label for="notes" class="form-label">
                <?php _e('Comentarios o solicitudes especiales', 'masajista-masculino'); ?>
                <span class="text-muted text-sm">(<?php _e('opcional', 'masajista-masculino'); ?>)</span>
              </label>
              <textarea id="notes"
                name="notes"
                class="form-textarea neo-input"
                rows="4"
                placeholder="<?php esc_attr_e('Cuéntanos si tienes alguna preferencia especial, condición médica relevante, o cualquier otra información...', 'masajista-masculino'); ?>"
                maxlength="2000"><?php echo isset($_POST['notes']) ? esc_textarea($_POST['notes']) : ''; ?></textarea>
            </div>
          </div>

          <!-- Información importante -->
          <div class="form-info bg-light p-lg rounded mb-lg">
            <div class="flex flex--start gap-md">
              <i class="fa fa-info-circle text-primary text-lg"></i>
              <div>
                <h4 class="text-primary mb-sm">
                  <?php _e('Información importante', 'masajista-masculino'); ?>
                </h4>
                <ul class="text-sm text-body space-y-xs">
                  <li><?php _e('Te contactaremos para confirmar la disponibilidad', 'masajista-masculino'); ?></li>
                  <li><?php _e('Política de cancelación: 24h de antelación', 'masajista-masculino'); ?></li>
                  <li><?php _e('Llega 10 minutos antes de tu cita', 'masajista-masculino'); ?></li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Botón de envío -->
          <div class="form-actions">
            <button type="submit"
              name="mm_reserva_submit"
              class="btn btn--primary btn--lg btn--block">
              <i class="fa fa-calendar-check-o mr-sm"></i>
              <?php _e('Enviar Reserva', 'masajista-masculino'); ?>
            </button>
          </div>

        </form>
      </div>
    </div>

  </div>
</section>