/**
 * Script de verificación para la página de servicios con fondo torso.jpg
 * Ejecutar en la consola del navegador cuando la página de servicios esté cargada
 */

// Información de la página actual
console.log('=== INFORMACIÓN DE LA PÁGINA ===');
console.log('URL actual:', window.location.href);
console.log('Título de la página:', document.title);
console.log('Body classes:', document.body.className);

// Verificar que la variable CSS esté definida
const rootStyles = getComputedStyle(document.documentElement);
const parallaxImage = rootStyles.getPropertyValue('--parallax-image-url');

console.log('=== VERIFICACIÓN PÁGINA DE SERVICIOS ===');
console.log('Variable --parallax-image-url:', parallaxImage);

// Verificar que la sección .mm-servicios existe
const serviciosSection = document.querySelector('.mm-servicios');
console.log('Sección .mm-servicios encontrada:', !!serviciosSection);

if (serviciosSection) {
    console.log('Contenido de .mm-servicios:', serviciosSection.innerHTML.substring(0, 200) + '...');
}

// Verificar que el CSS parallax.css se está cargando
const parallaxLinks = Array.from(document.querySelectorAll('link[rel="stylesheet"]'))
  .filter(link => link.href.includes('parallax.css'));
console.log('CSS parallax.css cargado:', parallaxLinks.length > 0);

if (parallaxLinks.length > 0) {
    console.log('URL del CSS parallax:', parallaxLinks[0].href);
}

// Verificar que el JS parallax.js se está cargando
const parallaxScripts = Array.from(document.querySelectorAll('script[src]'))
  .filter(script => script.src.includes('parallax.js'));
console.log('JS parallax.js cargado:', parallaxScripts.length > 0);

if (parallaxScripts.length > 0) {
    console.log('URL del JS parallax:', parallaxScripts[0].src);
}

// Verificar que la imagen existe
if (parallaxImage) {
  const imageUrl = parallaxImage.replace('url(', '').replace(')', '').replace(/['"]/g, '');
  console.log('URL de la imagen:', imageUrl);

  // Intentar cargar la imagen
  const img = new Image();
  img.onload = () => console.log('✅ Imagen torso.jpg cargada correctamente');
  img.onerror = () => console.log('❌ Error al cargar la imagen torso.jpg');
  img.src = imageUrl;
} else {
    console.log('❌ Variable --parallax-image-url no definida');
}

console.log('=== FIN VERIFICACIÓN ===');