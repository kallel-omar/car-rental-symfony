FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y git unzip libpq-dev libzip-dev zip nodejs npm \
    && docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN touch .env \
    && composer install --no-interaction --optimize-autoloader --no-scripts \
    && composer dump-autoload --optimize \
    && npm install \
    && npm run build

RUN ls -la /etc/apache2/mods-enabled/ \
    && a2query -m mpm_event || true \
    && a2query -m mpm_worker || true \
    && a2query -m mpm_prefork || true

EXPOSE 80
