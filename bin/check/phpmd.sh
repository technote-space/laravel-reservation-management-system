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
composer install --no-interaction --prefer-dist --no-suggest

echo ""
echo ">> Run composer phpmd"
composer phpmd
