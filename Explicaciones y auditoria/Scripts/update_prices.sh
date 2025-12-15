#!/bin/bash
# Script para actualizar precios desde API
echo "Iniciando actualización de precios..."
# Aquí iría la lógica de actualización
echo "Precios actualizados correctamente"
EOF
chown root:administracion "$ADMINISTRACION_DIR/update_prices.sh"
chmod 750 "$ADMINISTRACION_DIR/update_prices.sh"
Martín Leites, Santiago Cabrera, Matías Franca De Lima, Miguel Malavé, Nicolás Núñez
}
# Función para generar contraseña segura
generate_password() {
openssl rand -base64 12 | tr -d '/+=' | head -c 12
}
# Función para validar complejidad de contraseña
validate_password() {
local password="$1"
if [[ ${#password} -lt 8 ]]; then
return 1
fi
return 0
}
# Función para crear usuario
create_user() {
local username="$1"
local password="$2"
local role="$3"
log "Creando usuario: $username con rol: $role"
# Verificar si el usuario ya existe
if id "$username" &>/dev/null; then
log "Usuario $username ya existe, saltando creación..."
return 1
fi
# Crear usuario con home directory y shell bash
useradd -m -s /bin/bash "$username"
if [[ $? -ne 0 ]]; then
log "ERROR: No se pudo crear el usuario $username"
return 1
fi
# Asignar contraseña (generar si está vacía)
if [[ -z "$password" ]]; then
password=$(generate_password)
log "Contraseña generada automáticamente para $username"
fi
# Validar contraseña
if ! validate_password "$password"; then
log "ERROR: La contraseña no cumple con los requisitos mínimos (8 caracteres)"
userdel -r "$username" 2>/dev/null
return 1
Martín Leites, Santiago Cabrera, Matías Franca De Lima, Miguel Malavé, Nicolás Núñez
fi
# Establecer contraseña
echo "$username:$password" | chpasswd
# Configurar políticas de contraseña
chage -d 0 "$username" # Forzar cambio en primer login
chage -M 90 "$username" # Caducidad a 90 días
chage -W 7 "$username" # Aviso 7 días antes
# Asignar grupo según rol
case "$role" in
"admin")
usermod -aG admin,sudo "$username"
log "Usuario $username agregado a grupos: admin, sudo"
;;
"backups")
usermod -aG backups "$username"
log "Usuario $username agregado a grupo: backups"
;;
"administracion")
usermod -aG administracion "$username"
log "Usuario $username agregado a grupo: administracion"
;;
*)
log "ADVERTENCIA: Rol no reconocido: $role. Usuario creado sin grupos
adicionales"
;;
esac
log "Usuario $username creado exitosamente"
echo "Contraseña temporal: $password"
return 0
}
# Función para generar clave SSH
generate_ssh_key() {
local username="$1"
local home_dir="/home/$username"
log "Generando clave SSH para $username"
# Crear directorio .ssh con permisos seguros
mkdir -p "$home_dir/.ssh"
chmod 700 "$home_dir/.ssh"
chown "$username:$username" "$home_dir/.ssh"
# Generar clave SSH RSA 4096 bits
Martín Leites, Santiago Cabrera, Matías Franca De Lima, Miguel Malavé, Nicolás Núñez
sudo -u "$username" ssh-keygen -t rsa -b 4096 -f "$home_dir/.ssh/id_rsa" -N "" -q
# Configurar authorized_keys
cat "$home_dir/.ssh/id_rsa.pub" >> "$home_dir/.ssh/authorized_keys"
chmod 600 "$home_dir/.ssh/authorized_keys"
chown "$username:$username" "$home_dir/.ssh/authorized_keys"
# Asegurar permisos del home directory
chmod 755 "$home_dir"
log "Clave SSH generada y configurada para $username"
}
# Función para configurar servidor SSH
configure_ssh() {
log "Configurando servidor SSH..."
# Backup de configuración original
cp "$SSH_CONFIG" "$SSH_CONFIG.backup_$(date +%Y%m%d)"
# Configurar opciones de seguridad
sed -i 's/^#*PasswordAuthentication.*/PasswordAuthentication no/' "$SSH_CONFIG"
sed -i 's/^#*PermitRootLogin.*/PermitRootLogin no/' "$SSH_CONFIG"
sed -i 's/^#*PubkeyAuthentication.*/PubkeyAuthentication yes/' "$SSH_CONFIG"
# Recargar servicio SSH
systemctl reload ssh
log "SSH configurado: Solo autenticación por claves, Root login deshabilitado"
}
# Función para ingresar datos de usuario
get_user_data() {
echo "=== Creación de Nuevo Usuario ==="
read -p "Nombre de usuario: " username
read -s -p "Contraseña (dejar vacío para generar automática): " password
echo
echo "Roles disponibles: admin, backups, administracion"
read -p "Rol del usuario: " role
# Validar entrada básica
if [[ -z "$username" ]]; then
echo "ERROR: El nombre de usuario no puede estar vacío"
return 1
fi
create_user "$username" "$password" "$role"
if [[ $? -eq 0 ]]; then
Martín Leites, Santiago Cabrera, Matías Franca De Lima, Miguel Malavé, Nicolás Núñez
generate_ssh_key "$username"
fi
}
# Función para mostrar políticas de seguridad
show_security_policies() {
echo ""
echo "=== POLÍTICAS DE SEGURIDAD IMPLEMENTADAS ==="
echo "1. Contraseñas: Mínimo 8 caracteres, caducidad 90 días"
echo "2. SSH: Solo autenticación por claves, root login deshabilitado"
echo "3. Permisos: Estructura de grupos con privilegios mínimos"
echo "4. Backups: Responsables grupo 'backups', retención 30 días"
echo "5. Auditoría: Logs detallados en /var/log/user_setup.log"
echo ""
}
# Función principal
main() {
check_root
log "Iniciando script de configuración de usuarios"
create_groups
configure_permissions
echo "¿Cuántos usuarios deseas crear?"
read -p "Número de usuarios: " user_count
for ((i=1; i<=user_count; i++)); do
echo ""
echo "Usuario $i de $user_count"
get_user_data
done
configure_ssh
show_security_policies
log "Proceso completado. Ver $LOG_FILE para detalles completos"
echo "Configuración completada. Revise el log en $LOG_FILE"
}
# Ejecutar función principal
main "$@"
