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
if [[ -f .env ]]; then
    mv -f .env .env.dusk.backup
fi
cp .env.travis .env
ls -la .env
php artisan key:generate
composer prepare:php
composer cache

echo ""
echo ">> Build JS"
# shellcheck disable=SC1090
source "${current}"/../prepare/install-latest-node.sh
node -v
composer prepare:js
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

if [[ -f .env.dusk.backup ]]; then
    mv -f .env.dusk.backup .env
fi

sudo kill "$(pgrep -f "artisan serve")"
sudo kill "$(sudo lsof -t -i:8000)"
