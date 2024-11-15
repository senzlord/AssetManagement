# Use the official PHP 8.3 image with FPM (FastCGI Process Manager)
FROM php:8.3-fpm

# Set working directory inside the container
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && apt-get clean

# Install PHP extensions required for Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Node.js and npm (needed for Laravel Mix or Vite assets)
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the application code into the container
COPY . /var/www/html

<<<<<<< HEAD
# Install Composer dependencies
=======
# Copy the .env.example file to .env
RUN cp .env.example .env

# Install project dependencies with optimized autoloader
>>>>>>> parent of 4706127 (Update Dockerfile)
RUN composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader

# Set permissions for Laravel's storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate the application key
RUN php artisan key:generate

# Expose port 80 (for web server)
EXPOSE 2309

<<<<<<< HEAD
# Run the Laravel development server (this can be replaced by Nginx or Apache for production)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=2309"]
=======
# Run the Laravel development server by default
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=2309"]
>>>>>>> parent of 4706127 (Update Dockerfile)
