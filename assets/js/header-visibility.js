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
        header.style.zIndex = '9998';
        
        // Calcular la altura del header y ajustar/crear el espacio compensatorio
        const headerHeight = header.offsetHeight;
        let spacer = document.getElementById('header-spacer') || document.querySelector('div[style*="height: 85px"]');
        
        if (spacer) {
            spacer.style.height = headerHeight + 'px';
            console.log('Espacio compensatorio ajustado a: ' + headerHeight + 'px');
        } else {
            // Crear un nuevo espaciador si no existe
            const newSpacer = document.createElement('div');
            newSpacer.id = 'header-spacer';
            newSpacer.style.height = headerHeight + 'px';
            document.body.insertBefore(newSpacer, document.body.firstChild.nextSibling);
            console.log('Nuevo espacio compensatorio creado: ' + headerHeight + 'px');
        }
    } else {
        // No hacemos throw, solo un log informativo (mantener consola limpia en producción)
        console.info('header-visibility: no se encontró un header conocido (auto-header/site-header).');
    }
    
    // Ajustar al cambiar el tamaño de la ventana
    window.addEventListener('resize', function() {
        const hdr = document.getElementById('auto-header') || document.getElementById('site-header') || document.querySelector('header');
        if (hdr) {
            const headerHeight = hdr.offsetHeight;
            const spacer = document.getElementById('header-spacer') || document.querySelector('div[style*="height"]');
            
            if (spacer) {
                spacer.style.height = headerHeight + 'px';
            }
        }
    });
});