#!/bin/sh
chmod -R 777 storage bootstrap/cache
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --force
exec apache2-foreground