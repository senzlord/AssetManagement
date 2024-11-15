# Gunakan PHP 8.2
FROM php:8.2-fpm

# Install dependensi yang dibutuhkan
RUN apt-get update && apt-get install -y \
    nano \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libxml2-dev \
    libicu-dev \
    libmcrypt-dev \
    libxslt-dev \
    libsodium-dev \    
    libonig-dev \      
    libzip-dev

# Install ekstensi PHP untuk Laravel
RUN docker-php-ext-install bcmath gd pdo_mysql sodium mbstring xml ctype zip

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set direktori kerja
WORKDIR /var/www

# Salin file proyek
COPY . .

# Install Laravel dependencies
RUN composer install --no-interaction --optimize-autoloader

# Berikan izin ke direktori storage
RUN chmod -R 775 storage bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

RUN php artisan key:generate

# Ekspose port yang digunakan oleh PHP-FPM
EXPOSE 9000