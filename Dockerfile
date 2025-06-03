# syntax=docker/dockerfile:1

FROM php:8.2-fpm

# Instalar dependencias de sistema necesarias para Symfony y Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    curl \
    && docker-php-ext-install intl pdo pdo_mysql zip xml opcache

# Instalar Composer (global)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Crear un usuario no root
RUN useradd -ms /bin/bash appuser

WORKDIR /app

# Copiar composer.json y composer.lock primero para aprovechar cache de Docker
COPY composer.json composer.lock ./

# Instalar dependencias PHP sin ejecutar scripts y sin dev
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --prefer-dist

# Copiar el resto de la app
COPY . .

# Cambiar permisos al usuario no root
RUN chown -R appuser:appuser /app

USER appuser

# Puerto que exponemos (Fly.io espera el puerto configurado en fly.toml)
EXPOSE 8080

# Para modo desarrollo (puedes cambiar por php-fpm o nginx en producción)
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
