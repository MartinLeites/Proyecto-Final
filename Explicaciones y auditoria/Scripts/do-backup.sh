#!/bin/bash
#
# SCRIPT SEGURO PARA BACKUPS - SOLO ENVÍO

set -e  # Sale ante cualquier error

# === CONFIGURACIÓN SEGURA ===
LOG_FILE="/var/log/backups.log"
ALLOWED_SOURCES=(
    "/datos/"
    "/var/www/" 
    "/app/"
    "/home//"
)
BACKUP_USER="backup-user"
BACKUP_SERVER="10.10.10.16”
DESTINATION_BASE="/backups/"

# === FUNCIONES ===
log_action() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') - $1" | tee -a "$LOG_FILE"
}

validate_source() {
    local source_path="$1"
    local valid=0
    
    for allowed_path in "${ALLOWED_SOURCES[@]}"; do
        if [[ "$source_path" == "$allowed_path"* ]]; then
            valid=1
            break
        fi
    done
    
    if [[ $valid -eq 0 ]]; then
        log_action "ERROR: Origen no permitido - $source_path"
        echo "Fuentes permitidas:"
        printf '  - %s\n' "${ALLOWED_SOURCES[@]}"
        exit 1
    fi
    
    # Verificar que el origen existe
    if [[ ! -d "$source_path" && ! -f "$source_path" ]]; then
        log_action "ERROR: Origen no existe - $source_path"
        exit 1
    fi
}

validate_destination() {
    local dest_path="$1"
    
    # Solo permitir destinos al servidor de backups
    if [[ "$dest_path" != "$BACKUP_USER@$BACKUP_SERVER:"* ]]; then
        log_action "ERROR: Destino no permitido - $dest_path"
        echo "Solo se permite: $BACKUP_USER@$BACKUP_SERVER:[ruta]"
        exit 1
    fi
    
    # Validar que la ruta destino esté bajo el directorio base
    if [[ "$dest_path" != *"$DESTINATION_BASE"* ]]; then
        log_action "ERROR: Destino fuera del directorio base - $dest_path"
        exit 1
    fi
}

# === VALIDACIÓN DE ARGUMENTOS ===
if [[ $# -ne 2 ]]; then
    echo "Uso: $0 [origen] [destino]"
    echo "Ejemplo: $0 /datos/ backup-user@backup-server:/backups/datos/"
    exit 1
fi

SOURCE="$1"
DESTINATION="$2"

# === VALIDACIONES DE SEGURIDAD ===
log_action "INICIO Backup: $SOURCE -> $DESTINATION"

validate_source "$SOURCE"
validate_destination "$DESTINATION"

# === EJECUCIÓN DEL BACKUP ===
log_action "Ejecutando rsync: $SOURCE -> $DESTINATION"

# Rsync con opciones seguras
/usr/bin/rsync -av --progress --delete \
    --exclude='*.tmp' \
    --exclude='*.log' \
    --exclude='/tmp/' \
    --exclude='/cache/' \
    "$SOURCE" "$DESTINATION"

# === VERIFICACIÓN ===
if [[ $? -eq 0 ]]; then
    log_action "BACKUP EXITOSO: $SOURCE -> $DESTINATION"
    echo "Backup completado correctamente"
else
    log_action "ERROR en backup: $SOURCE -> $DESTINATION"
    echo "Error durante el backup. Ver logs: $LOG_FILE"
    exit 1
fi