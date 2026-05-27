#!/bin/bash
set -e
echo "=== Déploiement SGI Immo ==="
git pull origin main
composer install --no-dev --optimize-autoloader --quiet
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "=== Déploiement terminé ==="
