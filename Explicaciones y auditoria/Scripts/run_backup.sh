#!/bin/bash
# Script para ejecutar backup de base de datos
mysqldump -u backup_user --all-databases > /srv/backups/backup_$(date +%Y%m%d).sql
echo "Backup completado: backup_$(date +%Y%m%d).sql"
EOF
chown root:backups "$BACKUP_DIR/run_backup.sh"
chmod 750 "$BACKUP_DIR/run_backup.sh"
}
# Función para crear scripts de administración
create_administracion_scripts() {
cat > "$ADMINISTRACION_DIR/update_prices.sh" << 'EOF'
