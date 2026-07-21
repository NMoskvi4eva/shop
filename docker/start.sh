#!/bin/bash

set -x

echo "Starting..."

php artisan config:clear
php artisan cache:clear

php artisan migrate --force

php artisan db:seed --force

exec apache2-foreground