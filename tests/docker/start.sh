#!/bin/bash
set -e

echo "Waiting for database..."
sleep 10

php artisan config:clear
php artisan cache:clear

php artisan migrate --force

exec apache2-foreground