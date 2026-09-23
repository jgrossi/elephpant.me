#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

# After git reset, re-exec so the rest of this deploy uses the updated script
# (bash would otherwise keep running the pre-pull inode).
if [[ "${1:-}" != "--post-pull" ]]; then
    echo "==> Maintenance mode ON"
    php artisan down

    echo "==> Pulling latest code"
    git fetch origin
    git reset --hard origin/master

    exec bash "$0" --post-pull
fi

trap 'php artisan up' EXIT

echo "==> Installing PHP dependencies"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
php artisan storage:link || true

GIT_REV=$(git rev-parse HEAD)
echo '<!-- '"$GIT_REV"' -->' >> resources/views/layouts/app.blade.php

echo "==> Clearing caches"
php artisan config:clear
php artisan view:clear
php artisan cache:clear

echo "==> Running database migrations"
php artisan migrate --force
php artisan elephpants:read

echo "==> Generating API docs"
php artisan scribe:generate

echo "==> Building frontend assets"
npm ci && npm run build

php artisan view:clear
php artisan config:clear

echo "==> Rebuilding caches"
php artisan config:cache
php artisan route:cache

echo "==> Fixing storage permissions"
chmod -R 775 storage bootstrap/cache

echo "Done."
