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

RUN rm -f /etc/apache2/mods-enabled/mpm_* \
    && ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load \
    && ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf \
    && a2enmod rewrite \
    && sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

CMD ["apache2-foreground"]

EXPOSE 80
