# Etapa única: PHP 8.2 con Alpine
FROM php:8.2-fpm-alpine

# 1) Paquetes del sistema y extensiones PHP necesarias
RUN apk add --no-cache \
    bash git curl zip unzip \
    libpng libpng-dev libxml2-dev oniguruma-dev icu-dev \
    autoconf g++ make openssl \
    nodejs npm \
    ca-certificates libzip-dev \
    libjpeg-turbo-dev libwebp-dev libxpm-dev \
    libjpeg libjpeg-turbo imagemagick \
 && docker-php-ext-install \
    pdo pdo_mysql soap zip opcache \
 && docker-php-ext-configure gd --with-jpeg --with-webp \
 && docker-php-ext-install gd \
 && docker-php-ext-configure intl \
 && docker-php-ext-install intl \
 && rm -rf /var/cache/apk/*

# 2) Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3) Directorio de trabajo
WORKDIR /var/www

# 4) Copia de archivos
# (Opcional pero recomendado: añade un .dockerignore para excluir node_modules, vendor, .git, etc.)
COPY . .

# 5) Instala dependencias PHP y construye assets
# - Composer en modo prod
# - npm ci (si tienes package-lock.json). Si no, npm install.
# - Build de Vite y limpieza de node_modules (no los necesitas en producción)
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader \
 && if [ -f package-lock.json ]; then npm ci; else npm install; fi \
 && npm run build \
 && rm -rf node_modules

# 6) Permisos mínimos sobre directorios de runtime
#    (Serán montados como volúmenes en Dokploy, pero esto ayuda al primer arranque)
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache || true

# 7) NO generes caches ni hagas storage:link en build
#    Se hará en runtime con el entrypoint (las env reales se inyectan en ejecución)

# 8) Entrypoint: prepara permisos, storage:link, caches y (opcional) migraciones
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

# 9) Puerto
EXPOSE 8080

# 10) Comando de arranque
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
