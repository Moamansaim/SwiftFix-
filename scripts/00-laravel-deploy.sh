#!/usr/bin/env bash

php /var/www/html/artisan migrate --force

php /var/www/html/artisan db:seed --force