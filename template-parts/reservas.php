<?php if (!defined('ABSPATH')) exit; ?>
<section class="mm-reservas">
  <div class="container">

    <?php
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

        if ($name === '')  $errors[] = __('El nombre es obligatorio.', 'masajista-masculino');
        if ($phone === '') $errors[] = __('El teléfono es obligatorio.', 'masajista-masculino');
        if ($date === '')  $errors[] = __('La fecha es obligatoria.', 'masajista-masculino');
        if ($time === '')  $errors[] = __('La hora es obligatoria.', 'masajista-masculino');

        // Validación fecha/hora simple
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

    if ($sent) {
      echo '<div class="mm-notice success">' . esc_html__('Reserva enviada correctamente. Te contactaremos para confirmar.', 'masajista-masculino') . '</div>';
    } elseif ($errors) {
      echo '<div class="mm-notice error"><ul>';
      foreach ($errors as $e) echo '<li>' . esc_html($e) . '</li>';
      echo '</ul></div>';
    }
    ?>

    <h2><?php _e('Reservas', 'masajista-masculino'); ?></h2>
    <form class="mm-form neo-form" method="post" action="<?php echo esc_url(get_permalink()); ?>" novalidate>
      <?php wp_nonce_field('mm_reserva'); ?>
      
      <!-- Primera fila: Nombre y Teléfono -->
      <div class="form-row double">
        <div class="form-group">
          <input type="text" id="name" name="name" class="neo-input" placeholder="<?php esc_attr_e('Nombre completo', 'masajista-masculino'); ?> *" required maxlength="80" />
        </div>
        <div class="form-group">
          <input type="tel" id="phone" name="phone" class="neo-input" placeholder="<?php esc_attr_e('Teléfono', 'masajista-masculino'); ?> *" required maxlength="40" />
        </div>
      </div>
      
      <!-- Segunda fila: Fecha y Hora -->
      <div class="form-row double">
        <div class="form-group">
          <input type="date" id="date" name="date" class="neo-input" required />
        </div>
        <div class="form-group">
          <input type="time" id="time" name="time" class="neo-input" required />
        </div>
      </div>
      
      <!-- Tercera fila: Tipo de masaje -->
      <div class="form-row single">
        <div class="form-group">
          <input type="text" id="service" name="service" class="neo-input" placeholder="<?php esc_attr_e('Tipo de masaje (opcional)', 'masajista-masculino'); ?>" maxlength="100" />
        </div>
      </div>
      
      <!-- Cuarta fila: Comentarios -->
      <div class="form-row single">
        <div class="form-group">
          <textarea id="notes" name="notes" class="neo-input" rows="4" placeholder="<?php esc_attr_e('Comentarios adicionales...', 'masajista-masculino'); ?>" maxlength="2000"></textarea>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" name="mm_reserva_submit" class="neo-button"><?php _e('Reservar', 'masajista-masculino'); ?></button>
      </div>
    </form>
  </div>
</section>