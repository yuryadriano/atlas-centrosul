# 1. Base Image: PHP 8.2 with Apache
FROM php:8.2-apache

# 2. Set Environment Variables for Apache
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data

# 3. Install Required PHP Extensions and System Packages
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       libzip-dev \
       libpng-dev \
       libjpeg-dev \
       libfreetype6-dev \
       libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo_mysql zip intl 

# 4. Enable Apache mod_rewrite for friendly URLs
RUN a2enmod rewrite

# 5. Copy Project Files to the Webroot
COPY . /var/www/html/

# 6. Define Permissions (Crucial for uploads)
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/uploads

# 7. Apply Custom PHP Configurations
COPY error_log.ini /usr/local/etc/php/conf.d/

# Apache exposes port 80 by default
EXPOSE 80
