#!/bin/sh
set -eu

# Bind mounts and named volumes are created by Docker as root. Prepare all
# application-writable paths before Apache drops privileges to www-data.
mkdir -p /app/runtime /app/web/assets /app/web/uploads/covers
chown -R www-data:www-data /app/runtime /app/web/assets /app/web/uploads
chmod -R ug+rwX /app/runtime /app/web/assets /app/web/uploads

if [ ! -f /app/vendor/autoload.php ] || [ ! -f /app/vendor/yiisoft/yii2-gii/src/Module.php ]; then
    composer install --working-dir=/app --no-interaction --prefer-dist
fi

php /app/yii migrate --interactive=0

exec docker-php-entrypoint apache2-foreground
