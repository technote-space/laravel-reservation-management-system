#!/usr/bin/env bash

set -e

yarn update


rm -rdf vendor
rm -f composer.lock
chmod 777 bootstrap/cache

< composer.json jq -r '.require | to_entries[] | select(.value | startswith("^") or startswith("~")) | select(.key | contains("/")) | .key' | tr '\n' ' ' | xargs -r php -d memory_limit=2G "$(command -v composer)" require --no-interaction --prefer-dist --no-suggest
< composer.json jq -r '."require-dev" | to_entries[] | select(.value | startswith("^") or startswith("~")) | select(.key | contains("/")) | .key' | tr '\n' ' ' | xargs -r php -d memory_limit=2G "$(command -v composer)" require --dev --no-interaction --prefer-dist --no-suggest
