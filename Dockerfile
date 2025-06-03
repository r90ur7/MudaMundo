# Dockerfile para Laravel + SQLite
FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update \
    && apt-get install -y libpng-dev libonig-dev libxml2-dev libsqlite3-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Cria diretório de trabalho
WORKDIR /var/www

# Copia arquivos do projeto
COPY . .

# Instala dependências do PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Garante que os diretórios de cache e logs existem
RUN mkdir -p /var/www/storage/framework/views /var/www/storage/framework/cache /var/www/storage/logs

# Garante que o diretório de sessões existe
RUN mkdir -p /var/www/storage/framework/sessions

# Permissões para o storage e cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Garante que o banco SQLite existe e tem permissão
RUN mkdir -p /var/www/database && touch /var/www/database/database.sqlite && chmod 777 /var/www/database/database.sqlite

# Instala Node.js (necessário para build do Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Instala dependências JS e builda assets
RUN npm install && npm run build

# Copia o entrypoint customizado
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Define o entrypoint
ENTRYPOINT ["/entrypoint.sh"]

# Porta padrão do Render
EXPOSE 10000

# Comando para iniciar o servidor na porta 10000
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
