<!-- Footer con nueva estructura CSS ITCSS -->
<footer id="site-footer" class="footer" role="contentinfo">
  <div class="footer__bg" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/fonde-header.webp');"></div>
  <div class="footer__overlay"></div>

  <!-- Contenido principal del footer -->
  <div class="footer__content">
    <div class="container">
      <div class="footer__inner grid grid--3-cols gap-lg align-center">

        <!-- Columna 1: Logo -->
        <div class="footer__logo">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-sin-fondo.webp"
            alt="<?php bloginfo('name'); ?>"
            class="footer__logo-img">
        </div>

        <!-- Columna 2: Botones de acción -->
        <div class="footer__actions text-center">
          <h4 class="footer__section-title text-white mb-sm">
            <?php _e('Reservar', 'masajista-masculino'); ?>
          </h4>
          <div class="footer__buttons flex flex--center gap-sm">
            <a href="https://wa.me/34666777888"
              class="btn btn--neo btn--sm"
              target="_blank">
              <i class="fa fa-whatsapp mr-xs"></i>
              WhatsApp
            </a>
            <a href="mailto:info@masajes.com"
              class="btn btn--neo btn--sm">
              <i class="fa fa-envelope mr-xs"></i>
              Email
            </a>
          </div>
        </div>

        <!-- Columna 3: Información de contacto -->
        <div class="footer__contact">
          <h4 class="footer__section-title text-white mb-sm">
            <?php _e('Contacto', 'masajista-masculino'); ?>
          </h4>

          <div class="footer__contact-grid grid grid--2-cols gap-md text-sm">
            <!-- Columna izquierda -->
            <div class="footer__contact-col">
              <div class="footer__contact-item flex flex--start gap-xs mb-xs">
                <i class="fa fa-phone text-primary"></i>
                <span class="text-white">+34 666 777 888</span>
              </div>
              <div class="footer__contact-item flex flex--start gap-xs">
                <i class="fa fa-envelope text-primary"></i>
                <span class="text-white">info@masajes.com</span>
              </div>
            </div>

            <!-- Columna derecha -->
            <div class="footer__contact-col">
              <div class="footer__contact-item flex flex--start gap-xs mb-xs">
                <i class="fa fa-map-marker text-primary"></i>
                <span class="text-white">Barcelona, España</span>
              </div>
              <div class="footer__contact-item flex flex--start gap-xs">
                <i class="fa fa-clock-o text-primary"></i>
                <span class="text-white">Lun-Dom: 10:00-22:00</span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Copyright -->
  <div class="footer__copyright">
    <div class="container">
      <div class="text-center text-white text-xs py-sm">
        <p class="mb-0">
          &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>.
          <?php _e('Todos los derechos reservados.', 'masajista-masculino'); ?>
        </p>
      </div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>

</html>