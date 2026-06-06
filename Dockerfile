FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y git unzip libpq-dev libzip-dev zip nodejs npm \
    && docker-php-ext-install pdo_pgsql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN touch .env \
    && composer install --no-interaction --optimize-autoloader --no-scripts \
    && npm install \
    && npm run build \
    && composer dump-autoload --optimize

RUN a2enmod rewrite \
    && sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf

CMD ["apache2-foreground"]

EXPOSE 80
