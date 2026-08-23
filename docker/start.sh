#!/bin/bash

echo "Creating SQLite database..."

touch /var/www/html/database/database.sqlite

echo "Running migrations..."

php /var/www/html/artisan migrate --force

echo "Running seeders..."

php /var/www/html/artisan db:seed --force

echo "Starting PHP-FPM..."

php-fpm -D

echo "Starting Nginx..."

nginx -g "daemon off;"