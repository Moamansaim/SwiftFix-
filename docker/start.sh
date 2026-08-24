#!/bin/bash

echo "DB_HOST=$DB_HOST"
echo "DB_DATABASE=$DB_DATABASE"
echo "DB_USERNAME=$DB_USERNAME"
echo "DB_PORT=$DB_PORT"

echo "Clearing Laravel config cache..."

php /var/www/html/artisan config:clear

echo "Running migrations..."

php /var/www/html/artisan migrate --force

echo "Running CountrySeeder..."

php /var/www/html/artisan db:seed \
    --class="App\Features\ShopOwner\Seeders\CountrySeeder" \
    --force

echo "Running CitySeeder..."

php /var/www/html/artisan db:seed \
    --class="App\Features\ShopOwner\Seeders\CitySeeder" \
    --force

echo "Running ServiceSeeder..."

php /var/www/html/artisan db:seed \
    --class="App\Features\ShopOwner\Seeders\ServiceSeeder" \
    --force

echo "Running DistrictsSeeder..."

php /var/www/html/artisan db:seed \
    --class="App\Features\ShopOwner\Seeders\DistrictsSeeder" \
    --force

echo "Starting PHP-FPM..."

php-fpm -D

echo "Starting Nginx..."

nginx -g "daemon off;"