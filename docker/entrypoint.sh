#!/bin/sh
set -eu

# Bind mounts and named volumes are created by Docker as root. Prepare all
# application-writable paths before Apache drops privileges to www-data.
mkdir -p /app/runtime /app/web/assets /app/web/uploads/covers
chown -R www-data:www-data /app/runtime /app/web/assets /app/web/uploads
chmod -R ug+rwX /app/runtime /app/web/assets /app/web/uploads

php /app/yii migrate --interactive=0

exec docker-php-entrypoint apache2-foreground
