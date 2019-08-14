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
echo ">> Run composer phpunit"
composer phpunit

if [[ -n "${CI}" ]]; then
  ls -la "${TRAVIS_BUILD_DIR}"/coverage/php/clover.xml
  echo ""
  echo ">> Run composer coveralls"
  composer coveralls-php
fi
