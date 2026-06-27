#!/usr/bin/env bash
PHP="/opt/plesk/php/8.5/bin/php"
set -euo pipefail

cd "$(dirname "$0")"

echo "==> Maintenance mode ON"
${PHP} artisan down
trap 'php artisan up' EXIT

echo "==> Pulling latest code"
git fetch origin
git reset --hard origin/master

echo "==> Installing PHP dependencies"
/usr/local/bin/composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
${PHP} artisan storage:link || true

echo "==> Clearing caches"
${PHP} artisan config:clear
${PHP} artisan view:clear
${PHP} artisan cache:clear

echo "==> Running database migrations"
${PHP} artisan migrate --force
${PHP} artisan elephpants:read

echo "==> Building frontend assets"
npm ci && npm run build

${PHP} artisan view:clear
${PHP} artisan config:clear

echo "==> Rebuilding caches"
${PHP} artisan config:cache
${PHP} artisan route:cache

echo "==> Fixing storage permissions"
chmod -R 775 storage bootstrap/cache

echo "Done."
