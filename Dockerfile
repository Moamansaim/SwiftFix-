FROM php:8.4-fpm

# تثبيت Nginx وامتدادات PHP المطلوبة
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install \
    pdo_mysql \
    bcmath \
    zip \
    && rm -rf /var/lib/apt/lists/*

# تثبيت Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# نسخ المشروع
COPY . .

# تثبيت مكتبات Laravel
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# صلاحيات Laravel
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

# إعداد Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default

# سكربت التشغيل
COPY docker/start.sh /start.sh

RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]