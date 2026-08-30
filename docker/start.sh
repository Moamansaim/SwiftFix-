#!/bin/bash

echo "DB_HOST=$DB_HOST"
echo "DB_DATABASE=$DB_DATABASE"
echo "DB_USERNAME=$DB_USERNAME"
echo "DB_PORT=$DB_PORT"
echo "PORT=$PORT"

echo "Clearing Laravel config cache..."

php /var/www/html/artisan config:clear

echo "Refreshing database..."

php /var/www/html/artisan migrate:fresh --force

echo "Running CountrySeeder..."

php /var/www/html/artisan db:seed --class="App\Features\Country\Seeders\CountrySeeder" --force

echo "Running CitySeeder..."

php /var/www/html/artisan db:seed --class="App\Features\City\Seeders\CitySeeder" --force

echo "Running ServiceSeeder..."

php /var/www/html/artisan db:seed --class="App\Features\Services\Seeders\ServiceSeeder" --force

echo "Running DistrictsSeeder..."

php /var/www/html/artisan db:seed --class="App\Features\Districts\Seeders\DistrictsSeeder" --force

echo "Running BrandSeeder..."

php /var/www/html/artisan db:seed --class="App\Features\Brand\Seeders\BrandSeeder" --force

echo "Running DeviceModel..."

php /var/www/html/artisan db:seed --class="App\Features\DeviceModel\Seeders\DeviceModelSeeder" --force

echo "Running Category..."

php /var/www/html/artisan db:seed --class="App\Features\Category\Seeders\CategorySeeder" --force


echo "Running Product..."

php /var/www/html/artisan db:seed --class="App\Features\Product\Seeders\ProductSeeder" --force


echo "Starting PHP-FPM..."

php-fpm -D

echo "Configuring Nginx port..."

sed -i "s/listen 80;/listen $PORT;/" /etc/nginx/sites-available/default

echo "Starting Nginx on port $PORT..."

nginx -g "daemon off;"