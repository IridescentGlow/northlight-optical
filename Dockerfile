# Deployment image for Railway. Not present for local development — the
# local workflow throughout this project has been `php artisan serve` +
# `npm run build` directly against SQLite. This exists solely so the
# build/runtime environment is deterministic on Railway rather than
# relying on its auto-detection heuristics for a PHP+Vite app.

# --- Stage 1: compile front-end assets --------------------------------
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js ./
RUN npm run build

# --- Stage 2: PHP runtime ------------------------------------------------
FROM php:8.2-cli AS app
WORKDIR /var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev libonig-dev unzip git \
    && docker-php-ext-install pdo pdo_mysql mbstring bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --optimize-autoloader

COPY . .
COPY --from=assets /app/public/build ./public/build

# Not `mkdir -p storage/framework/{sessions,views,cache}` — Docker's
# default RUN shell is dash (/bin/sh), which does not expand bash-style
# braces. That form silently creates one literal directory named
# "{sessions,views,cache}" instead of three real ones, and Laravel then
# fails at request time with "file_put_contents(.../sessions/<id>): No
# such file or directory" — caught by actually running the built image
# against a real database before pushing, not by reading the Dockerfile.
RUN composer run-script post-autoload-dump \
    && mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs \
    && chmod -R 775 storage bootstrap/cache

COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 8080
CMD ["/usr/local/bin/start.sh"]
