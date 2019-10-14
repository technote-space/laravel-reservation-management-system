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
yarn install

echo ""
echo ">> Build JS"
composer build:js

echo ""
echo ">> Run composer phpunit"
composer phpunit
