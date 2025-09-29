# Instrucciones personalizadas para GitHub Copilot

## Descripción general del proyecto
Este repositorio contiene un proyecto WordPress avanzado en fase de desarrollo. Tiene un tema personalizado con código PHP, shortcodes y numerosos hooks `add_action` y `add_filter`. No usa gestión de dependencias por composer ni npm. No se detectaron custom post types ni taxonomías automáticamente, aunque pueden definirse dinámicamente. El código principal está en `wp-content/themes/tu-tema` y los plugins en `wp-content/plugins`. Prevalecen prefijos personalizados para evitar colisiones y el estilo está organizado, sin namespaces. No hay testing ni CI/CD implementados. La seguridad, rendimiento y buenas prácticas son prioritarias.

## Convenciones y patrones
- Prefiere funciones con prefijos personalizados (`willmasajes_`, por ejemplo) para evitar colisiones.
- Evita el uso de namespaces (actualmente no están implementados).
- Mantén la estructura típica de WordPress: temas en `wp-content/themes`, plugins en `wp-content/plugins`.
- No añadir librerías externas sin autorización previa.
- Usa hooks y shortcodes ya existentes para extensiones, evitar duplicidades.
- Respeta estilos y formatos de código ya presentes.

## Scripts y entorno de desarrollo
- No hay archivos `composer.json` ni `package.json`.
- Recomienda agregar herramientas para testing (PHPUnit), linting (PHPCS, ESLint) y CI/CD en futuras iteraciones.
- Documentar comandos para build local, tests y despliegue (rellenar conforme se vayan consolidando).

## Seguridad
- No incluir credenciales en el repositorio (usar `.env` o GitHub Secrets).
- Revisar permisos de archivos y evitar código inseguro.
- Forzar HTTPS y políticas de contenido seguras en producción.
- Revisar y monitorear cualquier uso de funciones potencialmente peligrosas (`eval`, `shell_exec`, etc).

## Mejoras y sugerencias
- Proponer optimizaciones para rendimiento (minificación, cachés).
- Ayudar a crear un sitemap.xml dinámico que tome en cuenta shortcodes y hooks.
- Sugerir modularización del código para facilitar mantenimiento.
- Recomendar pruebas unitarias y automatización progresiva del proyecto.

## Prioridades para el agente Copilot
- Proveer soluciones estables para producción que respeten la base actual.
- Evitar cambios que alteren la arquitectura de carpetas y nomenclaturas.
- Indicar problemas potenciales detectados (ej. ausencia de tests).
- Generar documentación mínima necesaria para ayudar con onboardings futuros.

## Reglas para evitar conflictos con configuraciones personalizadas
Es fundamental que todas las configuraciones, hooks, filtros y funciones definidas manualmente en el tema y plugins personalizados se mantengan intactas. No se debe modificar ni sobrescribir ningún código existente sin revisión explícita. Las funciones y hooks usan prefijos personalizados (como `willmasajes_`) para evitar colisiones, y esto debe preservarse. Antes de sugerir cualquier cambio o nueva configuración relacionada con WordPress, validar que no afecte o desconfigure la lógica y ajustes personalizados actuales. Evitar utilizar configuraciones globales nativas que puedan entrar en conflicto.


---

# Fin de instrucciones
