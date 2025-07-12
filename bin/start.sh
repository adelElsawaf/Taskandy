#!/bin/sh

echo "🏗 Running migrations..."
php artisan migrate --force

echo "🚀 Starting queue worker..."
php artisan queue:work --tries=3 --timeout=90 >> storage/logs/worker.log 2>&1 &

echo "Generating DOCS"
php artisan scribe:generate

echo "🌐 Starting web server..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}



