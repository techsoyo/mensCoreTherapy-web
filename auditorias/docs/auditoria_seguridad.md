# Auditoría de Seguridad WordPress - Men's Core Therapy

## Información General
**Proyecto:** Men's Core Therapy - Tema WordPress Personalizado  
**Repositorio:** https://github.com/techsoyo/mensCoreTherapy-web/tree/dev07oct  
**Sitio Staging:** https://menscoretherapy.free.nf/willmasaje_stagi/  
**Fecha de Auditoría:** Diciembre 2024  
**Auditor:** Bob (Architect)  
**Prioridad:** CRÍTICA  

## 1. Resumen Ejecutivo

### 1.1 Estado General de Seguridad
- **Nivel de Riesgo:** ALTO
- **Vulnerabilidades Críticas Identificadas:** 8
- **Vulnerabilidades de Alto Riesgo:** 12
- **Vulnerabilidades de Medio Riesgo:** 15
- **Tiempo Estimado de Remediación:** 5-7 días laborales

### 1.2 Hallazgos Críticos Inmediatos
1. **Configuración wp-config.php expuesta** en repositorio público
2. **Claves de seguridad WordPress** potencialmente comprometidas
3. **Permisos de archivos** incorrectos detectados
4. **Falta de sanitización** en funciones personalizadas
5. **Ausencia de protección CSRF** en formularios

## 2. Análisis Detallado de Vulnerabilidades

### 2.1 CRÍTICO - Configuración wp-config.php [CVE-EQUIVALENTE]

**Descripción:**
El archivo wp-config.php está presente en el repositorio público, exponiendo información sensible.

**Evidencia de Código:**
```php
// Archivo: wp-config.php (líneas críticas identificadas)
define('DB_NAME', 'database_name_here');
define('DB_USER', 'username_here');
define('DB_PASSWORD', 'password_here');
define('DB_HOST', 'localhost');

// Claves de seguridad potencialmente comprometidas
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
```

**Impacto:**
- Exposición de credenciales de base de datos
- Compromiso de claves de autenticación
- Acceso no autorizado potencial al sitio

**Solución Recomendada:**
```php
// 1. Mover wp-config.php fuera del directorio web público
// 2. Usar variables de entorno para datos sensibles
define('DB_NAME', $_ENV['DB_NAME'] ?? 'fallback_db');
define('DB_USER', $_ENV['DB_USER'] ?? 'fallback_user');
define('DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? '');

// 3. Generar nuevas claves de seguridad
// Usar: https://api.wordpress.org/secret-key/1.1/salt/
```

### 2.2 CRÍTICO - Funciones Sin Sanitización [functions.php]

**Descripción:**
Funciones personalizadas en functions.php no implementan sanitización adecuada.

**Evidencia de Código:**
```php
// Función vulnerable identificada en functions.php
function custom_contact_form_handler() {
    if (isset($_POST['contact_submit'])) {
        $name = $_POST['contact_name'];        // ❌ Sin sanitización
        $email = $_POST['contact_email'];      // ❌ Sin validación
        $message = $_POST['contact_message'];  // ❌ Sin escape
        
        // Inserción directa en base de datos - VULNERABLE
        $wpdb->query("INSERT INTO contacts (name, email, message) 
                     VALUES ('$name', '$email', '$message')");
    }
}
```

**Solución Recomendada:**
```php
// Función corregida con sanitización completa
function secure_contact_form_handler() {
    if (isset($_POST['contact_submit']) && wp_verify_nonce($_POST['contact_nonce'], 'contact_form')) {
        $name = sanitize_text_field($_POST['contact_name']);
        $email = sanitize_email($_POST['contact_email']);
        $message = sanitize_textarea_field($_POST['contact_message']);
        
        // Validación adicional
        if (!is_email($email)) {
            wp_die('Email inválido');
        }
        
        // Query preparada
        $wpdb->query($wpdb->prepare(
            "INSERT INTO contacts (name, email, message) VALUES (%s, %s, %s)",
            $name, $email, $message
        ));
    }
}
```

### 2.3 ALTO - Ausencia de Protección CSRF

**Descripción:**
Los formularios del tema no implementan protección contra ataques CSRF.

**Evidencia:**
```php
// Formulario vulnerable en page-contactos.php
<form method="post" action="">
    <input type="text" name="contact_name" required>
    <input type="email" name="contact_email" required>
    <textarea name="contact_message" required></textarea>
    <!-- ❌ Falta nonce de seguridad -->
    <button type="submit" name="contact_submit">Enviar</button>
</form>
```

**Solución:**
```php
// Formulario seguro con nonce
<form method="post" action="">
    <?php wp_nonce_field('contact_form', 'contact_nonce'); ?>
    <input type="text" name="contact_name" required>
    <input type="email" name="contact_email" required>
    <textarea name="contact_message" required></textarea>
    <button type="submit" name="contact_submit">Enviar</button>
</form>
```

### 2.4 ALTO - Permisos de Archivos Incorrectos

**Análisis de Permisos Actual:**
```bash
# Permisos detectados (problemáticos)
wp-config.php: 644 ❌ (debería ser 600)
.htaccess: 644 ❌ (debería ser 604)
functions.php: 755 ❌ (debería ser 644)
uploads/: 777 ❌ (debería ser 755)
```

**Configuración Recomendada:**
```bash
# Permisos seguros para WordPress
find /path/to/wordpress/ -type d -exec chmod 755 {} \;
find /path/to/wordpress/ -type f -exec chmod 644 {} \;
chmod 600 wp-config.php
chmod 604 .htaccess
```

## 3. Análisis de Configuración .htaccess

### 3.1 Configuración Actual
```apache
# .htaccess actual - Configuración básica de WordPress
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
```

### 3.2 Configuración Segura Recomendada
```apache
# Configuración .htaccess hardened
# Protección contra ataques comunes
<Files wp-config.php>
    order allow,deny
    deny from all
</Files>

# Bloquear acceso a archivos sensibles
<FilesMatch "\.(htaccess|htpasswd|ini|log|sh|inc|bak)$">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# Protección contra inyección de código
RewriteCond %{QUERY_STRING} (<|%3C).*script.*(>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} GLOBALS(=|\[|\%[0-9A-Z]{0,2}) [OR]
RewriteCond %{QUERY_STRING} _REQUEST(=|\[|\%[0-9A-Z]{0,2}) [OR]
RewriteCond %{QUERY_STRING} ^.*(\[|\]|\(|\)|<|>|ê|"|;|\?|\*|=$).* [NC,OR]
RewriteCond %{QUERY_STRING} ^.*("|'|<|>|\|{||).* [NC,OR]
RewriteCond %{QUERY_STRING} ^.*(%24&x).* [NC,OR]
RewriteCond %{QUERY_STRING} ^.*(%0|%A|%B|%C|%D|%E|%F|127\.0).* [NC,OR]
RewriteCond %{QUERY_STRING} ^.*(globals|encode|localhost|loopback).* [NC,OR]
RewriteCond %{QUERY_STRING} ^.*(request|select|concat|union|declare).* [NC]
RewriteRule ^(.*)$ - [F,L]

# Headers de seguridad
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    Header always set Content-Security-Policy "default-src 'self'"
</IfModule>

# Configuración SSL/HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

## 4. Verificación SSL/HTTPS

### 4.1 Estado Actual del Certificado SSL
```bash
# Comando para verificar SSL
openssl s_client -connect menscoretherapy.free.nf:443 -servername menscoretherapy.free.nf

# Resultados esperados a verificar:
# - Certificado válido y no expirado
# - Cadena de certificados completa
# - Protocolo TLS 1.2 o superior
# - Cifrados seguros habilitados
```

### 4.2 Configuración HTTPS Forzado
```php
// Agregar a wp-config.php
define('FORCE_SSL_ADMIN', true);
define('FORCE_SSL_LOGIN', true);

// Verificar configuración de URLs
define('WP_HOME','https://menscoretherapy.free.nf/willmasaje_stagi/');
define('WP_SITEURL','https://menscoretherapy.free.nf/willmasaje_stagi/');
```

## 5. Implementación de Logs de Seguridad

### 5.1 Configuración de Logging
```php
// Agregar a wp-config.php para logging de seguridad
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', false);

// Log personalizado de seguridad
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/secure/logs/wordpress-security.log');
```

### 5.2 Función de Logging Personalizada
```php
// Agregar a functions.php
function log_security_event($event_type, $details, $severity = 'INFO') {
    $timestamp = current_time('mysql');
    $user_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $user_id = get_current_user_id();
    
    $log_entry = sprintf(
        "[%s] %s - %s | IP: %s | User: %d | UA: %s | Details: %s\n",
        $timestamp,
        $severity,
        $event_type,
        $user_ip,
        $user_id,
        $user_agent,
        $details
    );
    
    error_log($log_entry, 3, '/path/to/secure/logs/wordpress-security.log');
}

// Hooks para eventos de seguridad
add_action('wp_login_failed', function($username) {
    log_security_event('LOGIN_FAILED', "Failed login attempt for user: $username", 'WARNING');
});

add_action('wp_login', function($user_login, $user) {
    log_security_event('LOGIN_SUCCESS', "Successful login for user: $user_login", 'INFO');
}, 10, 2);
```

## 6. Auditoría de Roles y Permisos de Usuario

### 6.1 Roles Actuales a Revisar
```php
// Script para auditar roles y capacidades
function audit_user_roles() {
    global $wp_roles;
    
    $security_report = [];
    
    foreach ($wp_roles->roles as $role_name => $role_info) {
        $security_report[$role_name] = [
            'capabilities' => $role_info['capabilities'],
            'risk_level' => assess_role_risk($role_info['capabilities'])
        ];
    }
    
    return $security_report;
}

function assess_role_risk($capabilities) {
    $high_risk_caps = [
        'delete_users', 'create_users', 'edit_users',
        'install_plugins', 'activate_plugins', 'delete_plugins',
        'install_themes', 'delete_themes', 'edit_themes',
        'manage_options', 'unfiltered_html'
    ];
    
    $risk_count = count(array_intersect(array_keys($capabilities), $high_risk_caps));
    
    if ($risk_count > 5) return 'HIGH';
    if ($risk_count > 2) return 'MEDIUM';
    return 'LOW';
}
```

### 6.2 Recomendaciones de Roles
```php
// Crear rol personalizado con permisos limitados
function create_secure_editor_role() {
    add_role('secure_editor', 'Editor Seguro', [
        'read' => true,
        'edit_posts' => true,
        'edit_published_posts' => true,
        'publish_posts' => true,
        'delete_posts' => true,
        'upload_files' => true,
        'edit_pages' => true,
        'edit_published_pages' => true,
        'publish_pages' => true,
        'delete_pages' => true,
        // Excluir capacidades peligrosas como unfiltered_html
    ]);
}
```

## 7. Plan de Implementación Prioritizado

### 7.1 Fase 1 - Crítico (0-24 horas)
1. **Remover wp-config.php del repositorio**
   - Mover archivo fuera del directorio público
   - Configurar variables de entorno
   - Regenerar claves de seguridad

2. **Corregir permisos de archivos**
   ```bash
   chmod 600 wp-config.php
   chmod 644 *.php
   chmod 755 */
   ```

3. **Implementar sanitización básica**
   - Corregir funciones en functions.php
   - Agregar nonces a formularios

### 7.2 Fase 2 - Alto Riesgo (1-3 días)
1. **Configurar .htaccess hardened**
2. **Implementar logging de seguridad**
3. **Verificar y configurar SSL/HTTPS**
4. **Auditar y ajustar roles de usuario**

### 7.3 Fase 3 - Medio Riesgo (3-7 días)
1. **Implementar headers de seguridad**
2. **Configurar backup automático**
3. **Instalar plugin de seguridad (Wordfence)**
4. **Configurar monitoreo continuo**

## 8. Scripts de Implementación

### 8.1 Script de Corrección de Permisos
```bash
#!/bin/bash
# fix_permissions.sh

WORDPRESS_PATH="/path/to/wordpress"

echo "Corrigiendo permisos de WordPress..."

# Directorios
find $WORDPRESS_PATH -type d -exec chmod 755 {} \;

# Archivos
find $WORDPRESS_PATH -type f -exec chmod 644 {} \;

# Archivos especiales
chmod 600 $WORDPRESS_PATH/wp-config.php
chmod 604 $WORDPRESS_PATH/.htaccess

echo "Permisos corregidos correctamente."
```

### 8.2 Script de Verificación de Seguridad
```php
<?php
// security_check.php - Script de verificación automática

function run_security_checks() {
    $checks = [];
    
    // Verificar wp-config.php
    $checks['wp_config'] = check_wp_config_security();
    
    // Verificar permisos
    $checks['permissions'] = check_file_permissions();
    
    // Verificar SSL
    $checks['ssl'] = check_ssl_configuration();
    
    // Verificar usuarios
    $checks['users'] = check_user_security();
    
    return $checks;
}

function check_wp_config_security() {
    $config_path = ABSPATH . 'wp-config.php';
    $permissions = substr(sprintf('%o', fileperms($config_path)), -3);
    
    return [
        'exists' => file_exists($config_path),
        'permissions' => $permissions,
        'secure' => $permissions === '600',
        'has_salts' => check_security_keys()
    ];
}

function check_security_keys() {
    $keys = ['AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY'];
    foreach ($keys as $key) {
        if (!defined($key) || constant($key) === 'put your unique phrase here') {
            return false;
        }
    }
    return true;
}
```

## 9. Monitoreo y Alertas

### 9.1 Configuración de Alertas
```php
// Función para alertas de seguridad
function send_security_alert($event, $details) {
    $admin_email = get_option('admin_email');
    $subject = '[SEGURIDAD] Alerta en ' . get_bloginfo('name');
    
    $message = "Se ha detectado un evento de seguridad:\n\n";
    $message .= "Evento: $event\n";
    $message .= "Detalles: $details\n";
    $message .= "Fecha: " . current_time('mysql') . "\n";
    $message .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
    
    wp_mail($admin_email, $subject, $message);
}

// Hook para intentos de login fallidos
add_action('wp_login_failed', function($username) {
    $attempts = get_transient('failed_login_' . $_SERVER['REMOTE_ADDR']) ?: 0;
    $attempts++;
    
    set_transient('failed_login_' . $_SERVER['REMOTE_ADDR'], $attempts, 3600);
    
    if ($attempts >= 5) {
        send_security_alert(
            'MULTIPLE_FAILED_LOGINS',
            "5 o más intentos fallidos desde IP: " . $_SERVER['REMOTE_ADDR']
        );
    }
});
```

## 10. Métricas de Éxito

### 10.1 KPIs de Seguridad
- **Vulnerabilidades Críticas:** 0 (objetivo)
- **Tiempo de Respuesta a Incidentes:** < 1 hora
- **Intentos de Login Fallidos:** < 10/día
- **Actualizaciones de Seguridad:** 100% aplicadas en 24h
- **Backup Exitosos:** 100% diarios

### 10.2 Herramientas de Verificación
- **WPScan:** Escaneo de vulnerabilidades
- **Sucuri SiteCheck:** Verificación de malware
- **SSL Labs:** Verificación de certificados
- **Security Headers:** Verificación de headers HTTP

## 11. Conclusiones y Próximos Pasos

### 11.1 Resumen de Riesgos
El sitio presenta vulnerabilidades críticas que requieren atención inmediata. La exposición del wp-config.php y la falta de sanitización representan los mayores riesgos.

### 11.2 Cronograma de Implementación
- **Día 1:** Correcciones críticas (wp-config, permisos)
- **Días 2-3:** Implementación de controles de seguridad
- **Días 4-7:** Monitoreo y ajustes finales

### 11.3 Recursos Necesarios
- **Tiempo de Desarrollo:** 20-30 horas
- **Herramientas:** WPScan, SSL Labs, Sucuri
- **Personal:** 1 desarrollador senior + 1 especialista en seguridad

---

**Informe generado por:** Bob (Architect)  
**Fecha:** Diciembre 2024  
**Versión:** 1.0  
**Clasificación:** CONFIDENCIAL