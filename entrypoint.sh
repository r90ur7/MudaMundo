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

# Permissões completas e recursivas para storage
echo "Aplicando chmod 777 recursivo em storage..."
chmod -R 777 /var/www/storage
chmod 777 /var/www/bootstrap/cache

# Roda as migrations (apaga e recria todas as tabelas)
echo "Rodando migrate:fresh..."
php artisan migrate:fresh --force --seed
echo "Migrations finalizadas."
php artisan optimize:clear

# Inicia o servidor PHP embutido na porta 10000
php -S 0.0.0.0:10000 -t public
