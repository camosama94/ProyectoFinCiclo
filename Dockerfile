# syntax=docker/dockerfile:1

FROM php:8.2-fpm

# Instalar dependencias necesarias para Symfony y Composer
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

# Instalar Composer globalmente copiándolo desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Crear usuario no root
RUN useradd -ms /bin/bash appuser

WORKDIR /app

# Crear directorio y asignar permisos a appuser para que pueda escribir aquí
RUN mkdir -p /app && chown -R appuser:appuser /app

# Cambiar a usuario no root para que composer instale con permisos correctos
USER appuser

# Copiar los archivos de dependencias con el usuario correcto para aprovechar cache de docker
COPY --chown=appuser:appuser composer.json composer.lock ./

# Instalar dependencias sin dev, optimizando autoload, sin interacción
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copiar el resto de la aplicación con el usuario correcto
COPY --chown=appuser:appuser . .

# Exponer puerto (ajústalo según fly.toml)
EXPOSE 8080

# Arrancar el servidor PHP embebido apuntando a la carpeta public
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
