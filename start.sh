#!/bin/bash
set -e

echo "=== Railway Laravel Deploy ==="

# Load environment variables for production
if [ ! -f .env ] && [ -f .env.production ]; then
    echo "Loading production environment..."
    cp .env.production .env
fi

echo "Setting permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage bootstrap/cache

echo "Running migrations..."
php artisan migrate --force || true

echo "Clearing caches..."
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache

echo "Starting Laravel on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT
