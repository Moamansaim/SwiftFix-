
#!/bin/bash

set -e

# SQLite database configuration
export DB_CONNECTION="${DB_CONNECTION:-sqlite}"
export DB_DATABASE="${DB_DATABASE:-/var/data/database.sqlite}"

echo "DB_CONNECTION=$DB_CONNECTION"
echo "DB_DATABASE=$DB_DATABASE"
echo "PORT=$PORT"

echo "Preparing SQLite database..."

# Create the directory and database file if they do not exist.
mkdir -p "$(dirname "$DB_DATABASE")"
touch "$DB_DATABASE"

# Allow the web server to access the database.
chown www-data:www-data "$DB_DATABASE"
chmod 664 "$DB_DATABASE"

echo "Clearing Laravel config cache..."

php /var/www/html/artisan config:clear

echo "Running migrations..."

php /var/www/html/artisan migrate --force

echo "Running CountrySeeder..."

php /var/www/html/artisan db:seed \
    --class="App\Features\Country\Seeders\CountrySeeder" --force

echo "Running CitySeeder..."

php /var/www/html/artisan db:seed \
    --class="App\Features\City\Seeders\CitySeeder" --force

echo "Running ServiceSeeder..."

php /var/www/html/artisan db:seed \
    --class="App\Features\Services\Seeders\ServiceSeeder" --force

echo "Running BrandSeeder..."

php /var/www/html/artisan db:seed \
    --class="App\Features\Brand\Seeders\BrandSeeder" --force

echo "Running DeviceModelSeeder..."

php /var/www/html/artisan db:seed \
    --class="App\Features\DeviceModel\Seeders\DeviceModelSeeder" --force

echo "Running CategorySeeder..."

php /var/www/html/artisan db:seed \
    --class="App\Features\Category\Seeders\CategorySeeder" --force

echo "Running ProductSeeder..."

php /var/www/html/artisan db:seed \
    --class="App\Features\Product\Seeders\ProductSeeder" --force

echo "Running RoleSeeder..."

php /var/www/html/artisan db:seed \
    --class="App\Features\Role\Seeders\RoleSeeder" --force

echo "Running PermissionSeeder..."

php /var/www/html/artisan db:seed \
    --class="App\Features\Role\Seeders\PermissionSeeder" --force

echo "Running AdminRole..."

php /var/www/html/artisan db:seed \
    --class="App\Features\Role\Seeders\AdminRole" --force


echo "Running AuthSeeder..."

php /var/www/html/artisan db:seed \
    --class="App\Features\Auth\Seeders\UserSeeder" --force


echo "Starting PHP-FPM..."

php-fpm -D

echo "Configuring Nginx port..."

sed -i "s/listen 80;/listen ${PORT};/" \
    /etc/nginx/sites-available/default

echo "Starting Nginx on port $PORT..."

exec nginx -g "daemon off;"
