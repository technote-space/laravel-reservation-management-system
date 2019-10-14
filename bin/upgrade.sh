#!/usr/bin/env bash

set -e

ncu -u
yarn install
yarn upgrade
yarn audit


rm -rdf vendor
rm -f composer.lock

packages=()
packages+=( "fideloper/proxy" )
packages+=( "laravel/framework" )
packages+=( "laravel/tinker" )
packages+=( "composer/composer" )
packages+=( "doctrine/dbal" )
packages+=( "fzaninotto/faker" )
packages+=( "staudenmeir/eloquent-eager-limit" )
packages+=( "technote/laravel-crud-helper" )
packages+=( "technote/laravel-search-helper" )
# shellcheck disable=SC2068
php -d memory_limit=2G "$(command -v composer)" require ${packages[@]}

packages=()
packages+=( "facade/ignition" )
packages+=( "fzaninotto/faker" )
packages+=( "mockery/mockery" )
packages+=( "nunomaduro/collision" )
packages+=( "phpunit/phpunit" )
packages+=( "barryvdh/laravel-debugbar" )
packages+=( "barryvdh/laravel-ide-helper" )
packages+=( "beyondcode/laravel-dump-server" )
packages+=( "beyondcode/laravel-query-detector" )
packages+=( "codedungeon/phpunit-result-printer" )
packages+=( "dealerdirect/phpcodesniffer-composer-installer" )
packages+=( "laravel/dusk" )
packages+=( "phpmd/phpmd" )
packages+=( "squizlabs/php_codesniffer" )
# shellcheck disable=SC2068
php -d memory_limit=2G "$(command -v composer)" require --dev ${packages[@]}
