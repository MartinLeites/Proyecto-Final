#!/bin/bash
# Script para reiniciar servicios del backend
systemctl restart apache2
systemctl restart mysql
echo "Backend reiniciado correctamente"
EOF
chown root:admin "$ADMIN_DIR/restart_backend.sh"
chmod 750 "$ADMIN_DIR/restart_backend.sh"
}
# Función para crear scripts de backup
create_backup_scripts() {
cat > "$BACKUP_DIR/run_backup.sh" << 'EOF'
