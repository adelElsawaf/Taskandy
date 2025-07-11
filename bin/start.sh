#!/bin/sh

echo "🏗 Running migrations..."
php artisan migrate --force

echo "🚀 Starting queue worker in background..."
php artisan queue:work --tries=3 --timeout=90 &

echo "🌐 Starting web server..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
