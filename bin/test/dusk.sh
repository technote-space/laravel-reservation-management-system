#!/usr/bin/env bash

set -e

current=$(
  # shellcheck disable=SC2046
  cd $(dirname "$0")
  pwd
)
# shellcheck disable=SC1090
source "${current}"/../variables.sh

echo ""
echo ">> Setup"
rm -f .env
cp .env.travis .env
ls -la .env
composer install --no-interaction --prefer-dist --no-suggest
php artisan key:generate
php artisan config:cache

echo ""
echo ">> Build JS"
bash "${current}"/../prepare/install-latest-node.sh
yarn install
composer build:js

echo ""
echo ">> Prepare for Laravel Dusk"
bash "${current}"/../prepare/install-google-chrome.sh
bash "${current}"/../prepare/install-chrome-driver.sh
google-chrome-stable --headless --disable-gpu --remote-debugging-port=9222 http://localhost &
php artisan serve &

echo ""
echo ">> Run composer dusk"
composer dusk
