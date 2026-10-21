
#!/bin/bash

set -e

cd /var/www/html

# SQLite database configuration
export DB_CONNECTION="${DB_CONNECTION:-sqlite}"
export DB_DATABASE="${DB_DATABASE:-/var/data/database.sqlite}"

echo "========================================"
echo "Starting SwiftFix"
echo "DB_CONNECTION=$DB_CONNECTION"
echo "DB_DATABASE=$DB_DATABASE"
echo "PORT=${PORT:-10000}"
echo "========================================"

# Prepare SQLite database
echo "Preparing SQLite database..."

DB_DIR="$(dirname "$DB_DATABASE")"

mkdir -p "$DB_DIR"
touch "$DB_DATABASE"

# Configure SQLite permissions
chown www-data:www-data "$DB_DIR"
chown www-data:www-data "$DB_DATABASE"

chmod 775 "$DB_DIR"
chmod 664 "$DB_DATABASE"

echo "SQLite database permissions:"
ls -ld "$DB_DIR"
ls -l "$DB_DATABASE"

# Prepare Laravel writable directories
echo "Preparing Laravel storage directories..."

mkdir -p \
    /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache

# Set directory permissions
find /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    -type d -exec chmod 775 {} \;

find /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    -type f -exec chmod 664 {} \;

# Ensure PHP-FPM can write to Laravel directories
chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

echo "Clearing Laravel configuration cache..."

php artisan config:clear

# Run migrations without deleting existing tables
echo "Running migrations..."

php artisan migrate --force

# Run seeders
echo "Running CountrySeeder..."
php artisan db:seed --class="App\Features\Country\Seeders\CountrySeeder" --force

echo "Running CitySeeder..."
php artisan db:seed --class="App\Features\City\Seeders\CitySeeder" --force

echo "Running ServiceSeeder..."
php artisan db:seed --class="App\Features\Services\Seeders\ServiceSeeder" --force

echo "Running BrandSeeder..."
php artisan db:seed --class="App\Features\Brand\Seeders\BrandSeeder" --force

echo "Running DeviceModelSeeder..."
php artisan db:seed --class="App\Features\DeviceModel\Seeders\DeviceModelSeeder" --force

echo "Running CategorySeeder..."
php artisan db:seed --class="App\Features\Category\Seeders\CategorySeeder" --force

echo "Running ProductSeeder..."
php artisan db:seed --class="App\Features\Product\Seeders\ProductSeeder" --force

echo "Running RoleSeeder..."
php artisan db:seed --class="App\Features\Role\Seeders\RoleSeeder" --force

echo "Running PermissionSeeder..."
php artisan db:seed --class="App\Features\Role\Seeders\PermissionSeeder" --force

echo "Running AdminRole..."
php artisan db:seed --class="App\Features\Role\Seeders\AdminRole" --force

echo "Running AuthSeeder..."
php artisan db:seed --class="App\Features\Auth\Seeders\UserSeeder" --force

# Restore correct ownership after Artisan commands
echo "Finalizing Laravel permissions..."

chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

find /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    -type d -exec chmod 775 {} \;

find /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    -type f -exec chmod 664 {} \;

# Verify that the PHP-FPM user can write to Laravel directories
if ! su -s /bin/sh www-data -c \
    'test -w /var/www/html/storage/framework/cache/data &&
     test -w /var/www/html/storage/framework/sessions &&
     test -w /var/www/html/storage/framework/views &&
     test -w /var/www/html/storage/logs &&
     test -w /var/www/html/bootstrap/cache &&
     test -w "$DB_DATABASE"'; then
    echo "ERROR: Laravel or SQLite directories are not writable."
    exit 1
fi

echo "Laravel storage permissions verified."

# Configure Nginx port
echo "Configuring Nginx port..."

PORT="${PORT:-10000}"

sed -i "s/listen 80;/listen ${PORT};/" \
    /etc/nginx/sites-available/default

# Validate Nginx configuration before starting
nginx -t

echo "Starting PHP-FPM..."

php-fpm -D

echo "Starting Nginx on port $PORT..."

exec nginx -g "daemon off;"
