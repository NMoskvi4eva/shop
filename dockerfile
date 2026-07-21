# ---------- Stage 1: Composer ----------
FROM composer:2 AS composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .
RUN composer dump-autoload --optimize

# ---------- Stage 2: Node ----------
FROM node:22 AS node

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# ---------- Stage 3: PHP + Apache ----------
FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite

WORKDIR /var/www/html

COPY . .

COPY --from=composer /app/vendor ./vendor
COPY --from=node /app/public/build ./public/build

RUN cp .env.example .env

RUN php artisan key:generate --force

RUN chown -R www-data:www-data storage bootstrap/cache

RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2-foreground"]