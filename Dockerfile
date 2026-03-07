# Stage 1: Build frontend assets
FROM node:22-slim AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Build PHP dependencies
FROM composer:2 AS php-builder
WORKDIR /app
COPY composer*.json ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist
COPY . .
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

# Stage 3: Final production image
FROM dunglas/frankenphp:latest-php8.4 AS final

# Install PHP extensions
RUN install-php-extensions \
    pcntl \
    intl \
    zip \
    bcmath \
    gd \
    pdo_mysql \
    opcache

# Set Caddy server name to :80 for local run
ENV SERVER_NAME=:80
ENV APP_RUNTIME=FrankenPHP\Laravel\Runtime
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

# Copy code from builders
WORKDIR /app
COPY --from=php-builder /app /app
COPY --from=frontend-builder /app/public/build /app/public/build

# Ensure storage and cache directories are writable
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Production PHP settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Entrypoint to handle migrations and startup
COPY --chmod=755 <<EOF /usr/local/bin/docker-entrypoint.sh
#!/bin/sh
set -e

# Run migrations if DB is available (optional, but good for self-contained deploys)
# php artisan migrate --force

# Cache config and routes
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# If arguments are provided, execute them (e.g., worker)
if [ $# -gt 0 ]; then
    exec "$@"
fi

# Hand off to FrankenPHP
exec frankenphp run --config /etc/caddy/Caddyfile --adapter caddyfile
EOF

ENTRYPOINT ["docker-entrypoint.sh"]
