<?php

/* Template: Página de Inicio - Hero con Parallax Optimizado */

if (!defined('ABSPATH')) exit;



get_header(); // Carga header.phpget_header(); // Carga header.php

?>?>



<style><style>

/* Hero Parallax Expandido - para ocupar área amarilla completa *//* Hero Parallax Expandido - para ocupar área amarilla completa */

.hero-parallax {.hero-parallax {

  height: 35vh;  height: 35vh;

  min-height: 280px;  min-height: 280px;

  width: 100%;  width: 100%;

  position: relative;  position: relative;

  overflow: hidden;  overflow: hidden;

  display: flex;  display: flex;

  align-items: center;  align-items: center;

  justify-content: center;  justify-content: center;

  /* Optimización de performance */  /* Optimización de performance */

  will-change: transform;  will-change: transform;

  transform: translate3d(0, 0, 0);  transform: translate3d(0, 0, 0);

}}



.hero-background {.hero-background {

  position: absolute;  position: absolute;

  top: 0;  top: 0;

  left: 0;  left: 0;

  width: 100%;  width: 100%;

  height: 100%; /* Imagen llena todo el contenedor */  height: 100%; /* Imagen llena todo el contenedor */

  background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/foto.png');  background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/foto.png');

  background-size: cover;  background-size: cover;

  background-position: center 60%; /* Foco en velas y piedras */  background-position: center 60%; /* Foco en velas y piedras */

  background-repeat: no-repeat;  background-repeat: no-repeat;

  background-attachment: fixed;  background-attachment: fixed;

  transform: translate3d(0, 0, 0);  transform: translate3d(0, 0, 0);

  z-index: 1;  z-index: 1;

}}



.hero-overlay {.hero-overlay {

  position: relative;  position: relative;

  z-index: 2;  z-index: 2;

  background: linear-gradient(  background: linear-gradient(

    135deg,     135deg, 

    rgba(255, 237, 230, 0.75) 0%,    /* Más transparencia - era 0.85 */    rgba(255, 237, 230, 0.75) 0%,    /* Más transparencia - era 0.85 */

    rgba(255, 205, 185, 0.65) 50%,   /* Más transparencia - era 0.75 */    rgba(255, 205, 185, 0.65) 50%,   /* Más transparencia - era 0.75 */

    rgba(255, 229, 216, 0.75) 100%   /* Más transparencia - era 0.85 */    rgba(255, 229, 216, 0.75) 100%   /* Más transparencia - era 0.85 */

  );  );

  border-radius: 25px;  border-radius: 25px;

  backdrop-filter: blur(10px);  backdrop-filter: blur(10px);

  padding: 40px;  padding: 40px;

  max-width: 800px;  max-width: 800px;

  margin: 0 20px;  margin: 0 20px;

  text-align: center;  text-align: center;

  box-shadow:   box-shadow: 

    8px 8px 16px rgba(170, 48, 0, 0.2),    8px 8px 16px rgba(170, 48, 0, 0.2),

    -8px -8px 16px rgba(255, 229, 216, 0.8);    -8px -8px 16px rgba(255, 229, 216, 0.8);

}}



.hero-content h1 {.hero-content h1 {

  color: #240A00;  color: #240A00;

  font-size: clamp(2.375rem, 4.75vw, 3.8rem); /* 5% menos que antes */  font-size: clamp(2.375rem, 4.75vw, 3.8rem); /* 5% menos que antes */

  font-weight: 700;  font-weight: 700;

  line-height: 1.2;  line-height: 1.2;

  margin-bottom: 20px;  margin-bottom: 20px;

  text-shadow: 1px 1px 2px rgba(255, 229, 216, 0.8);  text-shadow: 1px 1px 2px rgba(255, 229, 216, 0.8);

}}



.hero-content h2 {.hero-content h2 {

  color: #3B1400;  color: #3B1400;

  font-size: clamp(1.045rem, 2.375vw, 1.425rem); /* 5% menos que antes */  font-size: clamp(1.045rem, 2.375vw, 1.425rem); /* 5% menos que antes */

  font-weight: 400;  font-weight: 400;

  margin-bottom: 15px;  margin-bottom: 15px;

  letter-spacing: 0.5em;  letter-spacing: 0.5em;

}}



.hero-content p {.hero-content p {

  color: #7D2400;  color: #7D2400;

  font-size: clamp(0.855rem, 1.9vw, 1.045rem); /* 5% menos que antes */  font-size: clamp(0.855rem, 1.9vw, 1.045rem); /* 5% menos que antes */

  font-weight: 300;  font-weight: 300;

  margin-bottom: 30px;  margin-bottom: 30px;

  line-height: 1.6;  line-height: 1.6;

}}



.hero-ctas {.hero-ctas {

  display: flex;  display: flex;

  gap: 20px;  gap: 20px;

  justify-content: center;  justify-content: center;

  flex-wrap: wrap;  flex-wrap: wrap;

  margin-top: 30px;  margin-top: 30px;

}}



.cta-primary {.cta-primary {

  background: linear-gradient(145deg, #FF6C32, #D73D00);  background: linear-gradient(145deg, #FF6C32, #D73D00);

  color: #FFEDE6;  color: #FFEDE6;

  padding: 15px 30px;  padding: 15px 30px;

  border-radius: 15px;  border-radius: 15px;

  text-decoration: none;  text-decoration: none;

  font-weight: 700;  font-weight: 700;

  font-size: 16px;  font-size: 16px;

  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);

  box-shadow:   box-shadow: 

    8px 8px 16px rgba(170, 48, 0, 0.3),    8px 8px 16px rgba(170, 48, 0, 0.3),

    -8px -8px 16px rgba(255, 229, 216, 0.8);    -8px -8px 16px rgba(255, 229, 216, 0.8);

  transform: translateY(0);  transform: translateY(0);

}}



.cta-primary:hover {.cta-primary:hover {

  transform: translateY(-4px);  transform: translateY(-4px);

  box-shadow:   box-shadow: 

    12px 12px 24px rgba(170, 48, 0, 0.4),    12px 12px 24px rgba(170, 48, 0, 0.4),

    -12px -12px 24px rgba(255, 229, 216, 0.9);    -12px -12px 24px rgba(255, 229, 216, 0.9);

}}



.cta-secondary {.cta-secondary {

  background: #FFEDE6;  background: #FFEDE6;

  color: #240A00;  color: #240A00;

  padding: 15px 30px;  padding: 15px 30px;

  border: 2px solid #FF8C5F;  border: 2px solid #FF8C5F;

  border-radius: 15px;  border-radius: 15px;

  text-decoration: none;  text-decoration: none;

  font-weight: 600;  font-weight: 600;

  font-size: 16px;  font-size: 16px;

  transition: all 0.3s ease;  transition: all 0.3s ease;

  box-shadow:   box-shadow: 

    6px 6px 12px rgba(170, 48, 0, 0.2),    6px 6px 12px rgba(170, 48, 0, 0.2),

    -6px -6px 12px rgba(255, 229, 216, 0.9);    -6px -6px 12px rgba(255, 229, 216, 0.9);

}}



.cta-secondary:hover {.cta-secondary:hover {

  background: #FF8C5F;  background: #FF8C5F;

  color: #FFEDE6;  color: #FFEDE6;

  transform: translateY(-2px);  transform: translateY(-2px);

}}



/* Responsive: Desactiva parallax en móviles *//* Responsive: Desactiva parallax en móviles */

@media (max-width: 768px) {@media (max-width: 768px) {

  .hero-parallax {  .hero-parallax {

    height: 30vh;    height: 30vh;

    min-height: 250px;    min-height: 250px;

  }  }

    

  .hero-background {  .hero-background {

    background-attachment: scroll;    background-attachment: scroll;

    height: 100%;    height: 100%;

    background-position: center 35%; /* Mejor foco en móvil */    background-position: center 35%; /* Mejor foco en móvil */

  }  }

    

  .hero-overlay {  .hero-overlay {

    margin: 0 10px;    margin: 0 10px;

    padding: 25px 15px;    padding: 25px 15px;

    max-width: 90%;    max-width: 90%;

  }  }

    

  .hero-content h1 {  .hero-content h1 {

    font-size: clamp(1.71rem, 4.275vw, 2.375rem); /* 5% menos en móvil */    font-size: clamp(1.71rem, 4.275vw, 2.375rem); /* 5% menos en móvil */

  }  }

    

  .hero-content h2 {  .hero-content h2 {

    font-size: clamp(0.95rem, 2.09vw, 1.14rem); /* 5% menos en móvil */    font-size: clamp(0.95rem, 2.09vw, 1.14rem); /* 5% menos en móvil */

  }  }

    

  .hero-ctas {  .hero-ctas {

    flex-direction: column;    flex-direction: column;

    align-items: center;    align-items: center;

    gap: 15px;    gap: 15px;

  }  }

    

  .cta-primary, .cta-secondary {  .cta-primary, .cta-secondary {

    width: 100%;    width: 100%;

    max-width: 220px;    max-width: 220px;

    padding: 12px 25px;    padding: 12px 25px;

    font-size: 15px;    font-size: 15px;

  }  }

}}



/* Respeta preferencias de animación *//* Respeta preferencias de animación */

@media (prefers-reduced-motion: reduce) {@media (prefers-reduced-motion: reduce) {

  .hero-background {  .hero-background {

    background-attachment: scroll;    background-attachment: scroll;

    transform: none;    transform: none;

  }  }

    

  .cta-primary, .cta-secondary {  .cta-primary, .cta-secondary {

    transition: none;    transition: none;

  }  }

}}

</style></style>



<!-- Hero Section con Parallax --><!-- Hero Section con Parallax -->

<section class="hero-parallax"><section class="hero-parallax">

  <div class="hero-background"></div>  <div class="hero-background"></div>

  <div class="hero-overlay">  <div class="hero-overlay">

    <div class="hero-content">    <div class="hero-content">

      <h1>Masajes Profesionales para Hombres</h1>      <h1>Masajes Profesionales para Hombres</h1>

      <h2>Experiencias de relajación y bienestar</h2>      <h2>Experiencias de relajación y bienestar</h2>

      <p>Discreción, profesionalidad y resultados garantizados. 100% Discreto.</p>      <p>Discreción, profesionalidad y resultados garantizados. 100% Discreto.</p>

            

      <div class="hero-ctas">      <div class="hero-ctas">

        <a href="<?php echo home_url('/reservas'); ?>" class="cta-primary">Reservar cita</a>        <a href="<?php echo home_url('/reservas'); ?>" class="cta-primary">Reservar cita</a>

        <a href="<?php echo home_url('/servicios'); ?>" class="cta-secondary">Ver servicios</a>        <a href="<?php echo home_url('/servicios'); ?>" class="cta-secondary">Ver servicios</a>

      </div>      </div>

    </div>    </div>

  </div>  </div>

</section></section>



<!-- Contenido adicional eliminado para evitar duplicación --><!-- Contenido adicional eliminado para evitar duplicación -->



<?php get_footer(); // Carga footer.php <?php get_footer(); // Carga footer.php 

?>