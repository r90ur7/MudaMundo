#!/bin/sh
set -e

# Debug: Mostra arquivos e permissões do banco
ls -l /var/www/database/

# Garante que o banco existe (caso o COPY não tenha trazido)
if [ ! -f /var/www/database/database.sqlite ]; then
    mkdir -p /var/www/database
    touch /var/www/database/database.sqlite
    chmod 777 /var/www/database/database.sqlite
fi

# Debug: Mostra permissões após garantir
ls -l /var/www/database/

# Roda as migrations (apaga e recria todas as tabelas)
echo "Rodando migrate:fresh..."
php artisan migrate:fresh --force
echo "Migrations finalizadas."

# Inicia o servidor
exec "$@"
