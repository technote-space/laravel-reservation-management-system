#!/usr/bin/env bash

set -e

current=$(cd $(dirname $0);
pwd)
source ${current}/variables.sh

if [[ -z "${NO_NPM}" ]]; then
    echo ""
    echo ">> Install latest node."
    source ${current}/prepare/install-latest-node.sh
fi

if [[ "${TRAVIS_BUILD_STAGE_NAME}" == "Deploy" ]]; then
    echo ""
    echo ">> Build"
    composer build
else
    echo ""
    echo ">> Setup"
    if [[ -z "${NO_COMPOSER}" ]]; then
        composer install
    fi
    if [[ -z "${NO_NPM}" ]]; then
        yarn install
    fi
fi

if [[ "${TRAVIS_BUILD_STAGE_NAME}" == "Test" ]] && [[ -z "${NO_COMPOSER}" ]]; then
    echo ""
    echo ">> Setup Database"
    mysql -e 'CREATE DATABASE travis_ci_test;'
    mysql -e "CREATE USER 'travis'@'localhost';"
    mysql -e "GRANT ALL ON travis_ci_test.* TO 'travis'@'localhost';"
    cp .env.travis .env
    php artisan key:generate

    if [[ -n "${LARAVEL_DUSK}" ]]; then
        echo ""
        echo ">> Prepare for Laravel Dusk"
        google-chrome-stable --headless --disable-gpu --remote-debugging-port=9222 http://localhost &
        php artisan serve &
    fi
fi
