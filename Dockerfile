FROM php:7.4-apache

# Install required dependencies
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git

# Install MySQL PDO extension
RUN docker-php-ext-install pdo pdo_mysql

# Install Redis extension for PHP
RUN pecl install redis && docker-php-ext-enable redis

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Enable necessary PHP extensions
RUN docker-php-ext-enable pdo_mysql

# Copy your local app files into the container
COPY ./company-ui /var/www/html/

