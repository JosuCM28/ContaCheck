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
    && rm -rf /var/cache/apk/*

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www

# Copia los archivos del proyecto
COPY . .

# Instala dependencias PHP y JS
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# Configura permisos (opcional según tu app)
RUN chmod -R 755 storage bootstrap/cache

# Genera caches
RUN php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# Ejecuta migraciones y crea storage link
RUN php artisan storage:link

# Puerto expuesto por php artisan serve o nginx
EXPOSE 8080

# Comando para iniciar Laravel
CMD php artisan serve --host=0.0.0.0 --port=8080
