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
echo ">> Run composer jest"
composer jest

if [[ -n "${CI}" ]]; then
  ls -la "${TRAVIS_BUILD_DIR}"/coverage/js/lcov.info
  echo ""
  echo ">> Run yarn coveralls."
  composer coveralls-js
fi
