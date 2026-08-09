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

# Додано бібліотеки для роботи з картинками (GD)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        zip \
        gd \
        opcache \
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

# Дозволяємо читання симлінків у папці public
RUN echo '<Directory /var/www/html/public>' > /etc/apache2/conf-available/laravel.conf \
    && echo '    Options +FollowSymLinks' >> /etc/apache2/conf-available/laravel.conf \
    && echo '    AllowOverride All' >> /etc/apache2/conf-available/laravel.conf \
    && echo '    Require all granted' >> /etc/apache2/conf-available/laravel.conf \
    && echo '</Directory>' >> /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# Створюємо необхідні директорії
RUN mkdir -p \
    storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache

# 🎯 Спочатку створюємо симлінк...
RUN php artisan storage:link || true

# 🎯 ...а потім віддаємо права www-data на storage, bootstrap/cache ТА public (включаючи симлінк)
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache \
    public

EXPOSE 80

CMD ["apache2-foreground"]