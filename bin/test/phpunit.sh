#!/usr/bin/env bash

set -e

current=$(
  # shellcheck disable=SC2046
  cd $(dirname "$0")
  pwd
)
# shellcheck disable=SC1090
source "${current}"/../variables.sh

finally() {
    if [[ -f .env.phpunit.backup ]]; then
        mv -f .env.phpunit.backup .env
    fi
}
trap finally EXIT

echo ""
echo ">> Setup"
if [[ -f .env ]]; then
    mv -f .env .env.phpunit.backup
fi
cp .env.travis .env
ls -la .env
sed -i -e 's/APP_ENV=testing/APP_ENV=development/' .env

composer prepare:php
php artisan key:generate
composer cache

# for Feature\IndexTest
echo ""
echo ">> Build JS"
# shellcheck disable=SC1090
source "${current}"/../prepare/install-latest-node.sh
node -v
composer prepare:js
composer build:js

echo ""
echo ">> Run composer phpunit"
composer phpunit
