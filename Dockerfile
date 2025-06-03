# syntax=docker/dockerfile:1

FROM php:8.2-fpm

# Instalar dependencias necesarias
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

# Instalar Composer globalmente
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalar Symfony CLI para que symfony-cmd funcione
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Crear usuario no root
RUN useradd -ms /bin/bash appuser

WORKDIR /app

# Cambiar a usuario no root antes de copiar e instalar dependencias
USER appuser

# Copiar composer files con permisos adecuados
COPY --chown=appuser:appuser composer.json composer.lock ./

# Instalar dependencias con scripts habilitados
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copiar el resto del proyecto con permisos
COPY --chown=appuser:appuser . .

EXPOSE 8080

# Arrancar servidor PHP integrado apuntando a la carpeta public
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]

