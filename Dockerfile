# Gunakan PHP 8.1 dengan Apache
FROM php:8.1-apache

# Install ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Aktifkan mod_rewrite Apache (penting untuk Laravel routing)
RUN a2enmod rewrite

# Salin file project ke direktori kerja di container
COPY . /var/www/html

# Ubah permission
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependensi Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copy file environment jika ada
COPY .env.example .env

# Generate app key
RUN php artisan key:generate

EXPOSE 80
