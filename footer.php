</div><!-- #content-wrap -->

<!-- FOOTER MINIMALISTA (como en la imagen) -->
<footer id="site-footer" class="mm-footer" role="contentinfo" aria-label="<?php esc_attr_e('Pie de página', 'masajista-masculino'); ?>" style="
  background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/fonde-header.webp');
  background-size: cover;
  background-position: center;
  color: #FFEDE6;
  margin: 0;
  padding: 0;
  border-radius: 20px 20px 0 0;
  box-shadow: none;
  overflow: hidden;
">
  <!-- Overlay para mejor legibilidad -->
  <div style="
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(170, 48, 0, 0.85);
    backdrop-filter: blur(5px);
    z-index: 0;
  "></div>

  <!-- Contenedor principal con grid de 3 columnas -->
  <div style="
    position: relative;
    z-index: 1;
    max-width: 100%;
    width: 90%;
    margin: 0 auto;
  padding: 0.14rem 0.5rem; /* Altura mínima reducida ~5% */
  display: grid;
  grid-template-columns: 1fr 1fr 2fr;
  gap: 0.19rem;
  align-items: center;
  justify-items: center;
  color: #FFEDE6;
  font-size: 0.665rem;
  ">

    <!-- Columna 1: Logo -->
    <div style="display: flex; align-items: center; justify-content: start; width: 100%;">
  <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-sin-fondo.png" alt="Logo" style="max-height: 4rem; width: auto;">
    </div>

    <!-- Columna 2: Botones WhatsApp y Email (h4 encima de botones) -->
    <div style="display: flex; flex-direction: column; gap: 0.24rem; align-items: center; justify-content: center; width: 100%;">
      <h4 style="margin:0; font-size:0.81rem; color:var(--neo-text-primary);">Reservar</h4>
      <div style="display:flex; gap:0.38rem; justify-content:center; align-items:center; width:100%;">
        <a href="#" class="neo-button" style="
          color: #240A00;
          padding: 0.14rem 0.33rem;
          background: #FFEDE6;
          border-radius: 0.3rem;
          box-shadow: 0.08rem 0.08rem 0.2rem #AA3000, -0.08rem -0.08rem 0.2rem #ffffff;
          text-decoration: none;
          font-size: 0.57rem;
          display: flex;
          align-items: center;
          gap: 0.14rem;
        " onmouseover="this.style.color='#FF6C32'; this.style.transform='translateY(-0.04rem)';"
          onmouseout="this.style.color='#240A00'; this.style.transform='translateY(0)';">
          <i class="fa fa-whatsapp" style="font-size: 0.62rem;"></i> WhatsApp
        </a>
        <a href="#" class="neo-button" style="
          color: #240A00;
          padding: 0.14rem 0.33rem;
          background: #FFEDE6;
          border-radius: 0.3rem;
          box-shadow: 0.08rem 0.08rem 0.2rem #AA3000, -0.08rem -0.08rem 0.2rem #ffffff;
          text-decoration: none;
          font-size: 0.57rem;
          display: flex;
          align-items: center;
          gap: 0.14rem;
        " onmouseover="this.style.color='#FF6C32'; this.style.transform='translateY(-0.04rem)';"
          onmouseout="this.style.color='#240A00'; this.style.transform='translateY(0)';">
          <i class="fa fa-envelope" style="font-size: 0.62rem;"></i> Email
        </a>
      </div>
    </div>

    <!-- Columna 3: Información de contacto (alineada a la derecha) -->
    <div style="display:flex; flex-direction:column; align-items:flex-end; justify-content:center; width:100%; text-align:right; justify-self:end;">
      

      <div style="display:flex; gap:0.8rem; justify-content:flex-end; width:100%; max-width:22rem; color:var(--neo-text-secondary); font-size:0.677rem; line-height:1.2;">
        <!-- Caja 1: h4 -->
        <div style="flex:0 0 auto; text-align:left;">
          <h4 style="margin:0; font-size:0.78rem; color:var(--neo-text-primary);">Contacto</h4>
        </div>

        <!-- Caja 2: teléfono y email (alineados a la izquierda) -->
        <div style="display:flex; flex-direction:column; gap:0.25rem; text-align:left; min-width:9rem;">
          <div style="display:flex; align-items:center; gap:0.38rem;"><i class="fa fa-phone" style="color: var(--neo-primary); font-size:0.74rem; min-width:0.74rem;"></i><span>+34 666 777 888</span></div>
          <div style="display:flex; align-items:center; gap:0.38rem;"><i class="fa fa-envelope" style="color: var(--neo-primary); font-size:0.74rem; min-width:0.74rem;"></i><span>info@masajes.com</span></div>
        </div>

        <!-- Caja 3: ubicación y horario (alineados a la izquierda) -->
        <div style="display:flex; flex-direction:column; gap:0.25rem; text-align:left; min-width:9rem;">
          <div style="display:flex; align-items:center; gap:0.38rem;"><i class="fa fa-map-marker" style="color: var(--neo-primary); font-size:0.74rem; min-width:0.74rem;"></i><span>Barcelona, España</span></div>
          <div style="display:flex; align-items:center; gap:0.38rem;"><i class="fa fa-clock-o" style="color: var(--neo-primary); font-size:0.74rem; min-width:0.74rem;"></i><span>Lun-Dom: 10:00-22:00</span></div>
        </div>
      </div>
    </div>

  </div>

  <!-- Copyright ultra-compacto -->
  <div style="
    border-top: 1px solid var(--neo-accent);
    padding: 1px 0;
    text-align: center;
    color: var(--neo-text-secondary);
    font-size: 0.55rem;
    background: var(--neo-overlay);
    margin: 0;
  "></div>
</footer>

<?php wp_footer(); ?>
</body>

</html>