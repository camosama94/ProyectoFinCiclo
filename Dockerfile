# syntax=docker/dockerfile:1

# Stage 1: dependencias y build
FROM php:8.2-fpm AS build

RUN apt-get update && apt-get install -y \
    git unzip zip libicu-dev libonig-dev libxml2-dev libzip-dev curl \
    && docker-php-ext-install intl pdo pdo_mysql zip xml opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copiar solo composer para usar cache
COPY composer.json composer.lock ./

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# Copiar el resto del código
COPY . .
COPY .env .env

# Compilar variables de entorno y ejecutar scripts
RUN composer dump-env prod
ENV APP_ENV=prod
RUN composer run-script post-install-cmd

# Stage 2: imagen final
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y libicu-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip xml opcache

WORKDIR /app

COPY --from=build /app /app

RUN useradd -ms /bin/bash appuser
RUN chown -R appuser:appuser /app

USER appuser

EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
