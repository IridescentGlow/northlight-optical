#!/bin/sh
set -e

# storage:link is a symlink, harmless to recreate on every boot.
php artisan storage:link --force || true

# migrate is additive/idempotent by design; safe on every boot.
php artisan migrate --force

# ProductSeeder inserts fixed IDs 1-8 in a single atomic INSERT (see
# database/seeders/ProductSeeder.php) — it is NOT idempotent on its own
# and errors on a duplicate-key collision if the catalog is already
# seeded. That's the desired failure mode here: on every boot after the
# first, this reruns, hits the duplicate key immediately (InnoDB rolls
# the whole statement back, so there's no partial state to worry about),
# and `|| true` lets the container keep starting instead of crash-looping.
php artisan db:seed --class=ProductSeeder --force || true

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
