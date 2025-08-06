# Usa una imagen base de PHP-FPM con la versión de PHP que necesites.
# Por ejemplo, php:8.2-fpm para PHP 8.2.
# Puedes usar -alpine para una imagen más ligera si prefieres, ajustando los comandos RUN (apk add en lugar de apt-get).
FROM php:8.2-fpm

# Establece el directorio de trabajo dentro del contenedor.
WORKDIR /var/www/html

# Argumentos para el UID/GID del usuario (útil para consistencia en desarrollo local)
# Dokploy suele manejar esto automáticamente, pero es buena práctica.
ARG UID=1000
ARG GID=1000

# --- Instalación de dependencias del sistema y extensiones de PHP ---
# Actualiza los paquetes del sistema e instala dependencias comunes.
# 'git' para clonar repositorios, 'unzip' para Composer,
# 'libpq-dev' si usas PostgreSQL, 'libzip-dev' para la extensión zip de PHP,
# 'libpng-dev', 'libjpeg-dev', 'libxml2-dev' para GD y SOAP,
# 'ca-certificates' es CRUCIAL para la validación SSL de peticiones externas (como WSDL).
# Se añaden 'libcurl4-openssl-dev' para la extensión curl y 'libfreetype-dev' para GD.
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    ca-certificates \
    libcurl4-openssl-dev \
    libfreetype-dev \
    # Limpia la caché de apt para reducir el tamaño de la imagen
    && rm -rf /var/lib/apt/lists/*

# Instala las extensiones de PHP necesarias.
# 'pdo_mysql' (o pdo_pgsql si usas PostgreSQL), 'zip', 'gd', 'mbstring', 'exif', 'pcntl', 'bcmath'.
# 'soap' es ESENCIAL para tus peticiones SOAP a WSDL.
# 'curl' es comúnmente usado por SOAP y otras librerías HTTP, y su configuración SSL es vital.
RUN docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath soap curl \
    # Configura GD con soporte para JPEG y PNG (y FreeType si es necesario)
    && docker-php-ext-configure gd --with-jpeg --with-png --with-freetype \
    # Instala las extensiones configuradas
    && docker-php-ext-install -j$(nproc) gd

# --- Configuración de Composer ---
# Descarga e instala Composer globalmente.
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Configura Composer para que use los certificados CA del sistema.
# Esto ayuda a Composer a verificar SSL al descargar paquetes.
RUN composer config -g cafile /etc/ssl/certs/ca-certificates.crt

# --- Configuración de PHP para SSL y memoria ---
# Crea un archivo de configuración PHP personalizado para asegurar que cURL y OpenSSL
# usen los certificados CA del sistema. Esto es VITAL para las peticiones SOAP a WSDL
# que requieren validación SSL.
RUN echo "curl.cainfo = /etc/ssl/certs/ca-certificates.crt" >> /usr/local/etc/php/conf.d/docker-php-ext-curl.ini && \
    echo "openssl.cafile = /etc/ssl/certs/ca-certificates.crt" >> /usr/local/etc/php/conf.d/docker-php-ext-openssl.ini

# Aumenta el límite de memoria de PHP para el CLI (Composer) y FPM.
# Esto previene errores de memoria en instalaciones de Composer grandes o scripts PHP pesados.
RUN echo "memory_limit = -1" > /usr/local/etc/php/conf.d/zz-custom-memory.ini

# --- Copia de la aplicación y dependencias ---
# Copia los archivos de Composer primero para aprovechar el cache de Docker.
# Si composer.json o composer.lock no cambian, Docker no reinstalará las dependencias.
COPY composer.json composer.lock ./

# Instala las dependencias de Composer.
# '--no-dev' para no instalar dependencias de desarrollo (producción).
# '--optimize-autoloader' para optimizar el autoloader para producción.
RUN composer install --no-dev --optimize-autoloader

# Copia el resto del código de tu aplicación.
COPY . .

# --- Permisos y optimizaciones de Laravel ---
# Crea el usuario y grupo 'www-data' si no existen y asigna permisos.
# Esto es crucial para que Laravel pueda escribir en los directorios 'storage' y 'bootstrap/cache'.
RUN groupadd -g $GID www-data || true && \
    useradd -u $UID -ms /bin/bash -g www-data www-data

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Genera la key de la aplicación (si no la tienes ya en tu .env).
# Dokploy puede inyectar variables de entorno, incluyendo APP_KEY.
# Si no, puedes descomentar la siguiente línea, pero es mejor que APP_KEY se maneje fuera del Dockerfile.
# RUN php artisan key:generate

# Opcional: Cachea la configuración y las rutas para rendimiento en producción.
# Asegúrate de que tu .env esté disponible en este punto o que las variables
# de entorno se inyecten en tiempo de ejecución.
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# --- Exposición de puerto y comando de inicio ---
# PHP-FPM escucha en el puerto 9000 por defecto.
EXPOSE 9000

# Comando para iniciar PHP-FPM cuando el contenedor se ejecute.
CMD ["php-fpm"]
