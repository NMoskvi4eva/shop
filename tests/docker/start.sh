#!/bin/bash

echo "========== ENV =========="
printenv | sort

echo "========== ARTISAN ABOUT =========="
php artisan about || true

echo "========== CONFIG CLEAR =========="
php artisan config:clear || true

echo "========== CACHE CLEAR =========="
php artisan cache:clear || true

echo "========== MIGRATE =========="
php artisan migrate --force || true

echo "========== SEED =========="
php artisan db:seed --force || true

echo "========== START APACHE =========="
exec apache2-foreground