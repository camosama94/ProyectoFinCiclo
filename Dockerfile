FROM php:8.2-fpm

# Instalar extensiones necesarias, git, unzip, composer...
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev && \
    docker-php-ext-install zip pdo pdo_mysql

WORKDIR /app

COPY . /app

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

EXPOSE 8080

# Arrancar servidor PHP embebido para prod (simple)
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
