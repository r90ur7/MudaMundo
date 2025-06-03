# Dockerfile para Laravel + SQLite
FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update \
    && apt-get install -y libpng-dev libonig-dev libxml2-dev zip unzip git curl sqlite3 \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Cria diretório de trabalho
WORKDIR /var/www

# Copia arquivos do projeto
COPY . .

# Instala dependências do PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Permissões para o storage e cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copia o arquivo .env.example para .env se não existir
RUN [ -f .env ] || cp .env.example .env

# Gera a chave da aplicação
RUN php artisan key:generate || true

# Copia o entrypoint customizado
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Define o entrypoint
ENTRYPOINT ["/entrypoint.sh"]

# Porta padrão do Laravel
EXPOSE 8000

# Comando para iniciar o servidor
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
