# Use the official PHP 8.3 image as the base image
FROM php:8.3

# Set the working directory inside the container
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

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Node.js and npm (needed for Laravel Mix or Vite assets)
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the application code to the container
COPY . /var/www/html

# Copy the .env.example file to .env
COPY .env.example .env

# Generate the application key
RUN php artisan key:generate

# Install project dependencies with optimized autoloader
RUN composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader

# Set permissions for Laravel's storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose the port for the Laravel development server
EXPOSE 8000

# Run the Laravel development server by default
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
