# Use an official PHP image with Apache
FROM php:8.1-apache

# Copy your project files to the container
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html

# Enable mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Expose port 80 for the web server
EXPOSE 80
