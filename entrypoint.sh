#!/bin/sh
set -e

# Garante que o banco existe (caso o COPY não tenha trazido)
if [ ! -f /var/www/database/database.sqlite ]; then
    mkdir -p /var/www/database
    touch /var/www/database/database.sqlite
    chmod 777 /var/www/database/database.sqlite
fi

# Garante que o storage e cache tenham as permissões corretas
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Executa as migrations (idempotente)
php artisan migrate --force || true

# Inicia o servidor
exec "$@"
