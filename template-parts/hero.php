<?php
if (!defined('ABSPATH')) exit;
$args = wp_parse_args($args ?? [], ['video_url' => '']);
$ci = mm_get_contact_info();
?>
<section class="mm-hero" aria-label="<?php esc_attr_e('Héroe principal','masajista-masculino'); ?>">
  <?php if (!empty($args['video_url'])): ?>
    <video class="mm-hero__video" src="<?php echo esc_url($args['video_url']); ?>" autoplay muted loop playsinline></video>
  <?php endif; ?>

  <div class="mm-hero__overlay">
    <div class="mm-hero__content montserrat-alternates-regular">
      <h1 class="mm-hero__title">Masajes Profesionales para Hombres</h1>
      <p class="mm-hero__subtitle">Experiencias de relajación y bienestar</p>
      <p class="mm-hero__claim">Discreción, profesionalidad y resultados garantizados 100% Discreto</p>

      <div class="mm-hero__cta">
        <a href="<?php echo esc_url( mm_link_by_slug('reservas') ); ?>" class="btn btn-primary">Reservar Cita</a>
        <a href="<?php echo esc_url( mm_link_by_slug('servicios') ); ?>" class="btn btn-outline">Ver Servicios</a>
      </div>

      <div class="mm-hero__info">
        <span>tel. <?php echo esc_html($ci['phone']); ?></span>
        <span class="sep">|</span>
        <span><?php echo esc_html($ci['hours']); ?></span>
      </div>
    </div>
  </div>
</section>
