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

# Garante que o storage e cache tenham as permissões corretas
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Roda as migrations (incluindo sessions)
echo "Rodando migrations..."
php artisan migrate --force
echo "Migrations finalizadas."

# Inicia o servidor
exec "$@"
