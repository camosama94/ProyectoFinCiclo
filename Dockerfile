# syntax=docker/dockerfile:1

# Stage 1: dependencias y build
FROM php:8.2-fpm AS build

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip zip libicu-dev libonig-dev libxml2-dev libzip-dev curl \
    && docker-php-ext-install intl pdo pdo_mysql zip xml opcache

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copiar composer.* para usar la caché de dependencias
COPY composer.json composer.lock ./

# Permitir Composer como root y evitar errores de scripts
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias SIN ejecutar scripts (evitamos errores por symfony-cmd)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# Copiar el resto del código del proyecto
COPY . .

# (Opcional) Ejecutar manualmente scripts si ya tienes bin/console listo y funciona:
# RUN php bin/console cache:clear

# Stage 2: imagen final ligera para producción
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libicu-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip xml opcache

WORKDIR /app

# Copiar el código ya procesado desde la etapa build
COPY --from=build /app /app

# Crear un usuario no root para ejecutar la app
RUN useradd -ms /bin/bash appuser \
    && chown -R appuser:appuser /app

USER appuser

# Puerto de la app para Fly.io
EXPOSE 8080

# Ejecutar servidor embebido de PHP apuntando a la carpeta public
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]

