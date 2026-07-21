# =========================
# stage 1 — composer
# =========================
FROM composer:2 AS composer

WORKDIR /app

COPY . .

RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction


# =========================
# stage 2 — node
# =========================
FROM node:22 AS node

WORKDIR /app

COPY . .

RUN npm install

RUN npm run build


# =========================
# stage 3 — php apache
# =========================
FROM php:8.3-apache


RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        zip \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


WORKDIR /var/www/html


COPY . .


COPY --from=composer /app/vendor ./vendor


COPY --from=node /app/public/build ./public/build


# Apache -> Laravel public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf


# разрешаем .htaccess
RUN echo '<Directory /var/www/html/public>' > /etc/apache2/conf-available/laravel.conf \
    && echo 'AllowOverride All' >> /etc/apache2/conf-available/laravel.conf \
    && echo 'Require all granted' >> /etc/apache2/conf-available/laravel.conf \
    && echo '</Directory>' >> /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel


RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache


RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache


RUN php artisan storage:link || true


EXPOSE 80


CMD ["apache2-foreground"]