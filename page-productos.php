<?php

/**
 * Template Name: Productos
 * Página de productos con efecto flip y diseño mejorado
 */

get_header();
?>

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
      "><?php _e('Nuestros Productos', 'masajista-masculino'); ?></h1>
      <p style="
        color: #FFEDE6;
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        font-style: italic;
        font-size: 1.2rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
      "><?php _e('Productos premium para tu bienestar', 'masajista-masculino'); ?></p>
    </div>

    <!-- Grid de productos responsivo -->
    <div class="productos-grid" style="
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
    ">

      <?php
      // Obtener productos de la base de datos
      $productos = get_productos_data();

      if (!empty($productos)) :
        // Mostrar cada producto
        foreach ($productos as $producto) :
          // Mapear los datos de la BD al formato esperado por el template
          $producto_data = array(
            'id' => $producto['id'],
            'titulo' => $producto['nombre'],
            'descripcion_corta' => $producto['descripcion'],
            'descripcion_larga' => $producto['descripcion'], // Usar la misma descripción si no hay una larga
            'precio' => '€' . number_format(floatval($producto['precio']), 2, ',', '.'),
            'imagen' => $producto['imagen'] ?: get_template_directory_uri() . '/assets/images/logo.webp',
            'icono' => 'fa-' . ($producto['icono'] ?: 'star'),
            'beneficios' => is_array($producto['beneficios']) ? $producto['beneficios'] : array('Producto premium', 'Alta calidad', 'Garantía incluida')
          );
      ?>
                <article class="product-card flip-card" onclick="this.classList.toggle('flipped')" data-producto-id="<?php echo $producto_data['id']; ?>" style="perspective: 1000px; cursor: pointer; height: 400px;">
            <div class="flip-card-inner" style="position: relative; width: 100%; height: 100%; text-align: center; transition: transform 0.8s; transform-style: preserve-3d;">              <!-- Cara frontal -->
              <div class="flip-card-front" style="position: absolute; width: 100%; height: 100%; backface-visibility: hidden; background: rgba(255, 255, 255, 0.95); border-radius: 1rem; padding: 1.5rem; box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.2); backdrop-filter: blur(15px); color: #111; display: flex; flex-direction: column;">
                <div class="producto-imagen">
                  <img src="<?php echo esc_url($producto_data['imagen']); ?>"
                    alt="<?php echo esc_attr($producto_data['titulo']); ?>"
                    onerror="this.src='<?php echo get_template_directory_uri(); ?>/assets/images/logo.webp'">
                  <div class="producto-precio-badge"><?php echo esc_html($producto_data['precio']); ?></div>
                </div>

                <div class="producto-info">
                  <h3 class="producto-titulo"><?php echo esc_html($producto_data['titulo']); ?></h3>
                  <p class="producto-descripcion"><?php echo esc_html($producto_data['descripcion_corta']); ?></p>

                  <div class="producto-cta">
                    <span class="flip-hint"><?php _e('Clic para más detalles', 'masajista-masculino'); ?></span>
                  </div>
                </div>
              </div>

              <!-- Cara trasera -->
              <div class="flip-card-back" style="position: absolute; width: 100%; height: 100%; backface-visibility: hidden; background: linear-gradient(135deg, #AA3000, #cc4000); border-radius: 1rem; padding: 1.5rem; box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.2); backdrop-filter: blur(15px); color: white; transform: rotateY(180deg); display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <h3 class="producto-titulo-back"><?php echo esc_html($producto_data['titulo']); ?></h3>

                <div class="producto-precio-destacado"><?php echo esc_html($producto_data['precio']); ?></div>

                <p class="producto-descripcion-larga"><?php echo esc_html($producto_data['descripcion_larga']); ?></p>

                <ul class="producto-beneficios">
                  <?php foreach ($producto_data['beneficios'] as $beneficio) : ?>
                    <li>✓ <?php echo esc_html($beneficio); ?></li>
                  <?php endforeach; ?>
                </ul>

                <div class="producto-acciones">
                  <button class="btn-comprar" onclick="comprarProducto(<?php echo $producto_data['id']; ?>)">
                    <?php _e('Comprar Ahora', 'masajista-masculino'); ?>
                  </button>
                  <span class="flip-hint-back"><?php _e('Clic para volver', 'masajista-masculino'); ?></span>
                </div>
              </div>

            </div>
          </article>
        <?php
        endforeach;
      else :
        ?>
        <div style="grid-column: 1 / -1; text-align: center; color: #FFEDE6; padding: 2rem;">
          <h3><?php _e('No hay productos disponibles', 'masajista-masculino'); ?></h3>
          <p><?php _e('Pronto tendremos nuevos productos para ti.', 'masajista-masculino'); ?></p>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>

<style>
  /* ===== CSS FLIP COPIADO DE SERVICIOS QUE FUNCIONA ===== */
  .flip-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    transition: transform 0.8s;
    transform-style: preserve-3d;
    transform: rotateY(0deg);
  }

  .product-card.flipped .flip-card-inner {
    transform: rotateY(180deg);
  }

  .flip-card-front {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    transform: rotateY(0deg);
  }

  .flip-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    transform: rotateY(180deg);
  }

  /* DEBUG: Borde para verificar funcionamiento */
  .product-card.flipped {
    border: 2px solid green !important;
  }

  .producto-flip-front,
  .producto-flip-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden !important;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    overflow: hidden;
  }

  /* ===== CARA FRONTAL ===== */
  .producto-flip-front {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    display: flex;
    flex-direction: column;
  }

  .producto-imagen {
    position: relative;
    height: 60%;
    overflow: hidden;
  }

  .producto-imagen img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }

  .producto-flip-card:hover .producto-imagen img {
    transform: scale(1.05);
  }

  .producto-precio-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #FF6C32;
    color: white;
    padding: 8px 12px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.9rem;
    box-shadow: 0 4px 12px rgba(255, 108, 50, 0.4);
  }

  .producto-info {
    padding: 1.5rem;
    height: 40%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .producto-titulo {
    font-size: 1.1rem;
    font-weight: 600;
    color: #240A00;
    margin-bottom: 0.5rem;
    line-height: 1.3;
  }

  .producto-descripcion {
    font-size: 0.9rem;
    color: #7D2400;
    line-height: 1.4;
    flex-grow: 1;
  }

  .flip-hint {
    font-size: 0.8rem;
    color: #AA3000;
    font-style: italic;
    opacity: 0.8;
  }

  /* ===== CARA TRASERA ===== */
  .producto-flip-back {
    background: linear-gradient(135deg, #FF6C32, #D73D00);
    color: white;
    transform: rotateY(180deg);
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .producto-titulo-back {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: white;
  }

  .producto-precio-destacado {
    font-size: 2rem;
    font-weight: bold;
    text-align: center;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
  }

  .producto-descripcion-larga {
    font-size: 0.85rem;
    line-height: 1.4;
    margin-bottom: 1rem;
    opacity: 0.95;
  }

  .producto-beneficios {
    list-style: none;
    padding: 0;
    margin: 0 0 1rem 0;
    font-size: 0.8rem;
  }

  .producto-beneficios li {
    margin: 0.3rem 0;
    opacity: 0.9;
  }

  .producto-acciones {
    text-align: center;
  }

  .btn-comprar {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.5);
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 0.5rem;
    display: block;
    width: 100%;
  }

  .btn-comprar:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
  }

  .flip-hint-back {
    font-size: 0.75rem;
    opacity: 0.7;
    font-style: italic;
  }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 768px) {
    .productos-grid {
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
    }

    .producto-flip-card {
      height: 350px;
    }

    .producto-info {
      padding: 1rem;
    }

    .producto-flip-back {
      padding: 1rem;
    }
  }

  @media (max-width: 480px) {
    .productos-cards-container {
      padding: 100px 1rem 2rem;
    }

    .productos-grid {
      grid-template-columns: 1fr;
    }

    .producto-flip-card {
      height: 320px;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // DEBUG: Verificar si el script se ejecuta
    console.log('🔄 Script de flip cards cargado');
    
    // Manejar flip de tarjetas
    const flipCards = document.querySelectorAll('.producto-flip-card');
    console.log('📦 Cards encontradas:', flipCards.length);

    flipCards.forEach(card => {
      // Clic para rotar
      card.addEventListener('click', function(e) {
        console.log('🖱️ Click detectado en card', e.target);
        if (!e.target.classList.contains('btn-comprar')) {
          console.log('🔄 Aplicando flip...');
          this.classList.toggle('flipped');
          console.log('✅ Clase flipped:', this.classList.contains('flipped'));
          console.log('🎨 Classes actuales:', this.className);
        } else {
          console.log('⏭️ Click en botón comprar, no aplicar flip');
        }
      });

      // Accesibilidad con teclado
      card.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          this.classList.toggle('flipped');
        }
      });
    });
  });

  // Función para manejar compra
  function comprarProducto(id) {
    // Aquí puedes integrar con tu sistema de e-commerce
    alert('Funcionalidad de compra para producto ID: ' + id + '\n\nPróximamente disponible.');

    // Ejemplo de integración con WhatsApp
    // const mensaje = encodeURIComponent(`Hola, estoy interesado en el producto ID: ${id}`);
    // window.open(`https://wa.me/TUNUMERO?text=${mensaje}`, '_blank');
  }
</script>

<?php get_footer(); ?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            