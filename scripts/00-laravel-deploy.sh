#!/usr/bin/env bash

touch /var/www/html/database/database.sqlite

php /var/www/html/artisan migrate --force

php /var/www/html/artisan db:seed --force