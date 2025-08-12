#!/usr/bin/env sh
set -e

APP_DIR="/var/www"
cd "$APP_DIR"

# Asegura directorios y permisos (por si el volumen viene vacío)
mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Symlink de storage si falta (típico al montar volumen nuevo)
[ -L public/storage ] || php artisan storage:link || true

# Recalcula caches con ENV reales de Dokploy
php artisan config:clear || true
php artisan route:clear  || true
php artisan view:clear   || true
php artisan config:cache || true
php artisan route:cache  || true
php artisan view:cache   || true

# (Opcional) migraciones automáticas controladas por variable
if [ "${RUN_MIGRATIONS:-0}" = "1" ]; then
  php artisan migrate --force || true
fi

# Ejecuta el proceso final (php artisan serve …)
exec "$@"
