<?php

/**
 * Configuración Segura de WordPress - Men's Core Therapy
 * Basado en Auditoría de Seguridad Crítica
 * 
 * IMPORTANTE: Reemplazar wp-config.php existente con esta versión
 * después de configurar variables de entorno
 */

// === CONFIGURACIÓN DE BASE DE DATOS ===
// Usar variables de entorno para credenciales sensibles
define('DB_NAME', $_ENV['DB_NAME'] ?? 'menscoretherapy_prod');
define('DB_USER', $_ENV['DB_USER'] ?? 'mt_user_secure');
define('DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? '');
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

// === CLAVES DE SEGURIDAD ÚNICAS ===
// GENERAR NUEVAS CLAVES EN: https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY',         'REEMPLAZAR_CON_CLAVE_UNICA_AUTH');
define('SECURE_AUTH_KEY',  'REEMPLAZAR_CON_CLAVE_UNICA_SECURE_AUTH');
define('LOGGED_IN_KEY',    'REEMPLAZAR_CON_CLAVE_UNICA_LOGGED_IN');
define('NONCE_KEY',        'REEMPLAZAR_CON_CLAVE_UNICA_NONCE');
define('AUTH_SALT',        'REEMPLAZAR_CON_SALT_UNICO_AUTH');
define('SECURE_AUTH_SALT', 'REEMPLAZAR_CON_SALT_UNICO_SECURE_AUTH');
define('LOGGED_IN_SALT',   'REEMPLAZAR_CON_SALT_UNICO_LOGGED_IN');
define('NONCE_SALT',       'REEMPLAZAR_CON_SALT_UNICO_NONCE');

// === CONFIGURACIONES DE SEGURIDAD ===
// Forzar HTTPS en admin y login
define('FORCE_SSL_ADMIN', true);
define('FORCE_SSL_LOGIN', true);

// URLs seguras
define('WP_HOME', 'https://menscoretherapy.free.nf/willmasaje_stagi/');
define('WP_SITEURL', 'https://menscoretherapy.free.nf/willmasaje_stagi/');

// Deshabilitar edición de archivos desde admin
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', false); // Permitir updates pero no edición

// Configuración de actualizaciones automáticas
define('WP_AUTO_UPDATE_CORE', 'minor'); // Solo actualizaciones menores
define('AUTOMATIC_UPDATER_DISABLED', false);

// === CONFIGURACIONES DE RENDIMIENTO ===
// Memoria y límites
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

// Caché
define('WP_CACHE', true);
define('COMPRESS_CSS', true);
define('COMPRESS_SCRIPTS', true);
define('CONCATENATE_SCRIPTS', false); // Deshabilitado para mejor control

// Optimización de base de datos
define('WP_POST_REVISIONS', 3);
define('AUTOSAVE_INTERVAL', 300); // 5 minutos
define('EMPTY_TRASH_DAYS', 7); // Vaciar papelera cada 7 días

// === CONFIGURACIONES DE DEBUG ===
// PRODUCCIÓN: Todas en false
// DESARROLLO: Habilitar según necesidad
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true); // Mantener logs habilitados
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', false);

// Log personalizado de seguridad
define('WP_DEBUG_LOG_SECURITY', true);
ini_set('log_errors', 1);
ini_set('error_log', ABSPATH . 'wp-content/debug.log');

// === CONFIGURACIONES DE SESIÓN ===
// Seguridad de cookies
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);

// === CONFIGURACIONES PERSONALIZADAS ===
// Límites de subida
@ini_set('upload_max_size', '64M');
@ini_set('post_max_size', '64M');
@ini_set('max_execution_time', '300');

// === TABLA PREFIX ===
$table_prefix = 'mt_wp_'; // Cambiar de wp_ por seguridad

// === CONFIGURACIÓN MULTISITE ===
// define('WP_ALLOW_MULTISITE', false);

// === CONFIGURACIONES ADICIONALES ===
// Deshabilitar XML-RPC si no se usa
define('XMLRPC_ENABLED', false);

// Configurar timezone
date_default_timezone_set('Europe/Madrid');

// === CARGA DE WORDPRESS ===
if (!defined('ABSPATH')) {
  define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';

// === FUNCIONES DE SEGURIDAD ADICIONALES ===
// Logging de eventos de seguridad
if (!function_exists('log_security_event')) {
  function log_security_event($event, $details, $severity = 'INFO')
  {
    if (defined('WP_DEBUG_LOG_SECURITY') && WP_DEBUG_LOG_SECURITY) {
      $timestamp = current_time('mysql');
      $user_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
      $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
      $user_id = function_exists('get_current_user_id') ? get_current_user_id() : 0;

      $log_entry = sprintf(
        "[%s] %s - %s | IP: %s | User: %d | UA: %s | Details: %s\n",
        $timestamp,
        $severity,
        $event,
        $user_ip,
        $user_id,
        substr($user_agent, 0, 100),
        $details
      );

      error_log($log_entry, 3, ABSPATH . 'wp-content/security.log');
    }
  }
}

// Hook para login fallido
if (!function_exists('wp_login_failed')) {
  add_action('wp_login_failed', function ($username) {
    log_security_event('LOGIN_FAILED', "Failed login attempt for user: $username", 'WARNING');
  });
}

// Hook para login exitoso
if (!function_exists('wp_login')) {
  add_action('wp_login', function ($user_login, $user) {
    log_security_event('LOGIN_SUCCESS', "Successful login for user: $user_login", 'INFO');
  }, 10, 2);
}
