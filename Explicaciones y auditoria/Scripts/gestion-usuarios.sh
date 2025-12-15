#!/bin/bash
# Script de automatización para creación de usuarios y configuración SSH
# Sistema: Debian 13
# Autor: Administración de Sistemas
# Descripción: Crea usuarios, genera claves SSH y configura permisos según roles
# Variables globales
LOG_FILE="/var/log/user_setup.log"
BACKUP_DIR="/srv/backups"
ADMIN_DIR="/srv/admin"
ADMINISTRACION_DIR="/srv/administracion"
SSH_CONFIG="/etc/ssh/sshd_config"
# Función para logging
log() {
echo "$(date '+%Y-%m-%d %H:%M:%S') - $1" | tee -a "$LOG_FILE"
}
Martín Leites, Santiago Cabrera, Matías Franca De Lima, Miguel Malavé, Nicolás Núñez
# Función para verificar ejecución como root
check_root() {
if [[ $EUID -ne 0 ]]; then
echo "ERROR: Este script debe ejecutarse como root"
exit 1
fi
}
# Función para crear grupos del sistema
create_groups() {
log "Creando grupos del sistema..."
# Grupos principales con descripción
declare -A groups=(
["admin"]="Administradores del sistema con acceso total"
["backups"]="Equipo de backups con permisos de lectura/exportación"
["administracion"]="Personal administrativo para gestión de datos"
)
for group in "${!groups[@]}"; do
if ! getent group "$group" >/dev/null; then
groupadd "$group"
log "Grupo $group creado - ${groups[$group]}"
else
log "Grupo $group ya existe"
fi
done
# Crear directorios de trabajo con permisos base
mkdir -p "$BACKUP_DIR" "$ADMIN_DIR" "$ADMINISTRACION_DIR"
}
# Función para configurar permisos de directorios
configure_permissions() {
log "Configurando permisos de directorios..."
# Permisos para directorios de servicios
chown root:admin "$ADMIN_DIR"
chmod 770 "$ADMIN_DIR"
log "Directorio $ADMIN_DIR - Propietario: root:admin, Permisos: 770"
chown root:backups "$BACKUP_DIR"
chmod 770 "$BACKUP_DIR"
log "Directorio $BACKUP_DIR - Propietario: root:backups, Permisos: 770"
chown root:administracion "$ADMINISTRACION_DIR"
chmod 770 "$ADMINISTRACION_DIR"
Martín Leites, Santiago Cabrera, Matías Franca De Lima, Miguel Malavé, Nicolás Núñez
log "Directorio $ADMINISTRACION_DIR - Propietario: root:administracion, Permisos:
770"
# Crear scripts básicos para cada grupo
create_admin_scripts
create_backup_scripts
create_administracion_scripts
}
# Función para crear scripts de administración
create_admin_scripts() {
cat > "$ADMIN_DIR/restart_backend.sh" << 'EOF'
