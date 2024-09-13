# Use an official PHP image as the base image
FROM php:7.4-apache

# Set the working directory inside the container
WORKDIR /var/www/html

ARG DB_SERVER
ARG DB_USERNAME
ARG DB_PASSWORD
ARG DB_NAME

ENV DB_SERVER=${DB_SERVER}
ENV DB_USERNAME=${DB_USERNAME}
ENV DB_PASSWORD=${DB_PASSWORD}
ENV DB_NAME=${DB_NAME}


RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy your PHP application files into the container
COPY . /var/www/html

# Expose port 80 for Apache
EXPOSE 80

# Start the Apache web server
CMD ["apache2-foreground"]