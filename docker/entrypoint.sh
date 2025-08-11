#!/usr/bin/env sh
set -euo pipefail

APP_DIR="/var/www"
cd "$APP_DIR"

# ---- Helpers ----
artisan() {
  if [ -f "$APP_DIR/artisan" ]; then
    php "$APP_DIR/artisan" "$@"
  else
    echo "artisan no encontrado, omitiendo: $*"
  fi
}

# ---- Permisos / Directorios ----
mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache || true

# Si corres como root en el contenedor, ajusta owner a www-data (php-fpm/alpine lo incluye)
if [ "$(id -u)" = "0" ]; then
  chown -R www-data:www-data storage bootstrap/cache || true
  chmod -R 775 storage bootstrap/cache || true
else
  chmod -R 775 storage bootstrap/cache || true
fi

# ---- Symlink storage -> public/storage ----
if [ ! -L "public/storage" ]; then
  artisan storage:link || true
fi

# ---- Caches en runtime (con variables reales de Dokploy) ----
artisan config:clear || true
artisan route:clear  || true
artisan view:clear   || true

artisan config:cache || true
artisan route:cache  || true
artisan view:cache   || true

# ---- Migraciones opcionales (controladas por env) ----
# RUN_MIGRATIONS=1 para ejecutarlas en cada arranque
if [ "${RUN_MIGRATIONS:-0}" = "1" ]; then
  artisan migrate --force || true
fi

# ---- Seeds opcionales ----
# RUN_SEEDERS=1 para seedear (útil solo en dev/stage)
if [ "${RUN_SEEDERS:-0}" = "0" ]; then
  :
else
  if [ "${RUN_SEEDERS:-0}" = "1" ]; then
    artisan db:seed --force || true
  fi
fi

# ---- (Opcional) Horizon/Queues ----
# Si usas Horizon y quieres reiniciar workers al redeploy:
# artisan horizon:terminate || true

echo "Entrypoint listo, lanzando proceso: $*"
exec "$@"
