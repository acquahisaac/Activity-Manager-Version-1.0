# Use the official PHP image with Apache
FROM php:8.1-apache

# Install necessary system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the Laravel project files into the container
COPY . .

# Install composer and PHP dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Set proper permissions for Laravel directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80 for the Apache server
EXPOSE 80

# Start the Apache server in the foreground
CMD ["apache2-foreground"]
