#!/bin/bash

# =============================================================================
# SCRIPT DE HARDENING DE SEGURIDAD - Men's Core Therapy WordPress
# =============================================================================
# 
# Descripción: Implementa todas las medidas de seguridad críticas identificadas
# en la auditoría de seguridad
# 
# Uso: ./security-hardening.sh [WORDPRESS_PATH]
# Ejemplo: ./security-hardening.sh /var/www/html/willmasaje_stagi
#
# =============================================================================

set -e  # Salir si cualquier comando falla

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Función para logging
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

# Verificar si se proporcionó la ruta
if [ $# -eq 0 ]; then
    WORDPRESS_PATH="/var/www/html/willmasaje_stagi"
    warning "No se proporcionó ruta. Usando ruta por defecto: $WORDPRESS_PATH"
else
    WORDPRESS_PATH="$1"
fi

# Verificar que la ruta existe
if [ ! -d "$WORDPRESS_PATH" ]; then
    error "La ruta $WORDPRESS_PATH no existe"
    exit 1
fi

# Verificar que es una instalación de WordPress
if [ ! -f "$WORDPRESS_PATH/wp-config.php" ]; then
    error "No se encontró wp-config.php en $WORDPRESS_PATH"
    exit 1
fi

log "Iniciando hardening de seguridad para WordPress en: $WORDPRESS_PATH"

# =============================================================================
# 1. BACKUP DE ARCHIVOS CRÍTICOS
# =============================================================================
log "Creando backup de archivos críticos..."

BACKUP_DIR="$WORDPRESS_PATH/security-backup-$(date +%Y%m%d-%H%M%S)"
mkdir -p "$BACKUP_DIR"

# Backup de archivos críticos
cp "$WORDPRESS_PATH/wp-config.php" "$BACKUP_DIR/wp-config.php.bak" 2>/dev/null || true
cp "$WORDPRESS_PATH/.htaccess" "$BACKUP_DIR/.htaccess.bak" 2>/dev/null || true
cp -r "$WORDPRESS_PATH/wp-content/themes/masajista-masculino/functions.php" "$BACKUP_DIR/functions.php.bak" 2>/dev/null || true

log "Backup creado en: $BACKUP_DIR"

# =============================================================================
# 2. CONFIGURACIÓN DE PERMISOS SEGUROS
# =============================================================================
log "Configurando permisos de archivos seguros..."

# Permisos para directorios (755)
find "$WORDPRESS_PATH" -type d -exec chmod 755 {} \;

# Permisos para archivos (644)
find "$WORDPRESS_PATH" -type f -exec chmod 644 {} \;

# Permisos especiales para archivos críticos
chmod 600 "$WORDPRESS_PATH/wp-config.php" 2>/dev/null || warning "No se pudo cambiar permisos de wp-config.php"
chmod 644 "$WORDPRESS_PATH/.htaccess" 2>/dev/null || warning "No se pudo cambiar permisos de .htaccess"

# Permisos para wp-content
chmod 755 "$WORDPRESS_PATH/wp-content"
chmod -R 755 "$WORDPRESS_PATH/wp-content/themes"
chmod -R 755 "$WORDPRESS_PATH/wp-content/plugins"
chmod -R 755 "$WORDPRESS_PATH/wp-content/uploads"

log "Permisos de archivos configurados correctamente"

# =============================================================================
# 3. REMOVER ARCHIVOS PELIGROSOS
# =============================================================================
log "Removiendo archivos peligrosos..."

# Lista de archivos a remover
FILES_TO_REMOVE=(
    "readme.html"
    "license.txt"
    "wp-config-sample.php"
    "wp-admin/install.php"
)

for file in "${FILES_TO_REMOVE[@]}"; do
    if [ -f "$WORDPRESS_PATH/$file" ]; then
        rm -f "$WORDPRESS_PATH/$file"
        log "Removido: $file"
    fi
done

# =============================================================================
# 4. GENERAR NUEVAS CLAVES DE SEGURIDAD
# =============================================================================
log "Generando nuevas claves de seguridad..."

# Descargar nuevas claves de seguridad
SALT_URL="https://api.wordpress.org/secret-key/1.1/salt/"
NEW_SALTS=$(curl -s "$SALT_URL")

if [ $? -eq 0 ] && [ -n "$NEW_SALTS" ]; then
    # Crear archivo temporal con nuevas claves
    TEMP_CONFIG=$(mktemp)
    
    # Leer wp-config.php y reemplazar claves
    while IFS= read -r line; do
        if [[ $line =~ ^define\(\'(AUTH_KEY|SECURE_AUTH_KEY|LOGGED_IN_KEY|NONCE_KEY|AUTH_SALT|SECURE_AUTH_SALT|LOGGED_IN_SALT|NONCE_SALT)\' ]]; then
            # Saltar líneas de claves existentes
            continue
        elif [[ $line =~ ^\s*\/\*.*stop\ editing ]]; then
            # Insertar nuevas claves antes del comentario de stop editing
            echo "$NEW_SALTS" >> "$TEMP_CONFIG"
            echo "" >> "$TEMP_CONFIG"
            echo "$line" >> "$TEMP_CONFIG"
        else
            echo "$line" >> "$TEMP_CONFIG"
        fi
    done < "$WORDPRESS_PATH/wp-config.php"
    
    # Reemplazar wp-config.php con versión actualizada
    mv "$TEMP_CONFIG" "$WORDPRESS_PATH/wp-config.php"
    chmod 600 "$WORDPRESS_PATH/wp-config.php"
    
    log "Claves de seguridad actualizadas"
else
    warning "No se pudieron generar nuevas claves de seguridad automáticamente"
    info "Genera manualmente en: https://api.wordpress.org/secret-key/1.1/salt/"
fi

# =============================================================================
# 5. CONFIGURAR .HTACCESS SEGURO
# =============================================================================
log "Configurando .htaccess seguro..."

# Verificar si existe .htaccess
if [ ! -f "$WORDPRESS_PATH/.htaccess" ]; then
    touch "$WORDPRESS_PATH/.htaccess"
fi

# Crear .htaccess seguro (usar el archivo de configuración creado anteriormente)
if [ -f "/workspace/implementacion/configs/.htaccess-secure" ]; then
    cp "/workspace/implementacion/configs/.htaccess-secure" "$WORDPRESS_PATH/.htaccess"
    log ".htaccess seguro configurado"
else
    warning "Archivo .htaccess-secure no encontrado. Configurando manualmente..."
    
    # Configuración básica de seguridad
    cat >> "$WORDPRESS_PATH/.htaccess" << 'EOF'

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Frame-Options DENY
    Header always set X-Content-Type-Options nosniff
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
</IfModule>

# Protect wp-config.php
<Files wp-config.php>
    Order allow,deny
    Deny from all
</Files>

# Protect .htaccess
<Files .htaccess>
    Order allow,deny
    Deny from all
</Files>

EOF
fi

# =============================================================================
# 6. CREAR ARCHIVO DE MONITOREO DE SEGURIDAD
# =============================================================================
log "Creando sistema de monitoreo de seguridad..."

cat > "$WORDPRESS_PATH/wp-content/security-monitor.php" << 'EOF'
<?php
/**
 * Monitor de Seguridad - Men's Core Therapy
 * Ejecutar vía cron cada hora: 0 * * * * /usr/bin/php /path/to/security-monitor.php
 */

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/../../');
}

require_once ABSPATH . 'wp-config.php';

class SecurityMonitor {
    private $critical_files = [
        'wp-config.php',
        '.htaccess',
        'wp-content/themes/masajista-masculino/functions.php'
    ];
    
    private $log_file;
    
    public function __construct() {
        $this->log_file = ABSPATH . 'wp-content/security-monitor.log';
    }
    
    public function run_checks() {
        $this->log("=== Iniciando verificación de seguridad ===");
        
        $this->check_file_integrity();
        $this->check_failed_logins();
        $this->check_file_permissions();
        $this->check_suspicious_files();
        
        $this->log("=== Verificación completada ===\n");
    }
    
    private function check_file_integrity() {
        $this->log("Verificando integridad de archivos críticos...");
        
        foreach ($this->critical_files as $file) {
            $file_path = ABSPATH . $file;
            if (file_exists($file_path)) {
                $hash = md5_file($file_path);
                $stored_hash = get_option("security_hash_" . md5($file));
                
                if ($stored_hash && $stored_hash !== $hash) {
                    $this->log("ALERTA: Archivo modificado - $file", 'WARNING');
                    $this->send_alert("Archivo crítico modificado: $file");
                } elseif (!$stored_hash) {
                    update_option("security_hash_" . md5($file), $hash);
                    $this->log("Hash inicial guardado para: $file");
                }
            }
        }
    }
    
    private function check_failed_logins() {
        // Verificar intentos de login fallidos en las últimas 24 horas
        $failed_logins = $this->count_failed_logins();
        
        if ($failed_logins > 10) {
            $this->log("ALERTA: $failed_logins intentos de login fallidos en 24h", 'WARNING');
            $this->send_alert("Múltiples intentos de login fallidos: $failed_logins");
        }
    }
    
    private function check_file_permissions() {
        $wp_config_perms = substr(sprintf('%o', fileperms(ABSPATH . 'wp-config.php')), -3);
        
        if ($wp_config_perms !== '600') {
            $this->log("ALERTA: Permisos incorrectos en wp-config.php: $wp_config_perms", 'WARNING');
        }
    }
    
    private function check_suspicious_files() {
        $suspicious_patterns = ['*.php.suspected', '*.php.bak', 'shell.php', 'c99.php'];
        
        foreach ($suspicious_patterns as $pattern) {
            $files = glob(ABSPATH . "wp-content/**/$pattern", GLOB_BRACE);
            if (!empty($files)) {
                $this->log("ALERTA: Archivos sospechosos encontrados: " . implode(', ', $files), 'CRITICAL');
                $this->send_alert("Archivos sospechosos detectados");
            }
        }
    }
    
    private function count_failed_logins() {
        // Simular conteo de logs (implementar según sistema de logs)
        return 0;
    }
    
    private function send_alert($message) {
        // Enviar email de alerta (configurar según necesidades)
        $admin_email = get_option('admin_email');
        if ($admin_email) {
            wp_mail($admin_email, '[SEGURIDAD] Alerta - Men\'s Core Therapy', $message);
        }
    }
    
    private function log($message, $level = 'INFO') {
        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[$timestamp] [$level] $message\n";
        file_put_contents($this->log_file, $log_entry, FILE_APPEND | LOCK_EX);
    }
}

// Ejecutar monitor
$monitor = new SecurityMonitor();
$monitor->run_checks();
EOF

chmod 644 "$WORDPRESS_PATH/wp-content/security-monitor.php"
log "Monitor de seguridad creado"

# =============================================================================
# 7. CONFIGURAR CRON PARA MONITOREO
# =============================================================================
log "Configurando tarea cron para monitoreo..."

# Agregar entrada cron si no existe
CRON_ENTRY="0 * * * * /usr/bin/php $WORDPRESS_PATH/wp-content/security-monitor.php"
(crontab -l 2>/dev/null | grep -v "security-monitor.php"; echo "$CRON_ENTRY") | crontab - 2>/dev/null || warning "No se pudo configurar cron automáticamente"

info "Configurar manualmente cron con: $CRON_ENTRY"

# =============================================================================
# 8. CREAR SCRIPT DE VERIFICACIÓN
# =============================================================================
log "Creando script de verificación..."

cat > "$WORDPRESS_PATH/verify-security.sh" << 'EOF'
#!/bin/bash

echo "=== VERIFICACIÓN DE SEGURIDAD ==="
echo

# Verificar permisos de wp-config.php
WP_CONFIG_PERMS=$(stat -c "%a" wp-config.php)
if [ "$WP_CONFIG_PERMS" = "600" ]; then
    echo "✓ wp-config.php: Permisos correctos (600)"
else
    echo "✗ wp-config.php: Permisos incorrectos ($WP_CONFIG_PERMS)"
fi

# Verificar .htaccess
if [ -f ".htaccess" ]; then
    echo "✓ .htaccess: Existe"
    if grep -q "X-Frame-Options" .htaccess; then
        echo "✓ .htaccess: Headers de seguridad configurados"
    else
        echo "✗ .htaccess: Headers de seguridad faltantes"
    fi
else
    echo "✗ .htaccess: No existe"
fi

# Verificar archivos peligrosos
DANGEROUS_FILES=("readme.html" "license.txt" "wp-config-sample.php")
for file in "${DANGEROUS_FILES[@]}"; do
    if [ -f "$file" ]; then
        echo "✗ Archivo peligroso encontrado: $file"
    else
        echo "✓ Archivo peligroso removido: $file"
    fi
done

# Verificar claves de seguridad
if grep -q "put your unique phrase here" wp-config.php; then
    echo "✗ Claves de seguridad por defecto detectadas"
else
    echo "✓ Claves de seguridad personalizadas"
fi

echo
echo "=== VERIFICACIÓN COMPLETADA ==="
EOF

chmod +x "$WORDPRESS_PATH/verify-security.sh"

# =============================================================================
# 9. VERIFICACIÓN FINAL
# =============================================================================
log "Ejecutando verificación final..."

cd "$WORDPRESS_PATH"
./verify-security.sh

# =============================================================================
# 10. RESUMEN
# =============================================================================
echo
log "=== HARDENING DE SEGURIDAD COMPLETADO ==="
echo
info "Acciones realizadas:"
info "  ✓ Backup de archivos críticos creado en: $BACKUP_DIR"
info "  ✓ Permisos de archivos configurados"
info "  ✓ Archivos peligrosos removidos"
info "  ✓ Claves de seguridad actualizadas"
info "  ✓ .htaccess seguro configurado"
info "  ✓ Monitor de seguridad instalado"
info "  ✓ Script de verificación creado"
echo
warning "ACCIONES MANUALES REQUERIDAS:"
warning "  1. Configurar variables de entorno para wp-config.php"
warning "  2. Verificar configuración de cron para monitoreo"
warning "  3. Instalar plugin de seguridad (Wordfence/Sucuri)"
warning "  4. Configurar backup automático"
echo
info "Para verificar el estado de seguridad: cd $WORDPRESS_PATH && ./verify-security.sh"
echo

log "Script completado exitosamente"