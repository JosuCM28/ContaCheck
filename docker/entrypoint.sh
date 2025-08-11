#!/usr/bin/env sh
set -e
cd /var/www
mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true
[ -L public/storage ] || php artisan storage:link || true
exec "$@"
