<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php wp_title('|', true, 'right'); ?></title>
  <!-- Google Fonts - Montserrat -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

  <!-- Header trigger removido - header siempre visible -->

  <!-- HEADER SIEMPRE VISIBLE CON ESTILO NEOMÓRFICO -->
  <header id="auto-header" class="neo-header" style="
  position: fixed;
  top: 2vh;
 
  width: 100%;
  background: rgba(170, 48, 0, 0.85);
  backdrop-filter: blur(15px);
  box-shadow: 8px 8px 16px var(--neo-shadow), -8px -8px 16px var(--neo-highlight);
  z-index: 9998;
  transition: all 0.3s ease;
  border-radius: 20px;
  border: 1px solid rgba(255,255,255,0.3);
">

    <!-- Container del header -->
    <div style="
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 20px;
  ">

      <!-- Logo Neomórfico -->
      <div class="neo-element" style="
        font-size: 1.3rem; 
        font-weight: 700; 
        color: var(--neo-text-primary);
        padding: 10px 20px;
        border-radius: 15px;
        box-shadow: inset 2px 2px 5px var(--neo-shadow), inset -2px -2px 5px var(--neo-highlight);
        text-shadow: 1px 1px 2px var(--neo-highlight), -1px -1px 2px var(--neo-shadow);
      ">
        <a href="<?php echo home_url(); ?>" style="color: var(--neo-text-primary); text-decoration: none;">
          Men's Core Therapy
        </a>
      </div>

      <!-- Navegación Neomórfica -->
      <nav style="display: flex; gap: 15px; align-items: center;" id="mainNav">
        <a href="<?php echo home_url('/servicios'); ?>" class="neo-button" style="
          color: var(--neo-text-primary);
          text-decoration: none;
          font-weight: 600;
          padding: 8px 16px;
          font-size: 14px;
          background: var(--neo-surface);
          border-radius: 10px;
          box-shadow: 3px 3px 6px var(--neo-shadow), -3px -3px 6px var(--neo-highlight);
          transition: all 0.3s ease;
        " onmouseover="this.style.boxShadow='5px 5px 10px var(--neo-shadow), -5px -5px 10px var(--neo-highlight)'; this.style.transform='translateY(-2px)';"
          onmouseout="this.style.boxShadow='3px 3px 6px var(--neo-shadow), -3px -3px 6px var(--neo-highlight)'; this.style.transform='translateY(0)';"
          onmousedown="this.style.boxShadow='inset 2px 2px 4px var(--neo-shadow), inset -2px -2px 4px var(--neo-highlight)';"
          onmouseup="this.style.boxShadow='5px 5px 10px var(--neo-shadow), -5px -5px 10px var(--neo-highlight)';">
          Servicios
        </a>

        <a href="<?php echo home_url('/precios'); ?>" class="neo-button" style="
          color: var(--neo-text-primary);
          text-decoration: none;
          font-weight: 600;
          padding: 8px 16px;
          font-size: 14px;
          background: var(--neo-surface);
          border-radius: 10px;
          box-shadow: 3px 3px 6px var(--neo-shadow), -3px -3px 6px var(--neo-highlight);
          transition: all 0.3s ease;
        " onmouseover="this.style.boxShadow='5px 5px 10px var(--neo-shadow), -5px -5px 10px var(--neo-highlight)'; this.style.transform='translateY(-2px)';"
          onmouseout="this.style.boxShadow='3px 3px 6px var(--neo-shadow), -3px -3px 6px var(--neo-highlight)'; this.style.transform='translateY(0)';"
          onmousedown="this.style.boxShadow='inset 2px 2px 4px var(--neo-shadow), inset -2px -2px 4px var(--neo-highlight)';"
          onmouseup="this.style.boxShadow='5px 5px 10px var(--neo-shadow), -5px -5px 10px var(--neo-highlight)';">
          Precios
        </a>

        <a href="<?php echo home_url('/blog'); ?>" class="neo-button" style="
          color: var(--neo-text-primary);
          text-decoration: none;
          font-weight: 600;
          padding: 8px 16px;
          font-size: 14px;
          background: var(--neo-surface);
          border-radius: 10px;
          box-shadow: 3px 3px 6px var(--neo-shadow), -3px -3px 6px var(--neo-highlight);
          transition: all 0.3s ease;
        " onmouseover="this.style.boxShadow='5px 5px 10px var(--neo-shadow), -5px -5px 10px var(--neo-highlight)'; this.style.transform='translateY(-2px)';"
          onmouseout="this.style.boxShadow='3px 3px 6px var(--neo-shadow), -3px -3px 6px var(--neo-highlight)'; this.style.transform='translateY(0)';"
          onmousedown="this.style.boxShadow='inset 2px 2px 4px var(--neo-shadow), inset -2px -2px 4px var(--neo-highlight)';"
          onmouseup="this.style.boxShadow='5px 5px 10px var(--neo-shadow), -5px -5px 10px var(--neo-highlight)';">
          Blog
        </a>

        <a href="<?php echo home_url('/contactos'); ?>" class="neo-button" style="
          color: var(--neo-text-primary);
          text-decoration: none;
          font-weight: 600;
          padding: 8px 16px;
          font-size: 14px;
          background: var(--neo-surface);
          border-radius: 10px;
          box-shadow: 3px 3px 6px var(--neo-shadow), -3px -3px 6px var(--neo-highlight);
          transition: all 0.3s ease;
        " onmouseover="this.style.boxShadow='5px 5px 10px var(--neo-shadow), -5px -5px 10px var(--neo-highlight)'; this.style.transform='translateY(-2px)';"
          onmouseout="this.style.boxShadow='3px 3px 6px var(--neo-shadow), -3px -3px 6px var(--neo-highlight)'; this.style.transform='translateY(0)';"
          onmousedown="this.style.boxShadow='inset 2px 2px 4px var(--neo-shadow), inset -2px -2px 4px var(--neo-highlight)';"
          onmouseup="this.style.boxShadow='5px 5px 10px var(--neo-shadow), -5px -5px 10px var(--neo-highlight)';">
          Contacto
        </a>

        <!-- Botón CTA Neomórfico -->
        <a href="<?php echo home_url('/reservas'); ?>" class="neo-button" style="
          background: linear-gradient(145deg, var(--neo-primary), var(--neo-secondary));
          color: white;
          padding: 10px 20px;
          text-decoration: none;
          border-radius: 15px;
          font-weight: 700;
          font-size: 14px;
          transition: all 0.3s ease;
          box-shadow: 5px 5px 10px rgba(255,108,50,0.3), -5px -5px 10px var(--neo-highlight);
          text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='8px 8px 15px rgba(255,108,50,0.4), -8px -8px 15px var(--neo-highlight)';"
          onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='5px 5px 10px rgba(255,108,50,0.3), -5px -5px 10px var(--neo-highlight)';"
          onmousedown="this.style.boxShadow='inset 3px 3px 6px rgba(255,108,50,0.3), inset -3px -3px 6px var(--neo-highlight)';"
          onmouseup="this.style.boxShadow='8px 8px 15px rgba(255,108,50,0.4), -8px -8px 15px var(--neo-highlight)';">
          Reservar
        </a>
      </nav>

    </div>
  </header>

  <!-- Script del header removido - ahora siempre visible -->

  <style>
    @media (max-width: 768px) {
      #mainNav {
        display: none !important;
      }

      /* En móvil se puede mostrar un menú hamburguesa si quieres */
    }
  </style>