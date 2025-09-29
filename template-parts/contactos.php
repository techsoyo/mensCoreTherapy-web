<?php if (!defined('ABSPATH')) exit; ?>
<section class="mm-contactos">
  <div class="container">
    <h1><?php _e('Contactos', 'masajista-masculino'); ?></h1>

    <?php
    $errors = []; $sent = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mm_contacto_submit'])) {
        if (!mm_verify_nonce('mm_contacto')) {
            $errors[] = __('Verificación de seguridad fallida. Recarga la página.', 'masajista-masculino');
        } else {
            $name  = sanitize_text_field($_POST['name'] ?? '');
            $email = sanitize_email($_POST['email'] ?? '');
            $msg   = wp_kses_post($_POST['message'] ?? '');

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

    if ($sent) {
        echo '<div class="mm-notice success">'.esc_html__('Mensaje enviado correctamente. Gracias.', 'masajista-masculino').'</div>';
    } elseif ($errors) {
        echo '<div class="mm-notice error"><ul>';
        foreach ($errors as $e) echo '<li>'.esc_html($e).'</li>';
        echo '</ul></div>';
    }
    ?>

    <form class="mm-form" method="post" action="<?php echo esc_url(get_permalink()); ?>" novalidate>
      <?php wp_nonce_field('mm_contacto'); ?>
      <div class="form-row">
        <label for="name"><?php _e('Nombre', 'masajista-masculino'); ?> *</label>
        <input type="text" id="name" name="name" required maxlength="80" />
      </div>
      <div class="form-row">
        <label for="email"><?php _e('Email', 'masajista-masculino'); ?> *</label>
        <input type="email" id="email" name="email" required maxlength="120" />
      </div>
      <div class="form-row">
        <label for="message"><?php _e('Mensaje', 'masajista-masculino'); ?> *</label>
        <textarea id="message" name="message" rows="6" required maxlength="2000"></textarea>
      </div>
      <div class="form-actions">
        <button type="submit" name="mm_contacto_submit" class="btn btn-primary"><?php _e('Enviar', 'masajista-masculino'); ?></button>
      </div>
    </form>
  </div>
</section>
