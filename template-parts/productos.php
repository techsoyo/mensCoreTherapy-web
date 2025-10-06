<?php
if (!defined('ABSPATH')) exit;
?>

<!-- Hero Section con efecto parallax -->
<section class="productos-hero-section" style="
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  z-index: 10;
  background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/ns-productos.webp');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  background-repeat: no-repeat;
  overflow: hidden;
">
  <!-- Overlay sutil -->
  <div class="productos-hero-overlay" style="
    position: absolute;
    inset: 0;
    background: rgba(170, 48, 0, 0.25);
    backdrop-filter: blur(1px);
    z-index: 1;
  "></div>

  <!-- Contenedor de cards con scroll -->
  <div class="productos-cards-container" style="
    position: relative;
    z-index: 2;
    height: 100vh;
    overflow-y: auto;
    padding: 120px 2rem 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
  ">
    <!-- Título principal -->
    <div style="text-align: center; margin-bottom: 3rem;">
      <h1 style="
        color: #FFEDE6;
        font-family: 'Montserrat', sans-serif;
        font-weight: 100;
        font-size: clamp(2.5rem, 5vw, 4rem);
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
      ">Nuestros Productos</h1>
      <p style="
        color: #FFEDE6;
        font-family: 'Montserrat', sans-serif;
        font-weight: 100;
        font-style: italic;
        font-size: 1.2rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
      ">Productos premium para tu bienestar</p>
    </div>

    <!-- Grid de productos 4x6 -->
    <div class="productos-grid" style="
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.5rem;
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
    ">

      <!-- Aquí se cargarán los productos dinámicamente -->
      <?php
      // Obtener productos usando nuestro custom post type
      $productos_data = get_productos_data();

      // DEBUG: Mostrar qué está devolviendo la función
      if (current_user_can('administrator')) {
        echo '<!-- DEBUG: Productos data: ' . print_r($productos_data, true) . ' -->';
      }

      if ($productos_data) :
        foreach ($productos_data as $producto) :
      ?>

          <!-- Tarjeta de producto con efecto flip -->
          <div class="producto-flip-card" tabindex="0" role="button" aria-label="Ver detalles del producto <?php echo esc_attr($producto['nombre']); ?>">
            <div class="producto-flip-card-inner">
              <!-- Frente de la tarjeta -->
              <div class="producto-flip-card-front">
                <?php if (!empty($producto['imagen'])) : ?>
                  <img src="<?php echo esc_url($producto['imagen']); ?>"
                    alt="<?php echo esc_attr($producto['nombre']); ?>">
                <?php else : ?>
                  <!-- Icono alternativo si no hay imagen -->
                  <div class="icono-alternativo">
                    <i class="fa fa-<?php echo esc_attr($producto['icono']); ?>"></i>
                  </div>
                <?php endif; ?>
                <h3><?php echo esc_html($producto['nombre']); ?></h3>
                <?php if (!empty($producto['precio']) && $producto['precio'] !== '0.00') : ?>
                  <p class="precio">€<?php echo number_format((float)$producto['precio'], 2, ',', '.'); ?></p>
                <?php else : ?>
                  <p class="precio">Consultar precio</p>
                <?php endif; ?>
              </div>

              <!-- Dorso de la tarjeta -->
              <div class="producto-flip-card-back">
                <h3><?php echo esc_html($producto['nombre']); ?></h3>
                <p class="descripcion"><?php echo esc_html($producto['descripcion'] ?: 'Producto premium para tu bienestar y relajación.'); ?></p>

                <!-- Mostrar beneficios si existen -->
                <?php if (!empty($producto['beneficios']) && is_array($producto['beneficios'])) : ?>
                  <ul class="beneficios">
                    <?php foreach (array_slice($producto['beneficios'], 0, 3) as $beneficio) : ?>
                      <li><?php echo esc_html($beneficio); ?></li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>

                <?php if (!empty($producto['permalink']) && $producto['id'] > 0) : ?>
                  <button onclick="event.stopPropagation(); window.location.href='<?php echo esc_url($producto['permalink']); ?>'">
                    Ver Detalles
                  </button>
                <?php else : ?>
                  <button onclick="event.stopPropagation(); alert('Más información próximamente');">
                    Más Info
                  </button>
                <?php endif; ?>
              </div>
            </div>
          </div>

        <?php
        endforeach;
      else :
        ?>

        <!-- Mensaje cuando no hay productos -->
        <div style="
        grid-column: 1 / -1;
        text-align: center;
        padding: 3rem;
        color: #FFEDE6;
      ">
          <h3>No hay productos disponibles en este momento</h3>
          <p>Pronto tendremos nuevos productos para ti.</p>
        </div>

      <?php endif; ?>

    </div>
  </div>
</section>

<!-- Estilos CSS para el efecto flip y productos -->
<style>
  /* Estilos base para el flip effect */
  .producto-flip-card {
    perspective: 1000px;
    height: 300px;
    cursor: pointer;
    transition: transform 0.3s ease;
  }

  .producto-flip-card:hover {
    transform: translateY(-5px);
  }

  .producto-flip-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    transition: transform 0.8s ease-in-out;
    transform-style: preserve-3d;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
  }

  .producto-flip-card.flipped .producto-flip-card-inner {
    transform: rotateY(180deg);
  }

  .producto-flip-card-front,
  .producto-flip-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    border-radius: 15px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 1.5rem;
    box-sizing: border-box;
  }

  .producto-flip-card-front {
    background: linear-gradient(135deg, #FFEDE6 0%, #F5E6D3 100%);
  }

  .producto-flip-card-back {
    background: linear-gradient(135deg, #AA3000 0%, #8B4513 100%);
    color: #FFEDE6;
    transform: rotateY(180deg);
  }

  .producto-flip-card img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 1rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .producto-flip-card .icono-alternativo {
    width: 80px;
    height: 80px;
    background: #AA3000;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    color: #FFEDE6;
    font-size: 2rem;
  }

  .producto-flip-card h3 {
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    text-align: center;
  }

  .producto-flip-card-front h3 {
    color: #AA3000;
  }

  .producto-flip-card-back h3 {
    color: #FFEDE6;
    margin-bottom: 1rem;
  }

  .producto-flip-card .precio {
    color: #8B4513;
    font-weight: 700;
    font-size: 1.2rem;
    margin: 0;
  }

  .producto-flip-card .descripcion {
    font-size: 0.9rem;
    text-align: center;
    line-height: 1.4;
    margin-bottom: 1rem;
  }

  .producto-flip-card .beneficios {
    font-size: 0.8rem;
    text-align: left;
    margin-bottom: 1rem;
    padding-left: 1rem;
  }

  .producto-flip-card button {
    background: #FFEDE6;
    color: #AA3000;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .producto-flip-card button:hover {
    background: #F5E6D3 !important;
    transform: scale(1.05);
  }

  @media (max-width: 768px) {
    .productos-grid {
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)) !important;
      gap: 1rem !important;
    }

    .productos-cards-container {
      padding: 100px 1rem 1rem !important;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad de flip cards
    document.querySelectorAll('.producto-flip-card').forEach(function(card) {
      // Accesibilidad con teclado
      card.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          this.classList.toggle('flipped');
        }
      });

      // Clic para voltear tarjeta
      card.addEventListener('click', function(e) {
        // Evitar que se active si se hizo clic en el botón
        if (e.target.tagName === 'BUTTON') return;
        this.classList.toggle('flipped');
      });
    });

    // Debug: Verificar que el JavaScript se ejecuta
    console.log('JavaScript de productos cargado, cards encontradas:', document.querySelectorAll('.producto-flip-card').length);
  });
</script>