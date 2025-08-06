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
    # Limpia la caché de APK para reducir el tamaño de la imagen
    && rm -rf /var/cache/apk/*

# Configura las extensiones de PHP que requieren configuración especial ANTES de instalarlas.
# Configura GD con soporte para JPEG y WebP (y FreeType si es necesario)
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype

# Instala las extensiones de PHP necesarias.
# 'pdo_mysql' (o pdo_pgsql si usas PostgreSQL), 'zip', 'gd', 'mbstring', 'exif', 'pcntl', 'bcmath'.
# 'soap' es ESENCIAL para tus peticiones SOAP a WSDL.
# 'curl' es comúnmente usado por SOAP y otras librerías HTTP, y su configuración SSL es vital.
# 'intl' es útil para localización y funciones de Carbon en Laravel.
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    soap \
    zip \
    opcache \
    curl \
    gd \
    intl \
    mbstring \
    exif \
    pcntl \
    bcmath \
    && update-ca-certificates \
    # --- INICIO DE LA MEJORA PARA SSL ---
    # Crea un enlace simbólico para que /etc/ssl/cert.pem apunte a ca-certificates.crt
    # Esto es crucial para que `curl` (y por ende, `SOAPClient`) encuentre los certificados CA en Alpine.
    && ln -sf /etc/ssl/certs/ca-certificates.crt /etc/ssl/cert.pem \
    # --- FIN DE LA MEJORA PARA SSL ---
    && rm -rf /var/cache/apk/*


# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www

# Copia los archivos del proyecto
COPY . .

# Instala dependencias PHP y JS
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader
COPY package.json package-lock.json ./
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
