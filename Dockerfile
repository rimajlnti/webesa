# Gunakan image PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi Laravel dan PHP yang dibutuhkan
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Salin file Laravel ke dalam image
COPY . /var/www/html

# Atur permission
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Copy default Laravel virtual host config
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf
