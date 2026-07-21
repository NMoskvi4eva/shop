# =========================
# stage 1 — composer
# =========================
FROM composer:2 AS composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

COPY . .

RUN composer dump-autoload --optimize


# =========================
# stage 2 — node build
# =========================
FROM node:22 AS node

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm install

COPY . .

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


# копируем Laravel проект
COPY . .


# vendor из composer stage
COPY --from=composer /app/vendor ./vendor


# Vite build
COPY --from=node /app/public/build ./public/build


# Apache должен работать через Laravel public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf


# разрешаем Laravel .htaccess
RUN echo '<Directory /var/www/html/public>' > /etc/apache2/conf-available/laravel.conf \
    && echo '    AllowOverride All' >> /etc/apache2/conf-available/laravel.conf \
    && echo '    Require all granted' >> /etc/apache2/conf-available/laravel.conf \
    && echo '</Directory>' >> /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel


# создаём папки Laravel
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache


# права Laravel
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache


# storage link
RUN php artisan storage:link || true


# кеширование Laravel
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true


EXPOSE 80


CMD ["apache2-foreground"]