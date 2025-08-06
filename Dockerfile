# Etapa 1: Dependencias del backend (Laravel)
# Usa una imagen base de PHP-FPM con Alpine para un tamaño más reducido.
FROM php:8.2-fpm-alpine AS backend

# Instala extensiones necesarias y dependencias del sistema para Alpine
# 'bash' para scripts, 'git', 'curl', 'zip', 'unzip' para Composer.
# Librerías de desarrollo para PHP (libpng-dev, libxml2-dev, oniguruma-dev, icu-dev, libzip-dev, libjpeg-turbo-dev, libwebp-dev, libxpm-dev).
# 'autoconf', 'g++', 'make' para compilar extensiones.
# 'openssl' y 'ca-certificates' son CRUCIALES para SSL/TLS y peticiones SOAP.
# 'nodejs' y 'npm' para las dependencias de frontend.
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
    # 'libjpeg' y 'libjpeg-turbo' ya deberían ser parte de libjpeg-turbo-dev o base
    # 'imagemagick' si lo usas, si no, puedes quitarlo para reducir el tamaño
    imagemagick \
    # Instala las extensiones de PHP que no requieren configuración previa
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        soap \
        zip \
        opcache \
        curl \
    # Configura GD con soporte para JPEG y WebP (y FreeType si es necesario)
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    # Instala la extensión GD después de configurarla
    && docker-php-ext-install gd \
    # Configura y luego instala la extensión intl
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    # Limpia la caché de APK para reducir el tamaño de la imagen
    && rm -rf /var/cache/apk/*

# Instala Composer globalmente.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo.
WORKDIR /var/www

# Copia los archivos de Composer primero para aprovechar el cache de Docker.
# Si composer.json o composer.lock no cambian, Docker no reinstalará las dependencias.
COPY composer.json composer.lock ./

# Instala dependencias PHP.
RUN composer install --no-dev --optimize-autoloader

# Copia el resto del código de tu aplicación.
# IMPORTANTE: El archivo .env NO debe copiarse aquí si lo gestionas con Dokploy.
# Asegúrate de que tu .gitignore excluya .env para no subirlo al repositorio.
COPY . .

# Instala dependencias JS y construye los assets de frontend.
# Asegúrate de que tu package.json y webpack.mix.js (o vite.config.js) estén configurados.
COPY package.json package-lock.json ./
RUN npm install
RUN npm run build

# Configura permisos para los directorios de Laravel.
# Esto es crucial para que Laravel pueda escribir en 'storage' y 'bootstrap/cache'.
RUN chmod -R 775 storage bootstrap/cache

# Elimina las líneas de cache de Artisan del Dockerfile.
# Estas cachés deben generarse DESPUÉS de que las variables de entorno sean inyectadas por Dokploy
# en tiempo de ejecución, o se generarán con valores vacíos/incorrectos.
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

# Ejecuta migraciones y crea storage link.
# Las migraciones suelen ejecutarse como un paso separado en Dokploy o en un entrypoint.
# Aquí se incluye el storage:link, que sí es parte de la preparación de la aplicación.
RUN php artisan storage:link

# Puerto expuesto por php artisan serve.
EXPOSE 8080

# Comando para iniciar Laravel con el servidor de desarrollo.
# NOTA: Para producción, es más común usar PHP-FPM con Nginx.
# Si Dokploy te proporciona un Nginx, este CMD podría no ser necesario o debería ser 'php-fpm'.
CMD php artisan serve --host=0.0.0.0 --port=8080
