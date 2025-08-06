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
    # Asegúrate de que freetype-dev esté explícitamente presente para GD
    freetype-dev \
    # Limpia la caché de APK para reducir el tamaño de la imagen
    && rm -rf /var/cache/apk/*

# --- SOLUCIÓN PARA EL ERROR SSL DE CURL/SOAP (Mantenido) ---
# Asegura que ca-certificates esté actualizado y que curl/openssl lo usen correctamente.
# Para Alpine, a veces se necesita un symlink para cert.pem.
# Esto es crucial para que `curl` (y por ende, `SOAPClient` que lo usa) confíe en los certificados SSL.
RUN update-ca-certificates && \
    ln -sf /etc/ssl/certs/ca-certificates.crt /etc/ssl/cert.pem

# --- MEJORA PARA LA INSTALACIÓN DE GD Y OTRAS EXTENSIONES ---
# Configura e instala GD con soporte para JPEG, WebP y FreeType en un solo paso.
# Esto ayuda a asegurar que las dependencias de GD se encuentren durante la compilación.
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-install -j$(nproc) gd

# Instala las demás extensiones de PHP necesarias en un bloque separado.
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    soap \
    zip \
    opcache \
    curl \
    intl \
    mbstring \
    exif \
    pcntl \
    bcmath

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www

# Copia los archivos de Composer primero para aprovechar el cache de Docker.
# Si composer.json o composer.lock no cambian, Docker no reinstalará las dependencias.
COPY composer.json composer.lock ./
# Instala dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Copia los archivos de NPM/Node.js antes de instalar las dependencias JS.
COPY package.json package-lock.json ./
# Instala dependencias JS y construye los assets de frontend.
RUN npm install
RUN npm run build

# Copia el resto del código de tu aplicación.
COPY . .

# Configura permisos (opcional según tu app)
RUN chmod -R 755 storage bootstrap/cache

# Genera caches (Recuerda que estas cachés deben generarse DESPUÉS de que las variables de entorno
# sean inyectadas por Dokploy en tiempo de ejecución, o se generarán con valores incorrectos.
# Considera mover estas líneas a un script de entrada o a un comando de post-despliegue en Dokploy).
RUN php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# Ejecuta migraciones y crea storage link
RUN php artisan storage:link

# Puerto expuesto por php artisan serve o nginx
EXPOSE 8080

# Comando para iniciar Laravel (formato JSON recomendado para evitar advertencias de Docker)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
