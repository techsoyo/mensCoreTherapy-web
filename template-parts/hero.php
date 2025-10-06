<?php
if (!defined('ABSPATH')) exit;
$args = wp_parse_args($args ?? [], ['bg_image' => '']);
$ci = mm_get_contact_info();

// Usar hoem.jpg como imagen de fondo por defecto
$bg_image = !empty($args['bg_image']) ? $args['bg_image'] : get_template_directory_uri() . '/assets/images/hoem.webp';
?>
<section class="mm-hero" aria-label="<?php esc_attr_e('Héroe principal','masajista-masculino'); ?>" style="background-image: url('<?php echo esc_url($bg_image); ?>');">
  <!-- Video ya no se usa, ahora es imagen de fondo -->

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
