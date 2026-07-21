# =========================
# Stage 1 — Composer
# =========================
FROM composer:2 AS composer

WORKDIR /app

# Копируем весь проект
COPY . .

# Устанавливаем зависимости
RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction


# =========================
# Stage 2 — Node
# =========================
FROM node:22 AS node

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .

RUN npm run build


# =========================
# Stage 3 — PHP + Apache
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
    && a2enmod rewrite

WORKDIR /var/www/html

# Копируем проект
COPY . .

# Копируем vendor
COPY --from=composer /app/vendor ./vendor

# Копируем Vite build
COPY --from=node /app/public/build ./public/build

# Создаем необходимые каталоги Laravel
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache

# Права
RUN chown -R www-data:www-data storage bootstrap/cache

# Символическая ссылка storage
RUN php artisan storage:link || true

# Apache -> public
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf

# Стартовый скрипт
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]