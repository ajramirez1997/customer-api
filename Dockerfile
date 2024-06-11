# Use the official PHP image as base
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer update --no-scripts --no-autoloader

# Copy application files
COPY . .

# Generate optimized autoload files
RUN composer dump-autoload --optimize

# Expose ports 4000 and 4001
EXPOSE 4000 4001

# Command to run the application on port 4000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=4000"]
