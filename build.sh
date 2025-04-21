#!/bin/bash

# Instala Composer (si no está presente)
if ! command -v composer &> /dev/null; then
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    php -r "unlink('composer-setup.php');"
fi

# Instala dependencias y configura Symfony
composer install --no-dev --optimize-autoloader
php bin/console cache:clear --env=prod --no-debug
php bin/console cache:warmup --env=prod --no-debug
php bin/console assets:install public