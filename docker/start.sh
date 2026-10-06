#!/bin/sh
set -e

# --- Free-tier demo mode (Render): SQLite file inside the container ----------
# The database is recreated on every deploy/restart and re-seeded below, which
# is fine for a portfolio demo. Set DB_CONNECTION=mysql plus DB_* vars instead
# to run against a real database (e.g. Railway).
if [ "${DB_CONNECTION:-}" = "sqlite" ]; then
    export DB_DATABASE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    mkdir -p "$(dirname "$DB_DATABASE")"
    touch "$DB_DATABASE"
fi

# Laravel needs an APP_KEY. If the platform did not provide one, generate an
# ephemeral key for this container (sessions reset on restart, acceptable here).
if [ -z "${APP_KEY:-}" ]; then
    export APP_KEY="base64:$(head -c 32 /dev/urandom | base64)"
fi

# Render exposes the public URL of the service; use it unless APP_URL is set.
if [ -z "${APP_URL:-}" ] && [ -n "${RENDER_EXTERNAL_URL:-}" ]; then
    export APP_URL="$RENDER_EXTERNAL_URL"
fi

# storage:link is a symlink, harmless to recreate on every boot.
php artisan storage:link --force || true

# migrate is additive/idempotent by design; safe on every boot.
php artisan migrate --force

# ProductSeeder uses insertOrIgnore, so re-running it is safe. Do not hide
# real failures: a seeding error should stop the boot and show in the logs.
php artisan db:seed --class=ProductSeeder --force

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
