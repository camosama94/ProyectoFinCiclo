#!/bin/sh
set -e

echo "Esperando a que la base de datos esté lista..."

# Espera activa hasta que MySQL esté disponible
until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
  echo "Base de datos no disponible aún, esperando 2s..."
  sleep 2
done

echo "Base de datos lista, ejecutando migraciones..."
php bin/console doctrine:migrations:migrate --no-interaction

echo "Migraciones ejecutadas, arrancando servidor..."
exec php -S 0.0.0.0:8080 -t public
