# Use PHP 8.3 FPM with Alpine
FROM php:8.3-cli-alpine as base

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    bash \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    zlib-dev \
    libxpm-dev \
    libxml2-dev \
    oniguruma-dev \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd opcache pdo pdo_mysql xml zip \
    && apk del .build-deps

# Set the working directory in the container
WORKDIR /var/www

# Install Composer globally
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install PHP dependencies with Composer
RUN composer install --no-interaction --prefer-dist

# Set up file permissions for Laravel
RUN chown -R www-data:www-data /var/www && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose the port for the PHP built-in server
EXPOSE 8000

# Command to run the PHP built-in server (Laravel default port)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]