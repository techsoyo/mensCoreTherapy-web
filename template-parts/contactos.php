<?php if (!defined('ABSPATH')) exit; ?>

<section class="contactos-section" role="region" aria-label="<?php esc_attr_e('Formulario de contacto', 'masajista-masculino'); ?>">
  <div class="container">

    <!-- Título principal -->
    <div class="text-center mb-xl">
      <h1 class="section-title text-primary">
        <?php _e('Contactos', 'masajista-masculino'); ?>
      </h1>
    </div>

    <?php
    // Procesamiento del formulario
    $errors = [];
    $sent = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mm_contacto_submit'])) {
      if (!mm_verify_nonce('mm_contacto')) {
        $errors[] = __('Verificación de seguridad fallida. Recarga la página.', 'masajista-masculino');
      } else {
        $name  = sanitize_text_field($_POST['name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $msg   = wp_kses_post($_POST['message'] ?? '');

        // Validaciones
        if ($name === '')  $errors[] = __('El nombre es obligatorio.', 'masajista-masculino');
        if (!is_email($email)) $errors[] = __('Email no válido.', 'masajista-masculino');
        if ($msg === '')   $errors[] = __('El mensaje es obligatorio.', 'masajista-masculino');

        if (!$errors) {
          $to = get_option('admin_email');
          $subject = sprintf(__('Nuevo mensaje de %s', 'masajista-masculino'), $name);
          $body = "Nombre: {$name}\nEmail: {$email}\n\nMensaje:\n{$msg}";
          $headers = ['Content-Type: text/plain; charset=UTF-8', "Reply-To: {$name} <{$email}>"];

          if (wp_mail($to, $subject, $body, $headers)) {
            $sent = true;
          } else {
            $errors[] = __('No se pudo enviar el mensaje. Inténtalo más tarde.', 'masajista-masculino');
          }
        }
      }
    }

    // Mostrar mensajes de estado
    if ($sent) {
      echo '<div class="alert alert--success mb-lg" role="status" aria-live="polite">';
      echo '<i class="fa fa-check-circle mr-sm"></i>';
      echo esc_html__('Mensaje enviado correctamente. Gracias.', 'masajista-masculino');
      echo '</div>';
    } elseif ($errors) {
      echo '<div class="alert alert--error mb-lg" role="alert" aria-live="assertive">';
      echo '<i class="fa fa-exclamation-triangle mr-sm"></i>';
      echo '<ul class="alert__list">';
      foreach ($errors as $e) echo '<li>' . esc_html($e) . '</li>';
      echo '</ul></div>';
    }
    ?>

    <!-- Formulario de contacto -->
    <div class="contactos__form-wrapper max-w-2xl mx-auto">
      <div class="form-container neo-surface p-xl rounded-lg">

        <form class="form space-y-md" method="post" action="<?php echo esc_url(get_permalink()); ?>" novalidate>
          <?php wp_nonce_field('mm_contacto'); ?>

          <!-- Campo nombre -->
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

          <!-- Campo email -->
          <div class="form-group">
            <label for="email" class="form-label">
              <?php _e('Email', 'masajista-masculino'); ?>
              <span class="text-error">*</span>
            </label>
            <input type="email"
              id="email"
              name="email"
              class="form-input neo-input"
              placeholder="<?php esc_attr_e('tu@email.com', 'masajista-masculino'); ?>"
              required
              maxlength="120"
              value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>">
          </div>

          <!-- Campo mensaje -->
          <div class="form-group">
            <label for="message" class="form-label">
              <?php _e('Mensaje', 'masajista-masculino'); ?>
              <span class="text-error">*</span>
            </label>
            <textarea id="message"
              name="message"
              class="form-textarea neo-input"
              placeholder="<?php esc_attr_e('Cuéntanos cómo podemos ayudarte...', 'masajista-masculino'); ?>"
              rows="6"
              required
              maxlength="2000"><?php echo isset($_POST['message']) ? esc_textarea($_POST['message']) : ''; ?></textarea>
          </div>

          <!-- Información adicional -->
          <div class="form-info bg-light p-md rounded text-sm">
            <div class="flex flex--start gap-sm">
              <i class="fa fa-info-circle text-primary"></i>
              <div>
                <p class="mb-xs font-medium">
                  <?php _e('Te responderemos lo antes posible', 'masajista-masculino'); ?>
                </p>
                <p class="text-muted mb-0">
                  <?php _e('Tiempo de respuesta habitual: 24-48 horas', 'masajista-masculino'); ?>
                </p>
              </div>
            </div>
          </div>

          <!-- Botón de envío -->
          <div class="form-actions">
            <button type="submit"
              name="mm_contacto_submit"
              class="btn btn--primary btn--lg btn--block">
              <i class="fa fa-paper-plane mr-sm"></i>
              <?php _e('Enviar mensaje', 'masajista-masculino'); ?>
            </button>
          </div>

        </form>
      </div>
    </div>

    <!-- Información de contacto adicional -->
    <div class="contactos__info mt-xl">
      <div class="grid grid--3-cols gap-lg">

        <!-- Teléfono -->
        <div class="contact-method text-center">
          <div class="contact-method__icon neo-surface p-lg rounded-full mb-md mx-auto w-fit">
            <i class="fa fa-phone text-primary text-xl"></i>
          </div>
          <h3 class="contact-method__title text-primary mb-sm">
            <?php _e('Teléfono', 'masajista-masculino'); ?>
          </h3>
          <p class="contact-method__info text-body">
            <a href="tel:+34666777888" class="text-inherit hover:text-primary">
              +34 666 777 888
            </a>
          </p>
        </div>

        <!-- Email -->
        <div class="contact-method text-center">
          <div class="contact-method__icon neo-surface p-lg rounded-full mb-md mx-auto w-fit">
            <i class="fa fa-envelope text-primary text-xl"></i>
          </div>
          <h3 class="contact-method__title text-primary mb-sm">
            <?php _e('Email', 'masajista-masculino'); ?>
          </h3>
          <p class="contact-method__info text-body">
            <a href="mailto:info@masajes.com" class="text-inherit hover:text-primary">
              info@masajes.com
            </a>
          </p>
        </div>

        <!-- Ubicación -->
        <div class="contact-method text-center">
          <div class="contact-method__icon neo-surface p-lg rounded-full mb-md mx-auto w-fit">
            <i class="fa fa-map-marker text-primary text-xl"></i>
          </div>
          <h3 class="contact-method__title text-primary mb-sm">
            <?php _e('Ubicación', 'masajista-masculino'); ?>
          </h3>
          <p class="contact-method__info text-body">
            Barcelona, España
          </p>
        </div>

      </div>
    </div>

  </div>
</section>