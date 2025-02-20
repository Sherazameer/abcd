# Use a Fedora base image
FROM fedora:37

# Set the working directory to home directory
WORKDIR /home

# Update the package repository and install the necessary packages
RUN dnf -y update && \
    dnf -y install php php-cli php-fpm php-mysqlnd php-xml php-mbstring php-zip php-json curl unzip && \
    dnf clean all

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer 

# Create a new Laravel application in the directory laravel-app
RUN composer create-project --prefer-dist laravel/laravel laravel-app

# Set the working directory to the Laravel application directory
WORKDIR /home/laravel-app

# Run composer install to install dependencies and optimize the autoloader
RUN composer install --no-dev --optimize-autoloader

# Expose the necessary port
EXPOSE 80

# Start PHP's built-in server (change as necessary)
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]

