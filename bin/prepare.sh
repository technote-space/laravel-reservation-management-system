#!/usr/bin/env bash

set -e

current=$(
  # shellcheck disable=SC2046
  cd $(dirname "$0")
  pwd
)
# shellcheck disable=SC1090
source "${current}"/variables.sh

if [[ -z "${NO_NPM}" ]]; then
  echo ""
  echo ">> Install latest node."
  # shellcheck disable=SC1090
  source "${current}"/prepare/install-latest-node.sh
fi

if [[ "${TRAVIS_BUILD_STAGE_NAME}" == "Deploy" ]]; then
  echo ""
  echo ">> Build"
  bash "${current}"/deploy.sh
else
  if [[ "${TRAVIS_BUILD_STAGE_NAME}" == "Test" ]] && [[ -z "${NO_COMPOSER}" ]]; then
    rm -f .env
    cp .env.travis .env
    ls -la .env
    php artisan key:generate
    php artisan config:cache
  fi

  echo ""
  echo ">> Setup"
  if [[ -z "${NO_COMPOSER}" ]]; then
    composer install --no-interaction --prefer-dist --no-suggest
  fi
  if [[ -z "${NO_NPM}" ]]; then
    yarn install
  fi
fi

if [[ "${TRAVIS_BUILD_STAGE_NAME}" == "Test" ]] && [[ -z "${NO_COMPOSER}" ]]; then
  if [[ -z "${NO_NPM}" ]]; then
    echo ""
    echo ">> Build JS"
    composer build:js
  fi

  if [[ -n "${LARAVEL_DUSK}" ]]; then
    echo ""
    echo ">> Prepare for Laravel Dusk"
    bash "${current}"/prepare/install-chrome.sh
    google-chrome-stable --headless --disable-gpu --remote-debugging-port=9222 http://localhost &
    php artisan serve &
  fi
fi
