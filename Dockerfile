FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpq-dev unzip curl libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

RUN composer install --no-dev --prefer-dist

EXPOSE 80

CMD ["apache2-foreground"]