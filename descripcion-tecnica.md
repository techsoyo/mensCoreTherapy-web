# Descripción Técnica del Problema: Espacio en Blanco Debajo del Footer

## Resumen Ejecutivo

En el sitio web de masajista-masculino (WordPress + Elementor), existe un espacio en blanco persistente debajo del footer que aparece **únicamente en la página de inicio (front-page.php)**. Este espacio no se presenta en otras páginas como Servicios, Blog o Contacto (donde es menor o inexistente). Se ha intentado resolver con CSS overrides sin éxito, sospechando conflicto con Elementor o wp_footer().

## Contexto Técnico

- **Tema**: masajista-masculino (custom theme)
- **Plugins**: Elementor (builder principal), posiblemente otros como optimizadores
- **Estructura**: Header fijo, hero con parallax, footer horizontal grid
- **CSS**: Variables CSS para paleta neomórfica, overrides para Elementor
- **Problema Específico**: Espacio ~50-100px debajo del footer en home, variable por página

## Análisis del Problema

### 1. Comportamiento Observado

- **Página Home**: Espacio presente (mayor)
- **Página Servicios**: Sin espacio
- **Página Blog**: Espacio menor
- **Página Contacto/Reserva**: Espacio presente (similar a home)

### 2. Posibles Causas Identificadas

#### a) Elementor Sections

- Elementor añade `.elementor-section` con `margin-bottom: 20px` por defecto
- Overrides aplicados: `.elementor-section { margin-bottom: 0 !important }` en style.css
- Posible: Secciones ocultas o dinámicas en home añadiendo márgenes

#### b) wp_footer() Hooks

- `wp_footer()` inyecta scripts/CSS de plugins al final del body
- Posible: Plugin añade elementos con altura/márgenes invisibles
- Diagnóstico: Comentar `wp_footer()` temporalmente para verificar

#### c) CSS Global de Tema

- `body { margin-bottom: 0 !important }` aplicado en front-page.php
- Posible: Contenedores `.site-main`, `.entry-content` o `.wp-site-blocks` con padding residual
- Elementor overrides en style.css: `margin: 20px 0 !important`

#### d) Admin Bar WordPress

- Código en header.php: `top: 2vh` (no 0) para compensar admin bar
- Posible: Márgenes residuales cuando logueado
- CSS aplicado: `body.admin-bar { margin-top: 0 !important }`

#### e) Estructura de Página Específica

- front-page.php usa hero parallax + get_footer()
- Posible: Duplicación de contenedores o estilos inline conflictivos
- CSS inline en front-page.php para overrides

### 3. Intentos de Solución Previos

- CSS overrides en style.css y front-page.php
- Resets globales: `html, body, #page, .site-main { margin-bottom: 0 !important }`
- Overrides Elementor: `.elementor-section { margin-bottom: 0 !important }`
- Diagnóstico inspector: Identificar regla CSS causante (margin/padding inferior)

### 4. Archivos Relevantes Incluidos

- **front-page.php**: Página problemática con hero y CSS inline
- **footer.php**: Footer con grid horizontal y wp_footer()
- **header.php**: Header fijo con compensación admin bar
- **functions.php**: Enqueue scripts, customizer, page seeding
- **style.css**: Variables CSS, overrides globales y Elementor
- **neomorphic-effects.css**: Efectos visuales (posible interferencia)
- **main.css**: Layout base, admin bar handling
- **readme.md**: Paleta de colores y contexto

## Pasos para Replicar el Problema

1. Activar tema masajista-masculino
2. Instalar/activar Elementor
3. Visitar página home (/)
4. Inspeccionar footer con F12 > Elements > Computed
5. Verificar espacio debajo del último elemento

## Requisitos para la Solución

- Eliminar espacio sin afectar otras páginas
- Mantener compatibilidad con Elementor
- Solución CSS/JS preferida sobre modificaciones estructurales
- Verificar en móvil/desktop
- Asegurar no rotura de parallax o neomorfismo

## Información Adicional

- WordPress versión: [especificar]
- Elementor versión: [especificar]
- Navegador: Chrome/Firefox/Edge
- Dispositivo: Desktop/Móvil
- Plugins activos: [lista completa]

## Contacto

Para consultas: [tu email/contacto]
