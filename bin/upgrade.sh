#!/usr/bin/env bash

set -e

yarn update


rm -rdf vendor
rm -f composer.lock
chmod 777 bootstrap/cache

TARGET=$(< composer.json jq -r '."require-dev" | to_entries[] | select(.value | startswith("^") or startswith("~")) | select(.key | contains("/")) | .key')
if [[ -n "${TARGET}" ]]; then
  echo "${TARGET}" | tr '\n' ' ' | xargs php -d memory_limit=4G "$(command -v composer)" require --dev --no-interaction --prefer-dist --no-suggest
fi
TARGET=$(< composer.json jq -r '.require | to_entries[] | select(.value | startswith("^") or startswith("~")) | select(.key | contains("/")) | .key')
if [[ -n "${TARGET}" ]]; then
  echo "${TARGET}" | tr '\n' ' ' | xargs php -d memory_limit=4G "$(command -v composer)" require --no-interaction --prefer-dist --no-suggest
fi
