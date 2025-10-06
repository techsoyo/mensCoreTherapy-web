/**
 * Script para asegurar la visibilidad del header en todas las páginas
 * Este script garantiza que el header permanezca visible y correctamente posicionado
 */
document.addEventListener('DOMContentLoaded', function() {
    // Intentamos localizar el header por varios IDs conocidos para evitar errores
    let header = document.getElementById('auto-header');
    if (!header) header = document.getElementById('site-header');
    if (!header) header = document.querySelector('header');
    
    if (header) {
        // Asegurar que el header tenga los estilos correctos
        header.style.position = 'fixed';
        header.style.top = '0';
        header.style.left = '0';
        header.style.width = '100%';
        header.style.zIndex = '9999';
        
        console.log('Header fijo aplicado correctamente');
    } else {
        // No hacemos throw, solo un log informativo (mantener consola limpia en producción)
        console.info('header-visibility: no se encontró un header conocido (auto-header/site-header).');
    }
    
    // Remover cualquier espaciador existente que pueda haber sido creado anteriormente
    const existingSpacer = document.getElementById('header-spacer');
    if (existingSpacer) {
        existingSpacer.remove();
        console.log('Espaciador eliminado');
    }
});