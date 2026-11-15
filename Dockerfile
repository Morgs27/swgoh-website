# Use an official PHP image as the base image
FROM php:7.4-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# Database settings (DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME) are passed in
# at run time as environment variables, never baked into the image.


RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy your PHP application files into the container
COPY . /var/www/html

# Expose port 80 for Apache
EXPOSE 80

# Start the Apache web server
CMD ["apache2-foreground"]