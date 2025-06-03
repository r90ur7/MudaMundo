#!/bin/sh
set -e

# Garante que o storage e cache tenham as permissões corretas
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Executa as migrations (idempotente)
php artisan migrate --force || true

# Inicia o servidor
exec "$@"
