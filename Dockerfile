# Etapa base: PHP con FPM sobre Debian
FROM php:8.2-fpm AS backend

# Instala utilidades y extensiones necesarias
RUN apt-get update && apt-get install -y \
    bash \
    git \
    unzip \
    curl \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libxpm-dev \
    libonig-dev \
    libicu-dev \
    libxml2-dev \
    libssl-dev \
    ca-certificates \
    libfreetype6-dev \
    gnupg \
    zlib1g-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        soap \
        intl \
        gd \
        zip \
        opcache \
    && update-ca-certificates \
    && echo "openssl.cafile=/etc/ssl/certs/ca-certificates.crt" >> /usr/local/etc/php/conf.d/99-custom-ssl.ini \
    && echo "openssl.capath=/etc/ssl/certs" >> /usr/local/etc/php/conf.d/99-custom-ssl.ini \
    && rm -rf /var/lib/apt/lists/*

# Instala Composer desde imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www

# Copia archivos del proyecto
COPY . .

# Instala dependencias PHP y JS
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# Permisos (ajústalo según tus necesidades)
RUN chmod -R 755 storage bootstrap/cache

# Caches de Laravel
RUN php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# Link de storage
RUN php artisan storage:link

# Expone el puerto (puedes usar php-fpm + nginx si prefieres)
EXPOSE 8080

# Comando final para iniciar servidor de desarrollo
CMD php artisan serve --host=0.0.0.0 --port=8080
