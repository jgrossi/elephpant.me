#!/usr/bin/env bash

set -euo pipefail

cd "$(dirname "$0")"

echo "==> Maintenance mode ON"
php artisan down
trap 'php artisan up' EXIT

echo "==> Pulling latest code"
git fetch origin
git reset --hard origin/master

echo "==> Installing PHP dependencies"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
php artisan storage:link || true

echo "==> Clearing caches"
php artisan config:clear
php artisan view:clear
php artisan cache:clear

echo "==> Running database migrations"
php artisan migrate --force

php artisan elephpants:read

echo "==> Building frontend assets"
npm install laravel-mix@5 webpack@4 webpack-cli@4 --save-dev
rm -rf node_modules
npm install --legacy-peer-deps
npm run production

echo "==> Rebuilding caches"
php artisan config:cache
php artisan view:cache

echo "==> Fixing storage permissions"
chmod -R 775 storage bootstrap/cache

echo "Done."
