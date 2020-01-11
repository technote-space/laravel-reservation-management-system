#!/usr/bin/env bash

set -e

yarn update


if [[ -f .env ]]; then
    mv -f .env .env.upgrade.backup
fi
cp .env.travis .env
ls -la .env

rm -rdf vendor
rm -f composer.lock
rm -rdf bootstrap/cache

packages=()
packages+=( "composer/composer" )
packages+=( "doctrine/dbal" )
packages+=( "fideloper/proxy" )
packages+=( "laravel/framework" )
packages+=( "laravel/tinker" )
packages+=( "staudenmeir/eloquent-eager-limit" )
packages+=( "technote/laravel-crud-helper" )
packages+=( "technote/laravel-search-helper" )
# shellcheck disable=SC2068
php -d memory_limit=2G "$(command -v composer)" require --prefer-dist ${packages[@]}

packages=()
packages+=( "barryvdh/laravel-debugbar" )
packages+=( "barryvdh/laravel-ide-helper" )
packages+=( "beyondcode/laravel-dump-server" )
packages+=( "beyondcode/laravel-query-detector" )
packages+=( "codedungeon/phpunit-result-printer" )
packages+=( "dealerdirect/phpcodesniffer-composer-installer" )
packages+=( "facade/ignition" )
packages+=( "fzaninotto/faker" )
packages+=( "laravel/dusk" )
packages+=( "mockery/mockery" )
packages+=( "nunomaduro/collision" )
packages+=( "phpunit/phpunit" )
packages+=( "phpmd/phpmd" )
packages+=( "squizlabs/php_codesniffer" )
# shellcheck disable=SC2068
php -d memory_limit=2G "$(command -v composer)" require --dev --prefer-dist ${packages[@]}

if [[ -f .env.upgrade.backup ]]; then
    mv -f .env.upgrade.backup .env
fi
