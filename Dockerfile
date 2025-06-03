# syntax=docker/dockerfile:1

# Stage 1: dependencias y build
FROM php:8.2-fpm AS build

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip zip libicu-dev libonig-dev libxml2-dev libzip-dev curl \
    && docker-php-ext-install intl pdo pdo_mysql zip xml opcache

# Instalar composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copiar solo composer para usar cache de dependencias
COPY composer.json composer.lock ./

# Instalar dependencias, scripts se ejecutan OK porque no falta bin/console (aún no copiado todo)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copiar resto del código
COPY . .

# Ejecutar scripts post-install (ejemplo: cache:clear)
RUN composer run-script post-install-cmd

# Stage 2: imagen final ligera para producción
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y libicu-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip xml opcache

WORKDIR /app

# Copiar solo la carpeta vendor y el código necesario desde la etapa build
COPY --from=build /app /app

# Crear usuario no root para correr la app
RUN useradd -ms /bin/bash appuser
RUN chown -R appuser:appuser /app

USER appuser

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
