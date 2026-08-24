#!/bin/bash

echo "Creating SQLite database..."

touch /var/www/html/database/database.sqlite

echo "Setting SQLite permissions..."

chown -R www-data:www-data /var/www/html/database
chmod 775 /var/www/html/database
chmod 664 /var/www/html/database/database.sqlite

echo "Running migrations..."

php /var/www/html/artisan migrate --force

echo "Running seeders..."

php /var/www/html/artisan db:seed --class="App\Features\ShopOwner\Seeders\CountrySeeder" --force
php /var/www/html/artisan db:seed --class="App\Features\ShopOwner\Seeders\CitySeeder" --force
php /var/www/html/artisan db:seed --class="App\Features\ShopOwner\Seeders\ServiceSeeder" --force
php /var/www/html/artisan db:seed --class="App\Features\ShopOwner\Seeders\DistrictsSeeder" --force

echo "Starting PHP-FPM..."

php-fpm -D

echo "Starting Nginx..."

nginx -g "daemon off;"