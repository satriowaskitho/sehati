# Stage 1: Node.js build for frontend assets
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install Node dependencies
RUN npm install

# Copy source files needed for Vite build
COPY . .

# Build Vite assets (this compiles Tailwind CSS)
RUN npm run build

# Stage 2: Composer dependencies
FROM serversideup/php:8.3-cli AS composer-builder

USER root

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install Composer dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev --prefer-dist

# Copy application files
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize --no-dev

# Stage 3: Production image
FROM serversideup/php:8.3-fpm-nginx

ENV PHP_OPCACHE_ENABLE=1

USER root

# Set working directory
WORKDIR /var/www/html

# Copy application files from composer-builder stage
COPY --chown=www-data:www-data --from=composer-builder /app /var/www/html

# Copy built Vite assets from node-builder stage
COPY --chown=www-data:www-data --from=node-builder /app/public/build /var/www/html/public/build

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Switch to www-data user
USER www-data

# Expose port (serversideup/php:8.3-fpm-nginx uses 8080 by default)
EXPOSE 8080