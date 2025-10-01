<!-- FOOTER NEOMÓRFICO HORIZONTAL COMPACTO CON IMAGEN DE FONDO -->
<footer class="neo-footer" style="
  background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/fonde-header.webp');
  background-size: cover;
  background-position: center;
  color: #FFEDE6;
  margin: 0;
  padding: 0;
  border-radius: 20px 20px 0 0;
  box-shadow: none;
  position: relative;
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

  <!-- Grid horizontal compacto de 3 columnas -->
  <div style="
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: 0 auto;
    padding: 15px;
    display: grid;
    grid-template-columns: 1fr 1fr 2fr;
    gap: 15px;
    align-items: start;
    color: #FFEDE6;
  ">
    
    <!-- Columna 1: Logo + Botones -->
    <div style="display: flex; flex-direction: column; align-items: center;">
      <div style="margin-bottom: 0.5rem;">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-sin-fondo.png" alt="Logo" style="max-height: 3.5rem; width: auto; margin-bottom: 0.5rem;">
      </div>
      
      <div style="display: flex; gap: 8px; font-size: 0.7rem;">
        <a href="#" class="neo-button" style="
          color: #240A00;
          transition: all 0.3s ease;
          display: flex;
          align-items: center;
          gap: 4px;
          padding: 6px 8px;
          background: #FFEDE6;
          border-radius: 8px;
          box-shadow: 2px 2px 5px #AA3000, -2px -2px 5px #ffffff;
          text-decoration: none;
          font-size: 0.7rem;
        " onmouseover="this.style.color='#FF6C32'; this.style.transform='translateY(-1px)'; this.style.boxShadow='4px 4px 8px #AA3000, -4px -4px 8px #ffffff';"
          onmouseout="this.style.color='#240A00'; this.style.transform='translateY(0)'; this.style.boxShadow='2px 2px 5px #AA3000, -2px -2px 5px #ffffff';">
          <i class="fa fa-whatsapp" style="font-size: 0.8rem;"></i> WhatsApp
        </a>
        <a href="#" class="neo-button" style="
          color: #240A00;
          transition: all 0.3s ease;
          display: flex;
          align-items: center;
          gap: 4px;
          padding: 6px 8px;
          background: #FFEDE6;
          border-radius: 8px;
          box-shadow: 2px 2px 5px #AA3000, -2px -2px 5px #ffffff;
          text-decoration: none;
          font-size: 0.7rem;
        " onmouseover="this.style.color='#FF6C32'; this.style.transform='translateY(-1px)'; this.style.boxShadow='4px 4px 8px #AA3000, -4px -4px 8px #ffffff';"
          onmouseout="this.style.color='#240A00'; this.style.transform='translateY(0)'; this.style.boxShadow='2px 2px 5px #AA3000, -2px -2px 5px #ffffff';">
          <i class="fa fa-envelope" style="font-size: 0.8rem;"></i> Email
        </a>
      </div>
    </div>

    <!-- Columna 2: Enlaces rápidos en 2 columnas -->
    <div style="display: flex; flex-direction: column; align-items: center;">
      <h3 style="
        color: var(--neo-text-primary);
        margin-bottom: 10px;
        font-size: 0.8rem;
        font-weight: 600;
        text-shadow: 1px 1px 2px var(--neo-highlight), -1px -1px 2px var(--neo-shadow);
        text-align: center;
      ">
        Enlaces Rápidos
      </h3>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 5px; max-width: 200px;">
        <!-- Sub-columna 1 -->
        <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem;">
          <li style="margin-bottom: 8px;">
            <a href="<?php echo home_url('/servicios'); ?>" style="
              color: var(--neo-text-secondary);
              text-decoration: none;
              transition: color 0.3s ease;
            " onmouseover="this.style.color='var(--neo-primary)';"
              onmouseout="this.style.color='var(--neo-text-secondary)';">
              Servicios
            </a>
          </li>
          <li style="margin-bottom: 8px;">
            <a href="<?php echo home_url('/precios'); ?>" style="
              color: var(--neo-text-secondary);
              text-decoration: none;
              transition: color 0.3s ease;
            " onmouseover="this.style.color='var(--neo-primary)';"
              onmouseout="this.style.color='var(--neo-text-secondary)';">
              Precios
            </a>
          </li>
        </ul>
        <!-- Sub-columna 2 -->
        <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem;">
          <li style="margin-bottom: 8px;">
            <a href="<?php echo home_url('/reservas'); ?>" style="
              color: var(--neo-text-secondary);
              text-decoration: none;
              transition: color 0.3s ease;
            " onmouseover="this.style.color='var(--neo-primary)';"
              onmouseout="this.style.color='var(--neo-text-secondary)';">
              Reservar
            </a>
          </li>
          <li style="margin-bottom: 8px;">
            <a href="<?php echo home_url('/contactos'); ?>" style="
              color: var(--neo-text-secondary);
              font-size: 0.8rem;
              text-decoration: none;
              transition: color 0.3s ease;
            " onmouseover="this.style.color='var(--neo-primary)';"
              onmouseout="this.style.color='var(--neo-text-secondary)';">
              Contacto
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Columna 3: Información de contacto horizontal -->
    <div style="display: flex; flex-direction: column; align-items: center;">
      <h3 style="
        color: var(--neo-text-primary);
        margin-bottom: 10px;
        font-size: 0.8rem;
        font-weight: 600;
        text-shadow: 1px 1px 2px var(--neo-highlight), -1px -1px 2px var(--neo-shadow);
        text-align: center;
      ">
        Contacto
      </h3>
      <div style="
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 12px;
        color: var(--neo-text-secondary);
        font-size: 0.75rem;
        line-height: 1.3;
        max-width: 320px;
      ">
        <span style="display: flex; align-items: center; gap: 6px; white-space: nowrap;">
          <i class="fa fa-phone" style="color: var(--neo-primary); width: 12px;"></i>
          +34 666 777 888
        </span>
        <span style="display: flex; align-items: center; gap: 6px; white-space: nowrap;">
          <i class="fa fa-envelope" style="color: var(--neo-primary); width: 12px;"></i>
          info@masajes.com
        </span>
        <span style="display: flex; align-items: center; gap: 6px; white-space: nowrap;">
          <i class="fa fa-map-marker" style="color: var(--neo-primary); width: 12px;"></i>
          Barcelona, España
        </span>
        <span style="display: flex; align-items: center; gap: 6px; white-space: nowrap;">
          <i class="fa fa-clock-o" style="color: var(--neo-primary); width: 12px;"></i>
          Lun-Dom: 10:00-22:00
        </span>
      </div>
    </div>

  </div>
  <!-- Copyright ultra-compacto -->
  <div style="
    border-top: 1px solid var(--neo-accent);
    padding: 6px 0;
    text-align: center;
    color: var(--neo-text-secondary);
    font-size: 0.7rem;
    background: var(--neo-overlay);
    margin: 0;
  ">
  </div>
</footer>
<script>
  console.log('Footer cargado correctamente');
</script>
<style>
  /* Footer horizontal compacto con grid responsive */
  .neo-footer {
    transition: all 0.3s ease;
  }

  @media (max-width: 768px) {
    .neo-footer > div {
      grid-template-columns: 1fr !important;
      gap: 20px !important;
      padding: 20px 15px !important;
      text-align: center;
    }
    
    .neo-footer h3 {
      text-align: center !important;
    }
    
    .neo-footer > div > div:last-child > div {
      grid-template-columns: 1fr !important;
      justify-items: center;
    }
  }

  @media (max-width: 480px) {
    .neo-footer > div > div:first-child > div {
      flex-direction: column !important;
      align-items: center;
    }
  }
</style>
<?php wp_footer(); ?>
</body>

</html>