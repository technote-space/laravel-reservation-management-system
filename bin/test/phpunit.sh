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
    mv -f .env .env.phpunit.backup
fi
cp .env.travis .env
ls -la .env
composer prepare:php
php artisan key:generate
composer cache

echo ""
echo ">> Run composer phpunit"
composer phpunit

if [[ -f .env.phpunit.backup ]]; then
    mv -f .env.phpunit.backup .env
fi
