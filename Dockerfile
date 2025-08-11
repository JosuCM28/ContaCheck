# Etapa 1: Dependencias del backend (Laravel)
FROM php:8.2-fpm-alpine AS backend

# Instala extensiones necesarias
RUN apk add --no-cache \
    bash \
    git \
    curl \
    zip \
    unzip \
    libpng \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev \
    autoconf \
    g++ \
    make \
    openssl \
    nodejs \
    npm \
    ca-certificates \
    libzip-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    libjpeg \
    libjpeg-turbo \
    imagemagick \
    imagemagick-dev \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        soap \
        zip \
        opcache \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && rm -rf /var/cache/apk/*

# Instala Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www

# Copia los archivos del proyecto
COPY . .

# Instala dependencias PHP y JS
RUN composer install --no-dev --optimize-autoloader \
 && if [ -f package-lock.json ]; then npm ci; else npm install; fi \
 && npm run build \
 && rm -rf node_modules

# Configura permisos (primer arranque)
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache || true

# (Tu build actual genera caches; las recalcularemos en runtime para tomar las ENV reales)
RUN php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# Copia entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

# Puerto expuesto por php artisan serve
EXPOSE 8080

# Comando para iniciar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
